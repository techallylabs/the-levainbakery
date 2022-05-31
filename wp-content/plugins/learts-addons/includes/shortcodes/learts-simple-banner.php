<?php

/**
 * ThemeMove Team Member Shortcode
 *
 * @version 1.0
 * @package Learts
 */
class WPBakeryShortCode_Learts_Simple_Banner extends WPBakeryShortCode {

}

// Mapping shortcode.
vc_map( array(
	'name'        => esc_html__( 'Simple Banner', 'learts-addons' ),
	'description' => esc_html__( 'Banner custom link with image or text', 'learts-addons' ),
	'base'        => 'learts_simple_banner',
	'icon'        => 'learts-element-icon-simple-banner',
	'category'    => sprintf( esc_html__( 'by %s', 'learts-addons' ), LEARTS_ADDONS_THEME_NAME ),
	'params'      => array(
		array(
			'heading'    => esc_html__( 'Style', 'learts-addons' ),
			'type'       => 'dropdown',
			'param_name' => 'style',
			'value'      => array(
				esc_html__( 'Image with text', 'learts-addons' ) => 'image',
				esc_html__( 'Only text', 'learts-addons' )       => 'text',
			),
		),
		array(
			'type'        => 'attach_image',
			'heading'     => esc_html__( 'Image', 'learts-addons' ),
			'param_name'  => 'image',
			'value'       => '',
			'description' => esc_html__( 'Select an image from media library.', 'learts-addons' ),
			'dependency'  => array(
				'element' => 'style',
				'value'   => 'image',
			),
		),
		array(
			'type'       => 'textarea_html',
			'heading'    => esc_html__( 'Content', 'learts-addons' ),
			'param_name' => 'content',
		),
		array(
			'type'        => 'dropdown',
			'heading'     => esc_html__( 'Align content', 'learts-addons' ),
			'param_name'  => 'align',
			'value'       => array(
				esc_html__( 'Text align left', 'learts-addons' )   => 'left',
				esc_html__( 'Text align center', 'learts-addons' ) => 'center',
				esc_html__( 'Text align right', 'learts-addons' )  => 'right',
			),
			'std'         => 'center',
			'description' => esc_html__( 'Select alignment for content.', 'learts-addons' ),
			'dependency'  => array(
				'element' => 'style',
				'value'   => 'image',
			),
		),
		array(
			'type'        => 'dropdown',
			'heading'     => esc_html__( 'Position content', 'learts-addons' ),
			'param_name'  => 'position',
			'value'       => array(
				esc_html__( 'Left top', 'learts-addons' )      => 'left-top',
				esc_html__( 'Left center', 'learts-addons' )   => 'left-center',
				esc_html__( 'Left bottom', 'learts-addons' )   => 'left-bottom',
				esc_html__( 'Right top', 'learts-addons' )     => 'right-top',
				esc_html__( 'Right center', 'learts-addons' )  => 'right-center',
				esc_html__( 'Right bottom', 'learts-addons' )  => 'right-bottom',
				esc_html__( 'Center top', 'learts-addons' )    => 'center-top',
				esc_html__( 'Center center', 'learts-addons' ) => 'center-center',
				esc_html__( 'Center bottom', 'learts-addons' ) => 'center-bottom',
			),
			'std'         => 'center-center',
			'description' => esc_html__( 'Select position for content.', 'learts-addons' ),
			'dependency'  => array(
				'element' => 'style',
				'value'   => 'image',
			),
		),
		array(
			'type'       => 'checkbox',
			'param_name' => 'fullwidth',
			'value'      => array( esc_html__( 'Display content full width 100%', 'learts-addons' ) => 'yes' ),
			'dependency' => array(
				'element' => 'style',
				'value'   => 'image',
			),
		),
		array(
			'type'       => 'checkbox',
			'param_name' => 'display_border',
			'value'      => array( esc_html__( 'Display border', 'learts-addons' ) => 'yes' ),
			'dependency' => array(
				'element' => 'style',
				'value'   => 'text',
			),
		),
		array(
			'type'       => 'checkbox',
			'param_name' => 'display_button',
			'value'      => array( esc_html__( 'Display button', 'learts-addons' ) => 'yes' ),
		),
		array(
			'type'        => 'vc_link',
			'heading'     => esc_html__( 'URL (Link)', 'learts-addons' ),
			'param_name'  => 'link',
			'description' => esc_html__( 'Enter button link', 'learts-addons' ),
		),

		// Animation
		array(
			'group'       => esc_html__( 'Animation', 'learts-addons' ),
			'type'        => 'dropdown',
			'heading'     => esc_html__( 'Banner Hover Effect', 'learts-addons' ),
			'admin_label' => true,
			'param_name'  => 'hover_style',
			'value'       => array(
				esc_html__( 'none', 'learts-addons' )            => '',
				esc_html__( 'Zoom in', 'learts-addons' )         => 'zoom-in',
				esc_html__( 'Border and zoom in', 'learts-addons' ) => 'border-zoom-in',
				esc_html__( 'Border zoom out and black overlay', 'learts-addons' ) => 'border-zoom-out',
				esc_html__( 'Blur', 'learts-addons' )            => 'blur',
				esc_html__( 'Gray scale', 'learts-addons' )      => 'grayscale',
				esc_html__( 'White Overlay', 'learts-addons' )   => 'white-overlay',
				esc_html__( 'Black Overlay', 'learts-addons' )   => 'black-overlay',
			),
			'std'         => 'zoom-in',
			'description' => esc_html__( 'Select animation style for banner when mouse over. Note: Some styles only work in modern browsers',
				'learts-addons' ),
		),
		array(
			'group'      => esc_html__( 'Animation', 'learts-addons' ),
			'type'        => 'dropdown',
			'heading'     => esc_html__( 'CSS Animation', 'learts-addons' ),
			'description' => esc_html__( 'Select type of animation for element to be animated when it "enters" the browsers viewport (Note: works only in modern browsers).',
				'learts-addons' ),
			'param_name'  => 'animation',
			'value'       => array(
				esc_html__( 'None', 'learts-addons' )             => '',
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
			'std'         => '',
		),

		Learts_VC::get_param( 'el_class' ),
		Learts_VC::get_param( 'css' ),
	),
) );
