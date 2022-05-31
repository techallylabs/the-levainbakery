<?php
/**
 * Plugin Name: WooCommerce Clover Payment Gateway by Zaytech
 * Plugin URI: https://wordpress.org/plugins/woo-clover-gateway-by-zaytech/
 * Description: Process payments by your Clover Merchant Account and auto print the orders to your Clover POS.
 * Author: Zaytech
 * Author URI: https://zaytech.com/
 * Version: 1.2.5
 * Requires at least: 4.4
 * Tested up to: 5.8
 * WC requires at least: 3.0
 * WC tested up to: 5.6
 * Text Domain: zaytech_woocci
 * Domain Path: /languages
 *
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * WooCommerce requirement.
 */
function zaytech_woocci_missing_wc_notice() {
    /* translators: 1. URL link. */
    echo '<div class="error"><p><strong>' . sprintf( esc_html__( 'WooCommerce Clover Payment Gateway by Zaytech
 : We require WooCommerce to be installed and active. You can download %s here.', 'zaytech_woocci' ), '<a href="https://woocommerce.com/" target="_blank">WooCommerce</a>' ) . '</strong></p></div>';
}

add_action( 'plugins_loaded', 'zaytech_woocci_init' );
function zaytech_woocci_init() {
    load_plugin_textdomain( 'zaytech_woocci', false, plugin_basename( dirname( __FILE__ ) ) . '/languages' );

    if ( ! class_exists( 'WooCommerce' ) ) {
        add_action( 'admin_notices', 'zaytech_woocci_missing_wc_notice' );
        return;
    }

    if ( ! class_exists( 'Woocci_Zaytech' ) ) :
        /**
         * Required minimums and constants
         */
        define( 'WOOCCI_VERSION', '1.2.5' );
        define( 'WOOCCI_MIN_PHP_VER', '5.6.0' );
        define( 'WOOCCI_MIN_WC_VER', '3.0.0' );
        define( 'WOOCCI_MAIN_FILE', __FILE__ );
        define( 'WOOCCI_PLUGIN_URL', untrailingslashit( plugins_url( basename( plugin_dir_path( __FILE__ ) ), basename( __FILE__ ) ) ) );
        define( 'WOOCCI_PLUGIN_PATH', untrailingslashit( plugin_dir_path( __FILE__ ) ) );

        class Woocci_Zaytech {

            /**
             * @var Singleton The reference the *Singleton* instance of this class
             */
            private static $instance;

            /**
             * Returns the *Singleton* instance of this class.
             *
             * @return Singleton The *Singleton* instance.
             */
            public static function get_instance() {
                if ( null === self::$instance ) {
                    self::$instance = new self();
                }
                return self::$instance;
            }

            /**
             * Private clone method to prevent cloning of the instance of the
             * *Singleton* instance.
             *
             * @return void
             */
            private function __clone() {}

            /**
             * Private unserialize method to prevent unserializing of the *Singleton*
             * instance.
             *
             * @return void
             */
            private function __wakeup() {}

            /**
             * Protected constructor to prevent creating a new instance of the
             * *Singleton* via the `new` operator from outside of this class.
             */
            private function __construct() {
                add_action( 'admin_init', array( $this, 'install' ) );
                $this->init();
            }

            /**
             * Init the plugin after plugins_loaded so environment variables are set.
             *
             * @since 1.0.0
             */
            public function init() {
                require_once dirname( __FILE__ ) . '/includes/woocci_Exception.php';
                require_once dirname( __FILE__ ) . '/includes/woocci_Logger.php';
                require_once dirname( __FILE__ ) . '/includes/woocci_Helper.php';
                include_once dirname( __FILE__ ) . '/includes/woocci_zaytech_api.php';
                require_once dirname( __FILE__ ) . '/includes/woocci_zay_gateway.php';
                require_once dirname( __FILE__ ) . '/includes/woocci_zaytech_rest_api.php';


                add_filter( 'woocommerce_payment_gateways', array( $this, 'add_gateways' ) );

                add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), array( $this, 'plugin_action_links' ) );

                add_filter( 'woocci_order_customer_note' , array( $this, 'update_order_note' ) );

                add_filter( 'woocci_line_item_note' , array( $this, 'update_item_line_note' ), 10, 2);

	            add_action( 'woocommerce_api_'. strtolower( get_class($this) ), array( $this, 'callback_handler' ) );

	            add_action( 'rest_api_init', array( $this, 'init_restApi' )  );

                add_action( 'woocommerce_order_actions', array( $this, 'add_check_payment_action' ));

                add_action( 'woocommerce_order_action_woocci_check_payment_order', array( $this, 'recheck_clover_payment' ));

                add_action( 'update_option_woocommerce_woocci_zaytech_settings', array( $this, 'reset_keys_when_secret_key_changed' ), 10, 2);


            }

            /**
             * Updates the plugin version in db
             *
             * @since 1.0
             */
            public function update_plugin_version() {
                delete_option( 'wooccii_zaytech_version' );
                update_option( 'wooccii_zaytech_version', WOOCCI_VERSION );
            }

            /**
             * Handles upgrade routines.
             *
             * @since 1.0.0
             */
            public function install() {
                if ( ! is_plugin_active( plugin_basename( __FILE__ ) ) ) {
                    return;
                }

                if ( ! defined( 'IFRAME_REQUEST' ) && ( WOOCCI_VERSION !== get_option( 'woocci_version' ) ) ) {
                    do_action( 'woocci_updated' );

                    if ( ! defined( 'WOOCCI_INSTALLING' ) ) {
                        define( 'WOOCCI_INSTALLING', true );
                    }

                    $this->update_plugin_version();
                }
            }

            /**
             * Adds plugin action links.
             * @since 1.0.0
             */
            public function plugin_action_links( $links ) {
                $plugin_links = array(
                    '<a href="admin.php?page=wc-settings&tab=checkout&section=woocci_zaytech">Settings</a>'
                );
                return array_merge( $plugin_links, $links );
            }
            /**
             * Init the rest api
             * @since 1.2.2
             */
            public function init_restApi() {
                $rest_api = new Woocci_zaytech_rest_api();
                $rest_api->register_routes();
            }

            /**
             * Add the gateways to WooCommerce.
             *
             * @since 1.0.0
             */
            public function add_gateways( $methods ) {
                $methods[] = 'Woocci_zay_gateway';
                return $methods;
            }
            /**
             * Check the payment response.
             */
            public function callback_handler( ) {
	            $raw_post = file_get_contents( 'php://input' );
	            $decoded  = json_decode( $raw_post );
	            if(isset($decoded->woo_order)) {
		            $order = new WC_Order( $decoded->woo_order );
		            if($order) {
			            if(isset($decoded->payment_status)) {
				            if( $decoded->payment_status == "APPROVED" ) {
								$order->payment_complete();
								$order->add_order_note('Payment accepted by Clover, the payment ID is '.$decoded->payment_uuid);
				            } else {
					            if( $decoded->payment_status == "FAILED" ) {
						            $order->update_status('failed');
					            }
				            }
			            }
		            } else {
						Woocci_Logger::log("Received a payment status for an order does not exist : ".$decoded->order_id);
		            }
	            }
	           die();
            }
            /**
             * Check the payment response.
             */
            public function update_order_note( $current_note, $order_id ) {
                if(isset($current_note) && !empty($current_note) && isset($order_id)){
                    return Woocci_Helper::woocci_get_wc_order_notes($order_id);
                }
                return $current_note;
            }
            /**
             * Update the item line note
             */
            public function update_item_line_note( $current_note, $item_line ) {

                if(isset($item_line)){
                   // You can add here your code that get the modifiers from the line item

                }
                return $current_note;

            }
            /**
             * Add a custom action to order actions select box on edit order page
             *
             */
            public function add_check_payment_action( $actions ) {
                // add "mark printed" custom action
                $actions['woocci_check_payment_order'] = __( 'Re-Check Clover Payment Status', 'zaytech_woocci' );
                return $actions;
            }
            /**
             * Add a custom action to order actions select box on edit order page
             *
             */
            public function reset_keys_when_secret_key_changed( $old, $new ) {
                if(
                    isset($old["secret_key"]) &&
                    isset($new["secret_key"]) &&
                    $old["secret_key"] !== $new["secret_key"]
                ) {
                    delete_option( 'woocci_pakms_key' );
                    delete_option( 'woocci_jwt_token' );
                }
                set_transient( "woocci_force_pakms", "yes", 60 );

            }
            /**
             * Add a custom action to order actions select box on edit order page
             *
             */
            public function recheck_clover_payment( $order ) {
                Woocci_Logger::log("Checking the payments status of the order : " . $order->get_id());
                $settings = get_option("woocommerce_woocci_zaytech_settings");
                $api = new Woocci_zaytech_api($settings["secret_key"]);
                $cloverOrderUuids = get_post_meta( $order->get_id(), '_clover_uuid');
                foreach ($cloverOrderUuids as $cloverOrderUuid) {
                    if(isset($cloverOrderUuid) && !empty($cloverOrderUuid)) {
                        Woocci_Logger::log("Checking the Clover order : " . $cloverOrderUuid);
                        $cloverOrder = json_decode($api->getOrder($cloverOrderUuid),true);
                        if($cloverOrder){
                            if(isset($cloverOrder['payments']) && count($cloverOrder['payments'])>0 ) {
                                $orderPayments = $cloverOrder['payments'];
                                foreach ($orderPayments as $p) {
                                    if (strtoupper($p["result"]) == "APPROVED") {
                                        if( $order ) {
                                            $newOrderStatus = $settings['order_status'];
                                            if(!isset($newOrderStatus)) {
                                                $newOrderStatus = "completed";
                                            }
                                            $order_status  = $order->get_status();
                                            if($order_status !==  $newOrderStatus) {
                                                // Mark Order as PAID
                                                $orderNote = 'Payment has been accepted by Clover. Check the online receipt from <a href="https://www.clover.com/r/' . $cloverOrderUuid.'" target="_blank">here</a>';
                                                $order->update_status($newOrderStatus, $orderNote);
                                                Woocci_Logger::log( "Status of the order ". $cloverOrderUuid . " has been updated from " .$order_status. " to : " .$newOrderStatus );
                                                do_action( 'woocci_process_payment_success', $order );
                                            } else {
                                                Woocci_Logger::log("Order status not changed");
                                            }
                                            return array( 'status' => "success" );
                                        } else {
                                            Woocci_Logger::log( "WooCommerce : The order : ". $order->get_id() . " not found" );
                                            return new WP_Error('The woo order is not found', array( 'status' => 404 ) );
                                        }
                                    }
                                }
                            }
                        } else {
                            Woocci_Logger::log( "The  Clover Order : ". $cloverOrderUuid . " is not found" );
                        }
                    }
                }
            }
        }

        Woocci_Zaytech::get_instance();
    endif;
}