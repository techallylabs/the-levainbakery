<?php

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Learts_VC {


	public function __construct() {


		// Define VC-Templates folder for shortcodes
		if ( function_exists( 'vc_set_shortcodes_templates_dir' ) ) {
			$new_vc_dir = LEARTS_ADDONS_DIR . 'includes/templates';
			vc_set_shortcodes_templates_dir( $new_vc_dir );
		}

		// active VC
		add_action( 'vc_before_init', array( $this, 'set_as_theme' ) );

		// Define VC-Templates folder for shortcodes
		add_filter( 'vc_shortcodes_css_class', array( $this, 'rewrite_class_name' ), 10, 2 );
		add_filter( 'vc_google_fonts_get_fonts_filter', array( $this, 'update_vc_google_fonts' ) );

		add_action( 'vc_after_init', array( $this, 'load_icon_fonts' ) );
		add_action( 'vc_after_init', array( $this, 'load_params' ) );
		add_action( 'vc_after_init', array( $this, 'load_shortcodes' ) );
		add_action( 'vc_after_init', array( $this, 'update_shortcode_params' ) );
		add_action( 'vc_after_init', array( $this, 'add_icons_param_to_shortcode' ) );

		add_action( 'learts_after_page_container', array( $this, 'shortcode_css' ), 999 );
	}

	public function set_as_theme() {
		vc_set_as_theme();
	}

	/**
	 * Rewrite class name for rows and columns
	 *
	 * @param $class_string
	 * @param $tag
	 *
	 * @return mixed
	 */
	public function rewrite_class_name( $class_string, $tag ) {

		if ( $tag == 'vc_row' || $tag == 'vc_row_inner' ) {
			$class_string = str_replace( 'vc_row-fluid', 'row', $class_string );
		}
		if ( $tag == 'vc_column' || $tag == 'vc_column_inner' ) {
			$class_string = preg_replace( '/vc_col-xs-(\d{1,2})/', 'col-xs-$1', $class_string );
			$class_string = preg_replace( '/vc_col-sm-(\d{1,2})/', 'col-sm-$1', $class_string );
			$class_string = preg_replace( '/vc_col-md-(\d{1,2})/', 'col-md-$1', $class_string );
			$class_string = preg_replace( '/vc_col-lg-(\d{1,2})/', 'col-lg-$1', $class_string );
			$class_string = preg_replace( '/vc_col-xs-offset-(\d{1,2})/', 'offset-xs-$1', $class_string );
			$class_string = preg_replace( '/vc_col-sm-offset-(\d{1,2})/', 'offset-sm-$1', $class_string );
			$class_string = preg_replace( '/vc_col-md-offset-(\d{1,2})/', 'offset-md-$1', $class_string );
			$class_string = preg_replace( '/vc_col-lg-offset-(\d{1,2})/', 'offset-lg-$1', $class_string );
		}

		return $class_string;
	}

	/**
	 * Update missing Google fonts
	 *
	 * @return array|mixed|object
	 */
	public function update_vc_google_fonts( $fonts ) {

		$fonts[] = (object) array(
			'font_family' => 'Work Sans',
			'font_styles' => '100,200,300,regular,500,600,700,800,900',
			'font_types'  => '100 thin regular:100:normal,200 extra-light regular:200:normal,300 light regular:300:normal,400 regular:400:normal,500 medium regular:500:normal,600 semi-bold regular:600:normal,700 bold regular:700:normal,800 extra-bold regular:800:normal,900 black regular:900:normal',
		);

		usort( $fonts, array( $this, 'sort_fonts' ) );

		return $fonts;
	}

	/**
	 * Sort fonts base on name
	 *
	 * @param object $a
	 * @param object $b
	 *
	 * @return int
	 */
	private function sort_fonts( $a, $b ) {
		return strcmp( $a->font_family, $b->font_family );
	}

	/**
	 * Generate shortcode CSS
	 */
	public function shortcode_css() {

		global $learts_shortcode_css;
		$js_output = '';
		$js_output .= 'if ( _leartsInlineStyle !== null ) {';
		$js_output .= '_leartsInlineStyle.textContent+=\'' . $this->text2line( $learts_shortcode_css ) . '\';';
		$js_output .= '}';
		wp_add_inline_script( 'learts-main-js', $this->text2line( $js_output ) );
	}

	/**
	 * Get shortcode id
	 *
	 * @param $name
	 *
	 * @return string
	 */
	public static function get_learts_shortcode_id( $name ) {

		global $learts_shortcode_id;

		if ( ! $learts_shortcode_id ) {
			$learts_shortcode_id = 1;
		}

		return $name . '-' . ( $learts_shortcode_id ++ );
	}

	/**
	 * Load icon fonts
	 */
	public function load_icon_fonts() {
		require_once LEARTS_ADDONS_DIR . 'includes/fontlibs/font-awesome-pro.php';
		require_once LEARTS_ADDONS_DIR . 'includes/fontlibs/ionicons.php';
		require_once LEARTS_ADDONS_DIR . 'includes/fontlibs/themify-icons.php';
	}

	/**
	 * Load VC Params
	 */
	public function load_params() {
		require_once LEARTS_ADDONS_DIR . 'includes/params/learts-ajax-search/learts_ajax_search.php';
		require_once LEARTS_ADDONS_DIR . 'includes/params/learts-chosen/learts_chosen.php';
		require_once LEARTS_ADDONS_DIR . 'includes/params/learts-datetime-picker/learts_datetime_picker.php';
		require_once LEARTS_ADDONS_DIR . 'includes/params/learts-number/learts_number.php';
		require_once LEARTS_ADDONS_DIR . 'includes/params/learts-social-links/learts_social_links.php';
	}

	/**
	 * Load shortcode
	 */
	public function load_shortcodes() {
		require_once LEARTS_ADDONS_DIR . '/includes/shortcodes/learts-simple-banner.php';
		require_once LEARTS_ADDONS_DIR . '/includes/shortcodes/learts-blog.php';
		require_once LEARTS_ADDONS_DIR . '/includes/shortcodes/learts-button.php';
		require_once LEARTS_ADDONS_DIR . '/includes/shortcodes/learts-countdown.php';
		require_once LEARTS_ADDONS_DIR . '/includes/shortcodes/learts-gmaps.php';
		require_once LEARTS_ADDONS_DIR . '/includes/shortcodes/learts-icon-box.php';
		require_once LEARTS_ADDONS_DIR . '/includes/shortcodes/learts-image-carousel.php';
		require_once LEARTS_ADDONS_DIR . '/includes/shortcodes/learts-image-360.php';
		require_once LEARTS_ADDONS_DIR . '/includes/shortcodes/learts-mailchimp.php';
		require_once LEARTS_ADDONS_DIR . '/includes/shortcodes/learts-space.php';
		require_once LEARTS_ADDONS_DIR . '/includes/shortcodes/learts-social.php';
		require_once LEARTS_ADDONS_DIR . '/includes/shortcodes/learts-team-member.php';
		require_once LEARTS_ADDONS_DIR . '/includes/shortcodes/learts-testimonial-carousel.php';
		require_once LEARTS_ADDONS_DIR . '/includes/shortcodes/learts-quote.php';
		require_once LEARTS_ADDONS_DIR . '/includes/shortcodes/learts-banner-grid-group.php';
		require_once LEARTS_ADDONS_DIR . '/includes/shortcodes/learts-megamenu.php';
		require_once LEARTS_ADDONS_DIR . '/includes/shortcodes/learts-menu-vertical.php';
		require_once LEARTS_ADDONS_DIR . '/includes/shortcodes/learts-menu-grid.php';
		require_once LEARTS_ADDONS_DIR . '/includes/shortcodes/learts-banner-carousel.php';
		require_once LEARTS_ADDONS_DIR . '/includes/shortcodes/learts-product-widget.php';
		require_once LEARTS_ADDONS_DIR . '/includes/shortcodes/learts-image-box.php';
		//
		if ( class_exists( 'WooCommerce' ) ) {
			require_once LEARTS_ADDONS_DIR . '/includes/shortcodes/learts-product-carousel.php';
			require_once LEARTS_ADDONS_DIR . '/includes/shortcodes/learts-product-grid.php';
			require_once LEARTS_ADDONS_DIR . '/includes/shortcodes/learts-product-tabs.php';
			require_once LEARTS_ADDONS_DIR . '/includes/shortcodes/learts-product-categories.php';
			require_once LEARTS_ADDONS_DIR . '/includes/shortcodes/learts-product-category-banner.php';
		}

		if ( class_exists( 'woo_brands' ) ) {
			require_once LEARTS_ADDONS_DIR . '/includes/shortcodes/learts-brands-grid.php';
			require_once LEARTS_ADDONS_DIR . '/includes/shortcodes/learts-brands-carousel.php';
		}
	}

	/**
	 * Update param for shortcodes
	 */
	public function update_shortcode_params() {

		if ( function_exists( 'vc_update_shortcode_param' ) ) {

			/* Row */
			vc_update_shortcode_param( 'vc_row',
				array(
					'param_name' => 'full_width',
					'value'      => array(
						esc_html__( 'Default', 'learts' )                 => '',
						esc_html__( 'Wide row (from theme)', 'learts' )   => 'learts_wide_row',
						esc_html__( 'Stretch row', 'learts' )             => 'stretch_row',
						esc_html__( 'Stretch row and content', 'learts' ) => 'stretch_row_content',
						esc_html__( 'Stretch row and content (no paddings)',
							'learts' )                                    => 'stretch_row_content_no_spaces',
					),
				) );

			/* Column */
			vc_update_shortcode_param( 'vc_column',
				array(
					'param_name'  => 'sticky_column',
					'type'        => 'checkbox',
					'heading'     => esc_html__( 'Make this column to sticky?', 'learts' ),
					'description' => esc_html__( 'Attach this column to the page when the user scrolls such that it is always visible',
						'learts' ),
					'value'       => array( esc_html__( 'Yes', 'learts' ) => 'yes' ),
					'weight'      => 100,
				) );

			/* Custom Heading */
			vc_update_shortcode_param( 'vc_custom_heading',
				array(
					'param_name' => 'use_theme_fonts',
					'std'        => 'yes',
				) );
			vc_add_param( 'vc_custom_heading',
				array(
					'type'       => 'checkbox',
					'heading'    => esc_html( 'Use style title from theme.', 'learts' ),
					'param_name' => 'title_learts',
					'weight'     => 1,
				) );

			vc_add_param( 'vc_custom_heading',
				array(
					'type'        => 'dropdown',
					'heading'     => esc_html( 'Use font face from theme.', 'learts' ),
					'value'       => array(
						esc_html__( 'None', 'learts' )             => 'none',
						esc_html__( 'Use font Modesty', 'learts' ) => 'modesty',
						esc_html__( 'Use font Notera', 'learts' )  => 'notera',
						esc_html__( 'Use font Futura', 'learts' )  => 'futura',
					),
					'description' => esc_html( 'This is the highest priority setting for font.', 'learts' ),
					'param_name'  => 'use_font_face',
					'weight'      => 2,
				) );

			/* Tab */
			vc_update_shortcode_param( 'vc_tta_tabs',
				array(
					'param_name' => 'style',
					'value'      => array(
						esc_html__( 'Learts (from theme)', 'learts' ) => 'learts',
						esc_html__( 'Classic', 'learts' )             => 'classic',
						esc_html__( 'Modern', 'learts' )              => 'modern',
						esc_html__( 'Flat', 'learts' )                => 'flat',
						esc_html__( 'Outline', 'learts' )             => 'outline',
					),
				) );
			vc_update_shortcode_param( 'vc_tta_tabs',
				array(
					'param_name' => 'spacing',
					'std'        => '0',
				) );
			vc_update_shortcode_param( 'vc_tta_tabs',
				array(
					'param_name' => 'shape',
					'std'        => 'square',
					'dependency' => array(
						'element'            => 'style',
						'value_not_equal_to' => array( 'learts' ),
					),
				) );
			vc_update_shortcode_param( 'vc_tta_tabs',
				array(
					'param_name' => 'color',
					'dependency' => array(
						'element'            => 'style',
						'value_not_equal_to' => array( 'learts' ),
					),
				) );
			vc_update_shortcode_param( 'vc_tta_tabs',
				array(
					'param_name' => 'no_fill_content_area',
					'dependency' => array(
						'element'            => 'style',
						'value_not_equal_to' => array( 'learts' ),
					),
				) );
			vc_update_shortcode_param( 'vc_tta_tabs',
				array(
					'param_name' => 'no_fill_content_area',
					'std'        => 'true',
				) );

			/* Accordion */
			vc_update_shortcode_param( 'vc_tta_accordion',
				array(
					'param_name' => 'style',
					'value'      => array(
						esc_html__( 'Learts (from theme)', 'learts' ) => 'learts',
						esc_html__( 'Classic', 'learts' )             => 'classic',
						esc_html__( 'Modern', 'learts' )              => 'modern',
						esc_html__( 'Flat', 'learts' )                => 'flat',
						esc_html__( 'Outline', 'learts' )             => 'outline',
					),
				) );

			vc_update_shortcode_param( 'vc_tta_accordion',
				array(
					'param_name' => 'shape',
					'std'        => 'square',
					'dependency' => array(
						'element'            => 'style',
						'value_not_equal_to' => array( 'learts' ),
					),
				) );

			vc_update_shortcode_param( 'vc_tta_accordion',
				array(
					'param_name' => 'color',
					'dependency' => array(
						'element'            => 'style',
						'value_not_equal_to' => array( 'learts' ),
					),
				) );

			vc_update_shortcode_param( 'vc_tta_accordion',
				array(
					'param_name' => 'no_fill',
					'dependency' => array(
						'element'            => 'style',
						'value_not_equal_to' => array( 'learts' ),
					),
				) );

			vc_update_shortcode_param( 'vc_tta_accordion',
				array(
					'param_name' => 'no_fill',
					'std'        => 'true',
				) );

			/* Toggle */
			vc_update_shortcode_param( 'vc_toggle',
				array(
					'param_name' => 'style',
					'value'      => array(
						esc_html__( 'Default', 'learts' )             => 'default',
						esc_html__( 'Learts (from theme)', 'learts' ) => 'learts',
						esc_html__( 'Simple', 'learts' )              => 'simple',
						esc_html__( 'Round', 'learts' )               => 'round',
						esc_html__( 'Round Outline', 'learts' )       => 'round_outline',
						esc_html__( 'Rounded', 'learts' )             => 'rounded',
						esc_html__( 'Rounded Outline', 'learts' )     => 'rounded_outline',
						esc_html__( 'Square', 'learts' )              => 'square',
						esc_html__( 'Square Outline', 'learts' )      => 'square_outline',
						esc_html__( 'Arrow', 'learts' )               => 'arrow',
						esc_html__( 'Text Only', 'learts' )           => 'text_only',
					),
					'std'        => 'learts',
				) );
			vc_update_shortcode_param( 'vc_toggle',
				array(
					'param_name' => 'color',
					'dependency' => array(
						'element'            => 'style',
						'value_not_equal_to' => array( 'learts' ),
					),
				) );

			//Single Image
			vc_add_param( 'vc_single_image',
				array(
					'type'       => 'checkbox',
					'heading'    => esc_html( 'Use border inline with style theme.', 'learts' ),
					'param_name' => 'border_inline',
					'weight'     => 1,
				) );

			// Woo Brand Pro
			if ( class_exists( 'woo_brands' ) ) {

				vc_update_shortcode_param( 'pw_brand_vc_az_view',
					array(
						'param_name' => 'pw_style',
						'value'      => array(
							esc_html__( 'Style 1', 'learts' )             => 'wb-filter-style1',
							esc_html__( 'Style 2', 'learts' )             => 'wb-filter-style2',
							esc_html__( 'Style 3', 'learts' )             => 'wb-filter-style3',
							esc_html__( 'Style 4', 'learts' )             => 'wb-filter-style4',
							esc_html__( 'Style 5', 'learts' )             => 'wb-filter-style5',
							esc_html__( 'Style 6', 'learts' )             => 'wb-filter-style6',
							esc_html__( 'Style 7', 'learts' )             => 'wb-filter-style7',
							esc_html__( 'Style 8', 'learts' )             => 'wb-filter-style8',
							esc_html__( 'Learts (from theme)', 'learts' ) => 'wb-filter-learts',
						),
					) );

				vc_update_shortcode_param( 'pw_brand_vc_az_view',
					array(
						'param_name' => 'pw_brand_list_style',
						'value'      => array(
							esc_html__( 'Style 1', 'learts' )             => 'wb-brandlist-style1',
							esc_html__( 'Style 2', 'learts' )             => 'wb-brandlist-style2',
							esc_html__( 'Style 3', 'learts' )             => 'wb-brandlist-style3',
							esc_html__( 'Learts (from theme)', 'learts' ) => 'wb-brandlist-learts',
						),
					) );

				vc_update_shortcode_param( 'pw_all_vc_view',
					array(
						'param_name' => 'pw_filter_style',
						'value'      => array(
							esc_html__( 'Style 1', 'learts' )             => 'wb-multi-filter-style1',
							esc_html__( 'Style 2', 'learts' )             => 'wb-multi-filter-style2',
							esc_html__( 'Style 3', 'learts' )             => 'wb-multi-filter-style3',
							esc_html__( 'Learts (from theme)', 'learts' ) => 'wb-multi-filter-learts',
						),
					) );

				vc_update_shortcode_param( 'pw_all_vc_view',
					array(
						'param_name' => 'pw_style',
						'value'      => array(
							esc_html__( 'Style 1', 'learts' )             => 'wb-allview-style1',
							esc_html__( 'Style 2', 'learts' )             => 'wb-allview-style2',
							esc_html__( 'Style 3', 'learts' )             => 'wb-allview-style3',
							esc_html__( 'Learts (from theme)', 'learts' ) => 'wb-allview-learts',
						),
					) );

				vc_update_shortcode_param( 'pw_brand_vc_carousel',
					array(
						'param_name' => 'pw_style',
						'value'      => array(
							esc_html__( 'Style 1', 'learts' )             => 'wb-car-style1',
							esc_html__( 'Style 2', 'learts' )             => 'wb-car-style2',
							esc_html__( 'Style 3', 'learts' )             => 'wb-car-style3',
							esc_html__( 'Style 4', 'learts' )             => 'wb-car-style3',
							esc_html__( 'Style 5', 'learts' )             => 'wb-car-style3',
							esc_html__( 'Learts (from theme)', 'learts' ) => 'wb-car-learts',
						),
					) );

				vc_update_shortcode_param( 'pw_brand_vc_carousel',
					array(
						'param_name' => 'pw_carousel_style',
						'value'      => array(
							esc_html__( 'Style 1', 'learts' )             => 'wb-carousel-style1',
							esc_html__( 'Style 2', 'learts' )             => 'wb-carousel-style2',
							esc_html__( 'Style 3', 'learts' )             => 'wb-carousel-style3',
							esc_html__( 'Learts (from theme)', 'learts' ) => 'wb-carousel-learts',
						),
					) );

				vc_update_shortcode_param( 'pw_brand_vc_carousel',
					array(
						'param_name' => 'pw_round_corner',
						'dependency' => array(
							'element'            => 'pw_style',
							'value_not_equal_to' => 'wb-car-learts',
						),
					) );

				vc_update_shortcode_param( 'pw_brand_vc_carousel',
					array(
						'param_name' => 'pw_carousel_skin_style',
						'dependency' => array(
							'element'            => 'pw_carousel_style',
							'value_not_equal_to' => 'wb-carousel-learts',
						),
					) );

				vc_update_shortcode_param( 'pw_brand_vc_prodcut_carousel',
					array(
						'param_name' => 'pw_title_style',
						'value'      => array(
							esc_html__( 'Style 1', 'learts' )             => 'wb-brandpro-car-header-style1',
							esc_html__( 'Style 2', 'learts' )             => 'wb-brandpro-car-header-style2',
							esc_html__( 'Style 3', 'learts' )             => 'wb-brandpro-car-header-style3',
							esc_html__( 'Learts (from theme)', 'learts' ) => 'wb-brandpro-car-header-learts',
						),
					) );

				vc_update_shortcode_param( 'pw_brand_vc_prodcut_carousel',
					array(
						'param_name' => 'pw_item_style',
						'value'      => array(
							esc_html__( 'Style 1', 'learts' )             => 'wb-brandpro-style1',
							esc_html__( 'Style 2', 'learts' )             => 'wb-brandpro-style2',
							esc_html__( 'Learts (from theme)', 'learts' ) => 'wb-brandpro-learts',
						),
					) );

				vc_update_shortcode_param( 'pw_brand_vc_prodcut_carousel',
					array(
						'param_name' => 'pw_carousel_style',
						'value'      => array(
							esc_html__( 'Style 1', 'learts' )             => 'wb-carousel-style1',
							esc_html__( 'Style 2', 'learts' )             => 'wb-carousel-style2',
							esc_html__( 'Style 3', 'learts' )             => 'wb-carousel-style3',
							esc_html__( 'Learts (from theme)', 'learts' ) => 'wb-carousel-learts',
						),
					) );

				vc_update_shortcode_param( 'pw_brand_vc_prodcut_carousel',
					array(
						'param_name' => 'pw_carousel_skin_style',
						'dependency' => array(
							'element'            => 'pw_carousel_style',
							'value_not_equal_to' => 'wb-carousel-learts',
						),
					) );

				vc_update_shortcode_param( 'pw_brand_vc_prodcut_carousel',
					array(
						'param_name' => 'pw_item_marrgin',
						'dependency' => array(
							'element'            => 'pw_carousel_style',
							'value_not_equal_to' => 'wb-carousel-learts',
						),
					) );

				vc_update_shortcode_param( 'pw_brand_vc_product_grid',
					array(
						'param_name' => 'pw_title_style',
						'value'      => array(
							esc_html__( 'Style 1', 'learts' )             => 'wb-brandpro-car-header-style1',
							esc_html__( 'Style 2', 'learts' )             => 'wb-brandpro-car-header-style2',
							esc_html__( 'Style 3', 'learts' )             => 'wb-brandpro-car-header-style3',
							esc_html__( 'Learts (from theme)', 'learts' ) => 'wb-brandpro-car-header-learts',
						),
					) );

				vc_update_shortcode_param( 'pw_brand_vc_product_grid',
					array(
						'param_name' => 'pw_columns',
						'std'        => 4,
					) );
			}
		}
	}

	/**
	 * Add params for shortcodes
	 */
	public function add_icons_param_to_shortcode() {

		/* vc_tta_section */
		$this->add_icon_fonts( 'vc_tta_section',
			'i_type',
			'i_icon_material',
			array(
				'themifyicons' => 'i_icon_themifyicons',
			) );

		/* vc_btn */
		$this->add_icon_fonts( 'vc_btn',
			'i_type',
			'i_icon_pixelicons',
			array(
				'themifyicons' => 'i_icon_themifyicons',
			) );

		/* Icon */
		$this->add_icon_fonts();
	}

	/**
	 * Add param to a shortcodes
	 *
	 * @param $shortcode
	 * @param $break_param_name
	 * @param $attribute
	 */
	public function add_param_in_custom_position( $shortcode, $break_param_name, $attribute ) {

		$params = vc_get_shortcode( $shortcode )['params'];
		$weight = count( $params ) * 2;

		foreach ( $params as $param ) {

			if ( $break_param_name == $param['param_name'] ) {
				$attribute['weight'] = $weight;
				vc_add_param( $shortcode, $attribute );
			}

			$weight -= 2;
		}
	}

	/**
	 * Add custom icon libraries to the shortcodes which made by VC
	 *
	 * @param        $shortcode
	 * @param        $param_name
	 * @param        $break_param_name
	 * @param        $params_name
	 */
	function add_icon_fonts(
		$shortcode = 'vc_icon', $param_name = 'type', $break_param_name = 'icon_material', $params_name = array(
		'themifyicons' => 'icon_themifyicons',
	)
	) {
		$icon_arr = array(
			esc_html__( 'Font Awesome 5 Pro', 'learts-addons' ) => 'fa5pro',
			esc_html__( 'Open Iconic', 'learts-addons' )        => 'openiconic',
			esc_html__( 'Typicons', 'learts-addons' )           => 'typicons',
			esc_html__( 'Entypo', 'learts-addons' )             => 'entypo',
			esc_html__( 'Linecons', 'learts-addons' )           => 'linecons',
			esc_html__( 'Mono Social', 'learts-addons' )        => 'monosocial',
			esc_html__( 'Material', 'learts-addons' )           => 'material',
			esc_html__( 'Themify Icons', 'learts-addons' )      => 'themifyicons',
			esc_html__( 'Ionicons', 'learts-addons' )           => 'ionicons',
		);

		if ( $shortcode == 'vc_btn' ) {
			$icon_arr = array(
				esc_html__( 'Font Awesome 5 Pro', 'learts-addons' ) => 'fa5pro',
				esc_html__( 'Open Iconic', 'learts-addons' )        => 'openiconic',
				esc_html__( 'Typicons', 'learts-addons' )           => 'typicons',
				esc_html__( 'Entypo', 'learts-addons' )             => 'entypo',
				esc_html__( 'Linecons', 'learts-addons' )           => 'linecons',
				esc_html__( 'Mono Social', 'learts-addons' )        => 'monosocial',
				esc_html__( 'Material', 'learts-addons' )           => 'material',
				esc_html__( 'Pixel', 'learts-addons' )              => 'pixelicons',
				esc_html__( 'Themify Icons', 'learts-addons' )      => 'themifyicons',
				esc_html__( 'Ionicons', 'learts-addons' )           => 'ionicons',
			);
		}

		vc_update_shortcode_param( $shortcode,
			array(
				'param_name' => $param_name,
				'value'      => $icon_arr,
			) );

		$params = vc_get_shortcode( $shortcode )['params'];
		$weight = count( $params ) * 2;

		foreach ( $params as $param ) {

			vc_update_shortcode_param( $shortcode,
				array(
					'param_name' => $param['param_name'],
					'weight'     => $weight,
				) );

			if ( $break_param_name == $param['param_name'] ) {
				vc_add_params( $shortcode,
					array(

						array(
							'type'        => 'iconpicker',
							'heading'     => esc_html__( 'Icon', 'learts-addons' ),
							'param_name'  => 'icon_fa5pro',
							'value'       => 'far fa-address-book',
							'settings'    => array(
								'emptyIcon'    => false,
								'type'         => 'fa5pro',
								'iconsPerPage' => 400,
							),
							'dependency'  => array(
								'element' => $param_name,
								'value'   => 'fa5pro',
							),
							'description' => esc_html__( 'Select icon from library.', 'learts-addons' ),
							'weight'      => $weight - 1,
						),

						array(
							'type'        => 'iconpicker',
							'heading'     => esc_html__( 'Icon', 'learts-addons' ),
							'param_name'  => 'icon_themifyicons',
							'value'       => 'ti-arrow-up',
							'settings'    => array(
								'emptyIcon'    => false,
								'type'         => 'themifyicons',
								'iconsPerPage' => 400,
							),
							'dependency'  => array(
								'element' => $param_name,
								'value'   => 'themifyicons',
							),
							'description' => esc_html__( 'Select icon from library.', 'learts-addons' ),
							'weight'      => $weight - 1,
						),

						array(
							'type'        => 'iconpicker',
							'heading'     => esc_html__( 'Icon', 'learts-addons' ),
							'param_name'  => 'icon_ionicons',
							'value'       => 'ion-ionic',
							'settings'    => array(
								'emptyIcon'    => false,
								'type'         => 'ionicons',
								'iconsPerPage' => 500,
							),
							'dependency'  => array(
								'element' => $param_name,
								'value'   => 'ionicons',
							),
							'description' => esc_html__( 'Select icon from library.', 'learts-addons' ),
							'weight'      => $weight - 1,
						),

					) );
			}

			$weight -= 2;
		}
	}

	/**
	 * Icon libraries for our theme
	 *
	 * @param array $dependency
	 * @param bool  $admin_label
	 * @param bool  $allow_none
	 *
	 * @return array icon_array
	 */
	public static function icon_libraries( $dependency = array(), $admin_label = true, $allow_none = false ) {

		$icon_arr = array(
			esc_html__( 'Font Awesome 5 Pro', 'learts-addons' ) => 'fa5pro',
			esc_html__( 'Open Iconic', 'learts-addons' )        => 'openiconic',
			esc_html__( 'Typicons', 'learts-addons' )           => 'typicons',
			esc_html__( 'Entypo', 'learts-addons' )             => 'entypo',
			esc_html__( 'Linecons', 'learts-addons' )           => 'linecons',
			esc_html__( 'Mono Social', 'learts-addons' )        => 'monosocial',
			esc_html__( 'Material', 'learts-addons' )           => 'material',
			esc_html__( 'Themify Icons', 'learts-addons' )      => 'themifyicons',
			esc_html__( 'Ionicons', 'learts-addons' )           => 'ionicons',
		);

		if ( $allow_none ) {
			$icon_arr = array( esc_html__( 'None', 'learts-addons' ) => '' ) + $icon_arr;
		}

		return array(
			array(
				'group'       => esc_html__( 'Icon', 'learts-addons' ),
				'type'        => 'dropdown',
				'heading'     => esc_html__( 'Icon library', 'learts-addons' ),
				'admin_label' => $admin_label,
				'value'       => $icon_arr,
				'param_name'  => 'type',
				'description' => esc_html__( 'Select icon library.', 'learts-addons' ),
				'dependency'  => $dependency,
			),
			array(
				'group'       => esc_html__( 'Icon', 'learts-addons' ),
				'type'        => 'iconpicker',
				'heading'     => esc_html__( 'Icon', 'learts-addons' ),
				'param_name'  => 'icon_fa5pro',
				'value'       => 'far fa-address-book',
				'settings'    => array(
					'emptyIcon'    => false,
					'type'         => 'fa5pro',
					'iconsPerPage' => 4000,
				),
				'dependency'  => array(
					'element' => 'type',
					'value'   => 'fa5pro',
				),
				'description' => esc_html__( 'Select icon from library.', 'learts-addons' ),
			),
			array(
				'group'       => esc_html__( 'Icon', 'learts-addons' ),
				'type'        => 'iconpicker',
				'heading'     => esc_html__( 'Icon', 'learts-addons' ),
				'param_name'  => 'icon_openiconic',
				'value'       => 'vc-oi vc-oi-dial',
				'settings'    => array(
					'emptyIcon'    => false,
					'type'         => 'openiconic',
					'iconsPerPage' => 4000,
				),
				'dependency'  => array(
					'element' => 'type',
					'value'   => 'openiconic',
				),
				'description' => esc_html__( 'Select icon from library.', 'learts-addons' ),
			),
			array(
				'group'       => esc_html__( 'Icon', 'learts-addons' ),
				'type'        => 'iconpicker',
				'heading'     => esc_html__( 'Icon', 'learts-addons' ),
				'param_name'  => 'icon_typicons',
				'value'       => 'typcn typcn-adjust-brightness',
				'settings'    => array(
					'emptyIcon'    => false,
					'type'         => 'typicons',
					'iconsPerPage' => 4000,
				),
				'dependency'  => array(
					'element' => 'type',
					'value'   => 'typicons',
				),
				'description' => esc_html__( 'Select icon from library.', 'learts-addons' ),
			),
			array(
				'group'       => esc_html__( 'Icon', 'learts-addons' ),
				'type'        => 'iconpicker',
				'heading'     => esc_html__( 'Icon', 'learts-addons' ),
				'param_name'  => 'icon_entypo',
				'value'       => 'entypo-icon entypo-icon-note',
				'settings'    => array(
					'emptyIcon'    => false,
					'type'         => 'entypo',
					'iconsPerPage' => 4000,
				),
				'dependency'  => array(
					'element' => 'type',
					'value'   => 'entypo',
				),
				'description' => esc_html__( 'Select icon from library.', 'learts-addons' ),
			),
			array(
				'group'       => esc_html__( 'Icon', 'learts-addons' ),
				'type'        => 'iconpicker',
				'heading'     => esc_html__( 'Icon', 'learts-addons' ),
				'param_name'  => 'icon_linecons',
				'value'       => 'vc_li vc_li-heart',
				'settings'    => array(
					'emptyIcon'    => false,
					'type'         => 'linecons',
					'iconsPerPage' => 4000,
				),
				'dependency'  => array(
					'element' => 'type',
					'value'   => 'linecons',
				),
				'description' => esc_html__( 'Select icon from library.', 'learts-addons' ),
			),
			array(
				'group'       => esc_html__( 'Icon', 'learts-addons' ),
				'type'        => 'iconpicker',
				'heading'     => esc_html__( 'Icon', 'learts-addons' ),
				'param_name'  => 'icon_monosocial',
				'value'       => 'vc-mono vc-mono-fivehundredpx',
				'settings'    => array(
					'emptyIcon'    => false,
					'type'         => 'monosocial',
					'iconsPerPage' => 400,
				),
				'dependency'  => array(
					'element' => 'type',
					'value'   => 'monosocial',
				),
				'description' => esc_html__( 'Select icon from library.', 'learts-addons' ),
			),
			array(
				'group'       => esc_html__( 'Icon', 'learts-addons' ),
				'type'        => 'iconpicker',
				'heading'     => esc_html__( 'Icon', 'learts-addons' ),
				'param_name'  => 'icon_meterial',
				'value'       => 'vc-material vc-material-cake',
				'settings'    => array(
					'emptyIcon'    => false,
					'type'         => 'material',
					'iconsPerPage' => 400,
				),
				'dependency'  => array(
					'element' => 'type',
					'value'   => 'material',
				),
				'description' => esc_html__( 'Select icon from library.', 'learts-addons' ),
			),
			array(
				'group'       => esc_html__( 'Icon', 'learts-addons' ),
				'type'        => 'iconpicker',
				'heading'     => esc_html__( 'Icon', 'learts-addons' ),
				'param_name'  => 'icon_themifyicons',
				'value'       => 'ti-arrow-up',
				'settings'    => array(
					'emptyIcon'    => false,
					'type'         => 'themifyicons',
					'iconsPerPage' => 400,
				),
				'dependency'  => array(
					'element' => 'type',
					'value'   => 'themifyicons',
				),
				'description' => esc_html__( 'Select icon from library.', 'learts-addons' ),
			),

			array(
				'group'       => esc_html__( 'Icon', 'learts-addons' ),
				'type'        => 'iconpicker',
				'heading'     => esc_html__( 'Icon', 'learts-addons' ),
				'param_name'  => 'icon_ionicons',
				'value'       => 'ion-ionic',
				'settings'    => array(
					'emptyIcon'    => false,
					'type'         => 'ionicons',
					'iconsPerPage' => 500,
				),
				'dependency'  => array(
					'element' => 'type',
					'value'   => 'ionicons',
				),
				'description' => esc_html__( 'Select icon from library.', 'learts-addons' ),
			),
		);
	}

	/**
	 * Get common param for shortcodes
	 *
	 * @param        $param_name
	 * @param string $group
	 * @param string $dependency
	 *
	 * @return array
	 */
	public static function get_param( $param_name, $group = '', $dependency = '' ) {

		$param = array();

		switch ( $param_name ) {
			case 'css':
				$param = array(
					'group'      => esc_html__( 'Design Options', 'learts-addons' ),
					'type'       => 'css_editor',
					'heading'    => esc_html__( 'CSS box', 'learts-addons' ),
					'param_name' => 'css',
				);
				break;
			case 'columns':
				$param = array(
					'type'        => 'dropdown',
					'heading'     => esc_html__( 'Number of columns', 'learts-addons' ),
					'description' => esc_html__( 'Select number of columns in a row', 'learts-addons' ),
					'param_name'  => 'columns',
					'value'       => array(
						1,
						2,
						3,
						4,
						5,
						6,
					),
					'std'         => 4,
				);
				break;
			case 'el_class':
				$param = array(
					'type'        => 'textfield',
					'heading'     => esc_html__( 'Extra class name', 'learts-addons' ),
					'param_name'  => 'el_class',
					'description' => esc_html__( 'Style particular content element differently - add a class name and refer to it in custom CSS.',
						'learts-addons' ),
				);
				break;
			case 'animation':
				$param = array(
					'type'        => 'animation_style',
					'heading'     => esc_html__( 'Animation Style', 'text-domain' ),
					'param_name'  => 'animation',
					'description' => esc_html__( 'Choose your animation style', 'text-domain' ),
					'admin_label' => false,
					'weight'      => 0,
				);
				break;
			case 'order':
				$param = array(
					'group'       => $group,
					'type'        => 'dropdown',
					'param_name'  => 'orderby',
					'heading'     => esc_html__( 'Order by', 'learts-addons' ),
					'value'       => array(
						'',
						esc_html__( 'Date', 'learts-addons' )                  => 'date',
						esc_html__( 'Post ID', 'learts-addons' )               => 'ID',
						esc_html__( 'Author', 'learts-addons' )                => 'author',
						esc_html__( 'Title', 'learts-addons' )                 => 'title',
						esc_html__( 'Last modified date', 'learts-addons' )    => 'modified',
						esc_html__( 'Number of comments', 'learts-addons' )    => 'comment_count',
						esc_html__( 'Menu order/Page Order', 'learts-addons' ) => 'menu_order',
						esc_html__( 'Random order', 'learts-addons' )          => 'rand',
					),
					'description' => sprintf( wp_kses( __( 'Select how to sort retrieved posts. More at <a href="%s" target="_blank">WordPress codex page</a>.',
						'learts-addons' ),
						array(
							'a' => array(
								'href'   => array(),
								'target' => array(),
							),
						) ),
						esc_url( 'http://codex.wordpress.org/Class_Reference/WP_Query#Order_.26_Orderby_Parameters' ) ),
					'dependency'  => $dependency,
				);
				break;
			case 'order_product':
				$param = array(
					'group'      => $group,
					'type'       => 'dropdown',
					'param_name' => 'orderby',
					'heading'    => esc_html__( 'Order by', 'learts-addons' ),
					'value'      => array(
						esc_html__( 'None', 'learts-addons' )               => 'none',
						esc_html__( 'Date', 'learts-addons' )               => 'date',
						esc_html__( 'Price', 'learts-addons' )              => 'price',
						esc_html__( 'Sales', 'learts-addons' )              => 'sales',
						esc_html__( 'Rating', 'learts-addons' )             => 'rating',
						esc_html__( 'Post ID', 'learts-addons' )            => 'ID',
						esc_html__( 'Title', 'learts-addons' )              => 'title',
						esc_html__( 'Last modified date', 'learts-addons' ) => 'modified',
						esc_html__( 'Random order', 'learts-addons' )       => 'rand',
					),
					'dependency' => $dependency,
				);
				break;
			case 'order_way':
				$param = array(
					'group'       => $group,
					'type'        => 'dropdown',
					'param_name'  => 'order',
					'heading'     => esc_html__( 'Sort order', 'learts-addons' ),
					'value'       => array(
						'',
						esc_html__( 'Descending', 'learts-addons' ) => 'DESC',
						esc_html__( 'Ascending', 'learts-addons' )  => 'ASC',
					),
					'description' => sprintf( wp_kses( __( 'Designates the ascending or descending order. More at <a href="%s" target="_blank">WordPress codex page</a>.',
						'learts-addons' ),
						array(
							'a' => array(
								'href'   => array(),
								'target' => array(),
							),
						) ),
						esc_url( 'http://codex.wordpress.org/Class_Reference/WP_Query#Order_.26_Orderby_Parameters' ) ),
					'dependency'  => $dependency,
				);
				break;
			case 'product_autocomplete':
				$param = array(
					'type'        => 'autocomplete',
					'heading'     => esc_html__( 'Products', 'learts-addons' ),
					'param_name'  => 'product_ids',
					'description' => esc_html__( 'List of product', 'learts-addons' ),
					'settings'    => array(
						'multiple' => true,
						'sortable' => true,
					),
					'dependency'  => $dependency,
				);
				break;
			case 'product_cat_autocomplete':
				$param = array(
					'type'        => 'chosen',
					'heading'     => esc_html__( 'Categories', 'learts-addons' ),
					'param_name'  => 'product_cat_slugs',
					'options'     => array(
						'multiple' => true, // multiple or not
						'type'     => 'taxonomy', // taxonomy or post_type
						'get'      => 'product_cat', // term or post type name, split by comma
						'field'    => 'slug', // slug or id
					),
					'description' => esc_html__( 'Select what categories you want to use. Leave it empty to use all categories.',
						'learts-addons' ),
					'dependency'  => $dependency,
				);
				break;
			case 'product_cat_dropdown':
				$args = array(
					'type'         => 'post',
					'child_of'     => 0,
					'parent'       => '',
					'orderby'      => 'id',
					'order'        => 'ASC',
					'hide_empty'   => false,
					'hierarchical' => 1,
					'exclude'      => '',
					'include'      => '',
					'number'       => '',
					'taxonomy'     => 'product_cat',
					'pad_counts'   => false,
				);

				$categories = get_categories( $args );

				$product_categories_dropdown = array();

				$first_value = array(
					'label' => esc_html__( 'Select category', 'learts-addons' ),
					'value' => '',
				);

				if ( ! class_exists( 'Learts_Vendor_Woocommerce' ) ) {
					return $param;
				}

				$vc_vendor_woo = new Learts_Vendor_Woocommerce();

				$vc_vendor_woo->getCategoryChildsFull( 0, 0, $categories, 0, $product_categories_dropdown );

				array_unshift( $product_categories_dropdown, $first_value );

				$param = array(
					'type'        => 'dropdown',
					'heading'     => esc_html__( 'Category', 'learts-addons' ),
					'value'       => $product_categories_dropdown,
					'param_name'  => 'category',
					'description' => esc_html__( 'Select a product category', 'learts-addons' ),
					'dependency'  => $dependency,
				);
				break;
			case 'product_attribute':

				if ( function_exists( 'wc_get_attribute_taxonomies' ) ) {

					$attributes_tax = wc_get_attribute_taxonomies();
					$attributes     = array();
					foreach ( $attributes_tax as $attribute ) {
						$attributes[ $attribute->attribute_label ] = $attribute->attribute_name;
					}
					$param = array(
						'type'        => 'dropdown',
						'heading'     => esc_html__( 'Attribute', 'learts-addons' ),
						'param_name'  => 'attribute',
						'save_always' => true,
						'value'       => $attributes,
						'description' => esc_html__( 'List of product taxonomy attribute', 'learts-addons' ),
						'dependency'  => $dependency,
					);
				}
				break;
			case 'product_term':
				$dependency['callback'] = 'leartsProductAttributeFilterDependencyCallback'; // on admin.js

				$param = array(
					'type'        => 'checkbox',
					'heading'     => esc_html__( 'Filter', 'learts-addons' ),
					'param_name'  => 'filter',
					'save_always' => true,
					'value'       => array( 'empty' => 'empty' ),
					'description' => esc_html__( 'Taxonomy values', 'learts-addons' ),
					'dependency'  => $dependency,
				);
				break;
		}

		return $param;
	}

	/**
	 * Calculate the width of columns
	 *
	 * @param $number_of_cols
	 *
	 * @return float|int|string
	 */
	public static function calculate_column_width( $number_of_cols ) {
		$total_cols = 12;

		if ( 0 == $total_cols % $number_of_cols ) {
			$width = $total_cols / $number_of_cols;
		} else {
			if ( 5 == $number_of_cols ) {
				$width = 'is-5';
			}
		}

		return $width;
	}

	/**
	 * Get taxonomy for autocomplete field of Blog Shortcode
	 *
	 * @param string $tax
	 *
	 * @return array
	 */
	public function get_tax_for_autocomplete( $tax = '' ) {

		$results = array();

		if ( 'category' == $tax ) {
			$categories = get_categories();
			foreach ( $categories as $category ) {
				$cat_arr          = array();
				$cat_arr['label'] = $category->cat_name;
				$cat_arr['value'] = $category->cat_ID;
				$cat_arr['group'] = 'CATEGORY';

				$results[] = $cat_arr;
			}
		}

		if ( 'tag' == $tax ) {
			$tags = get_tags();
			foreach ( $tags as $tag ) {
				$tag_arr          = array();
				$tag_arr['label'] = $tag->name;
				$tag_arr['value'] = $tag->term_id;
				$tag_arr['group'] = 'TAG';

				$results[] = $tag_arr;
			}
		}

		return $results;
	}

	public function product_id_callback( $query ) {

		if ( class_exists( 'Vc_Vendor_Woocommerce' ) ) {
			$vc_vendor_wc = new Vc_Vendor_Woocommerce();

			return $vc_vendor_wc->productIdAutocompleteSuggester( $query );
		}

		return '';
	}

	public function product_id_render( $query ) {

		if ( class_exists( 'Vc_Vendor_Woocommerce' ) ) {
			$vc_vendor_wc = new Vc_Vendor_Woocommerce();

			return $vc_vendor_wc->productIdAutocompleteRender( $query );
		}

		return '';
	}

	public function product_categories_slugs_callback( $query ) {

		if ( class_exists( 'Vc_Vendor_Woocommerce' ) ) {
			$vc_vendor_wc = new Vc_Vendor_Woocommerce();

			return $vc_vendor_wc->productCategoryCategoryAutocompleteSuggesterBySlug( $query );
		}

		return '';
	}

	public function product_categories_slugs_render( $query ) {

		if ( class_exists( 'Vc_Vendor_Woocommerce' ) ) {
			$vc_vendor_wc = new Vc_Vendor_Woocommerce();

			return $vc_vendor_wc->productCategoryCategoryRenderBySlugExact( $query );
		}

		return '';
	}

	/**
	 * Defines default value for param if not provided. Takes from other param value.
	 *
	 * @param array $param_settings
	 * @param       $current_value
	 * @param       $map_settings
	 * @param       $atts
	 *
	 * @return array
	 */
	public static function product_attribute_filter_param_value( $param_settings, $current_value, $map_settings, $atts ) {
		if ( isset( $atts['attribute'] ) ) {
			$value = self::get_attribute_terms( $atts['attribute'] );
			if ( is_array( $value ) && ! empty( $value ) ) {
				$param_settings['value'] = $value;
			}
		}

		return $param_settings;
	}

	/**
	 * Get attribute terms suggester
	 *
	 * @param $attribute
	 *
	 * @return array
	 */
	public static function get_attribute_terms( $attribute ) {
		$terms = get_terms( 'pa_' . $attribute ); // return array. take slug
		$data  = array();
		if ( ! empty( $terms ) && empty( $terms->errors ) ) {
			foreach ( $terms as $term ) {
				$data[ $term->name ] = $term->slug;
			}
		}

		return $data;
	}

	/**
	 * Convert text to 1 line
	 *
	 * @param $str
	 *
	 * @return string
	 */
	public static function text2line( $str ) {
		return trim( preg_replace( "/[\r\v\n\t]*/", '', $str ) );
	}

	public static function social_icons( $custom = true, $colors = false ) {

		$networks = array(
			'amazon'         => array( 'label' => 'Amazon', 'color' => '#ff9900' ),
			'500px'          => array( 'label' => '500px', 'color' => '#222222' ),
			'behance'        => array( 'label' => 'Behance', 'color' => '#00a1d1' ),
			'bitbucket'      => array( 'label' => 'Bitbucket', 'color' => '#1c4f83' ),
			'codepen'        => array( 'label' => 'Codepen', 'color' => '#000000' ),
			'deviantart'     => array( 'label' => 'Deviantart', 'color' => '#4dc47d' ),
			'digg'           => array( 'label' => 'Digg', 'color' => '#000000' ),
			'dribbble'       => array( 'label' => 'Dribbble', 'color' => '#ea4c89' ),
			'dropbox'        => array( 'label' => 'Dropbox', 'color' => '#007ee5' ),
			'envelope-o'     => array(
				'label' => esc_html__( 'Email Address', 'learts-addons' ),
				'color' => '#000000',
			),
			'facebook'       => array( 'label' => 'Facebook', 'color' => '#3b5998' ),
			'flickr'         => array( 'label' => 'Flickr', 'color' => '#0063dc' ),
			'foursquare'     => array( 'label' => 'Foursquare', 'color' => '#2d5be3' ),
			'github'         => array( 'label' => 'Github', 'color' => '#222222' ),
			'google-plus'    => array( 'label' => 'Google+', 'color' => '#dc4e41' ),
			'instagram'      => array( 'label' => 'Instagram', 'color' => '#3d6997' ),
			'linkedin'       => array( 'label' => 'LinkedIn', 'color' => '#0077b5' ),
			'odnoklassniki'  => array( 'label' => 'Odnoklassniki', 'color' => '#f78200' ),
			'pinterest'      => array( 'label' => 'Pinterest', 'color' => '#bd081c' ),
			'qq'             => array( 'label' => 'QQ', 'color' => '#000000' ),
			'rss'            => array( 'label' => 'RSS', 'color' => '#f26522' ),
			'reddit'         => array( 'label' => 'Reddit', 'color' => '#ff4500' ),
			'skype'          => array( 'label' => 'Skype', 'color' => '#00aff0' ),
			'slack'          => array( 'label' => 'Slack', 'color' => '#776ebd' ),
			'snapchat-ghost' => array( 'label' => 'Snapchat', 'color' => '#fffc00' ),
			'soundcloud'     => array( 'label' => 'Soundcloud', 'color' => '#ff8800' ),
			'spotify'        => array( 'label' => 'Spotify', 'color' => '#2ebd59' ),
			'stack-exchange' => array( 'label' => 'Stack Exchange', 'color' => '#2ebd59' ),
			'stack-overflow' => array( 'label' => 'Stack Overflow', 'color' => '#125099' ),
			'stumbleupon'    => array( 'label' => 'StumbleUpon', 'color' => '#ed4a10' ),
			'telegram'       => array( 'label' => 'Telegram', 'color' => '#0088cc' ),
			'tripadvisor'    => array( 'label' => 'Tripadvisor', 'color' => '#178a29' ),
			'tumblr'         => array( 'label' => 'Tumblr', 'color' => '#35465c' ),
			'twitch'         => array( 'label' => 'Twitch', 'color' => '#64429d' ),
			'twitter'        => array( 'label' => 'Twitter', 'color' => '#55acee' ),
			'vimeo'          => array( 'label' => 'Vimeo', 'color' => '#1ab7ea' ),
			'vine'           => array( 'label' => 'Vine', 'color' => '#00a577' ),
			'vk'             => array( 'label' => 'VK', 'color' => '#45668e' ),
			'weibo'          => array( 'label' => 'Weibo', 'color' => '#d72822' ),
			'xing'           => array( 'label' => 'Xing', 'color' => '#026466' ),
			'yahoo'          => array( 'label' => 'Yahoo', 'color' => '#410093' ),
			'yelp'           => array( 'label' => 'Yelp', 'color' => '#af0606' ),
			'youtube-play'   => array( 'label' => 'Youtube', 'color' => '#cd201f' ),
		);

		if ( $custom ) {
			$networks['custom'] = array( 'label' => esc_attr__( 'Custom', 'learts-addons' ), 'color' => '' );
		}

		if ( ! $colors ) {
			$simple_networks = array();
			foreach ( $networks as $id => $args ) {
				$simple_networks[ $id ] = $args['label'];
			}
			$networks = $simple_networks;
		}

		return $networks;
	}

	/**
	 * GET CUSTOM POST TYPE TAXONOMY LIST.
	 *
	 * @param        $category_name
	 * @param int    $filter
	 * @param string $category_child
	 * @param bool   $frontend_display
	 *
	 * @return array|void
	 */
	public static function get_category_list( $category_name, $filter = 0, $category_child = '', $frontend_display = false ) {

		if ( ! $frontend_display && ! is_admin() ) {
			return;
		}

		if ( $category_name == 'product-category' ) {
			$category_name = 'product_cat';
		}

		if ( ! $filter ) {

			$get_category  = get_categories( array( 'taxonomy' => $category_name ) );
			$category_list = array( '0' => 'All' );

			foreach ( $get_category as $category ) {
				if ( isset( $category->cat_name ) ) {
					$category_list[] = $category->cat_name;
				}
			}

			return $category_list;

		} else if ( $category_child != '' && $category_child != 'All' ) {

			$childcategory = get_term_by( 'slug', $category_child, $category_name );
			$get_category  = get_categories( array(
				'taxonomy' => $category_name,
				'child_of' => $childcategory->term_id,
			) );
			$category_list = array( '0' => 'All' );

			foreach ( $get_category as $category ) {
				if ( isset( $category->cat_name ) ) {
					$category_list[] = $category->cat_name;
				}
			}

			return $category_list;

		} else {

			$get_category  = get_categories( array( 'taxonomy' => $category_name ) );
			$category_list = array( '0' => 'All' );

			foreach ( $get_category as $category ) {
				if ( isset( $category->cat_name ) ) {
					$category_list[] = $category->cat_name;
				}
			}

			return $category_list;
		}
	}

	/**
	 *
	 *    Lists lists o_O
	 *
	 * @param string $list_id - string - sth like "6edd80a499"
	 *
	 * @return array $result
	 */
	public function get_lists( $list_id = "" ) {
		$data = array(
			"filters" => array(
				"list_id" => $list_id,
			),
		);

		return $this->rest( "lists/list", $data, 20 );
	}

	public function get_lists_for_dropdown_vc() {

		$lists    = $this->get_lists();
		$lists_vc = array( esc_html__( 'Select a list', 'learts-addons' ) => '' );

		if ( ! $lists ) {
			return;
		}

		foreach ( $lists['data'] as $list ) {
			$lists_vc[ $list['name'] ] = $list['id'];
		}

		return $lists_vc;
	}

	public function subscribe_to_list() {

		$email   = stripslashes( $_POST['email'] );
		$list_id = stripslashes( $_POST['list_id'] );
		$optin   = stripslashes( $_POST['optin'] );

		$result = $this->subscribe( $email, $list_id, $optin );

		if ( empty( $result['status'] ) == false ) {
			switch ( $result['name'] ) {
				case 'Invalid_ApiKey':
					echo json_encode( array(
						'action_status' => false,
						'message'       => $result['error'],
					) );
					break;
				case 'List_DoesNotExist':
					echo json_encode( array(
						'action_status' => false,
						'message'       => $result['error'],
					) );
					break;
				case 'ValidationError':
					echo json_encode( array(
						'action_status' => false,
						'message'       => esc_html__( 'Oops! Enter a valid Email address', 'learts-addons' ),
					) );
					break;

				case 'List_AlreadySubscribed':
					echo json_encode( array(
						'action_status' => false,
						'message'       => esc_html__( 'This email already subscribed to the list.', 'learts-addons' ),
					) );
					break;

				case 'curl_package_disabled':
					echo json_encode( array(
						'action_status' => false,
						'message'       => esc_html__( 'Curl is disabled. Please enable curl in server php.ini settings.',
							'learts-addons' ),
					) );
					break;
			}
		} elseif ( isset( $result['email'] ) ) {
			echo json_encode( array(
				'action_status' => true,
				'message'       => $result['email'] . esc_html__( ' has been subscribed.', 'learts-addons' ),
			) );
		}
		wp_die();
	}

	/**
	 * Convert image size in pixels to array
	 * Eg, 200x100 => array (200, 100)
	 *
	 * If image size is string, don't need to convert
	 *
	 * @param $size
	 *
	 * @return array|false
	 */
	public static function convert_image_size( $size ) {

		global $_wp_additional_image_sizes;

		if ( is_string( $size ) && ( ( ! empty( $_wp_additional_image_sizes[ $size ] ) && is_array( $_wp_additional_image_sizes[ $size ] ) ) || in_array( $size,
					array(
						'thumbnail',
						'thumb',
						'medium',
						'large',
						'full',
					) ) ) ) {
			return $size;
		} else {
			if ( is_string( $size ) ) {
				preg_match_all( '/\d+/', $size, $thumb_matches );
				if ( isset( $thumb_matches[0] ) ) {
					$size  = array();
					$count = count( $thumb_matches[0] );
					if ( $count > 1 ) {
						$size[] = $thumb_matches[0][0]; // width
						$size[] = $thumb_matches[0][1]; // height
					} elseif ( 1 === $count ) { // square image
						$size[] = $thumb_matches[0][0]; // width
						$size[] = $thumb_matches[0][0]; // height
					} else {
						$size = false;
					}
				}
			}
			if ( is_array( $size ) ) {
				return $size;
			}
		}

		return '';
	}

	/**
	 * Get product by given data source
	 *
	 * @param $data_source
	 * @param $atts array
	 * @param $args array Additional arguments
	 *
	 * @return mixed|WP_Query
	 */
	public static function get_products_by_datasource( $data_source, $atts, $args = array() ) {

		global $learts_options;

		if ( isset( $atts['product_style'] ) ) {
			if ( $atts['product_style'] == 'button-hover' ) {
				$learts_options['product_style'] = 'button-hover';
			}

			if ( $atts['product_style'] == 'button-hover-alt' ) {
				$learts_options['product_style'] = 'button-hover-alt';
			}

			if ( $atts['product_style'] == 'default' ) {
				$learts_options['product_style'] = 'default';
			}
		}

		$defaults = array(
			'post_type'           => 'product',
			'status'              => 'published',
			'ignore_sticky_posts' => 1,
			'orderby'             => $atts['orderby'],
			'order'               => $atts['order'],
			'posts_per_page'      => intval( $atts['number'] ) > 0 ? intval( $atts['number'] ) : 1000,
		);

		$args = wp_parse_args( $args, $defaults );

		switch ( $data_source ) {
			case 'featured_products':
				if ( version_compare( WC()->version, '3.0.0', '<' ) ) {
					$args['meta_key']   = '_featured';
					$args['meta_value'] = 'yes';
				} else {
					$args['tax_query'] = array(
						array(
							'taxonomy' => 'product_visibility',
							'field'    => 'name',
							'terms'    => array( 'featured' ),
							'operator' => 'IN',
						),
					);
				}
				break;
			case 'sale_products':
				$product_ids_on_sale   = wc_get_product_ids_on_sale();
				$product_ids_on_sale[] = 0;
				$args['post__in']      = $product_ids_on_sale;
				break;
			case 'best_selling_products':
				$args['meta_key'] = 'total_sales';
				$args['orderby']  = 'meta_value_num';
				$args['order']    = 'DESC';
				break;
			case 'top_rated_products':
				$args['meta_key'] = '_wc_average_rating';
				$args['orderby']  = 'meta_value_num';
				$args['order']    = 'DESC';
				break;
			case 'product_attribute':
				$args['tax_query'] = array(
					array(
						'taxonomy' => strstr( $atts['attribute'],
							'pa_' ) ? sanitize_title( $atts['attribute'] ) : 'pa_' . sanitize_title( $atts['attribute'] ),
						'field'    => 'slug',
						'terms'    => array_map( 'sanitize_title', explode( ',', $atts['filter'] ) ),
					),
				);
				break;
			case 'products':
				if ( $atts['product_ids'] != '' ) {
					$args['post__in'] = explode( ',', $atts['product_ids'] );
					$args['orderby']  = ( ! $args['orderby'] || $args['orderby'] == 'none' ) ? 'post__in' : '';
				}
				break;
			case 'categories':
				$args['tax_query'] = array(
					'relation' => 'OR',
					array(
						'taxonomy'         => 'product_cat',
						'field'            => 'slug',
						'terms'            => explode( ',', $atts['product_cat_slugs'] ),
						'include_children' => $atts['include_children'],
					),
				);
				break;
			case 'category':
				$args['tax_query'] = array(
					'relation' => 'OR',
					array(
						'taxonomy'         => 'product_cat',
						'field'            => 'slug',
						'terms'            => $atts['category'],
						'include_children' => $atts['include_children'],
					),
				);
				break;
			case 'recent_products':
			default:
				$args['orderby'] = 'date';
				$args['order']   = 'DESC';
				break;
		}

		switch ( $atts['orderby'] ) {
			case 'price':
				$args['meta_key'] = '_price';
				$args['orderby']  = 'meta_value_num';
				break;
			case 'salse':
				$args['meta_key'] = 'total_sales';
				$args['orderby']  = 'meta_value_num';
				break;
			case 'rating':
				$args['meta_key'] = '_wc_average_rating';
				$args['orderby']  = 'meta_value_num';
				break;
		}

		if ( ! empty( $atts['exclude'] ) ) {
			$args['post__not_in'] = explode( ',', $atts['exclude'] );
		}

		$transient_name = 'learts_wc_loop' . substr( md5( json_encode( $args ) . $data_source ),
				28 ) . WC_Cache_Helper::get_transient_version( 'product_query' );
		$query          = get_transient( $transient_name );

		if ( false === $query || ! is_a( $query, 'WP_Query' ) ) {
			$query = new WP_Query( $args );
			set_transient( $transient_name, $query, DAY_IN_SECONDS * 30 );
		}

		return $query;

	}

	/**
	 * Get size information for all currently-registered image sizes.
	 *
	 * @global $_wp_additional_image_sizes
	 * @uses   get_intermediate_image_sizes()
	 * @return array $sizes Data for all currently-registered image sizes.
	 */
	public static function get_image_sizes() {

		global $_wp_additional_image_sizes;

		$sizes = array();

		foreach ( get_intermediate_image_sizes() as $_size ) {
			if ( in_array( $_size, array( 'thumbnail', 'medium', 'medium_large', 'large' ) ) ) {
				$sizes[ $_size ]['width']  = get_option( "{$_size}_size_w" );
				$sizes[ $_size ]['height'] = get_option( "{$_size}_size_h" );
				$sizes[ $_size ]['crop']   = (bool) get_option( "{$_size}_crop" );
			} elseif ( isset( $_wp_additional_image_sizes[ $_size ] ) ) {
				$sizes[ $_size ] = array(
					'width'  => $_wp_additional_image_sizes[ $_size ]['width'],
					'height' => $_wp_additional_image_sizes[ $_size ]['height'],
					'crop'   => $_wp_additional_image_sizes[ $_size ]['crop'],
				);
			}
		}

		return $sizes;
	}

	/**
	 * Get size information for a specific image size.
	 *
	 * @uses   get_image_sizes()
	 *
	 * @param  string $size The image size for which to retrieve data.
	 *
	 * @return bool|array $size Size data about an image size or false if the size doesn't exist.
	 */
	public static function get_image_size( $size ) {

		$sizes = self::get_image_sizes();

		if ( isset( $sizes[ $size ] ) ) {
			return $sizes[ $size ];
		}

		return false;
	}

	/**
	 * Get the width of a specific image size.
	 *
	 * @uses   get_image_size()
	 *
	 * @param  string $size The image size for which to retrieve data.
	 *
	 * @return bool|string $size Width of an image size or false if the size doesn't exist.
	 */
	public static function get_image_width( $size ) {

		if ( ! $size = self::get_image_size( $size ) ) {
			return false;
		}

		if ( isset( $size['width'] ) ) {
			return $size['width'];
		}

		return false;
	}

	/**
	 * Get the height of a specific image size.
	 *
	 * @uses   get_image_size()
	 *
	 * @param  string $size The image size for which to retrieve data.
	 *
	 * @return bool|string $size Height of an image size or false if the size doesn't exist.
	 */
	public static function get_image_height( $size ) {

		if ( ! $size = self::get_image_size( $size ) ) {
			return false;
		}

		if ( isset( $size['height'] ) ) {
			return $size['height'];
		}

		return false;
	}

	/**
	 * Get option from Redux Framework
	 *
	 * @since 1.0
	 *
	 * @param string $option
	 *
	 * @return mixed
	 */
	public static function get_option( $option = '' ) {

		global $learts_options;

		return isset( $learts_options[ $option ] ) ? $learts_options[ $option ] : '';
	}

	public static function social_links( $classes = array() ) {

		ob_start();

		// Get social links from Redux
		$icons           = Learts_Addons::get_option( 'icon' );
		$icon_classes    = Learts_Addons::get_option( 'icon_class' );
		$urls            = Learts_Addons::get_option( 'url' );
		$titles          = Learts_Addons::get_option( 'title' );
		$custom_classes  = Learts_Addons::get_option( 'custom_class' );
		$tooltip         = Learts_Addons::get_option( 'tooltip' );
		$open_in_new_tab = Learts_Addons::get_option( 'social_open_in_new_tab' );
		$labels          = Learts_VC::social_icons( false );

		$social_links = array();

		if ( ! empty( $icons ) ) {
			for ( $i = 0; $i < count( $icons ); $i ++ ) {
				if ( ! empty( $icons[ $i ] ) ) {
					$social_links[ $i ]['icon'] = $icons[ $i ];
				}
			}
		}

		if ( ! empty( $icon_classes ) ) {
			for ( $i = 0; $i < count( $icon_classes ); $i ++ ) {
				if ( ! empty( $icon_classes[ $i ] ) ) {
					$social_links[ $i ]['icon_class'] = $icon_classes[ $i ];
				}
			}
		}

		if ( ! empty( $urls ) ) {
			for ( $i = 0; $i < count( $urls ); $i ++ ) {
				if ( ! empty( $urls[ $i ] ) ) {
					$social_links[ $i ]['url'] = $urls[ $i ];
				}
			}
		}

		if ( ! empty( $titles ) ) {
			for ( $i = 0; $i < count( $titles ); $i ++ ) {
				if ( ! empty( $titles[ $i ] ) ) {
					$social_links[ $i ]['title'] = $titles[ $i ];
				}
			}
		}

		if ( ! empty( $custom_classes ) ) {
			for ( $i = 0; $i < count( $custom_classes ); $i ++ ) {
				if ( ! empty( $custom_classes[ $i ] ) ) {
					$social_links[ $i ]['custom_class'] = $custom_classes[ $i ];
				}
			}
		}

		// Now let's render HTML
		if ( ! empty( $social_links ) ) :
			array_unshift( $classes, 'social-links' );
			?>
			<ul class="<?php echo esc_attr( implode( ' ', $classes ) ); ?>">
				<?php foreach ( $social_links as $link ) :

					$li_classes = array();
					$tooltip_label = '';

					if ( isset( $link['title'] ) && ! empty( $link['title'] ) ) {
						$li_classes[] = 'has-title';
					}

					if ( $tooltip ) {
						$li_classes[] = 'hint--top hint--bounce';
					}

					if ( isset( $link['custom_class'] ) && ! empty( $link['custom_class'] ) ) {
						$li_classes[] = esc_attr( $link['custom_class'] );
					}

					if ( isset( $labels[ $link['icon'] ] ) ) {
						$tooltip_label = $labels[ $link['icon'] ];
					}

					if ( isset( $link['title'] ) && ! empty( $link['title'] ) ) {
						$tooltip_label = $link['title'];
					}

					?>
					<li class="<?php echo implode( ' ', $li_classes ); ?>"
					    aria-label="<?php echo esc_attr( $tooltip_label ); ?>">
						<?php if ( isset( $link['url'] ) && ! empty( $link['url'] ) ) : ?><a
							href="<?php echo esc_url_raw( $link['url'] ); ?>"
							target="<?php echo ( $open_in_new_tab ) ? '_blank' : '_self'; ?>"><?php endif; ?>
							<?php if ( isset( $link['icon'] ) && ! empty( $link['icon'] ) && ( ! isset( $link['icon_class'] ) || empty( $link['icon_class'] ) ) ) : ?>
								<i class="fa fa-<?php echo esc_attr( $link['icon'] ); ?>" aria-hidden="true"></i>
							<?php elseif ( isset( $link['icon_class'] ) && ! empty( $link['icon_class'] ) ) : ?>
								<i class="fa <?php echo esc_attr( $link['icon_class'] ); ?>" aria-hidden="true"></i>
							<?php endif; ?>
							<?php if ( isset( $link['title'] ) && ! empty( $link['title'] ) ) : ?>
								<span class="title"><?php echo esc_html( $link['title'] ); ?></span>
							<?php endif; ?>
							<?php if ( isset( $link['url'] ) && ! empty( $link['url'] ) ) : ?></a><?php endif; ?>
					</li>
				<?php endforeach; ?>
			</ul>
			<?php
		endif;

		return ob_get_clean();

	}

	public static function get_animation_field( $default = 'move-up' ) {
		return array(
			'type'        => 'dropdown',
			'heading'     => esc_html__( 'CSS Animation', 'learts-addons' ),
			'description' => esc_html__( 'Select type of animation for element to be animated when it "enters" the browsers viewport (Note: works only in modern browsers).',
				'learts-addons' ),
			'param_name'  => 'animation',
			'value'       => array(
				esc_html__( 'None', 'learts-addons' )             => 'none',
				esc_html__( 'Fade In', 'learts-addons' )          => 'fade-in',
				esc_html__( 'Move Up', 'learts-addons' )          => 'move-up',
				esc_html__( 'Move Down', 'learts-addons' )        => 'move-down',
				esc_html__( 'Move Left', 'learts-addons' )        => 'move-left',
				esc_html__( 'Move Right', 'learts-addons' )       => 'move-right',
				esc_html__( 'Scale Up', 'learts-addons' )         => 'scale-up',
				esc_html__( 'Fall Perspective', 'learts-addons' ) => 'fall-perspective',
				esc_html__( 'Fly', 'learts-addons' )              => 'fly',
				esc_html__( 'Flip', 'learts-addons' )             => 'flip',
				esc_html__( 'Helix', 'learts-addons' )            => 'helix',
				esc_html__( 'Pop Up', 'learts-addons' )           => 'pop-up',
			),
			'std'         => $default,
		);
	}

	public static function get_grid_item_class( $number_of_cols ) {

		$total_cols = 12;
		$classes    = array();

		if ( ! is_array( $number_of_cols ) && is_numeric( $number_of_cols ) && $number_of_cols > 0 ) {

			if ( 0 == $total_cols % $cols ) {
				$width     = $total_cols / $cols;
				$classes[] = 'col-md-' . $width;
			} else {
				if ( 5 == $cols ) {
					$classes[] = 'col-md-is-5';
				}
			}
		} else {

			foreach ( $number_of_cols as $media_query => $cols ) {

				$cols = intval( $cols );

				if ( $cols == 0 ) {
					$cols = 1;
				}

				if ( 0 == $total_cols % $cols ) {
					$width     = $total_cols / $cols;
					$classes[] = 'col-' . $media_query . '-' . $width;
				} else {
					if ( 5 == $cols ) {
						$classes[] = 'col-' . $media_query . '-is-5';
					}
				}
			}
		}

		return join( ' ', $classes );
	}

    public static function render_rating( $rating = 5, $args = array() ) {
        $default = [
            'wrapper_class' => '',
            'echo'          => true,
        ];

        $args = wp_parse_args( $args, $default );

        $wrapper_classes = 'tm-star-rating';
        if ( ! empty( $args['wrapper_class'] ) ) {
            $wrapper_classes .= " {$args['wrapper_class']}";
        }

        $full_stars = intval( $rating );
        $template   = '';

        $template .= str_repeat( '<span class="fas fa-star"></span>', $full_stars );

        $half_star = floatval( $rating ) - $full_stars;

        if ( $half_star != 0 ) {
            $template .= '<span class="fas fa-star-half-alt"></span>';
				}
				
				if ( isset($rating) && $rating != '' ) {
					$empty_stars = intval( 5 - $rating );
				}

				$template    .= str_repeat( '<span class="far fa-star"></span>', $empty_stars );
				
				if ( $template !== '' ) {
					$template = '<div class="' . esc_attr( $wrapper_classes ) . '">' . $template . '</div>';
				}

        if ( true === $args['echo'] ) {
            echo '' . $template;
        } else {
            return $template;
        }
    }

}

new Learts_VC();
