<?php
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Enqueue child scripts
 */
add_action( 'wp_enqueue_scripts', 'learts_child_enqueue_scripts' );
if ( ! function_exists( 'learts_child_enqueue_scripts' ) ) {

	function learts_child_enqueue_scripts() {
		if ( is_rtl() ) {
			wp_enqueue_style( 'learts-main-style', trailingslashit( LEARTS_THEME_URI ) . 'style-rtl.css' );
			wp_enqueue_style( 'learts-style-rtl-custom', trailingslashit( LEARTS_THEME_URI ) . 'style-rtl-custom.css' );
		} else {
			wp_enqueue_style( 'learts-main-style', trailingslashit( LEARTS_THEME_URI ) . 'style.css' );
		}
		wp_enqueue_style( 'learts-child-style', trailingslashit( LEARTS_CHILD_THEME_URI ) . 'style.css' );
		wp_enqueue_script( 'child-script',
			trailingslashit( LEARTS_CHILD_THEME_URI ) . 'script.js',
			array( 'jquery' ),
			null,
			true );
	}

}

// Prevent loading this file directly
defined( 'ABSPATH' ) || exit;

