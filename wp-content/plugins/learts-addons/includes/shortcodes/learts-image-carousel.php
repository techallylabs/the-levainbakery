<?php

/**
 * ThemeMove Image Carousel Shortcode
 *
 * @version 1.0
 * @package learts
 */
class WPBakeryShortCode_Learts_Image_Carousel extends WPBakeryShortCode {
}

vc_map( array(
	'name'        => esc_html__( 'Image Carousel', 'learts-addons' ),
	'description' => esc_html__( 'Display image carousel', 'learts-addons' ),
	'base'        => 'learts_image_carousel',
	'icon'        => 'learts-element-icon-image-carousel',
	'category'    => sprintf( esc_html__( 'by %s', 'learts-addons' ), LEARTS_ADDONS_THEME_NAME ),
	'params'      => array(

		array(
			'type'       => 'param_group',
			'heading'    => esc_html__( 'Single Image', 'learts-addons' ),
			'param_name' => 'single_image',
			'params'     => array(
				array(
					'type'        => 'attach_image',
					'heading'     => esc_html__( 'Image', 'learts-addons' ),
					'param_name'  => 'image',
					'value'       => '',
					'description' => esc_html__( 'Select an image from media library.', 'learts-addons' ),
				),
				array(
					'heading'     => esc_html__( 'Title Image', 'learts-addons' ),
					'description' => esc_html__( 'A short text display below a image.', 'learts-addons' ),
					'type'        => 'textfield',
					'param_name'  => 'title',
				),
				array(
					'heading'    => esc_html__( 'Banner Link', 'learts-addons' ),
					'type'       => 'vc_link',
					'param_name' => 'link',
				),
			),
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
