<?php

/**
 * Define constants
 *
 * @since 1.0
 */
$learts_theme = wp_get_theme();

if ( ! empty( $learts_theme['Template'] ) ) {
	$learts_theme = wp_get_theme( $learts_theme['Template'] );
}

if ( ! defined( 'DS' ) ) {
	define( 'DS', DIRECTORY_SEPARATOR );
}

define( 'LEARTS_THEME_NAME', $learts_theme['Name'] );
define( 'LEARTS_THEME_SLUG', $learts_theme['Template'] );
define( 'LEARTS_THEME_VERSION', $learts_theme['Version'] );
define( 'LEARTS_THEME_DIR', get_template_directory() );
define( 'LEARTS_THEME_URI', get_template_directory_uri() );
define( 'LEARTS_CHILD_THEME_URI', get_stylesheet_directory_uri() );
define( 'LEARTS_CHILD_THEME_DIR', get_stylesheet_directory() );

define( 'LEARTS_LIBS_URI', LEARTS_THEME_URI . '/assets/libs' );

define( 'LEARTS_IMAGES', LEARTS_THEME_URI . '/assets/images' );
define( 'LEARTS_ADMIN_IMAGES', LEARTS_THEME_URI . '/assets/admin/images' );

define( 'LEARTS_INC_DIR', LEARTS_THEME_DIR . DS . 'includes' );
define( 'LEARTS_INC_URI', LEARTS_THEME_URI . '/includes' );
define( 'LEARTS_FRAMEWORK_DIR', LEARTS_THEME_DIR . DS . 'framework' );
define( 'LEARTS_FRAMEWORK_URI', LEARTS_THEME_URI . '/framework' );

define( 'LEARTS_OPTIONS_DIR', LEARTS_INC_DIR . DS . 'theme-options' );
define( 'LEARTS_IS_RTL', is_rtl() ? true : false );

/**
 * Load Insight Framework.
 *
 * @since 1.0
 */
require_once( LEARTS_FRAMEWORK_DIR . DS . 'class-debug.php' );
require_once( LEARTS_FRAMEWORK_DIR . DS . 'class-coming-soon.php' );
require_once( LEARTS_FRAMEWORK_DIR . DS . 'class-compatible.php' );
require_once( LEARTS_FRAMEWORK_DIR . DS . 'class-enqueue.php' );
require_once( LEARTS_FRAMEWORK_DIR . DS . 'class-extras.php' );
require_once( LEARTS_FRAMEWORK_DIR . DS . 'class-helper.php' );
require_once( LEARTS_FRAMEWORK_DIR . DS . 'class-import.php' );
require_once( LEARTS_FRAMEWORK_DIR . DS . 'class-init.php' );
require_once( LEARTS_FRAMEWORK_DIR . DS . 'class-instagram.php' );
require_once( LEARTS_FRAMEWORK_DIR . DS . 'class-metabox.php' );
require_once( LEARTS_FRAMEWORK_DIR . DS . 'class-tgm-plugin-activation.php' );
require_once( LEARTS_FRAMEWORK_DIR . DS . 'class-plugins.php' );
require_once( LEARTS_FRAMEWORK_DIR . DS . 'class-sercurity.php' );
require_once( LEARTS_FRAMEWORK_DIR . DS . 'class-templates.php' );
require_once( LEARTS_FRAMEWORK_DIR . DS . 'class-walker-nav-menu.php' );
require_once( LEARTS_FRAMEWORK_DIR . DS . 'class-walker-nav-menu-edit.php' );

if ( class_exists( 'Redux' ) ) {
	require_once( LEARTS_FRAMEWORK_DIR . DS . 'class-redux.php' );
}

if ( class_exists( 'WooCommerce' ) ) {
	require_once( LEARTS_FRAMEWORK_DIR . DS . 'class-woo.php' );
}

/**
 * Get option from Theme Options
 */
if ( ! function_exists( 'learts_get_option' ) ) {
	function learts_get_option( $option ) {
		return Learts_Helper::get_option( $option );
	}
}
