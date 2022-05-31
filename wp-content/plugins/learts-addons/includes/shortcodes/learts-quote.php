<?php

/**
 * Learts Product Grid Shortcode
 *
 * @version 1.0
 * @package Learts
 */
class WPBakeryShortCode_Learts_Quote extends WPBakeryShortCode {
	public function shortcode_css( $css_id ) {

		$atts  = vc_map_get_attributes( $this->getShortcode(), $this->getAtts() );
		$cssID = '#' . $css_id;
		$css   = '';

		$color_quote = $atts['color_quote'] ? $atts['color_quote'] : '#ffffff';
		$css .= $cssID . '.learts-quote{background-color:' . $color_quote . '}';

		global $learts_shortcode_css;
		$learts_shortcode_css .= Learts_VC::text2line( $css );
	}
}

// Mapping shortcode.
vc_map( array(
	'name'        => esc_html__( 'Quote Block', 'learts-addons' ),
	'base'        => 'learts_quote',
	'icon'        => 'learts-element-icon-quote',
	'category'    => sprintf( esc_html__( 'by %s', 'learts-addons' ), LEARTS_ADDONS_THEME_NAME ),
	'description' => esc_html__( 'Show a single quote.',
		'learts-addons' ),
	'params'      => array(
		array(
			'type'       => 'colorpicker',
			'heading'    => esc_html__( 'Background color for block quote', 'learts-addons' ),
			'param_name' => 'color_quote',
			'value'      => '#F5EDE6',
		),
		array(
			'type'        => 'textfield',
			'heading'     => esc_html__( 'Heading', 'learts-addons' ),
			'param_name'  => 'heading',
		),
		array(
			'type'        => 'textarea',
			'heading'     => esc_html__( 'Content', 'learts-addons' ),
			'param_name'  => 'content',
		),
		array(
			'heading'    => esc_html__( 'Quote Button Link', 'learts-addons' ),
			'type'       => 'vc_link',
			'param_name' => 'link',
		),
		array(
			'type'       => 'dropdown',
			'heading'    => esc_html__( 'Position button link', 'learts-addons' ),
			'param_name' => 'style',
			'value'      => array(
				esc_html__( 'Align Right', 'learts-addons' ) => 'right',
				esc_html__( 'Align Left', 'learts-addons' )  => 'left',
			),
			'std'        => 'right',
		),

		Learts_VC::get_param( 'animation' ),
		Learts_VC::get_param( 'el_class' ),
		Learts_VC::get_param( 'css' ),
	),
) );

