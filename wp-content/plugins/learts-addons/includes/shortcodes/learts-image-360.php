<?php

/**
 * ThemeMove Image 360 Shortcode
 *
 * @version 1.0
 * @package learts
 */
class WPBakeryShortCode_Learts_Image_360 extends WPBakeryShortCode {

}

vc_map( array(
	'name'        => esc_html__( 'Image 360', 'learts-addons' ),
	'description' => esc_html__( 'Display image 360', 'learts-addons' ),
	'base'        => 'learts_image_360',
	'icon'        => 'learts-element-icon-360',
	'category'    => sprintf( esc_html__( 'by %s', 'learts-addons' ), LEARTS_ADDONS_THEME_NAME ),
	'params'      => array(

		array(
			'type'       => 'dropdown',
			'param_name' => 'frames',
			'heading'    => esc_html__( 'Select Frames', 'learts-addons' ),
			'value'      => array(
				esc_html__( '8 frames', 'learts-addons' )  => 8,
				esc_html__( '16 frames', 'learts-addons' ) => 16,
				esc_html__( '24 frames', 'learts-addons' ) => 24,
			),
		),
		array(
			'type'        => 'attach_image',
			'heading'     => esc_html__( 'Image preview', 'learts-addons' ),
			'param_name'  => 'image_preview',
			'value'       => '',
			'description' => esc_html__( 'Upload product’s review image which is shown at the beginning', 'learts-addons' ),
		),
		array(
			'type'        => 'attach_image',
			'heading'     => esc_html__( 'Image 360', 'learts-addons' ),
			'param_name'  => 'image_360',
			'value'       => '',
			'description' => sprintf( wp_kses( __( 'Upload your 360 product’s image. You can read  <a href="%s" target="_blank">this blog</a> to know how to create a 360 product’s image',
				'learts-addons' ),
				array(
					'a' => array(
						'href'   => array(),
						'target' => array(),
					),
				) ),
				esc_url( 'https://www.ecwid.com/blog/guide-to-360-product-photography.html' ) ),
		),

		Learts_VC::get_param( 'animation' ),
		Learts_VC::get_param( 'el_class' ),
		Learts_VC::get_param( 'css' ),
	),
) );
