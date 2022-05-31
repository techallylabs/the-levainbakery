<?php

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Initial OneClick import for this theme
 *
 * @package   InsightFramework
 */
if ( ! class_exists( 'Learts_Import' ) ) {

	class Learts_Import {

		/**
		 * The constructor.
		 */
		public function __construct() {
			// Import Demo.
			add_filter( 'insight_core_import_demos', array( $this, 'import_demos' ) );

			// generate thumbnail
			add_filter( 'insight_core_import_generate_thumb', array( $this, 'generate_thumb' ) );
		}

		/**
		 * Import Demo
		 *
		 * @since 1.0
		 */
		public function import_demos() {
			return array(
				'full'  => array(
					'screenshot'  => LEARTS_THEME_URI . Learts_Helper::get_config( 'screenshot' ),
					'name'        => LEARTS_THEME_NAME . esc_html__( ' - Full Demo', 'learts' ),
					'description' => esc_html__( 'This version will import the full demo including all images into your site. Once installed, your site would look exactly like our demo. However, this version is large in size and will take more time.',
						'learts' ),
					'url'         => 'https://api.thememove.com/import/learts/learts-import-full-1.5.3.zip',
				),
				'dummy' => array(
					'screenshot'  => LEARTS_THEME_URI . Learts_Helper::get_config( 'screenshot' ),
					'name'        => LEARTS_THEME_NAME . esc_html__( ' - Dummy', 'learts' ),
					'description' => esc_html__( 'This is the miniature version of Learts demo where images are replaced by placeholders. If you encounter problems installing the full demo, try this one. It’s lightweight and much faster.',
						'learts' ),
					'url'         => 'https://api.thememove.com/import/learts/learts-import-dummy.zip',
				),
			);
		}

		public function generate_thumb() {
			return true;
		}
	}

	new Learts_Import();
}
