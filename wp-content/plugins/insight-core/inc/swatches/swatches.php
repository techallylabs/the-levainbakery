<?php
define( 'INSIGHT_SW_VERSION', '1.2.1' );
define( 'INSIGHT_SW_URL', plugin_dir_url( __FILE__ ) );
define( 'INSIGHT_SW_PATH', plugin_dir_path( __FILE__ ) );

if ( ! class_exists( 'Insight_Attribute_Swatches' ) ) {

	class Insight_Attribute_Swatches {

		private $settings;

		function __construct() {
			add_action( 'init', array( $this, 'init' ) );
			add_action( 'woocommerce_variable_add_to_cart', array( $this, 'enqueue_variable_script' ) );
		}

		public function enqueue_variable_script() {
			wp_enqueue_script( 'wc-add-to-cart-variation' );
		}

		public function init() {

			if ( ! class_exists( 'Woocommerce' ) ) {
				return;
			}

			$this->settings['wc_settings_isw_loop_action'] = apply_filters( 'insight_sw_loop_action',
				'woocommerce_after_shop_loop_item_title' );
			$this->settings['wc_settings_use_shortcode']   = apply_filters( 'insight_sw_use_shortcode', false );

			add_action( 'wp_enqueue_scripts', array( $this, 'insight_sw_scripts' ) );
			add_shortcode( 'insight_swatches', array( $this, 'insight_swatches_shortcode' ) );

			// remove default variations form by our variations form
			remove_action( 'woocommerce_variable_add_to_cart', 'woocommerce_variable_add_to_cart', 30 );
			add_action( 'woocommerce_variable_add_to_cart', array( $this, 'insight_sw_swatches' ) );

			// add to products loop
			if ( ! $this->settings['wc_settings_use_shortcode'] ) {
				add_action( $this->settings['wc_settings_isw_loop_action'],
					array(
						$this,
						'insight_sw_swatches',
					),
					999 );
			}

			// ajax add to cart
			add_action( 'wp_ajax_nopriv_insight_sw_add_to_cart', array( $this, 'insight_sw_add_to_cart' ) );
			add_action( 'wp_ajax_insight_sw_add_to_cart', array( $this, 'insight_sw_add_to_cart' ) );

			add_action( 'woocommerce_add_to_cart', array( $this, 'insight_sw_repair_cart' ) );
		}

		/**
		 * Get WPML Language
		 *
		 * @return bool|string
		 */
		public static function insight_sw_wpml_lang() {

			if ( class_exists( 'SitePress' ) ) {
				global $sitepress;

				if ( method_exists( $sitepress, 'get_default_language' ) ) {

					$default_language = $sitepress->get_default_language();
					$current_language = $sitepress->get_current_language();

					if ( $default_language != $current_language ) {
						return sanitize_title( $current_language );
					}
				}
			}

			return false;

		}

		/**
		 * Get WPML slug
		 *
		 * @param $curr_term
		 * @param $attr
		 *
		 * @return string
		 */
		public static function insight_sw_wpml_get_slug( $curr_term, $attr ) {

			if ( function_exists( 'icl_object_id' ) ) {

				global $sitepress;

				if ( method_exists( $sitepress, 'get_default_language' ) ) {

					$default_language = $sitepress->get_default_language();
					$current_language = $sitepress->get_current_language();

					if ( $default_language != $current_language ) {

						$term_id = icl_object_id( $curr_term->term_id, $attr, false, $default_language );
						$term    = get_term( $term_id, $attr );

						return $term->slug;

					}

				}

			}

			return $curr_term->slug;

		}

		/**
		 * Require scripts
		 */
		public function insight_sw_scripts() {

			wp_enqueue_script( 'wc-add-to-cart-variation' );

			$curr_args = array(
				'ajax'             => admin_url( 'admin-ajax.php' ),
				'product_selector' => apply_filters( 'insight_sw_product_selector', '.product' ),
				'price_selector'   => apply_filters( 'insight_sw_price_selector', '.price' ),
				'localization'     => array(
					'add_to_cart_text'    => __( 'Add to cart', 'insight-core' ),
					'read_more_text'      => __( 'Read more', 'insight-core' ),
					'select_options_text' => __( 'Select options', 'insight-core' ),
				),
			);

			wp_enqueue_style( 'isw-style', INSIGHT_SW_URL . 'assets/css/style.css', INSIGHT_SW_VERSION );
			wp_enqueue_style( 'hint-css', INSIGHT_CORE_PATH . 'assets/css/hint.css', INSIGHT_SW_VERSION );
			wp_enqueue_script( 'isw-scripts',
				INSIGHT_SW_URL . 'assets/js/scripts.js',
				array( 'jquery' ),
				INSIGHT_SW_VERSION,
				true );
			wp_localize_script( 'isw-scripts', 'isw_vars', $curr_args );

		}

		/**
		 * Get all attributes
		 *
		 * @return array
		 */
		public static function insight_sw_get_attributes() {
			$atts             = get_object_taxonomies( 'product' );
			$ready_attributes = array();

			if ( ! empty( $atts ) ) {

				foreach ( $atts as $k ) {

					if ( substr( $k, 0, 3 ) == 'pa_' ) {
						$ready_attributes[] = $k;
					}

				}

			}

			return $ready_attributes;
		}

		/**
		 * Customize product variations
		 *
		 * @param $variations
		 *
		 * @return array
		 */
		public static function insight_sw_variations( $variations ) {

			$new_variations = array();

			foreach ( $variations as $variation ) {

				if ( $variation['variation_id'] != '' ) {

					$id = get_post_thumbnail_id( $variation['variation_id'] );

					$src    = wp_get_attachment_image_src( $id, 'shop_catalog' );
					$srcset = wp_get_attachment_image_srcset( $id, 'shop_catalog' );
					$sizes  = wp_get_attachment_image_sizes( $id, 'shop_catalog' );

					$variation['image_src']    = $src;
					$variation['image_srcset'] = $srcset;
					$variation['image_sizes']  = $sizes;

					$new_variations[] = $variation;
				}
			}

			return $new_variations;
		}

		/**
		 * Show swatches
		 */
		public function insight_sw_swatches() {

			global $product;

			if ( $product->is_type( 'variable' ) ) {

				$attributes = $product->get_attributes();

				$available_variations = $product->get_available_variations();

				$variation_attributes = $product->get_variation_attributes();

				$selected_attributes = $product->get_default_attributes();

				$lang = Insight_Attribute_Swatches::insight_sw_wpml_lang();

				if ( $lang === false ) {
					$settings = get_option( 'isw_settings', '' );
				} else {
					$settings = get_option( 'isw_settings_' . $lang, '' );
				}

				if ( $settings == '' ) {
					$settings = array( 'isw_attr' => array() );
				}

				$is_loop = current_filter() == $this->settings['wc_settings_isw_loop_action'];

				$args = array(
					'is_loop'              => $is_loop,
					'lang'                 => $lang,
					'settings'             => $settings,
					'config'               => $this->settings,
					'attributes'           => $attributes,
					'available_variations' => $available_variations,
					'variation_attributes' => $variation_attributes,
					'selected_attributes'  => $selected_attributes,
				);

				if ( $is_loop ) {
					$template = Insight_Attribute_Swatches_Utils::insight_sw_get_template( 'swatches-loop.php',
						$args,
						true );
				} else {
					$template = Insight_Attribute_Swatches_Utils::insight_sw_get_template( 'swatches-single.php',
						$args,
						true );
				}

				echo $template;
			}
		}

		/**
		 * Ajax add to cart
		 */
		public function insight_sw_add_to_cart() {

			$product_id   = apply_filters( 'woocommerce_add_to_cart_product_id', absint( $_POST['product_id'] ) );
			$quantity     = empty( $_POST['quantity'] ) ? 1 : apply_filters( 'woocommerce_stock_amount',
				$_POST['quantity'] );
			$variation_id = $_POST['variation_id'];
			$variation    = array();

			if ( is_array( $_POST['variation'] ) ) {
				foreach ( $_POST['variation'] as $key => $value ) {
					$variation[ $key ] = Insight_Attribute_Swatches_Utils::utf8_urldecode( $value );
				}
			}

			$passed_validation = apply_filters( 'woocommerce_add_to_cart_validation', true, $product_id, $quantity );

			if ( $passed_validation && WC()->cart->add_to_cart( $product_id, $quantity, $variation_id, $variation ) ) {
				do_action( 'woocommerce_ajax_added_to_cart', $product_id );

				if ( get_option( 'woocommerce_cart_redirect_after_add' ) == 'yes' ) {
					wc_add_to_cart_message( $product_id );
				}
				$data = WC_AJAX::get_refreshed_fragments();
			} else {

				WC_AJAX::json_headers();

				$data = array(
					'error'       => true,
					'product_url' => apply_filters( 'woocommerce_cart_redirect_after_error',
						get_permalink( $product_id ),
						$product_id ),
				);
			}

			wp_send_json( $data );
		}

		/**
		 * Repair cart
		 */
		public function insight_sw_repair_cart() {
			if ( defined( 'DOING_AJAX' ) ) {
				wc_setcookie( 'woocommerce_items_in_cart', 1 );
				wc_setcookie( 'woocommerce_cart_hash', md5( json_encode( WC()->cart->get_cart() ) ) );
				do_action( 'woocommerce_set_cart_cookies', true );
			}
		}

		public function insight_swatches_shortcode() {

			global $product;

			if ( $product->is_type( 'variable' ) ) {

				$attributes = $product->get_attributes();

				$available_variations = $product->get_available_variations();

				$variation_attributes = $product->get_variation_attributes();

				$selected_attributes = $product->get_default_attributes();

				$lang = Insight_Attribute_Swatches::insight_sw_wpml_lang();

				if ( $lang === false ) {
					$settings = get_option( 'isw_settings', '' );
				} else {
					$settings = get_option( 'isw_settings_' . $lang, '' );
				}

				if ( $settings == '' ) {
					$settings = array( 'isw_attr' => array() );
				}

				$is_loop = current_filter() == $this->settings['wc_settings_isw_loop_action'];

				$args = array(
					'is_loop'              => $is_loop,
					'lang'                 => $lang,
					'settings'             => $settings,
					'config'               => $this->settings,
					'attributes'           => $attributes,
					'available_variations' => $available_variations,
					'variation_attributes' => $variation_attributes,
					'selected_attributes'  => $selected_attributes,
				);

				if ( $is_loop ) {
					$template = Insight_Attribute_Swatches_Utils::insight_sw_get_template( 'swatches-loop.php',
						$args,
						true );
				} else {
					$template = Insight_Attribute_Swatches_Utils::insight_sw_get_template( 'swatches-single.php',
						$args,
						true );
				}

				return apply_filters( 'insight_swatch_html', $template );

			}
		}
	}
}

include_once( INSIGHT_SW_PATH . 'includes/class.insight.sw.utils.php' );

if ( is_admin() ) {
	include_once( INSIGHT_SW_PATH . 'includes/class.insight.sw.settings.php' );
}

new Insight_Attribute_Swatches();