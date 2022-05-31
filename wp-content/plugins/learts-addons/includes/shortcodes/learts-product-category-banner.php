<?php

/**
 * ThemeMove Category Banner Shortcode
 *
 * @version 1.0
 * @package Learts
 */
class WPBakeryShortCode_Learts_Product_Category_Banner extends WPBakeryShortCode {

	public function shortcode_css( $css_id ) {

		$atts  = vc_map_get_attributes( $this->getShortcode(), $this->getAtts() );
		$cssID = '#' . $css_id;
		$css   = '';

		$font_size                 = $atts['font_size'] ? $atts['font_size'] : 18;
		$color_name                = $atts['color_name'] ? $atts['color_name'] : SECONDARY_COLOR;
		$color_name_hover          = $atts['color_name_hover'] ? $atts['color_name_hover'] : PRIMARY_COLOR;
		$color_count               = $atts['color_count'] ? $atts['color_count'] : '#ababab';
		$color_product_count_hover = $atts['color_product_count_hover'] ? $atts['color_product_count_hover'] : PRIMARY_COLOR;
		$color_label               = $atts['color_label'] ? $atts['color_label'] : '#F4EDE7';

		$css .= $cssID . ' .category-name{';
		$css .= 'color:' . $color_name . ';';
		$css .= 'font-size:' . $font_size . 'px;}';
		$css .= $cssID . ':hover .category-name{color:' . $color_name_hover . '}';
		$css .= $cssID . ' .product-count{color:' . $color_count . '}';
		$css .= $cssID . ':hover .product-count{color:' . $color_product_count_hover . '}';
		$css .= $cssID . '.style-banner-color .banner-content{background-color:'. $color_label .'}';

		global $learts_shortcode_css;
		$learts_shortcode_css .= Learts_VC::text2line( $css );
	}

	/**
	 *
	 * Custom title in backend, show image instead of icon
	 *
	 * @param $param
	 * @param $value
	 *
	 * @return string
	 */
	public function singleParamHtmlHolder( $param, $value ) {

		$output = '';

		$param_name = isset( $param['param_name'] ) ? $param['param_name'] : '';
		$type       = isset( $param['type'] ) ? $param['type'] : '';
		$class      = isset( $param['class'] ) ? $param['class'] : '';

		if ( 'attach_image' === $param['type'] && 'image' === $param_name ) {
			$output       .= '<input type="hidden" class="wpb_vc_param_value ' . $param_name . ' ' . $type . ' ' . $class . '" name="' . $param_name . '" value="' . $value . '" />';
			$element_icon = $this->settings( 'icon' );
			$img          = wpb_getImageBySize( array(
				'attach_id'  => (int) preg_replace( '/[^\d]/', '', $value ),
				'thumb_size' => 'thumbnail',
				'class'      => 'attachment-thumbnail vc_general vc_element-icon tm-element-icon-none',
			) );
			$this->setSettings( 'logo',
				( $img ? $img['thumbnail'] : '<img width="150" height="150" src="' . vc_asset_url( 'vc/blank.gif' ) . '" class="attachment-thumbnail vc_general vc_element-icon learts-element-icon-banner"  data-name="' . $param_name . '" alt="image" title="logo" style="display: none;" />' ) . '<span class="no_image_image vc_element-icon' . ( ! empty( $element_icon ) ? ' ' . $element_icon : '' ) . ( $img && ! empty( $img['p_img_large'][0] ) ? ' image-exists' : '' ) . '" /><a href="#" class="column_edit_trigger' . ( $img && ! empty( $img['p_img_large'][0] ) ? ' image-exists' : '' ) . '">' . esc_html__( 'Add image',
					'learts-addons' ) . '</a>' );
			$output .= $this->outputCustomTitle( $this->settings['name'] );
		} elseif ( ! empty( $param['holder'] ) ) {
			if ( 'input' === $param['holder'] ) {
				$output .= '<' . $param['holder'] . ' readonly="true" class="wpb_vc_param_value ' . $param_name . ' ' . $type . ' ' . $class . '" name="' . $param_name . '" value="' . $value . '">';
			} elseif ( in_array( $param['holder'],
				array(
					'img',
					'iframe',
				) ) ) {
				$output .= '<' . $param['holder'] . ' class="wpb_vc_param_value ' . $param_name . ' ' . $type . ' ' . $class . '" name="' . $param_name . '" src="' . $value . '">';
			} elseif ( 'hidden' !== $param['holder'] ) {
				$output .= '<' . $param['holder'] . ' class="wpb_vc_param_value ' . $param_name . ' ' . $type . ' ' . $class . '" name="' . $param_name . '">' . $value . '</' . $param['holder'] . '>';
			}
		}

		if ( ! empty( $param['admin_label'] ) && true === $param['admin_label'] ) {
			$output .= '<span class="vc_admin_label admin_label_' . $param['param_name'] . ( empty( $value ) ? ' hidden-label' : '' ) . '"><label>' . $param['heading'] . '</label>: ' . $value . '</span>';
		}

		return $output;
	}

	protected function outputTitle( $title ) {
		return '';
	}

	protected function outputCustomTitle( $title ) {
		return '<h4 class="wpb_element_title">' . $title . ' ' . $this->settings( 'logo' ) . '</h4>';
	}
}

vc_map( array(
	'name'        => esc_html__( 'Product Category Banner', 'learts-addons' ),
	'description' => esc_html__( 'Simple banner for single product category', 'learts-addons' ),
	'base'        => 'learts_product_category_banner',
	'icon'        => 'learts-element-icon-product-category-banner',
	'category'    => sprintf( esc_html__( 'by %s', 'learts-addons' ), LEARTS_ADDONS_THEME_NAME ),
	'params'      => array(

		// General
		array(
			'type'        => 'dropdown',
			'heading'     => esc_html__( 'Image Source', 'learts-addons' ),
			'param_name'  => 'source',
			'value'       => array(
				esc_html__( 'Media library', 'learts-addons' ) => 'media_library',
				esc_html__( 'External link', 'learts-addons' ) => 'external_link',
			),
			'std'         => 'media_library',
			'description' => esc_html__( 'Select image source.', 'learts-addons' ),
		),
		array(
			'type'        => 'attach_image',
			'heading'     => esc_html__( 'Banner Image', 'learts-addons' ),
			'param_name'  => 'image',
			'value'       => '',
			'description' => esc_html__( 'Select an image from media library.', 'learts-addons' ),
			'dependency'  => array(
				'element' => 'source',
				'value'   => 'media_library',
			),
		),
		array(
			'type'        => 'textfield',
			'heading'     => esc_html__( 'External Link', 'learts-addons' ),
			'param_name'  => 'custom_src',
			'description' => esc_html__( 'Select external link.', 'learts-addons' ),
			'dependency'  => array(
				'element' => 'source',
				'value'   => 'external_link',
			),
		),
		array(
			'type'        => 'textfield',
			'heading'     => esc_html__( 'Image Size (Optional)', 'learts-addons' ),
			'param_name'  => 'img_size',
			'value'       => 'full',
			'description' => esc_html__( 'Enter image size (Example: "thumbnail", "medium", "large", "full" or other sizes defined by theme). Alternatively enter size in pixels (Example: 200x100 (Width x Height)).',
				'learts-addons' ),
			'dependency'  => array(
				'element' => 'source',
				'value'   => array( 'media_library' ),
			),
		),
		array(
			'type'        => 'textfield',
			'heading'     => esc_html__( 'Image Size (Optional)', 'learts-addons' ),
			'param_name'  => 'external_img_size',
			'value'       => '',
			'description' => esc_html__( 'Enter image size in pixels. Example: 200x100 (Width x Height).',
				'learts-addons' ),
			'dependency'  => array(
				'element' => 'source',
				'value'   => 'external_link',
			),
		),
		Learts_VC::get_param( 'product_cat_dropdown' ),
		array(
			'type'        => 'number',
			'heading'     => esc_html__( 'Font size', 'learts-addons' ),
			'description' => esc_html__( 'Font size of the product category name.', 'learts-addons' ),
			'param_name'  => 'font_size',
			'value'       => 18,
			'min'         => 16,
			'max'         => 50,
			'step'        => 1,
			'suffix'      => 'px',
		),
		array(
			'heading'    => esc_html__( 'Style product category banner', 'learts-addons' ),
			'type'       => 'dropdown',
			'param_name' => 'style_banner',
			'value'      => array(
				esc_html__( 'Name of category below', 'learts-addons' )      => 'below',
				esc_html__( 'Name of category inside', 'learts-addons' )     => 'inside',
				esc_html__( 'Name of category with color', 'learts-addons' ) => 'color',
			),
			'std'        => 'below',
		),
		array(
			'type'        => 'textfield',
			'heading'     => esc_html__( 'Description', 'learts-addons' ),
			'param_name'  => 'description_banner',
			'description' => esc_html__( 'Display description of banner category.', 'learts-addons' ),
			'dependency'  => array(
				'element' => 'style_banner',
				'value'   => 'below',
			),
		),
		array(
			'heading'    => esc_html__( 'Position of description.', 'learts-addons' ),
			'type'       => 'dropdown',
			'param_name' => 'position_description',
			'value'      => array(
				esc_html__( 'On the left', 'learts-addons' )  => 'left',
				esc_html__( 'On the right', 'learts-addons' ) => 'right',
			),
			'dependency' => array(
				'element' => 'style_banner',
				'value'   => 'below',
			),
		),
		array(
			'heading'    => esc_html__( 'Product Count Visibility', 'learts-addons' ),
			'type'       => 'dropdown',
			'param_name' => 'product_count_visibility',
			'value'      => array(
				esc_html__( 'Always visible', 'learts-addons' ) => 'always',
				esc_html__( 'When hover', 'learts-addons' )     => 'hover',
				esc_html__( 'Hidden', 'learts-addons' )         => 'hidden',
			),
			'dependency' => array(
				'element' => 'style_banner',
				'value'   => array( 'below', 'inside' ),
			),
		),
		array(
			'type'       => 'colorpicker',
			'heading'    => esc_html__( 'Background color of label category', 'learts-addons' ),
			'param_name' => 'color_label',
			'value'      => "#F4EDE7",
			'dependency' => array(
				'element' => 'style_banner',
				'value'   => 'color',
			),
		),
		array(
			'type'       => 'checkbox',
			'param_name' => 'open_new_tab',
			'value'      => array( esc_html__( 'Open link in a new tab', 'learts-addons' ) => 'yes' ),
		),
		Learts_VC::get_param( 'el_class' ),

		// Color
		array(
			'group'      => esc_html__( 'Color', 'learts-addons' ),
			'type'       => 'colorpicker',
			'heading'    => esc_html__( 'Category Name', 'learts-addons' ),
			'param_name' => 'color_name',
			'value'      => Learts_Addons::get_option('primary_color'),
		),
		array(
			'group'      => esc_html__( 'Color', 'learts-addons' ),
			'type'       => 'colorpicker',
			'heading'    => esc_html__( 'Category Name (on hover)', 'learts-addons' ),
			'param_name' => 'color_name_hover',
			'value'      => Learts_Addons::get_option('primary_color'),
		),
		array(
			'group'      => esc_html__( 'Color', 'learts-addons' ),
			'type'       => 'colorpicker',
			'heading'    => esc_html__( 'Product Count', 'learts-addons' ),
			'param_name' => 'color_count',
			'value'      => '#ababab',
		),
		array(
			'group'      => esc_html__( 'Color', 'learts-addons' ),
			'type'       => 'colorpicker',
			'heading'    => esc_html__( 'Product Count (on hover)', 'learts-addons' ),
			'param_name' => 'color_product_count_hover',
			'value'      => Learts_Addons::get_option('primary_color'),
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
				esc_html__( 'Border and zoom', 'learts-addons' ) => 'border-zoom',
				esc_html__( 'Zoom in', 'learts-addons' )         => 'zoom-in',
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
			'type'       => 'animation_style',
			'heading'    => esc_html__( 'Banner Animation', 'learts-addons' ),
			'param_name' => 'animation',
			'settings'   => array(
				'type' => array( 'in', 'other' ),
			),
		),
		Learts_VC::get_param( 'css' ),

	),
) );
