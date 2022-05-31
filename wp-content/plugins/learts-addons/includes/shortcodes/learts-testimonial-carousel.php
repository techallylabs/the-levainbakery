<?php

/**
 * Testimonial Carousel Shortcode
 *
 * @version 1.0
 * @package Learts
 */
class WPBakeryShortCode_Learts_Testimonial_Carousel extends WPBakeryShortCode {

}

// Mapping shortcode.
vc_map( array(
	'name'        => esc_html__( 'Testimonials Carousel', 'learts-addons' ),
	'base'        => 'learts_testimonial_carousel',
	'icon'        => 'learts-element-icon-testimonials',
	'category'    => sprintf( esc_html__( 'by %s', 'learts-addons' ), LEARTS_ADDONS_THEME_NAME ),
	'description' => esc_html__( 'Show testimonials in a carousel', 'learts-addons' ),
	'params'      => array(

		array(
			'type'        => 'dropdown',
			'heading'     => esc_html__( 'Style', 'learts-addons' ),
			'param_name'  => 'style_testimonial',
			'description' => esc_html__( 'Choose the style of testimonial ', 'learts-addons' ),
			'value'       => array(
				__( 'Single testimonial', 'learts-addons' )   => 'single',
				__( 'Multiple testimonials', 'learts-addons' ) => 'multiple',
				__( 'Multiple testimonials with star', 'learts-addons' ) => 'multiple-star',
				__( 'Modern slide testimonials', 'learts-addons' ) => 'modern-slide',
			),

		),

		array(
			'type'        => 'number',
			'heading'     => esc_html__( 'Number of items', 'learts-addons' ),
			'param_name'  => 'item_count',
			'description' => esc_html__( 'The number of testimonials to show. Enter -1 to show ALL testimonials (limited to 1000)',
				'learts-addons' ),
			'value'       => - 1,
			'max'         => 1000,
			'min'         => - 1,
			'step'        => 1,
		),
		array(
			'type'        => 'dropdown',
			'heading'     => esc_html__( 'Number of items to show', 'learts-addons' ),
			'param_name'  => 'items_to_show',
			'description' => esc_html__( 'The number items testimonials to show on page.',
				'learts-addons' ),
			'value'       => array(
				__( '1 testimonial', 'learts-addons' )  => 1,
				__( '2 testimonials', 'learts-addons' ) => 2,
				__( '3 testimonials', 'learts-addons' ) => 3,
				__( '4 testimonials', 'learts-addons' ) => 4,
			),
			'dependency'  => array(
				'element' => 'style_testimonial',
				'value'   => array('multiple', 'multiple-star'),
			),

		),
		array(
			'type'        => 'dropdown',
			'heading'     => __( 'Testimonials Order', 'learts-addons' ),
			'param_name'  => 'order',
			'value'       => array(
				__( 'Random', 'learts-addons' ) => 'rand',
				__( 'Latest', 'learts-addons' ) => 'date',
			),
			'description' => __( 'Choose the order of the testimonials.', 'learts-addons' ),
		),
		array(
			'type'        => 'dropdown',
			'heading'     => __( 'Testimonials category', 'learts-addons' ),
			'param_name'  => 'category',
			'value'       => Learts_VC::get_category_list( 'testimonial-category' ),
			'description' => __( 'Choose the category for the testimonials.', 'learts-addons' ),
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

		Learts_VC::get_param( 'animation' ),
		Learts_VC::get_param( 'el_class' ),
		Learts_VC::get_param( 'css' ),
	),
) );

