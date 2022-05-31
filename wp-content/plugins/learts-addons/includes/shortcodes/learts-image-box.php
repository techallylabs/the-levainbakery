<?php

/**
 * Learts Icon Box Shortcode
 *
 * @version 1.0
 * @package Learts
 */
class WPBakeryShortCode_Learts_Image_Box extends WPBakeryShortCode
{
    public function shortcode_css( $css_id ) {

        $atts   = vc_map_get_attributes( $this->getShortcode(), $this->getAtts() );
        $css_id = '#' . $css_id;



        $title_font_size  = $atts['title_font_size'];
        $title_font_color = $atts['title_font_color'] ? $atts['title_font_color'] : 'transparent';

        $content_font_size  = $atts['content_font_size'];
        $content_font_color = $atts['content_font_color'] ? $atts['content_font_color'] : 'transparent';

        $link_color = $atts['link_color'] ? $atts['link_color'] : 'transparent';


        $css = '';
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
            'heading'     => esc_html__( ' Alignment', 'learts-addons' ),
            'param_name'  => 'image_box_align',
            'value'       => array(
                esc_html__( 'Left', 'learts-addons' )   => 'left',
                esc_html__( 'Center', 'learts-addons' ) => 'center',
                esc_html__( 'Right', 'learts-addons' )  => 'right',
            ),
            'description' => esc_html__( 'Select image box align', 'learts-addons' ),
        ),
        array(
            'type'        => 'attach_image',
            'heading'     => esc_html__( 'Choose image', 'learts-addons' ),
            'param_name'  => 'image_display',
            'value'       => '',
            'description' => esc_html__( 'Select image from the library', 'learts-addons' ),
        ),
        array(
            'type'        => 'textarea',
            'heading'     => esc_html__( 'Title', 'learts-addons' ),
            'param_name'  => 'title',
            'admin_label' => true,
            'value'       => esc_html__( 'This is image box title', 'learts-addons' ),
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
            'value'      => wp_kses_post( __( '<p>This is the description of image box element</p>', 'learts-addons' ) ),
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

    ),
    // Css box,
    array(
        Learts_VC::get_param( 'css' ),
    )
);

vc_map( array(
    'name'        => esc_html__( 'Image Box', 'learts-addons' ),
    'base'        => 'learts_image_box',
    'icon'        => 'learts-element-icon-image-box',
    'category'    => sprintf( esc_html__( 'by %s', 'learts-addons' ), LEARTS_ADDONS_THEME_NAME ),
    'description' => esc_html__( 'Image with heading and description', 'learts-addons' ),
    'js_view'     => 'VcIconElementView_Backend',
    'params'      => $params,
) );
