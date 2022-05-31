<?php

/**
 * Learts Icon Box Shortcode
 *
 * @version 1.0
 * @package Learts
 */
class WPBakeryShortCode_Learts_Icon_Box extends WPBakeryShortCode {

	public function shortcode_css( $css_id ) {

		$atts   = vc_map_get_attributes( $this->getShortcode(), $this->getAtts() );
		$css_id = '#' . $css_id;

		$icon_font_size = $atts['icon_font_size'];
		$icon_color     = $atts['icon_color'] ? $atts['icon_color'] : 'transparent';
		$icon_bgcolor   = $atts['icon_bgcolor'] ? $atts['icon_bgcolor'] : 'transparent';

		$title_font_size  = $atts['title_font_size'];
		$title_font_color = $atts['title_font_color'] ? $atts['title_font_color'] : 'transparent';

		$content_font_size  = $atts['content_font_size'];
		$content_font_color = $atts['content_font_color'] ? $atts['content_font_color'] : 'transparent';

		$link_color = $atts['link_color'] ? $atts['link_color'] : 'transparent';

		$with_bg  = $atts['with_bg'];
		$bg_shape = $atts['bg_shape'];

		$css = '';

		$icon = $css_id . ' i,' . $css_id . ' span';
		$css .= $icon . '{color: ' . $icon_color . ';';

		if ( 'yes' == $with_bg ) {
			if ( $bg_shape == 'square' || $bg_shape == 'circle' || $bg_shape == 'rounded' || $bg_shape == 'small-circle' ) {
				$css .= 'background-color:' . $icon_bgcolor . ';';
			} else {
				$css .= 'border-color:' . $icon_bgcolor . ';';
			}
		}

		if ( is_numeric( $icon_font_size ) ) {
			$css .= 'font-size:' . $icon_font_size . 'px;}';
		}

		$title = $css_id . ' .title, ' . $css_id . ' .title > a';
		$css .= $title . '{color:' . $title_font_color . ';';

		if ( is_numeric( $title_font_size ) ) {
			$css .= 'font-size:' . $title_font_size . 'px;}';
		}

		$description = $css_id . ' .description,' . $css_id . ' .description em,' . $css_id . ' .description p';
		$css .= $description . '{color:' . $content_font_color . ';';

		if ( is_numeric( $content_font_size ) ) {
			$css .= 'font-size:' . $content_font_size . 'px;}';
		}

		$css .= $css_id . ' a{color:' . $link_color . ';}';

		global $learts_shortcode_css;
		$learts_shortcode_css .= $css;
	}
}

$params = array_merge(
// General
	array(
		array(
			'type'        => 'dropdown',
			'heading'     => esc_html__( 'Style', 'learts-addons' ),
			'param_name'  => 'style',
			'value'       => array(
				esc_html__( 'Left', 'learts-addons' )   => 'left',
				esc_html__( 'Center', 'learts-addons' ) => 'center',
				esc_html__( 'Right', 'learts-addons' )  => 'right',
			),
			'description' => esc_html__( 'Select icon box style', 'learts-addons' ),
		),
		array(
			'type'       => 'dropdown',
			'heading'    => esc_html__( 'Vertical Alignment', 'learts-addons' ),
			'param_name' => 'v_align',
			'value'      => array(
				esc_html__( 'Top', 'learts-addons' )    => 'top',
				esc_html__( 'Middle', 'learts-addons' ) => 'middle',
				esc_html__( 'Bottom', 'learts-addons' ) => 'bottom',
			),
		),
		array(
			'type'        => 'textarea',
			'heading'     => esc_html__( 'Title', 'learts-addons' ),
			'param_name'  => 'title',
			'admin_label' => true,
			'value'       => esc_html__( 'This is icon box title', 'learts-addons' ),
		),
		array(
			'type'       => 'number',
			'heading'    => esc_html__( 'Title font size', 'learts-addons' ),
			'param_name' => 'title_font_size',
			'value'      => 20,
			'min'        => 10,
			'suffix'     => 'px',
		),
		array(
			'type'       => 'textarea_html',
			'heading'    => esc_html__( 'Description', 'learts-addons' ),
			'param_name' => 'content',
			'value'      => wp_kses_post( __( '<p>This is the description of icon box element</p>', 'learts-addons' ) ),
		),
		array(
			'type'       => 'number',
			'heading'    => esc_html__( 'Description font size', 'learts-addons' ),
			'param_name' => 'content_font_size',
			'value'      => 15,
			'min'        => 10,
			'suffix'     => 'px',
		),
		array(
			'type'        => 'vc_link',
			'heading'     => esc_html__( 'URL (Link)', 'learts-addons' ),
			'param_name'  => 'link',
			'description' => esc_html__( 'Add link to icon box', 'learts-addons' ),
		),
		array(
			'type'       => 'checkbox',
			'param_name' => 'use_link_title',
			'value'      => array( esc_html__( 'Use link in title', 'learts-addons' ) => 'yes' ),
		),
		array(
			'type'       => 'checkbox',
			'param_name' => 'use_text',
			'value'      => array( esc_html__( 'Use text instead of icon', 'learts-addons' ) => 'yes' ),
		),
        array(
            'type'       => 'checkbox',
            'param_name' => 'full_height',
            'value'      => array( esc_html__( 'Full height', 'learts-addons' ) => 'yes' ),
        ),

		array(
			'type'       => 'textfield',
			'heading'    => esc_html__( 'Text', 'learts-addons' ),
			'param_name' => 'text',
			'dependency' => array(
				'element' => 'use_text',
				'value'   => 'yes',
			),
		),

	),

	// Animation
	array(
		Learts_VC::get_param( 'animation' ),
	),

	// Extra class.
	array(
		Learts_VC::get_param( 'el_class' ),
	),

	// Icon.
	Learts_VC::icon_libraries( array( 'element' => 'use_text', 'value_not_equal_to' => 'yes' ) ),
	// Icon font-size.
	array(
		array(
			'group'      => esc_html__( 'Icon', 'learts-addons' ),
			'type'       => 'number',
			'heading'    => esc_html__( 'Font size', 'learts-addons' ),
			'param_name' => 'icon_font_size',
			'value'      => 40,
			'min'        => 10,
			'suffix'     => 'px',
		),
	),
	array(
		array(
			'group'      => esc_html__( 'Icon', 'learts-addons' ),
			'type'       => 'checkbox',
			'param_name' => 'with_bg',
			'value'      => array( esc_html__( 'Icon with background', 'learts-addons' ) => 'yes' ),
		),
		array(
			'group'      => esc_html__( 'Icon', 'learts-addons' ),
			'type'       => 'dropdown',
			'heading'    => esc_html__( 'Background shape', 'learts-addons' ),
			'param_name' => 'bg_shape',
			'value'      => array(
				esc_html__( 'Square', 'learts-addons' )          => 'square',
				esc_html__( 'Circle', 'learts-addons' )          => 'circle',
				esc_html__( 'Small circle', 'learts-addons' )    => 'small-circle',
				esc_html__( 'Rounded', 'learts-addons' )         => 'rounded',
				esc_html__( 'Outline Square', 'learts-addons' )  => 'outline-square',
				esc_html__( 'Outline Circle', 'learts-addons' )  => 'outline-circle',
				esc_html__( 'Outline Rounded', 'learts-addons' ) => 'outline-rounded',
			),
			'dependency' => array(
				'element' => 'with_bg',
				'value'   => 'yes',
			),
		),
	),

	// Color.
	array(
		array(
			'group'       => esc_html__( 'Color', 'learts-addons' ),
			'type'        => 'colorpicker',
			'heading'     => esc_html__( 'Title font color', 'learts-addons' ),
			'param_name'  => 'title_font_color',
			'value'       => '#222222',
			'description' => esc_html__( 'Select title font color', 'learts-addons' ),
		),
		array(
			'group'       => esc_html__( 'Color', 'learts-addons' ),
			'type'        => 'colorpicker',
			'heading'     => esc_html__( 'Description font color', 'learts-addons' ),
			'param_name'  => 'content_font_color',
			'value'       => '#878787',
			'description' => esc_html__( 'Select description font color', 'learts-addons' ),
		),
		array(
			'group'       => esc_html__( 'Color', 'learts-addons' ),
			'type'        => 'colorpicker',
			'heading'     => esc_html__( 'Link color', 'learts-addons' ),
			'param_name'  => 'link_color',
			'value'       => '#878787',
			'description' => esc_html__( 'Select link color', 'learts-addons' ),
		),
		array(
			'group'       => esc_html__( 'Color', 'learts-addons' ),
			'type'        => 'colorpicker',
			'heading'     => esc_html__( 'Icon color', 'learts-addons' ),
			'admin_label' => true,
			'param_name'  => 'icon_color',
			'value'       => Learts_Addons::get_option( 'primary_color' ),
			'description' => esc_html__( 'Select icon color', 'learts-addons' ),
		),
		array(
			'group'       => esc_html__( 'Color', 'learts-addons' ),
			'type'        => 'colorpicker',
			'heading'     => esc_html__( 'Icon background color', 'learts-addons' ),
			'param_name'  => 'icon_bgcolor',
			'value'       => Learts_Addons::get_option( 'primary_color' ),
			'description' => esc_html__( 'Select icon background color', 'learts-addons' ),
			'dependency'  => array(
				'element' => 'with_bg',
				'value'   => 'yes',
			),
		),
	),
	// Css box,
	array(
		Learts_VC::get_param( 'css' ),
	)
);

vc_map( array(
	'name'        => esc_html__( 'Icon Box', 'learts-addons' ),
	'base'        => 'learts_icon_box',
	'icon'        => 'learts-element-icon-icon-box',
	'category'    => sprintf( esc_html__( 'by %s', 'learts-addons' ), LEARTS_ADDONS_THEME_NAME ),
	'description' => esc_html__( 'Eye catching icons from libraries', 'learts-addons' ),
	'js_view'     => 'VcIconElementView_Backend',
	'params'      => $params,
) );
