<?php

/**
 * Learts Space shortcode
 *
 * @version 1.0
 * @package Learts
 */
class WPBakeryShortCode_Learts_Space extends WPBakeryShortCode {

	public function shortcode_css( $css_id ) {

		$atts   = vc_map_get_attributes( $this->getShortcode(), $this->getAtts() );
		$css    = '';
		$css_id = '#' . $css_id;

		$unit      = ( isset( $atts['unit'] ) && $atts['unit'] ) ? $atts['unit'] : 'px';
		$height    = ( isset( $atts['height'] ) && $atts['height'] ) ? $atts['height'] : 0;
		$height_lg = ( isset( $atts['height_lg'] ) && $atts['height_lg'] ) ? intval( $atts['height_lg'] ) : 0;
		$height_md = ( isset( $atts['height_md'] ) && $atts['height_md'] ) ? intval( $atts['height_md'] ) : 0;
		$height_sm = ( isset( $atts['height_sm'] ) && $atts['height_sm'] ) ? intval( $atts['height_sm'] ) : 0;
		$height_xs = ( isset( $atts['height_xs'] ) && $atts['height_xs'] ) ? intval( $atts['height_xs'] ) : 0;

		$css .= $css_id . '{height:' . $height . $unit . '}';

		$css .= '@media (max-width:1199px){' . $css_id . '{height:' . $height_lg . $unit . '}}';
		$css .= '@media (max-width:991px){' . $css_id . '{height:' . $height_md . $unit . '}}';
		$css .= '@media (max-width:767px){' . $css_id . '{height:' . $height_sm . $unit . '}}';
		$css .= '@media (max-width:543px){' . $css_id . '{height:' . $height_xs . $unit . '}}';

		$css = Learts_VC::text2line( $css );

		global $learts_shortcode_css;
		$learts_shortcode_css .= $css;
	}
}

vc_map(
	array(
		'name'        => esc_html__( 'Responsive Empty Space', 'learts-addons' ),
		'base'        => 'learts_space',
		'icon'        => 'learts-element-icon-space',
		'category'    => sprintf( esc_html__( 'by %s', 'learts-addons' ), LEARTS_ADDONS_THEME_NAME ),
		'description' => esc_html__( 'Responsive blank space width custom height', 'learts-addons' ),
		'params'      => array(
			array(
				'type'        => 'dropdown',
				'heading'     => esc_html__( 'Units', 'learts-addons' ),
				'param_name'  => 'units',
				'admin_label' => true,
				'std'         => 'px',
				'value'       => array(
					esc_html__( 'px', 'learts-addons' )  => 'px',
					esc_html__( 'em', 'learts-addons' )  => 'em',
					esc_html__( 'rem', 'learts-addons' ) => 'rem',
					esc_html__( 'ex', 'learts-addons' )  => 'ex',
					esc_html__( 'cm', 'learts-addons' )  => 'cm',
					esc_html__( 'mm', 'learts-addons' )  => 'mm',
					esc_html__( 'in', 'learts-addons' )  => 'in',
					esc_html__( 'pt', 'learts-addons' )  => 'pt',
					esc_html__( 'pc', 'learts-addons' )  => 'pc',
				),
			),
			array(
				'type'        => 'number',
				'heading'     => esc_html__( 'Height', 'learts-addons' ),
				'description' => esc_html__( 'Extra large devices (large desktops)', 'learts-addons' ),
				'admin_label' => true,
				'param_name'  => 'height',
			),
			array(
				'type'        => 'number',
				'heading'     => esc_html__( 'Large Devices Height', 'learts-addons' ),
				'description' => esc_html__( 'Large devices (desktops, less than 1200px)', 'learts-addons' ),
				'param_name'  => 'height_lg',
			),
			array(
				'type'        => 'number',
				'heading'     => esc_html__( 'Medium Devices Height', 'learts-addons' ),
				'description' => esc_html__( 'Tablets, screen resolutions less than 992px', 'learts-addons' ),
				'param_name'  => 'height_md',
			),
			array(
				'type'        => 'number',
				'heading'     => esc_html__( 'Small Devices Height', 'learts-addons' ),
				'description' => esc_html__( 'Landscape phones, screen resolutions less than 768px.', 'learts-addons' ),
				'param_name'  => 'height_sm',
			),
			array(
				'type'        => 'number',
				'heading'     => esc_html__( 'Extra Small Devices Height', 'learts-addons' ),
				'description' => esc_html__( 'Portrait phones,screen resolutions less than 576px.', 'learts-addons' ),
				'param_name'  => 'height_xs',
			),
			array(
				'type'        => 'textfield',
				'heading'     => esc_html__( 'Element ID', 'learts-addons' ),
				'description' => sprintf( __( 'Enter element ID (Note: make sure it is unique and valid according to <a href="%s" target="_blank">w3c specification</a>).', 'learts-addons' ), 'https://www.w3schools.com/tags/att_global_id.asp' ),
				'admin_label' => true,
				'param_name'  => 'id',
			),
			Learts_VC::get_param( 'el_class' ),
		)
	)
);
