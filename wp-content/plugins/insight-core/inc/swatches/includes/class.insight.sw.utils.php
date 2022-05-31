<?php

if ( ! class_exists( 'Insight_Attribute_Swatches_Utils' ) ) {

	class Insight_Attribute_Swatches_Utils {

		/**
		 * Locate the templates and return the path of the file found
		 *
		 * @param $path
		 * @param null $var
		 *
		 * @return mixed|void
		 */
		public static function insight_sw_locate_template( $path, $var = null ) {
			global $woocommerce;

			if ( function_exists( 'WC' ) ) {
				$woocommerce_base = WC()->template_path();
			} elseif ( defined( 'WC_TEMPLATE_PATH' ) ) {
				$woocommerce_base = WC_TEMPLATE_PATH;
			} else {
				$woocommerce_base = $woocommerce->plugin_path() . '/templates/';
			}

			$template_woocommerce_path = $woocommerce_base . $path;
			$template_path             = '/' . $path;
			$plugin_path               = INSIGHT_SW_PATH . 'templates/' . $path;

			$located = locate_template( array(
				$template_woocommerce_path, // Search in <theme>/woocommerce/
				$template_path,             // Search in <theme>/
			) );

			// locate templates from plugin
			if ( ! $located && file_exists( $plugin_path ) ) {
				return apply_filters( 'insight_sw_locate_template', $plugin_path, $path );
			}

			// locate templates from theme
			return apply_filters( 'insight_sw_locate_template', $located, $path );
		}

		/**
		 * Retrieve a template file.
		 *
		 * @param $path
		 * @param null $var
		 * @param bool $return
		 *
		 * @return string
		 */
		public static function insight_sw_get_template( $path, $var = null, $return = false ) {

			$located = self::insight_sw_locate_template( $path, $var );

			if ( $var && is_array( $var ) ) {
				extract( $var );
			}

			if ( $return ) {
				ob_start();
			}

			// include file located
			include( $located );

			if ( $return ) {
				return ob_get_clean();
			}
		}

		/**
		 * UTF8 URL decode
		 *
		 * @param $str
		 *
		 * @return string
		 */
		public static function utf8_urldecode( $str ) {
			$str = preg_replace( "/%u([0-9a-f]{3,4})/i", "&#x\\1;", urldecode( $str ) );

			return html_entity_decode( $str, null, 'UTF-8' );
		}

	}
}