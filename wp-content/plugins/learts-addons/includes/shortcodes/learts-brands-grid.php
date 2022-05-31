<?php

/**
 * Learts Brands Grid Shortcode
 *
 * @version 1.0
 * @package Learts
 */

class WPBakeryShortCode_Learts_Brands_Grid extends WPBakeryShortCode {
}

// Mapping shortcode.
vc_map( array(
	'name'        => esc_html__( 'Brands Image Grid', 'learts-addons' ),
	'base'        => 'learts_brands_grid',
	'icon'        => 'learts-element-icon-brand-image-grid',
	'category'    => sprintf( esc_html__( 'by %s', 'learts-addons' ), LEARTS_ADDONS_THEME_NAME ),
	'description' => esc_html__( 'Show brands in a grid.', 'learts-addons' ),
	'params'      => array(
		array(
			'type'       => 'chosen',
			'heading'    => esc_html__( 'Select Brand', 'learts-addons' ),
			'param_name' => 'brand_slugs',
			'options'    => array(
				'type'  => 'taxonomy',
				'get'   => 'product_brand',
				'field' => 'slug',
			)
		),
		array(
			'type'       => 'checkbox',
			'param_name' => 'hide_empty',
			'std'        => 'yes',
			'value'      => array( esc_html__( 'Hide empty brands', 'learts-addons' ) => 'yes' ),
		),
		array(
			'type'       => 'checkbox',
			'param_name' => 'display_featured',
			'value'      => array( esc_html__( 'Display only feature brands', 'learts-addons' ) => 'yes' ),
		),
		array(
			'type'       => 'checkbox',
			'param_name' => 'show_title',
			'value'      => array( esc_html__( 'Show brand\'s title', 'learts-addons' ) => 'yes' ),
		),
		array(
			'type'       => 'checkbox',
			'param_name' => 'new_tab',
			'value'      => array( esc_html__( 'Open link in a new tab', 'learts-addons' ) => 'yes' ),
		),
		Learts_VC::get_param( 'columns' ),
		Learts_VC::get_param( 'el_class' ),
		Learts_VC::get_param( 'css' ),
	)
) );
