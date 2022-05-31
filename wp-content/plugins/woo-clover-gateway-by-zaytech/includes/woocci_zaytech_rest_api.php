<?php

class Woocci_zaytech_rest_api {

    /*
     * isProduction : it's a flag to hide all php notices in production mode
     */
    protected $isProduction = true;

    /*
     * The Api that will handle calls to the server
     */
    protected $api;

    protected $settings;

    /**
     * The namespace and the version of the api
     * @var string
     */
    protected $namespace = 'woocci/v1';

    /**
     * Woocci_zaytech_rest_api constructor.
     */
    public function __construct() {

        $this->settings = get_option("woocommerce_woocci_zaytech_settings");
        $this->api = new Woocci_zaytech_api($this->settings["secret_key"]);

    }


    // Register our routes.
    public function register_routes(){
        // Update category route
        register_rest_route($this->namespace, '/check_order', array(
            // Here we register the readable endpoint for collections.
            array(
                'methods' => 'POST',
                'callback' => array($this, 'checkCloverOrder'),
                'permission_callback' => '__return_true'
            )
        ));
    }

    public function permissionCheck( $request ) {
        return current_user_can( 'manage_options' );
    }

    public function checkCloverOrder( $request ) {
        $body = json_decode($request->get_body(),true);
        if(isset($body["order_id"]) && isset($body["woo_order_id"])){
           $cloverOrder = json_decode($this->api->getOrder($body["order_id"]),true);
           if($cloverOrder){
               if(isset($cloverOrder['payments']) && count($cloverOrder['payments'])>0 ) {
                   $orderPayments = $cloverOrder['payments'];
                   foreach ($orderPayments as $p) {
                       if (strtoupper($p["result"]) == "APPROVED") {
                           $order = wc_get_order( $body["woo_order_id"]  );
                           if( $order ) {
                               $settings = get_option( 'woocommerce_woocci_zaytech_settings' );
                               $newOrderStatus = $settings['order_status'];
                               if(!isset($newOrderStatus)){
                                   $newOrderStatus = "completed";
                               }
                               $order_status  = $order->get_status();
                               if($order_status !==  $newOrderStatus) {
                                   // Mark Order as PAID
                                   $orderNote = 'Payment has been accepted by Clover. Check the online receipt from <a href="https://www.clover.com/r/' . $body["order_id"].'" target="_blank">here</a>';
                                   $order->update_status($newOrderStatus, $orderNote);
                                   Woocci_Logger::log( "Status of the order ". $body["order_id"] . " has been updated from " . $order_status . " to : " . $newOrderStatus );
                                   do_action( 'woocci_process_payment_success', $order );
                               }
                               return array( 'status' => "success" );
                           } else {
                               Woocci_Logger::log( "Woocommerce : The order ". $body["woo_order_id"] . " not found" );
                               return new WP_Error('The woo order is not found', array( 'status' => 404 ) );
                           }
                       }
                   }
               }
           } else {
               Woocci_Logger::log( "Clover : The order ". $body["order_id"] . "not found" );
               return new WP_Error('This order is not found', array( 'status' => 404 ) );
           }
        } else {
            return new WP_Error( 'order_id', 'The order id is required', array( 'status' => 400 ) );
        }

        return array( 'status' => "success" );
    }

}