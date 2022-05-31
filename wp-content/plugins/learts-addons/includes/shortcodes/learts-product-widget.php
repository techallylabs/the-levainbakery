<?php

/**
 * Learts Product Widget Shortcode
 *
 * @version 1.0
 * @package Learts
 */
class WPBakeryShortCode_Learts_Product_Widget extends WPBakeryShortCode {

	public function get_query( $atts ) {
		return Learts_VC::get_products_by_datasource( $atts['data_source'], $atts );
	}

	public function shortcode_css( $css_id ) {

		$atts  = vc_map_get_attributes( $this->getShortcode(), $this->getAtts() );
		$cssID = '#' . $css_id;
		$css   = '';

		if ( isset( $atts['img_size'] ) ) {

			if ( empty( $atts['img_size'] ) ) {
				$atts['img_size'] = 'woocommerce_thumbnail';
			}

			$size  = Learts_VC::convert_image_size( $atts['img_size'] );
			$width = '';

			if ( is_array( $size ) ) {
				$width = $size[0];
			} elseif ( is_string( $size ) && $w = Learts_VC::get_image_width( $size ) ) {
				$width = $w;
			}

			if ( $width ) {
				$css .= $cssID . ' .product_list_widget .product-thumb{width:' . $width . 'px}';
			}
		}

		global $learts_shortcode_css;
		$learts_shortcode_css .= $css;
	}

}

// Mapping shortcode.
vc_map( array(
	'name'        => esc_html__( 'Product Widget', 'learts-addons' ),
	'base'        => 'learts_product_widget',
	'icon'        => 'learts-element-icon-product-widget',
	'category'    => sprintf( esc_html__( 'by %s', 'learts-addons' ), LEARTS_ADDONS_THEME_NAME ),
	'description' => esc_html__( 'Add products in a widget', 'learts-addons' ),
	'params'      => array(
		array(
			'type'        => 'textfield',
			'heading'     => esc_html__( 'Title', 'learts-addons' ),
			'param_name'  => 'title',
			'admin_label' => true,
		),
		array(
			'type'        => 'dropdown',
			'param_name'  => 'data_source',
			'admin_label' => true,
			'heading'     => esc_html__( 'Data source', 'learts-addons' ),
			'value'       => array(
				esc_html__( 'Recent Products', 'learts-addons' )       => 'recent_products',
				esc_html__( 'Featured Products', 'learts-addons' )     => 'featured_products',
				esc_html__( 'On Sale Products', 'learts-addons' )      => 'sale_products',
				esc_html__( 'Best-Selling Products', 'learts-addons' ) => 'best_selling_products',
				esc_html__( 'Top Rated Products', 'learts-addons' )    => 'top_rated_products',
				esc_html__( 'Product Attribute', 'learts-addons' )     => 'product_attribute',
				esc_html__( 'List of Products', 'learts-addons' )      => 'products',
				esc_html__( 'Category', 'learts-addons' )              => 'category',
			),
			'description' => esc_html__( 'Select data source for your product widget', 'learts-addons' ),
		),
		Learts_VC::get_param( 'product_cat_dropdown', '', array(
			'element' => 'data_source',
			'value'   => array( 'category' ),
		) ),
		Learts_VC::get_param( 'product_autocomplete', '', array(
			'element' => 'data_source',
			'value'   => array( 'products' ),
		) ),
		Learts_VC::get_param( 'product_attribute', '', array(
			'element' => 'data_source',
			'value'   => array( 'product_attribute' ),
		) ),
		Learts_VC::get_param( 'product_term', '', array(
			'element' => 'data_source',
			'value'   => array( 'product_attribute' ),
		) ),
		array(
			'type'        => 'checkbox',
			'param_name'  => 'include_children',
			'description' => esc_html__( 'Whether or not to include children categories', 'learts-addons' ),
			'value'       => array( esc_html__( 'Include children', 'learts-addons' ) => 'yes' ),
			'std'         => 'yes',
			'dependency'  => array( 'element' => 'data_source', 'value' => array( 'category' ) ),
		),
		array(
			'type'        => 'number',
			'param_name'  => 'number',
			'heading'     => esc_html__( 'Number', 'learts-addons' ),
			'description' => esc_html__( 'Number of products in the widget (-1 is all, limited to 1000)', 'learts-addons' ),
			'value'       => 4,
			'max'         => 1000,
			'min'         => - 1,
		),
		array(
			'type'       => 'autocomplete',
			'heading'    => __( 'Exclude products', 'learts-addons' ),
			'param_name' => 'exclude',
			'settings'   => array(
				'multiple' => true,
				'sortable' => true,
			),
		),
		array(
			'type'       => 'checkbox',
			'param_name' => 'enable_carousel',
			'value'      => array( esc_html__( 'Enable Carousel', 'learts-addons' ) => 'yes' ),
		),
		array(
			'type'       => 'number',
			'param_name' => 'number_per_slide',
			'heading'    => esc_html__( 'Number of products per slide', 'learts-addons' ),
			'value'      => 2,
			'max'        => 1000,
			'min'        => 1,
			'dependency' => array(
				'element' => 'enable_carousel',
				'value'   => array( 'yes' ),
			),
		),
		array(
			'type'       => 'checkbox',
			'param_name' => 'loop',
			'value'      => array( esc_html__( 'Enable loop mode', 'learts-addons' ) => 'yes' ),
			'std'        => 'yes',
			'dependency' => array(
				'element' => 'enable_carousel',
				'value'   => array( 'yes' ),
			),
		),
		array(
			'type'       => 'checkbox',
			'param_name' => 'auto_play',
			'value'      => array( esc_html__( 'Enable carousel autoplay', 'learts-addons' ) => 'yes' ),
			'dependency' => array(
				'element' => 'enable_carousel',
				'value'   => array( 'yes' ),
			),
		),
		array(
			'type'       => 'number',
			'param_name' => 'auto_play_speed',
			'heading'    => esc_html__( 'Auto play speed', 'learts-addons' ),
			'value'      => 5,
			'max'        => 10,
			'min'        => 3,
			'step'       => 0.5,
			'suffix'     => 'seconds',
			'dependency' => array(
				'element' => 'auto_play',
				'value'   => 'yes',
			),
		),
		array(
			'type'       => 'dropdown',
			'param_name' => 'nav_type',
			'heading'    => esc_html__( 'Navigation type', 'learts-addons' ),
			'value'      => array(
				esc_html__( 'Arrows', 'learts-addons' ) => 'arrows',
				esc_html__( 'Dots', 'learts-addons' )   => 'dots',
				__( 'Arrows & Dots', 'learts-addons' )  => 'both',
				esc_html__( 'None', 'learts-addons' )   => '',
			),
			'dependency' => array(
				'element' => 'enable_carousel',
				'value'   => array( 'yes' ),
			),
		),
		array(
			'type'       => 'dropdown',
			'param_name' => 'arrows_position',
			'heading'    => esc_html__( 'Arrows Position', 'learts-addons' ),
			'value'      => array(
				esc_html__( 'In the title', 'learts-addons' )   => 'title',
				esc_html__( 'Left and Right', 'learts-addons' ) => 'left-right',
				esc_html__( 'Bottom', 'learts-addons' )         => 'bottom',
			),
			'dependency' => array(
				'element' => 'nav_type',
				'value'   => array( 'arrows', 'both' ),
			),
		),
		array(
			'type'        => 'checkbox',
			'param_name'  => 'enable_buttons',
			'value'       => array( esc_html__( 'Show Add to Cart Button', 'learts-addons' ) => 'yes' ),
		),
		array(
			'type'        => 'textfield',
			'heading'     => esc_html__( 'Image size', 'learts-addons' ),
			'param_name'  => 'img_size',
			'value'       => 'shop_thumbnail',
			'description' => esc_html__( 'Enter image size . Example: "thumbnail", "medium", "large", "full" or other sizes defined by current theme . Alternatively enter image size in pixels: 200x100( Width x Height). Leave empty to use "shop_thumbnail" size . ', 'learts-addons' ),
		),
		Learts_VC::get_param( 'el_class' ),
		// Data settings.
		Learts_VC::get_param( 'order_product', esc_html__( 'Data Settings', 'learts-addons' ), array(
			'element'            => 'data_source',
			'value_not_equal_to' => array(
				'recent_products',
				'best_selling_products',
				'top_rated_products',
			),
		) ),
		Learts_VC::get_param( 'order_way', esc_html__( 'Data Settings', 'learts-addons' ), array(
			'element'            => 'data_source',
			'value_not_equal_to' => array(
				'recent_products',
				'best_selling_products',
				'top_rated_products',
			),
		) ),
		Learts_VC::get_param( 'css' ),
		Learts_VC::get_animation_field(),
	),
) );


//Filters For autocomplete param:
//For suggestion: vc_autocomplete_[shortcode_name]_[param_name]_callback
add_filter( 'vc_autocomplete_learts_product_widget_product_ids_callback', array(
	'Learts_VC',
	'product_id_callback',
), 10, 1 );

add_filter( 'vc_autocomplete_learts_product_widget_product_ids_render', array(
	'Learts_VC',
	'product_id_render',
), 10, 1 );

add_filter( 'vc_autocomplete_learts_product_widget_exclude_callback', array(
	'Learts_VC',
	'product_id_callback',
), 10, 1 );

add_filter( 'vc_autocomplete_learts_product_widget_exclude_render', array(
	'Learts_VC',
	'product_id_render',
), 10, 0 );

//For param: "filter" param value
//vc_form_fields_render_field_{shortcode_name}_{param_name}_param
add_filter( 'vc_form_fields_render_field_learts_product_widget_filter_param', array(
	'Learts_VC',
	'product_attribute_filter_param_value',
), 10, 4 ); // Defines default value for param if not provided. Takes from other param value.

