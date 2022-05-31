<?php

/**
 * Learts Button Shortcode.
 *
 * @version 1.0
 * @package Learts
 */
class WPBakeryShortCode_Learts_Button extends WPBakeryShortCode {

	public function shortcode_css( $css_id ) {

		$atts  = vc_map_get_attributes( $this->getShortcode(), $this->getAtts() );
		$cssID = '#' . $css_id;

		$style = $atts['style'] ? $atts['style'] : '';

		$font_color          = $atts['font_color'] ? $atts['font_color'] : 'transparent';
		$button_bg_color     = $atts['button_bg_color'] ? $atts['button_bg_color'] : 'transparent';
		$button_border_color = $atts['button_border_color'] ? $atts['button_border_color'] : 'transparent';

		$font_color_hover          = $atts['font_color_hover'] ? $atts['font_color_hover'] : 'transparent';
		$button_bg_color_hover     = $atts['button_bg_color_hover'] ? $atts['button_bg_color_hover'] : 'transparent';
		$button_border_color_hover = $atts['button_border_color_hover'] ? $atts['button_border_color_hover'] : 'transparent';

		$font_size = $atts['font_size'] . 'px';

		$css = '';

		if ( $style == 'custom' ) {
			$css .= $cssID . '{color:' . $font_color . ';background-color:' . $button_bg_color . ';border-color:' . $button_border_color . ';font-size:' . $font_size . ';}';
			$css .= $cssID . ':hover{color:' . $font_color_hover . ';background-color:' . $button_bg_color_hover . ';border-color:' . $button_border_color_hover . ';}';
		}

		global $learts_shortcode_css;
		$learts_shortcode_css .= $css;
	}
}

$params = array_merge(
// General
	array(
		array(
			'type'        => 'dropdown',
			'heading'     => esc_html__( 'Button Style', 'learts-addons' ),
			'admin_label' => true,
			'param_name'  => 'style',
			'value'       => array(
				esc_html__( 'Default', 'learts-addons' )     => '',
				esc_html__( 'Alternative', 'learts-addons' ) => 'alt',
				esc_html__( 'Custom', 'learts-addons' )      => 'custom',
			),
			'description' => esc_html__( 'Select button style.', 'learts-addons' ),
		),
		array(
			'type'        => 'dropdown',
			'heading'     => esc_html__( 'Size', 'learts-addons' ),
			'admin_label' => true,
			'param_name'  => 'size',
			'value'       => array(
				esc_html__( 'Small', 'learts-addons' )       => 'small',
				esc_html__( 'Medium', 'learts-addons' )      => 'medium',
				esc_html__( 'Large', 'learts-addons' )       => 'large',
				esc_html__( 'Extra large', 'learts-addons' ) => 'xlarge',
			),
			'std'         => 'medium',
			'description' => esc_html__( 'Select button size.', 'learts-addons' ),
		),
		array(
			'type'        => 'textfield',
			'heading'     => esc_html__( 'Text', 'learts-addons' ),
			'admin_label' => true,
			'param_name'  => 'text',
			'description' => esc_html__( 'Enter text on the button.', 'learts-addons' ),
		),
		array(
			'type'        => 'vc_link',
			'heading'     => esc_html__( 'URL (Link)', 'learts-addons' ),
			'param_name'  => 'link',
			'description' => esc_html__( 'Enter button link', 'learts-addons' ),
		),
		array(
			'type'       => 'number',
			'heading'    => esc_html__( 'Font size', 'learts-addons' ),
			'param_name' => 'font_size',
			'value'      => 14,
			'min'        => 10,
			'suffix'     => 'px',
			'dependency' => array(
				'element' => 'style',
				'value'   => 'custom',
			),
		),
		array(
			'type'       => 'checkbox',
			'param_name' => 'add_icon',
			'value'      => array( esc_html__( 'Add icon?', 'learts-addons' ) => 'yes' ),
		),
	),
	// Animation class
	array(
		Learts_VC::get_param( 'animation' ),
	),
	// Extra class
	array(
		Learts_VC::get_param( 'el_class' ),
	),
	// Icon.
	Learts_VC::icon_libraries( array( 'element' => 'add_icon', 'not_empty' => true ) ),
	// Icon position.
	array(
		array(
			'group'       => esc_html__( 'Icon', 'learts-addons' ),
			'type'        => 'dropdown',
			'heading'     => esc_html__( 'Icon position', 'learts-addons' ),
			'value'       => array(
				esc_html__( 'Left', 'learts-addons' )  => 'left',
				esc_html__( 'Right', 'learts-addons' ) => 'right',
			),
			'param_name'  => 'icon_pos',
			'description' => esc_html__( 'Select icon library.', 'learts-addons' ),
			'dependency'  => array( 'element' => 'add_icon', 'not_empty' => true ),
		),
	),
	// Color.
	array(
		array(
			'group'      => esc_html__( 'Color', 'learts-addons' ),
			'type'       => 'colorpicker',
			'heading'    => esc_html__( 'Background color', 'learts-addons' ),
			'param_name' => 'button_bg_color',
			'value'      => Learts_Addons::get_option('secondary_color'),
			'dependency' => array(
				'element' => 'style',
				'value'   => 'custom',
			),
		),
		array(
			'group'      => esc_html__( 'Color', 'learts-addons' ),
			'type'       => 'colorpicker',
			'heading'    => esc_html__( 'Background color (on hover)', 'learts-addons' ),
			'param_name' => 'button_bg_color_hover',
			'value'      => Learts_Addons::get_option( 'primary_color' ),
			'dependency' => array(
				'element' => 'style',
				'value'   => 'custom',
			),
		),
		array(
			'group'      => esc_html__( 'Color', 'learts-addons' ),
			'type'       => 'colorpicker',
			'heading'    => esc_html__( 'Text color', 'learts-addons' ),
			'param_name' => 'font_color',
			'value'      => '#fff',
			'dependency' => array(
				'element' => 'style',
				'value'   => 'custom',
			),
		),
		array(
			'group'      => esc_html__( 'Color', 'learts-addons' ),
			'type'       => 'colorpicker',
			'heading'    => esc_html__( 'Text color (on hover)', 'learts-addons' ),
			'param_name' => 'font_color_hover',
			'value'      => '#fff',
			'dependency' => array(
				'element' => 'style',
				'value'   => 'custom',
			),
		),
		array(
			'group'      => esc_html__( 'Color', 'learts-addons' ),
			'type'       => 'colorpicker',
			'heading'    => esc_html__( 'Border color', 'learts-addons' ),
			'param_name' => 'button_border_color',
			'value'      => Learts_Addons::get_option( 'secondary_color' ),
			'dependency' => array(
				'element' => 'style',
				'value'   => 'custom',
			),
		),
		array(
			'group'      => esc_html__( 'Color', 'learts-addons' ),
			'type'       => 'colorpicker',
			'heading'    => esc_html__( 'Border color (on hover)', 'learts-addons' ),
			'param_name' => 'button_border_color_hover',
			'value'      => Learts_Addons::get_option( 'primary_color' ),
			'dependency' => array(
				'element' => 'style',
				'value'   => 'custom',
			),
		),
	),

	array(
		// Css box,
		Learts_VC::get_param( 'css' ),
	)
);

// Mapping shortcode.
vc_map( array(
	'name'        => esc_html__( 'Button', 'learts-addons' ),
	'base'        => 'learts_button',
	'icon'        => 'learts-element-icon-button',
	'category'    => sprintf( esc_html__( 'by %s', 'learts-addons' ), LEARTS_ADDONS_THEME_NAME ),
	'description' => esc_html__( 'Eye catching button', 'learts-addons' ),
	'js_view'     => 'VcIconElementView_Backend',
	'params'      => $params,
) );
