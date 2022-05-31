<?php
/*
Plugin Name: Insight Core
Description: Core functions for WordPress theme
Author: InsightStudio
Version: 1.6.7.6
Author URI: http://insightstud.io
Text Domain: insight-core
*/

if ( ! defined( 'ABSPATH' ) ) {
	exit();
}

$theme = wp_get_theme();
if ( ! empty( $theme['Template'] ) ) {
	$theme = wp_get_theme( $theme['Template'] );
}
define( 'INSIGHT_CORE_SITE_URI', site_url() );
define( 'INSIGHT_CORE_PATH', plugin_dir_url( __FILE__ ) );
define( 'INSIGHT_CORE_DIR', dirname( __FILE__ ) );
define( 'INSIGHT_CORE_INC_DIR', INSIGHT_CORE_DIR . '/inc' );
define( 'INSIGHT_CORE_THEME_NAME', $theme['Name'] );
define( 'INSIGHT_CORE_THEME_SLUG', $theme['Template'] );
define( 'INSIGHT_CORE_THEME_VERSION', $theme['Version'] );
define( 'INSIGHT_CORE_THEME_DIR', get_template_directory() );
define( 'INSIGHT_CORE_THEME_URI', get_template_directory_uri() );
define( 'INSIGHT_CORE_API_SERVER', 'api.thememove.com' );

if ( ! defined( 'DS' ) ) {
	define( 'DS', DIRECTORY_SEPARATOR );
}

if ( ! class_exists( 'InsightCore' ) ) {

	class InsightCore {

		public static $info = array(
			'support' => 'https://thememove.ticksy.com',
			'faqs'    => 'https://thememove.ticksy.com/articles/',
			'docs'    => 'http://document.thememove.com/structure-document',
			'api'     => 'https://api.thememove.com/update/heli',
			'icon'    => 'https://thumb-tf.s3.envato.com/files/184265119/thumb80x80.png',
			'desc'    => 'Thank you for using our theme, please reward it a full five-star &#9733;&#9733;&#9733;&#9733;&#9733; rating.',
			'tf'      => 'http://themeforest.net/item/heli-creative-multipurpose-wordpress-theme/14438769',
		);

		function __construct() {

			add_action( 'init', array( $this, 'load_textdomain' ), 1000 );

			add_action( 'admin_enqueue_scripts',
				array(
					$this,
					'admin_enqueue_scripts',
				) );
			add_action( 'wp_ajax_insight_core_patcher',
				array(
					$this,
					'ajax_patcher',
				) );
			add_action( 'after_setup_theme',
				array(
					$this,
					'after_setup_theme',
				),
				12 );

			add_action( 'do_meta_boxes',
				array(
					$this,
					'insight_remove_revolution_slider_meta_boxes',
				) );

			add_action( 'wp_ajax_insight_core_get_changelogs',
				array(
					$this,
					'ajax_get_changelogs',
				) );
			add_action( 'wp_ajax_nopriv_insight_core_get_changelogs',
				array(
					$this,
					'ajax_get_changelogs',
				) );

			add_filter( 'widget_text', 'do_shortcode' );

			add_filter( 'user_contactmethods', array( $this, 'add_extra_fields_for_contactmethods' ), 10, 1 );

			// Custom Functions
			include_once( INSIGHT_CORE_INC_DIR . '/custom_functions.php' );

			// Register Posttypes
			include_once( INSIGHT_CORE_INC_DIR . '/register-posttypes.php' );

			// Pages
			include_once( INSIGHT_CORE_INC_DIR . '/pages.php' );

			// TMG
			include_once( INSIGHT_CORE_INC_DIR . '/tgm-plugin-activation.php' );
			require_once( INSIGHT_CORE_INC_DIR . '/tgm-plugin-registration.php' );

			// Import & Export
			include_once( INSIGHT_CORE_INC_DIR . '/export/export.php' );
			include_once( INSIGHT_CORE_INC_DIR . '/import/import.php' );

			// Update
			//include_once( INSIGHT_CORE_INC_DIR . '/update/class-update.php' );

			// CMB2
			add_filter( 'cmb2_meta_box_url', array( $this, 'cmb2_meta_box_url' ) );
			include_once( INSIGHT_CORE_DIR . '/libs/cmb2/init.php' );
			include_once( INSIGHT_CORE_DIR . '/libs/cmb2-tabs/plugin.php' );

			// Others
			//include_once( INSIGHT_CORE_INC_DIR . '/customizer/io.php' );
			include_once( INSIGHT_CORE_INC_DIR . '/breadcrumb.php' );
			include_once( INSIGHT_CORE_INC_DIR . '/better-menu-widget.php' );

		}

		/**
		 * Load text domain
		 */
		public function load_textdomain() {
			$dir = trailingslashit( WP_LANG_DIR );
			load_plugin_textdomain( 'insight-core', false, $dir . 'plugins' );
		}

		public static function insight_core_activation_hook() {

			$pt_array = ( $pt_array = get_option( 'wpb_js_content_types' ) ) ? ( $pt_array ) : array( 'page' );

			if ( ! in_array( 'ic_mega_menu', $pt_array ) ) {
				$pt_array[] = 'ic_mega_menu';
			}

			if ( ! in_array( 'ic_popup', $pt_array ) ) {
				$pt_array[] = 'ic_popup';
			}

			// Update user roles
			$user_roles = get_option( 'wp_user_roles' );

			if ( ! empty( $user_roles ) ) {
				foreach ( $user_roles as $key => $value ) {
					$user_roles[ $key ]['capabilities']['vc_access_rules_post_types']              = 'custom';
					$user_roles[ $key ]['capabilities']['vc_access_rules_post_types/page']         = true;
					$user_roles[ $key ]['capabilities']['vc_access_rules_post_types/ic_mega_menu'] = true;
					$user_roles[ $key ]['capabilities']['vc_access_rules_post_types/ic_popup']     = true;
				}
			}

			update_option( 'wpb_js_content_types', $pt_array );
			update_option( 'wp_user_roles', $user_roles );
		}

		public function admin_enqueue_scripts() {
			$screen = get_current_screen();

			if ( strpos( $screen->id, 'page_insight-core-system' ) !== false ) {
				wp_enqueue_style( 'hint', INSIGHT_CORE_PATH . 'assets/css/hint.css' );
			}

			wp_enqueue_style( 'insight-core', INSIGHT_CORE_PATH . 'assets/css/insight-core.css' );
			wp_enqueue_style( 'font-awesome', INSIGHT_CORE_PATH . 'assets/css/font-awesome.min.css' );
			wp_enqueue_style( 'pe-icon-7-stroke', INSIGHT_CORE_PATH . 'assets/css/pe-icon-7-stroke.css' );
			//wp_enqueue_style( 'bootstrap', INSIGHT_CORE_PATH . 'assets/css/bootstrap.min.css' );
			wp_enqueue_script( 'insight-core',
				INSIGHT_CORE_PATH . 'assets/js/insight-core.js',
				array( 'jquery' ),
				INSIGHT_CORE_THEME_VERSION,
				true );
			//wp_enqueue_script( 'bootstrap', INSIGHT_CORE_PATH . 'assets/js/bootstrap.min.js', array( 'jquery' ), INSIGHT_CORE_THEME_VERSION, true );
			wp_localize_script( 'insight-core',
				'ic_vars',
				array(
					'ajax_url' => admin_url( 'admin-ajax.php' ),
					'ic_nonce' => wp_create_nonce( 'ic_nonce' ),
				) );
		}

		public function after_setup_theme() {

			// Detect
			require_if_theme_supports( 'insight-detect', INSIGHT_CORE_DIR . '/libs/mobile-detect/mobile.php' );

			// Mega menu
			require_if_theme_supports( 'insight-megamenu', INSIGHT_CORE_INC_DIR . '/mega-menu/mega-menu.php' );

			// Popup
			require_if_theme_supports( 'insight-popup', INSIGHT_CORE_INC_DIR . '/popup/popup.php' );

			// Attribute Swatches
			require_if_theme_supports( 'insight-swatches', INSIGHT_CORE_INC_DIR . '/swatches/swatches.php' );

			// Sidebars
			require_if_theme_supports( 'insight-sidebars', INSIGHT_CORE_INC_DIR . '/sidebars/sidebars.php' );
		}

		public function cmb2_meta_box_url() {
			return INSIGHT_CORE_PATH . '/libs/cmb2/';
		}

		// Check theme support
		public static function is_theme_support() {
			if ( current_theme_supports( 'insight-core' ) ) {
				return true;
			} else {
				return false;
			}
		}

		// Get theme info
		public static function get_info() {
			self::$info = apply_filters( 'insight_core_info', self::$info );

			return self::$info;
		}

		// Check if has changelogs file <api>/changelogs.json
		public static function has_changelogs() {
			$request = wp_remote_get( self::$info['api'] . '/changelogs.json', array( 'timeout' => 120 ) );
			if ( is_wp_error( $request ) ) {
				return false;
			} else {
				return true;
			}
		}

		// Get changelogs file content and filter
		public static function get_changelogs( $table = true ) {
			$changelogs = '';
			if ( self::has_changelogs() ) {
				$request = wp_remote_get( self::$info['api'] . '/changelogs.json', array( 'timeout' => 120 ) );
				$logs    = json_decode( wp_remote_retrieve_body( $request ), true );
				if ( is_array( $logs ) && count( $logs ) > 0 ) {
					foreach ( $logs as $logkey => $logval ) {
						if ( $table ) {
							$changelogs .= '<tr>';
							$changelogs .= '<td>' . $logval['time'] . '</td>';
							$changelogs .= '<td>' . $logkey . '</td>';
							$changelogs .= '<td>';
							if ( is_array( $logval['desc'] ) ) {
								$changelogs .= implode( '<br/>', $logval["desc"] );
							} else {
								$changelogs .= $logval['desc'];
							}
							$changelogs .= '</td>';
							$changelogs .= '</tr>';
						} else {
							$changelogs .= '<h4>' . $logkey . ' - <span>' . $logval['time'] . '</span></h4>';
							$changelogs .= '<pre>';
							if ( is_array( $logval['desc'] ) ) {
								$changelogs .= implode( '<br/>', $logval['desc'] );
							} else {
								$changelogs .= $logval['desc'];
							}
							$changelogs .= '</pre>';

						}
					}
				}
			}
			$changelogs = apply_filters( 'insight_core_changelogs', $changelogs );

			return $changelogs;
		}

		// Get changelogs file via AJAX for automatic update theme puporse
		public function ajax_get_changelogs() {

			self::get_info();
			require_once( INSIGHT_CORE_INC_DIR . '/update/changelogs.php' );
			die;
		}

		// Check has patcher
		public static function check_theme_patcher() {
			$json = get_transient('insight_core_check_theme_patcher');

			if (false === $json) {
				self::get_info();
				$request = wp_remote_get( self::$info['api'] . '/patcher.json', array( 'timeout' => 120 ) );
				if ( is_wp_error( $request ) ) {
					return false;
				}

				$json = wp_remote_retrieve_body( $request );

				set_transient('insight_core_check_theme_patcher', $json, 24 * HOUR_IN_SECONDS);
			}

			$patchers = json_decode( $json, true );
			if ( isset( $patchers[ INSIGHT_CORE_THEME_VERSION ] ) && ( count( $patchers[ INSIGHT_CORE_THEME_VERSION ] ) > 0 ) ) {
				$patchers_status = (array) get_option( 'insight_core_patcher' );
				foreach ( $patchers[ INSIGHT_CORE_THEME_VERSION ] as $key => $value ) {
					if ( ! in_array( $key, $patchers_status ) ) {
						return true;
					}
				}

				return false;
			} else {
				return false;
			}
		}

		// Get patcher
		public static function get_patcher() {
			self::get_info();
			$request = wp_remote_get( self::$info['api'] . '/patcher.json', array( 'timeout' => 120 ) );
			if ( is_wp_error( $request ) ) {
				return false;
			}
			$patchers = json_decode( wp_remote_retrieve_body( $request ), true );
			set_transient('insight_core_check_theme_patcher', $patchers, 24 * HOUR_IN_SECONDS);

			return $patchers;
		}

		// AJAX patcher
		public function ajax_patcher() {
			if ( ! isset( $_POST['ic_nonce'] ) || ! wp_verify_nonce( $_POST['ic_nonce'], 'ic_nonce' ) ) {
				die( 'Permissions check failed!' );
			}
			self::get_info();
			$ic_patcher       = $_POST['ic_patcher'];
			$ic_patcher_url   = self::$info['api'] . '/' . $ic_patcher . '.zip';
			$ic_patcher_error = false;
			require_once( ABSPATH . 'wp-admin/includes/file.php' );
			WP_Filesystem();
			// create temp folder
			$_tmp = wp_tempnam( $ic_patcher_url );
			@unlink( $_tmp );
			@ob_flush();
			@flush();
			if ( is_writable( INSIGHT_CORE_THEME_DIR ) ) {
				$package = download_url( $ic_patcher_url, 18000 );
				$unzip   = unzip_file( $package, INSIGHT_CORE_THEME_DIR );
				if ( ! is_wp_error( $package ) ) {
					if ( ! is_wp_error( $unzip ) ) {
						self::update_option_array( 'insight_core_patcher', $ic_patcher );
					} else {
						$ic_patcher_error = true;
					}
				} else {
					$ic_patcher_error = true;
				}
			} else {
				$ic_patcher_error = true;
			}

			echo $ic_patcher_error ? 'Error' : 'Done';
			die;
		}

		// Check purchase code
		public static function check_purchase_code( $code ) {
			$curl = curl_init();
			curl_setopt( $curl, CURLOPT_HEADER, false );
			curl_setopt( $curl, CURLINFO_HEADER_OUT, true );
			curl_setopt( $curl, CURLOPT_RETURNTRANSFER, true );
			curl_setopt( $curl, CURLOPT_URL, 'https://api.thememove.com/purchase/tf.php?code=' . $code );
			$json = curl_exec( $curl );
			$json = json_decode( $json, true );

			return $json;
		}

		// Check theme update
		public static function check_theme_update() {
			self::get_info();
			$update_data = array();
			$has_update  = false;
			if ( self::$info['api'] ) {
				$request = wp_remote_get( self::$info['api'] . '/changelogs.json', array( 'timeout' => 120 ) );
				if ( is_wp_error( $request ) ) {
					return;
				}
				$updates = json_decode( wp_remote_retrieve_body( $request ), true );
				if ( is_array( $updates ) ) {
					foreach ( $updates as $ukey => $uval ) {
						if ( version_compare( $ukey, INSIGHT_CORE_THEME_VERSION ) == 1 ) {
							$update_data['new_version'] = $ukey;
							$update_data['package']     = self::$info['api'] . '/' . $ukey . '.zip';
							$update_data['time']        = $uval['time'];
							$update_data['desc']        = $uval['desc'];
							$has_update                 = true;
							break;
						}
					}
				}
			}
			if ( $has_update ) {
				return $update_data;
			} else {
				return false;
			}
		}

		public static function check_valid_update() {

			if ( self::is_envato_hosted() ) {
				return true;
			}

			$can_update    = false;
			$purchase_code = get_option( 'insight_core_purchase_code' ); // Purchase code in database

			// Check purchase code still valid?
			$purchase_info = InsightCore::check_purchase_code( $purchase_code );

			if ( is_array( $purchase_info ) && count( $purchase_info ) > 0 ) {

				// Check item_id
				$tf        = explode( '/', self::$info['tf'] );
				$item_id   = end( $tf );
				$p_item_id = $purchase_info['item_id'];

				$can_update = ( $item_id == $p_item_id ) && current_user_can( 'update_themes' );
			}

			return $can_update;
		}

		// Update option count
		public static function update_option_count( $option ) {

			if ( get_option( $option ) != false ) {
				update_option( $option, get_option( $option ) + 1 );
			} else {
				update_option( $option, '1' );
			}
		}

		// Update option array
		public function update_option_array( $option, $value ) {
			if ( get_option( $option ) ) {
				$options = get_option( $option );
				if ( ! in_array( $value, $options ) ) {
					$options[] = $value;
					update_option( $option, $options );
				}
			} else {
				update_option( $option, array( $value ) );
			}
		}

		// Get action link for each plugin
		public static function plugin_action( $item ) {
			$installed_plugins        = get_plugins();
			$item['sanitized_plugin'] = $item['name'];
			$actions                  = array();
			// We have a repo plugin
			if ( ! $item['version'] ) {
				$item['version'] = TGM_Plugin_Activation::$instance->does_plugin_have_update( $item['slug'] );
			}
			if ( ! isset( $installed_plugins[ $item['file_path'] ] ) ) {
				// Display install link
				$actions = sprintf( '<a href="%1$s" title="Install %2$s">Install</a>',
					esc_url( wp_nonce_url( add_query_arg( array(
						'page'          => urlencode( TGM_Plugin_Activation::$instance->menu ),
						'plugin'        => urlencode( $item['slug'] ),
						'plugin_name'   => urlencode( $item['sanitized_plugin'] ),
						'plugin_source' => urlencode( $item['source'] ),
						'tgmpa-install' => 'install-plugin',
					),
						TGM_Plugin_Activation::$instance->get_tgmpa_url() ),
						'tgmpa-install',
						'tgmpa-nonce' ) ),
					$item['sanitized_plugin'] );
			} elseif ( is_plugin_inactive( $item['file_path'] ) ) {
				// Display activate link
				$actions = sprintf( '<a href="%1$s" title="Activate %2$s">Activate</a>',
					esc_url( add_query_arg( array(
						'plugin'               => urlencode( $item['slug'] ),
						'plugin_name'          => urlencode( $item['sanitized_plugin'] ),
						'plugin_source'        => urlencode( $item['source'] ),
						'tgmpa-activate'       => 'activate-plugin',
						'tgmpa-activate-nonce' => wp_create_nonce( 'tgmpa-activate' ),
					),
						admin_url( 'admin.php?page=insight-core' ) ) ),
					$item['sanitized_plugin'] );
			} elseif ( version_compare( $installed_plugins[ $item['file_path'] ]['Version'], $item['version'], '<' ) ) {
				// Display update link
				$actions = sprintf( '<a href="%1$s" title="Install %2$s">Update</a>',
					wp_nonce_url( add_query_arg( array(
						'page'          => urlencode( TGM_Plugin_Activation::$instance->menu ),
						'plugin'        => urlencode( $item['slug'] ),
						'tgmpa-update'  => 'update-plugin',
						'plugin_source' => urlencode( $item['source'] ),
						'version'       => urlencode( $item['version'] ),
					),
						TGM_Plugin_Activation::$instance->get_tgmpa_url() ),
						'tgmpa-update',
						'tgmpa-nonce' ),
					$item['sanitized_plugin'] );
			} elseif ( is_plugin_active( $item['file_path'] ) ) {
				// Display deactivate link
				$actions = sprintf( '<a href="%1$s" title="Deactivate %2$s">Deactivate</a>',
					esc_url( add_query_arg( array(
						'plugin'                 => urlencode( $item['slug'] ),
						'plugin_name'            => urlencode( $item['sanitized_plugin'] ),
						'plugin_source'          => urlencode( $item['source'] ),
						'tgmpa-deactivate'       => 'deactivate-plugin',
						'tgmpa-deactivate-nonce' => wp_create_nonce( 'tgmpa-deactivate' ),
					),
						admin_url( 'admin.php?page=insight-core' ) ) ),
					$item['sanitized_plugin'] );
			}

			return $actions;
		}

		// Remove Rev Slider Metabox
		public function insight_remove_revolution_slider_meta_boxes() {
			remove_meta_box( 'mymetabox_revslider_0', 'page', 'normal' );
			remove_meta_box( 'mymetabox_revslider_0', 'post', 'normal' );
			remove_meta_box( 'mymetabox_revslider_0', 'ic_popup', 'normal' );
			remove_meta_box( 'mymetabox_revslider_0', 'ic_mega_menu', 'normal' );
		}

		/**
		 * Add extra fields to Contact info section in edit profile page.
		 */
		public function add_extra_fields_for_contactmethods( $contactmethods ) {

			$extra_fields = apply_filters( 'insight_core_user_contactmethods', array() );
			if ( ! empty ( $extra_fields ) ) {
				foreach ( $extra_fields as $field ) {
					if ( ! isset( $contactmethods[ $field['name'] ] ) ) {
						$contactmethods[ $field['name'] ] = $field['label'];
					}
				}
			}

			return $contactmethods;
		}

		public static function is_envato_hosted() {
			return ( defined( 'ENVATO_HOSTED_SITE' ) && defined( 'SUBSCRIPTION_CODE' ) );
		}

	}

	new InsightCore();

	register_activation_hook( __FILE__,
		array(
			'InsightCore',
			'insight_core_activation_hook',
		) );
}
