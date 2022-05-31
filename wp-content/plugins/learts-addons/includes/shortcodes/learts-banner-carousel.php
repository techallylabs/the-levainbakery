<?php

/**
 * ThemeMove Banner Carousel Shortcode
 *
 * @version 1.0
 * @package learts
 */
class WPBakeryShortCode_Learts_Banner_Carousel extends WPBakeryShortCode {
}

vc_map( array(
	'name'        => esc_html__( 'Banner Carousel', 'learts-addons' ),
	'description' => esc_html__( 'Display banner center image carousel', 'learts-addons' ),
	'base'        => 'learts_banner_carousel',
	'icon'        => 'learts-element-icon-banner-carousel',
	'category'    => sprintf( esc_html__( 'by %s', 'learts-addons' ), LEARTS_ADDONS_THEME_NAME ),
	'params'      => array(

		array(
			'type'       => 'param_group',
			'heading'    => esc_html__( 'Single Banner', 'learts-addons' ),
			'param_name' => 'single_banner',
			'params'     => array(
				array(
					'type'        => 'attach_image',
					'heading'     => esc_html__( 'Image Product', 'learts-addons' ),
					'param_name'  => 'image',
					'value'       => '',
					'description' => esc_html__( 'Select an image from media library.', 'learts-addons' ),
				),
				array(
					'heading'     => esc_html__( 'Title Banner', 'learts-addons' ),
					'description' => esc_html__( 'A short text display before the banner text', 'learts-addons' ),
					'type'        => 'textfield',
					'param_name'  => 'title',
				),
				array(
					'heading'    => esc_html__( 'Title Color', 'learts-addons' ),
					'type'       => 'colorpicker',
					'param_name' => 'title_color',
					'value'      => '#333',
				),
				array(
					'heading'     => esc_html__( 'Banner Text', 'learts-addons' ),
					'description' => esc_html__( 'Enter the banner text', 'learts-addons' ),
					'type'        => 'textarea',
					'param_name'  => 'content',
				),
				array(
					'heading'    => esc_html__( 'Banner Text Color', 'learts-addons' ),
					'type'       => 'colorpicker',
					'param_name' => 'content_color',
					'value'      => '#333',
				),
				array(
					'heading'    => esc_html__( 'Banner Link', 'learts-addons' ),
					'type'       => 'vc_link',
					'param_name' => 'link',
				),
				array(
					'type'        => 'dropdown',
					'heading'     => esc_html__( 'Text Align and position', 'learts-addons' ),
					'description' => esc_html__( 'Select the text align and position for content.', 'learts-addons' ),
					'param_name'  => 'text_align_position',
					'value'       => array(
						esc_html__( 'Left', 'learts-addons' )   => 'left',
						esc_html__( 'Center', 'learts-addons' ) => 'center',
						esc_html__( 'Right', 'learts-addons' )  => 'right',
					),
					'std'         => 'left',
				),
			),
		),

		Learts_VC::get_param( 'animation' ),
		Learts_VC::get_param( 'el_class' ),
		Learts_VC::get_param( 'css' ),
	),
) );
