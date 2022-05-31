<?php

/**
 * Learts Product Grid Shortcode
 *
 * @version 1.0
 * @package Learts
 */
class WPBakeryShortCode_Learts_Product_Grid extends WPBakeryShortCode {

	public function __construct( $settings ) {
		parent::__construct( $settings );
		add_filter( 'learts_shop_products_columns', array( $this, 'product_columns' ) );
		add_filter( 'learts_product_loop_thumbnail_size', array( $this, 'image_size' ) );
	}

	public function product_columns() {

		$atts = $this->getAtts();

		if ( $atts['columns'] == 1 ) {
			return array(
				'xs' => 1,
				'sm' => 1,
				'md' => 1,
				'lg' => 1,
				'xl' => $atts['columns'],
			);
		} else {
			return array(
				'xs' => 1,
				'sm' => 2,
				'md' => 3,
				'lg' => 4,
				'xl' => $atts['columns'],
			);
		}

	}

	public function image_size() {

		$atts = $this->getAtts();

		if ( empty( $atts['img_size'] ) ) {
			$atts['img_size'] = 'woocommerce_thumbnail';
		}

		return isset( $atts['img_size'] ) ? Learts_VC::convert_image_size( $atts['img_size'] ) : 'woocommerce_thumbnail';
	}
}

// Mapping shortcode.
vc_map( array(
	'name'        => esc_html__( 'Product Grid', 'learts-addons' ),
	'base'        => 'learts_product_grid',
	'icon'        => 'learts-element-icon-product-grid',
	'category'    => sprintf( esc_html__( 'by %s', 'learts-addons' ), LEARTS_ADDONS_THEME_NAME ),
	'description' => esc_html__( 'Add products in a grid', 'learts-addons' ),
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
			'description' => esc_html__( 'Select data source for your product grid', 'learts-addons' ),
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
			'description' => esc_html__( 'Number of products in the grid (-1 is all, limited to 1000)', 'learts-addons' ),
			'value'       => 12,
			'max'         => 1000,
			'min'         => - 1,
			'dependency'  => array( 'element' => 'data_source', 'value_not_equal_to' => array( 'products' ) ),
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
			'type'       => 'dropdown',
			'param_name' => 'pagination_type',
			'heading'    => esc_html__( 'Pagination type', 'learts-addons' ),
			'value'      => array(
				esc_html__( 'None', 'learts-addons' )             => '',
				esc_html__( 'Load More Button', 'learts-addons' ) => 'more-btn',
				esc_html__( 'Infinite Scroll', 'learts-addons' )  => 'infinite',
			),
			'dependency' => array(
				'element'            => 'data_source',
				'value_not_equal_to' => array( 'products' ),
			),
		),

		array(
			'type'       => 'dropdown',
			'param_name' => 'product_style',
			'heading'    => esc_html__( 'Select product item style', 'learts-addons' ),
			'value'      => array(
				esc_html__( 'Default', 'learts-addons' )      => 'default',
				esc_html__( 'Button Hover', 'learts-addons' ) => 'button-hover',
			),
		),

		array(
			'type'        => 'checkbox',
			'param_name'  => 'display_star_stock',
			'value'       => array( esc_html__( 'Dispaly star rating and stock status in sale product', 'learts-addons' ) => 'yes' ),
			'std'         => 'no',
		),

		array(
			'group'       => esc_html__( 'Animation', 'learts-addons' ),
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
add_filter( 'vc_autocomplete_learts_product_grid_product_ids_callback',
	array(
		'Learts_VC',
		'product_id_callback',
	),
	10,
	1 );

add_filter( 'vc_autocomplete_learts_product_grid_product_ids_render',
	array(
		'Learts_VC',
		'product_id_render',
	),
	10,
	1 );

add_filter( 'vc_autocomplete_learts_product_grid_exclude_callback',
	array(
		'Learts_VC',
		'product_id_callback',
	),
	10,
	1 );

add_filter( 'vc_autocomplete_learts_product_grid_exclude_render',
	array(
		'Learts_VC',
		'product_id_render',
	),
	10,
	1 );

//For param: "filter" param value
//vc_form_fields_render_field_{shortcode_name}_{param_name}_param
add_filter( 'vc_form_fields_render_field_learts_product_grid_filter_param',
	array(
		'Learts_VC',
		'product_attribute_filter_param_value',
	),
	10,
	4 ); // Defines default value for param if not provided. Takes from other param value.
