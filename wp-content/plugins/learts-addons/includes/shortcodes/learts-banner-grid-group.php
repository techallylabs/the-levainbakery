<?php

/**
 * Learts Banner Grid Group Shortcode
 *
 * @version 1.0
 * @package Learts
 */
class WPBakeryShortCode_Learts_Banner_Grid_Group extends WPBakeryShortCodesContainer {

}

vc_map( array(
	'name'                    => esc_html__( 'Banner Grid Group', 'learts-addons' ),
	'description'             => esc_html__( 'Arrange multiple banners per row with unusual structure.', 'learts-addons' ),
	'base'                    => 'learts_banner_grid_group',
	'icon'                    => 'learts-element-icon-banner-grid-group',
	'category'                => sprintf( esc_html__( 'by %s', 'learts-addons' ), LEARTS_ADDONS_THEME_NAME ),
	'js_view'                 => 'VcColumnView',
	'content_element'         => true,
	'show_settings_on_create' => false,
	'as_parent'               => array( 'only' => 'learts_banner, learts_banner2, learts_banner3, learts_product_category_banner, rev_slider, rev_slider_vc,learts_quote,learts_simple_banner,learts_product_grid' ),
	'params'                  => array(
		array(
			'type'        => 'dropdown',
			'heading'     => esc_html__( 'Style', 'learts-addons' ),
			'param_name'  => 'style',
			'value'       => array(
				esc_html__( 'Group maximum 3 items', 'learts-addons' ) => 'group-3-items',
				esc_html__( 'Group maximum 5 items', 'learts-addons' ) => 'group-5-items',
				esc_html__( 'Group maximum 6 items', 'learts-addons' ) => 'group-6-items',
				esc_html__( 'Group maximum 9 items', 'learts-addons' ) => 'group-9-items',
			),
			'std'         => 'group-6-items',
			'description' => esc_html__( 'Choose style for banner.', 'learts-addons' ),
		),


		Learts_VC::get_param( 'el_class' ),
		Learts_VC::get_param( 'css' ),
	),
) );
