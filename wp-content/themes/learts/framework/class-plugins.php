<?php

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Plugin installation and activation for WordPress themes
 *
 * @package learts
 */
if ( ! class_exists( 'Learts_Register_Plugins' ) ) {

	class Learts_Register_Plugins {

		/**
		 * Insight_Register_Plugins constructor.
		 */
		public function __construct() {
			add_filter( 'insight_core_tgm_plugins', array( $this, 'plugin_list' ) );
			add_action( 'tgmpa_register', array( $this, 'register_plugins' ), 11, 1 );
		}

		/**
		 * Register required plugins
		 *
		 * @return array
		 */
		public function plugin_list() {
			$plugins = array(

				array(
					'name'     => 'Insight Core',
					'slug'     => 'insight-core',
                    'source'   => get_template_directory() . '/plugins/insight-core-1.6.7.6-no-kirki.zip',
                    'version'  => '1.6.7.6',
					'required' => true,
				),

				array(
					'name'     => 'Redux Framework',
					'slug'     => 'redux-framework',
					'required' => true,
				),

				array(
					'name'     => 'WooCommerce',
					'slug'     => 'woocommerce',
					'required' => true,
				),

				array(
					'name'     => 'WPBakery Page Builder',
					'slug'     => 'js_composer',
					'source'   => 'https://api.thememove.com/download/js_composer-6.8.0-8iS5uZAk3e.zip',
					'version'  => '6.8.0',
					'required' => true,
				),

				array(
					'name'     => esc_html__( 'WPBakery Page Builder (Visual Composer) Clipboard', 'learts' ),
					'slug'     => 'vc_clipboard',
					'source'   => 'https://api.thememove.com/download/vc_clipboard-5.0.2-c1T0eAUXZF.zip',
					'version'  => '5.0.2',
					'required' => false,
				),

				array(
					'name'     => 'Revolution Slider',
					'slug'     => 'revslider',
					'source'   => 'https://api.thememove.com/download/revslider-6.5.14-8iS5uZAk3e.zip',
					'version'  => '6.5.14',
					'required' => true,
				),

				array(
					'name'     => 'Learts Addons',
					'slug'     => 'learts-addons',
					'source'   => get_template_directory() . '/plugins/learts-addons.zip',
					'version'  => '1.6.6',
					'required' => true,
				),

				array(
					'name'     => 'Insight Swatches',
					'slug'     => 'insight-swatches',
					'source'   => 'https://api.thememove.com/download/insight-swatches-1.2.4-eUhbSfkI5b.zip',
					'version'  => '1.2.4',
					'required' => true,
				),

				array(
					'name'     => 'WooCommerce Brands Pro',
					'slug'     => 'woo-brand',
					'source'   => 'https://api.thememove.com/download/woo-brand-4.5.0-F8W9od8Ga.zip',
					'required' => false,
					'version'  => '4.5.0',
				),

				array(
					'name'     => 'WooCommerce Bought Together',
					'slug'     => 'woo-bought-together',
					'required' => false,
				),

				array(
					'name'     => 'WP-PostViews',
					'slug'     => 'wp-postviews',
					'required' => false,
				),
				array(
					'name'     => 'Shoppable Images Lite',
					'slug'     => 'mabel-shoppable-images-lite',
					'required' => false,
				),
				array(
					'name'     => 'Safe SVG',
					'slug'     => 'safe-svg',
					'required' => false,
				),
				array(
					'name'     => 'Regenerate Thumbnails',
					'slug'     => 'regenerate-thumbnails',
					'required' => false,
				),
				array(
					'name'     => 'YITH WooCommerce Wishlist',
					'slug'     => 'yith-woocommerce-wishlist',
					'required' => false,
				),

				array(
					'name'     => 'YITH WooCommerce Compare',
					'slug'     => 'yith-woocommerce-compare',
					'required' => false,
				),

				array(
					'name'     => 'Contact Form 7',
					'slug'     => 'contact-form-7',
					'required' => false,
				),

			);

			return $plugins;
		}

		function register_plugins() {
			$plugins = array();
			$plugins = apply_filters( 'insight_core_tgm_plugins', $plugins );
			$config  = array(
				'id'           => 'tgmpa',
				// Unique ID for hashing notices for multiple instances of TGMPA.
				'default_path' => '',
				// Default absolute path to pre-packaged plugins.
				'menu'         => 'tgmpa-install-plugins',
				// Menu slug.
				'parent_slug'  => 'insight-core',
				// Parent menu slug.
				'capability'   => 'edit_theme_options',
				// Capability needed to view plugin install page, should be a capability associated with the parent menu used.
				'has_notices'  => true,
				// Show admin notices or not.
				'dismissable'  => true,
				// If false, a user cannot dismiss the nag message.
				'dismiss_msg'  => '',
				// If 'dismissable' is false, this message will be output at top of nag.
				'is_automatic' => true,
				// Automatically activate plugins after installation or not.
				'message'      => '',
				// Message to output right before the plugins table.
				'strings'      => array(
					'page_title'                      => esc_html__( 'Install Required Plugins', 'learts' ),
					'menu_title'                      => esc_html__( 'Install Plugins', 'learts' ),
					'installing'                      => esc_html__( 'Installing Plugin: %s', 'learts' ),
					// %s = plugin name.
					'oops'                            => esc_html__( 'Something went wrong with the plugin API.',
						'learts' ),
					'notice_can_install_required'     => _n_noop( 'This theme requires the following plugin: %1$s.',
						'This theme requires the following plugins: %1$s.',
						'learts' ),
					// %1$s = plugin name(s).
					'notice_can_install_recommended'  => _n_noop( 'This theme recommends the following plugin: %1$s.',
						'This theme recommends the following plugins: %1$s.',
						'learts' ),
					// %1$s = plugin name(s).
					'notice_cannot_install'           => _n_noop( 'Sorry, but you do not have the correct permissions to install the %1$s plugin.',
						'Sorry, but you do not have the correct permissions to install the %1$s plugins.',
						'learts' ),
					// %1$s = plugin name(s).
					'notice_ask_to_update'            => _n_noop( 'The following plugin needs to be updated to its latest version to ensure maximum compatibility with this theme: %1$s.',
						'The following plugins need to be updated to their latest version to ensure maximum compatibility with this theme: %1$s.',
						'learts' ),
					// %1$s = plugin name(s).
					'notice_ask_to_update_maybe'      => _n_noop( 'There is an update available for: %1$s.',
						'There are updates available for the following plugins: %1$s.',
						'learts' ),
					// %1$s = plugin name(s).
					'notice_cannot_update'            => _n_noop( 'Sorry, but you do not have the correct permissions to update the %1$s plugin.',
						'Sorry, but you do not have the correct permissions to update the %1$s plugins.',
						'learts' ),
					// %1$s = plugin name(s).
					'notice_can_activate_required'    => _n_noop( 'The following required plugin is currently inactive: %1$s.',
						'The following required plugins are currently inactive: %1$s.',
						'learts' ),
					// %1$s = plugin name(s).
					'notice_can_activate_recommended' => _n_noop( 'The following recommended plugin is currently inactive: %1$s.',
						'The following recommended plugins are currently inactive: %1$s.',
						'learts' ),
					// %1$s = plugin name(s).
					'notice_cannot_activate'          => _n_noop( 'Sorry, but you do not have the correct permissions to activate the %1$s plugin.',
						'Sorry, but you do not have the correct permissions to activate the %1$s plugins.',
						'learts' ),
					// %1$s = plugin name(s).
					'install_link'                    => _n_noop( 'Begin installing plugin',
						'Begin installing plugins',
						'learts' ),
					'update_link'                     => _n_noop( 'Begin updating plugin',
						'Begin updating plugins',
						'learts' ),
					'activate_link'                   => _n_noop( 'Begin activating plugin',
						'Begin activating plugins',
						'learts' ),
					'return'                          => esc_html__( 'Return to Required Plugins Installer', 'learts' ),
					'plugin_activated'                => esc_html__( 'Plugin activated successfully.', 'learts' ),
					'activated_successfully'          => esc_html__( 'The following plugin was activated successfully:',
						'learts' ),
					'plugin_already_active'           => esc_html__( 'No action taken. Plugin %1$s was already active.',
						'learts' ),
					// %1$s = plugin name(s).
					'plugin_needs_higher_version'     => esc_html__( 'Plugin not activated. A higher version of %s is needed for this theme. Please update the plugin.',
						'learts' ),
					// %1$s = plugin name(s).
					'complete'                        => esc_html__( 'All plugins installed and activated successfully. %1$s',
						'learts' ),
					// %s = dashboard link.
					'contact_admin'                   => esc_html__( 'Please contact the administrator of this site for help.',
						'learts' ),
					'nag_type'                        => 'updated',
					// Determines admin notice type - can only be 'updated', 'update-nag' or 'error'.
				),
			);

			tgmpa( $plugins, $config );

		}
	}

	new Learts_Register_Plugins();
}
