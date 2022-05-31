<?php

/**
 * Learts Brands Carousel
 *
 * @version 1.0
 * @package Learts
 */
class WPBakeryShortCode_Learts_Brands_Carousel extends WPBakeryShortCode {
}

// Mapping shortcode.
vc_map( array(
	'name'        => esc_html__( 'Brands Image Carousel', 'learts-addons' ),
	'base'        => 'learts_brands_carousel',
	'icon'        => 'learts-element-icon-brand-image-carousel',
	'category'    => sprintf( esc_html__( 'by %s', 'learts-addons' ), LEARTS_ADDONS_THEME_NAME ),
	'description' => esc_html__( 'Show brands in a carousel.', 'learts-addons' ),
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
			'type'       => 'dropdown',
			'heading'    => esc_html__( 'Number of brands to show', 'learts-addons' ),
			'param_name' => 'number',
			'std'        => 6,
			'value'      => array(
				1,
				2,
				3,
				4,
				5,
				6,
			)
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
		array(
			'type'       => 'checkbox',
			'param_name' => 'loop',
			'value'      => array( esc_html__( 'Enable carousel loop mode', 'learts-addons' ) => 'yes' ),
			'std'        => 'yes',
		),
		array(
			'type'       => 'checkbox',
			'param_name' => 'auto_play',
			'value'      => array( esc_html__( 'Enable carousel autolay', 'learts-addons' ) => 'yes' ),
		),
		array(
			'type'       => 'number',
			'param_name' => 'auto_play_speed',
			'heading'    => esc_html__( 'Auto play speed', 'learts-addons' ),
			'value'      => 5,
			'max'        => 10,
			'min'        => 3,
			'step'       => 0.5,
			'suffix'     => 'seconds',
			'dependency' => array(
				'element' => 'auto_play',
				'value'   => 'yes',
			),
		),
		array(
			'type'       => 'dropdown',
			'param_name' => 'nav_type',
			'heading'    => esc_html__( 'Navigation type', 'learts-addons' ),
			'value'      => array(
				esc_html__( 'Arrows', 'learts-addons' ) => 'arrows',
				esc_html__( 'Dots', 'learts-addons' )   => 'dots',
				__( 'Arrows & Dots', 'learts-addons' )  => 'both',
				esc_html__( 'None', 'learts-addons' )   => '',
			),
		),
		Learts_VC::get_param( 'el_class' ),
		Learts_VC::get_param( 'css' ),
	)
) );
