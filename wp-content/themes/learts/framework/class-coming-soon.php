<?php

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Helper functions
 *
 * @package   InsightFramework
 */
if ( ! class_exists( 'Learts_Coming_Soon' ) ) {

	class Learts_Coming_Soon {

		public function __construct() {

			add_action( 'template_redirect',
				array(
					$this,
					'redirect',
				) );
		}

		/**
		 * Check current page is coming soon page or not?
		 *
		 * @return bool
		 */
		public static function is_coming_soon_page() {

			$pages_ids = Learts_Helper::get_pages_ids_from_template( 'coming-soon' );

			return ( ! empty( $pages_ids ) && is_page( $pages_ids ) );
		}

		/**
		 * Redirect your site to the coming soon page
		 */
		public static function redirect() {

			if ( ! learts_get_option( 'coming_soon_mode_on' ) || is_user_logged_in() ) {
				return;
			}

			$page_id = Learts_Helper::get_pages_ids_from_template( 'coming-soon' );

			$page_id = current( $page_id );

			if ( ! $page_id ) {
				return;
			}

			if ( ! is_page( $page_id ) && ! is_user_logged_in() ) {
				wp_redirect( get_permalink( $page_id ) );
				exit();
			}
		}
	}

	new Learts_Coming_Soon();
}
