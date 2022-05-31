<?php
/**
 * Plugin Name: Learts Addons
 * Plugin URI: http://learts.thememove.com
 * Description: A collection of shortcodes, custom post type, widgets and more for Learts theme.
 * Author: ThemeMove
 * Author URI: http://thememove.com
 * Version: 1.6.6
 * Text Domain: learts-addons
 * License: GPLv2
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$current_theme = wp_get_theme();

if ( ! empty( $current_theme['Template'] ) ) {
	$current_theme = wp_get_theme( $current_theme['Template'] );
}

define( 'LEARTS_ADDONS_DIR', plugin_dir_path( __FILE__ ) );
define( 'LEARTS_ADDONS_URL', plugin_dir_url( __FILE__ ) );
define( 'LEARTS_ADDONS_VERSION', '1.0' );
define( 'LEARTS_ADDONS_THEME_NAME', $current_theme['Name'] );
define( 'LEARTS_ADDONS_LIBS_URI', LEARTS_ADDONS_URL . '/assets/libs' );

/**
 * Class Learts_Addons
 */
if ( ( $current_theme->name === 'Learts' ) || ( $current_theme->name === 'Learts Child' ) || ( $current_theme->name === 'Learts Child Demo' ) ) {
	class Learts_Addons {

		public function __construct() {
			$this->init();
			$this->includes();
		}

		/**
		 * Initialize
		 */
		public function init() {
			add_action( 'admin_notices', array( $this, 'check_dependencies' ) );

			add_action( 'wp_enqueue_scripts', array( $this, 'enqueue', ) );
			add_action( 'admin_enqueue_scripts', array( $this, 'admin_css', ), 10 );

			add_action( 'init',
				function() {
					load_plugin_textdomain( 'learts-addons', false, plugin_basename( dirname( __FILE__ ) ) . '/languages' );
				} );
		}

		/**
		 * Load files
		 */
		public function includes() {
			include_once( LEARTS_ADDONS_DIR . 'includes/class-learts-testimonial.php' );
			include_once( LEARTS_ADDONS_DIR . 'includes/class-learts-portfolio.php' );
			include_once( LEARTS_ADDONS_DIR . 'includes/class-learts-vendor-woocommerce.php' );
			include_once( LEARTS_ADDONS_DIR . 'includes/class-learts-vc.php' );
			include_once( LEARTS_ADDONS_DIR . 'includes/class-mailchimp.php' );

			/**
			 * Widgets.
			 *
			 * @since 1.0
			 */
			require_once( LEARTS_ADDONS_DIR . 'includes/widgets/wph-widget-class.php' );
			require_once( LEARTS_ADDONS_DIR . 'includes/widgets/widget-contact-info.php' );
			require_once( LEARTS_ADDONS_DIR . 'includes/widgets/widget-instagram.php' );
			require_once( LEARTS_ADDONS_DIR . 'includes/widgets/widget-social.php' );
			require_once( LEARTS_ADDONS_DIR . 'includes/widgets/widget-recent-posts.php' );

			if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
				require_once( LEARTS_ADDONS_DIR . 'includes/widgets/widget-layered-nav.php' );
				require_once( LEARTS_ADDONS_DIR . 'includes/widgets/widget-sorting.php' );
				require_once( LEARTS_ADDONS_DIR . 'includes/widgets/widget-price-filter.php' );
				require_once( LEARTS_ADDONS_DIR . 'includes/class-video-product.php' );
			}
		}

		/**
		 * Check plugin dependencies
		 * Check if Visual Composer plugin is installed
		 */
		public function check_dependencies() {
			if ( ! defined( 'WPB_VC_VERSION' ) ) {
				$plugin_data = get_plugin_data( __FILE__ );

				printf( '<div class="notice notice-error"><p>%s</p></div>',
					sprintf( __( '<strong>%s</strong> requires <strong><a href="http://bit.ly/vcomposer" target="_blank">Visual Composer</a></strong> plugin to be installed and activated on your site.',
						'learts-addons' ),
						$plugin_data['Name'] ) );
			}
		}

		/**
		 * Add admin css
		 *
		 * @since 1.0
		 */
		public function admin_css() {
			/*
			 * Enqueue main JS
			 */
			wp_enqueue_style( 'learts-addons-admin',
				LEARTS_ADDONS_URL . 'assets/css/admin.css',
				array(),
				LEARTS_ADDONS_VERSION );
		}

		/**
		 * Enqueue scrips & styles.
		 *
		 * @access public
		 */
		public function enqueue() {
			wp_enqueue_script( 'learts-shortcode-js',
				LEARTS_ADDONS_URL . 'assets/js/shortcodes.js',
				array( 'jquery' ),
				LEARTS_ADDONS_VERSION,
				true );
		}

		/**
		 * Get option from Redux Framework
		 *
		 * @param string $option
		 *
		 * @return mixed
		 * @since 1.0
		 *
		 */
		public static function get_option( $option = '' ) {

			global $learts_options;

			return isset( $learts_options[ $option ] ) ? $learts_options[ $option ] : '';
		}
	}

	new Learts_Addons();
}
