<?php

/**
 * Learts Product Carousel Shortcode
 *
 * @version 1.0
 * @package Learts
 */
class WPBakeryShortCode_Learts_Product_Carousel extends WPBakeryShortCode {

	public function __construct( $settings ) {
		parent::__construct( $settings );
		add_filter( 'learts_shop_products_columns', array( $this, 'product_columns' ) );
		add_filter( 'learts_product_loop_thumbnail_size', array( $this, 'image_size' ) );
	}

	public function product_columns() {
		$atts = $this->getAtts();

		return array(
			'xs' => 1,
			'sm' => 2,
			'md' => 3,
			'lg' => 4,
			'xl' => $atts['columns'],
		);
	}

	public function image_size() {

		$atts = $this->getAtts();

		if ( empty( $atts['img_size'] ) ) {
			$atts['img_size'] = 'woocommerce_thumbnail';
		}

		return isset( $atts['img_size'] ) ? Learts_VC::convert_image_size( $atts['img_size'] ) : 'shop_catalog';
	}

	public function get_query( $atts ) {

		return Learts_Woo::get_products_by_datasource( $atts['data_source'], $atts );

	}
}

// Mapping shortcode.
vc_map( array(
	'name'        => esc_html__( 'Product Carousel', 'learts-addons' ),
	'base'        => 'learts_product_carousel',
	'icon'        => 'learts-element-icon-product-carousel',
	'category'    => sprintf( esc_html__( 'by %s', 'learts-addons' ), LEARTS_ADDONS_THEME_NAME ),
	'description' => esc_html__( 'Add products in a carousel', 'learts-addons' ),
	'params'      => array(
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
				esc_html__( 'Related Products', 'learts-addons' )      => 'related_products',
				esc_html__( 'Top Rated Products', 'learts-addons' )    => 'top_rated_products',
				esc_html__( 'Product Attribute', 'learts-addons' )     => 'product_attribute',
				esc_html__( 'List of Products', 'learts-addons' )      => 'products',
				esc_html__( 'Categories', 'learts-addons' )            => 'categories',
			),
			'description' => esc_html__( 'Select data source for your product carousel', 'learts-addons' ),
		),
		Learts_VC::get_param( 'product_cat_autocomplete',
			'',
			array(
				'element' => 'data_source',
				'value'   => array( 'categories' ),
			) ),
		Learts_VC::get_param( 'product_autocomplete',
			'',
			array(
				'element' => 'data_source',
				'value'   => array( 'products' ),
			) ),
		Learts_VC::get_param( 'product_attribute',
			'',
			array(
				'element' => 'data_source',
				'value'   => array( 'product_attribute' ),
			) ),
		Learts_VC::get_param( 'product_term',
			'',
			array(
				'element' => 'data_source',
				'value'   => array( 'product_attribute' ),
			) ),
		array(
			'type'        => 'number',
			'param_name'  => 'number',
			'heading'     => esc_html__( 'Number', 'learts-addons' ),
			'description' => esc_html__( 'Number of products in the carousel (-1 is all, limited to 1000)',
				'learts-addons' ),
			'value'       => 12,
			'max'         => 1000,
			'min'         => - 1,
		),
		Learts_VC::get_param( 'columns' ),
		array(
			'type'       => 'autocomplete',
			'heading'    => esc_html__( 'Exclude products', 'learts-addons' ),
			'param_name' => 'exclude',
			'settings'   => array(
				'multiple' => true,
				'sortable' => true,
			),
		),
		array(
			'type'        => 'checkbox',
			'param_name'  => 'include_children',
			'description' => esc_html__( 'Whether or not to include children categories', 'learts-addons' ),
			'value'       => array( esc_html__( 'Include children', 'learts-addons' ) => 'yes' ),
			'std'         => 'yes',
			'dependency'  => array( 'element' => 'data_source', 'value' => array( 'categories' ) ),
		),
		array(
			'type'        => 'textfield',
			'heading'     => esc_html__( 'Image size', 'learts-addons' ),
			'param_name'  => 'img_size',
			'value'       => 'woocommerce_thumbnail',
			'description' => esc_html__( 'Enter image size . Example: "thumbnail", "medium", "large", "full" or other sizes defined by current theme . Alternatively enter image size in pixels: 200x100( Width x Height). Leave empty to use "woocommerce_thumbnail" size . ',
				'learts-addons' ),
		),
		array(
			'type'        => 'checkbox',
			'param_name'  => 'display_star_stock',
			'value'       => array( esc_html__( 'Dispaly star rating and stock status in sale product', 'learts-addons' ) => 'yes' ),
			'std'         => 'no',
		),
		array(
			'type'       => 'checkbox',
			'param_name' => 'loop',
			'value'      => array( esc_html__( 'Enable loop mode', 'learts-addons' ) => 'yes' ),
			'std'        => 'yes',
		),
		array(
			'type'       => 'checkbox',
			'param_name' => 'auto_play',
			'value'      => array( esc_html__( 'Enable carousel autoplay', 'learts-addons' ) => 'yes' ),
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
		),
		Learts_VC::get_param( 'el_class' ),
		// Data settings.
		Learts_VC::get_param( 'order_product',
			esc_html__( 'Data Settings', 'learts-addons' ),
			array(
				'element'            => 'data_source',
				'value_not_equal_to' => array(
					'recent_products',
					'best_selling_products',
					'top_rated_products',
				),
			) ),
		Learts_VC::get_param( 'order_way',
			esc_html__( 'Data Settings', 'learts-addons' ),
			array(
				'element'            => 'data_source',
				'value_not_equal_to' => array(
					'recent_products',
					'best_selling_products',
					'top_rated_products',
				),
			) ),
		Learts_VC::get_param( 'css' ),
	),
) );


//Filters For autocomplete param:
//For suggestion: vc_autocomplete_[shortcode_name]_[param_name]_callback
add_filter( 'vc_autocomplete_learts_product_carousel_product_ids_callback',
	array(
		'Learts_VC',
		'product_id_callback',
	),
	10,
	1 );

add_filter( 'vc_autocomplete_learts_product_carousel_product_ids_render',
	array(
		'Learts_VC',
		'product_id_render',
	),
	10,
	1 );

add_filter( 'vc_autocomplete_learts_product_carousel_exclude_callback',
	array(
		'Learts_VC',
		'product_id_callback',
	),
	10,
	1 );

add_filter( 'vc_autocomplete_learts_product_carousel_exclude_render',
	array(
		'Learts_VC',
		'product_id_render',
	),
	10,
	1 );

//For param: "filter" param value
//vc_form_fields_render_field_{shortcode_name}_{param_name}_param
add_filter( 'vc_form_fields_render_field_learts_product_carousel_filter_param',
	array(
		'Learts_VC',
		'product_attribute_filter_param_value',
	),
	10,
	4 ); // Defines default value for param if not provided. Takes from other param value.
