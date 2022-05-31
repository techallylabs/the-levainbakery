<?php

/**
 * Learts MailChimp Maps Shortcode
 *
 * @version 1.0
 * @package Learts
 */
class WPBakeryShortCode_Learts_MailChimp extends WPBakeryShortCode {

	public function shortcode_css( $css_id ) {

		$atts   = vc_map_get_attributes( $this->getShortcode(), $this->getAtts() );
		$css_id = '#' . $css_id;

		$max_width = $atts['max_width'];

		$placeholder_color       = $atts['placeholder_color'];
		$placeholder_color_focus = $atts['placeholder_color_focus'];
		$input_color             = $atts['input_color'];
		$input_color_focus       = $atts['input_color_focus'];

		$input_border_color       = $atts['input_border_color'];
		$input_border_color_focus = $atts['input_border_color_focus'];
		$input_bg_color           = $atts['input_bg_color'];
		$input_bg_color_focus     = $atts['input_bg_color_focus'];

		$button_text_color       = $atts['button_text_color'];
		$button_text_color_hover = $atts['button_text_color_hover'];
		$button_bg_color         = $atts['button_bg_color'];
		$button_bg_color_hover   = $atts['button_bg_color_hover'];

		$css = '';

		if ( $max_width && $max_width != 0 ) {
			$css .= $css_id . '{ max-width:'. $max_width .'px }';
		}

		if ( $placeholder_color || $input_color || $input_border_color || $input_bg_color ) {

			$css .= $css_id . ' .mailchimp-email{';

			if ( $input_color ) {
				$css .= 'color:' . $input_color . ';';
			}

			if ( $input_border_color ) {
				$css .= 'border-color:' . $input_border_color . ';';
			}

			if ( $input_bg_color ) {
				$css .= 'background-color:' . $input_bg_color . ';';
			}

			$css .= '}';

			if ( $placeholder_color ) {
				$css .= $css_id . ' .mailchimp-email::-webkit-input-placeholder{color:' . $placeholder_color . '}';
				$css .= $css_id . ' .mailchimp-email::-moz-placeholder{color:' . $placeholder_color . ';opacity:1}';
				$css .= $css_id . ' .mailchimp-email:-ms-input-placeholder{color:' . $placeholder_color . '}';
				$css .= $css_id . ' .mailchimp-email:-moz-placeholder{color:' . $placeholder_color . '}';
			}
		}

		if ( $input_color_focus || $input_border_color_focus || $input_bg_color_focus ) {

			$css .= $css_id . ' .mailchimp-email:focus{';

			if ( $input_color_focus ) {
				$css .= 'color:' . $input_color_focus . ';';
			}

			if ( $input_border_color_focus ) {
				$css .= 'border-color:' . $input_border_color_focus . ';';
			}

			if ( $input_bg_color_focus ) {
				$css .= 'background-color:' . $input_bg_color_focus . ';';
			}

			$css .= '}';

			if ( $placeholder_color_focus ) {
				$css .= $css_id . ' .mailchimp-email:focus::-webkit-input-placeholder{color:' . $placeholder_color_focus . '}';
				$css .= $css_id . ' .mailchimp-email:focus::-moz-placeholder{color:' . $placeholder_color_focus . '}';
				$css .= $css_id . ' .mailchimp-email:focus::-ms-input-placeholder{color:' . $placeholder_color_focus . '}';
				$css .= $css_id . ' .mailchimp-email:focus:-moz-placeholder{color:' . $placeholder_color_focus . ';opacity:1}';
			}
		}

		if ( $button_text_color || $button_bg_color ) {

			$css .= $css_id . ' button[type="submit"]{';

			if ( $button_text_color ) {
				$css .= 'color:' . $button_text_color . ';';
			}

			if ( $button_bg_color ) {
				$css .= 'background-color:' . $button_bg_color . ';';
			}

			$css .= '}';
		}

		if ( $button_text_color_hover || $button_bg_color_hover ) {

			$css .= $css_id . ' button[type="submit"]:hover{';

			if ( $button_text_color_hover ) {
				$css .= 'color:' . $button_text_color_hover . ';';
			}

			if ( $button_bg_color_hover ) {
				$css .= 'background-color:' . $button_bg_color_hover . ';';
			}

			$css .= '}';
		}

		global $learts_shortcode_css;
		$learts_shortcode_css .= Learts_VC::text2line( $css );
	}
}

$api_key        = Learts_Addons::get_option( 'mailchimp_api_key' );
$mailchimp_list = array();
if ( $api_key ) {
	$mailchimp      = new Learts_Mailchimp();
	$mailchimp_list = $mailchimp->get_lists_for_dropdown_vc();
}

// Mapping shortcode.
vc_map( array(
	'name'        => esc_html__( 'Subscribe box (MailChimp)', 'learts-addons' ),
	'base'        => 'learts_mailchimp',
	'icon'        => 'learts-element-icon-mailchimp',
	'category'    => sprintf( esc_html__( 'by % s', 'learts-addons' ), LEARTS_ADDONS_THEME_NAME ),
	'description' => esc_html__( 'Displays your MailChimp for WordPress sign-up form', 'learts-addons' ),
	'params'      => array(
		array(
			'type'        => 'textfield',
			'heading'     => esc_html__( 'Placeholder Text', 'learts-addons' ),
			'param_name'  => 'placeholder_text',
			'value'       => esc_html__( 'Your email address', 'learts-addons' ),
			'description' => esc_html__( 'Text to display in the form input holder . ', 'learts-addons' ),
		),
		array(
			'type'        => 'textfield',
			'heading'     => esc_html__( 'Button Text', 'learts-addons' ),
			'param_name'  => 'button_text',
			'value'       => esc_html__( 'Subscribe', 'learts-addons' ),
			'description' => esc_html__( 'Text of the form’s submit button . ', 'learts-addons' ),
		),
		array(
			'type'        => 'dropdown',
			'heading'     => esc_html__( 'MailChimp list', 'learts-addons' ),
			'param_name'  => 'list_id',
			'admin_label' => true,
			'value'       => $mailchimp_list,
		),
		array(
			'type'        => 'dropdown',
			'heading'     => esc_html__( 'Color Scheme', 'learts-addons' ),
			'param_name'  => 'color_scheme',
			'value'       => array(
				esc_html__( 'Light', 'learts-addons' )  => 'light',
				esc_html__( 'Dark', 'learts-addons' )   => 'dark',
				esc_html__( 'Custom', 'learts-addons' ) => 'custom',
			),
			'description' => esc_html__( 'Select color scheme', 'learts-addons' ),
		),
		array(
			'type'        => 'number',
			'heading'     => esc_html__( 'Max Width', 'learts-addons' ),
			'admin_label' => true,
			'param_name'  => 'max_width',
		),
		array(
			'group'      => esc_html__( 'Color', 'learts-addons' ),
			'type'       => 'colorpicker',
			'heading'    => esc_html__( 'Placeholder color', 'learts-addons' ),
			'param_name' => 'placeholder_color',
			'value'      => '#ababab',
			'dependency' => array(
				'element' => 'color_scheme',
				'value'   => 'custom',
			),
		),
		array(
			'group'      => esc_html__( 'Color', 'learts-addons' ),
			'type'       => 'colorpicker',
			'heading'    => esc_html__( 'Placeholder color (on focus)', 'learts-addons' ),
			'param_name' => 'placeholder_color_focus',
			'value'      => '#ababab',
			'dependency' => array(
				'element' => 'color_scheme',
				'value'   => 'custom',
			),
		),
		array(
			'group'      => esc_html__( 'Color', 'learts-addons' ),
			'type'       => 'colorpicker',
			'heading'    => esc_html__( 'Input text color', 'learts-addons' ),
			'param_name' => 'input_color',
			'value'      => '#333333',
			'dependency' => array(
				'element' => 'color_scheme',
				'value'   => 'custom',
			),
		),
		array(
			'group'      => esc_html__( 'Color', 'learts-addons' ),
			'type'       => 'colorpicker',
			'heading'    => esc_html__( 'Input text color (on focus)', 'learts-addons' ),
			'param_name' => 'input_color_focus',
			'value'      => '#333333',
			'dependency' => array(
				'element' => 'color_scheme',
				'value'   => 'custom',
			),
		),
		array(
			'group'      => esc_html__( 'Color', 'learts-addons' ),
			'type'       => 'colorpicker',
			'heading'    => esc_html__( 'Input border color', 'learts-addons' ),
			'param_name' => 'input_border_color',
			'value'      => '#333333',
			'dependency' => array(
				'element' => 'color_scheme',
				'value'   => 'custom',
			),
		),
		array(
			'group'      => esc_html__( 'Color', 'learts-addons' ),
			'type'       => 'colorpicker',
			'heading'    => esc_html__( 'Input border color (on focus)', 'learts-addons' ),
			'param_name' => 'input_border_color_focus',
			'value'      => '#333333',
			'dependency' => array(
				'element' => 'color_scheme',
				'value'   => 'custom',
			),
		),
		array(
			'group'      => esc_html__( 'Color', 'learts-addons' ),
			'type'       => 'colorpicker',
			'heading'    => esc_html__( 'Input background color', 'learts-addons' ),
			'param_name' => 'input_bg_color',
			'value'      => '#ffffff',
			'dependency' => array(
				'element' => 'color_scheme',
				'value'   => 'custom',
			),
		),
		array(
			'group'      => esc_html__( 'Color', 'learts-addons' ),
			'type'       => 'colorpicker',
			'heading'    => esc_html__( 'Input background color (on focus)', 'learts-addons' ),
			'param_name' => 'input_bg_color_focus',
			'value'      => '#ffffff',
			'dependency' => array(
				'element' => 'color_scheme',
				'value'   => 'custom',
			),
		),
		array(
			'group'      => esc_html__( 'Color', 'learts-addons' ),
			'type'       => 'colorpicker',
			'heading'    => esc_html__( 'Button text color', 'learts-addons' ),
			'param_name' => 'button_text_color',
			'value'      => '#ffffff',
			'dependency' => array(
				'element' => 'color_scheme',
				'value'   => 'custom',
			),
		),
		array(
			'group'      => esc_html__( 'Color', 'learts-addons' ),
			'type'       => 'colorpicker',
			'heading'    => esc_html__( 'Button text color (on hover)', 'learts-addons' ),
			'param_name' => 'button_text_color_hover',
			'value'      => '#333333',
			'dependency' => array(
				'element' => 'color_scheme',
				'value'   => 'custom',
			),
		),
		array(
			'group'      => esc_html__( 'Color', 'learts-addons' ),
			'type'       => 'colorpicker',
			'heading'    => esc_html__( 'Button background color', 'learts-addons' ),
			'param_name' => 'button_bg_color',
			'value'      => '#333333',
			'dependency' => array(
				'element' => 'color_scheme',
				'value'   => 'custom',
			),
		),
		array(
			'group'      => esc_html__( 'Color', 'learts-addons' ),
			'type'       => 'colorpicker',
			'heading'    => esc_html__( 'Button background color (on hover)', 'learts-addons' ),
			'param_name' => 'button_bg_color_hover',
			'value'      => '#f4ede7',
			'dependency' => array(
				'element' => 'color_scheme',
				'value'   => 'custom',
			),
		),
		Learts_VC::get_param( 'animation' ),
		Learts_VC::get_param( 'el_class' ),
		Learts_VC::get_param( 'css' ),
	),
) );
