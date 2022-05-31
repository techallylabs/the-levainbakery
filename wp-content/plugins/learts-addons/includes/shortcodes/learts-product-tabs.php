<?php

/**
 * Learts Product Tabs Shortcode
 *
 * @version 1.0
 * @package Learts
 */
class WPBakeryShortCode_Learts_Product_Tabs extends WPBakeryShortCode {

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

		return isset( $atts['img_size'] ) ? Learts_VC::convert_image_size( $atts['img_size'] ) : 'woocommerce_thumbnail';
	}

	public function make_tab( $index, $tab ) {

		$atts = $this->getAtts();

		switch ( $tab ) {
			case 'featured_products':
				$filter = ( isset( $atts['filter_type'] ) && $atts['filter_type'] == 'ajax' ) ? 'featured_products' : '.featured';
				$name   = esc_html__( 'Trendy items', 'learts-addons' );
				break;
			case 'sale_products':
				$filter = ( isset( $atts['filter_type'] ) && $atts['filter_type'] == 'ajax' ) ? 'sale_products' : '.sale';
				$name   = esc_html__( 'Sale items', 'learts-addons' );
				break;
			case 'best_selling_products':
				$filter = 'best_selling_products';
				$name   = esc_html__( 'Best sellers', 'learts-addons' );
				break;
			case 'top_rated_products':
				$filter = 'top_rated_products';
				$name   = esc_html__( 'Top rated', 'learts-addons' );
				break;
			case 'recent_products':
			default:
				$filter = ( isset( $atts['filter_type'] ) && $atts['filter_type'] == 'ajax' ) ? 'recent_products' : '.product';
				$name   = esc_html__( 'New arrivals', 'learts-addons' );
				break;
		}

		return sprintf( '<li><a class="%s" href="#" data-filter="%s" data-page="1">%s</a></li>',
			( isset( $atts['filter_type'] ) && $atts['filter_type'] == 'ajax' && ! $index ) ? 'active' : '',
			$filter,
			$name );
	}

	public function shortcode_css( $css_id ) {

		$atts  = vc_map_get_attributes( $this->getShortcode(), $this->getAtts() );
		$cssID = '#' . $css_id;

		$effect = $atts['effect'] ? $atts['effect'] : '';

		$font_color          = $atts['font_color'] ? $atts['font_color'] : 'transparent';
		$button_bg_color     = $atts['button_bg_color'] ? $atts['button_bg_color'] : 'transparent';
		$button_border_color = $atts['button_border_color'] ? $atts['button_border_color'] : 'transparent';

		$font_color_hover          = $atts['font_color_hover'] ? $atts['font_color_hover'] : 'transparent';
		$button_bg_color_hover     = $atts['button_bg_color_hover'] ? $atts['button_bg_color_hover'] : 'transparent';
		$button_border_color_hover = $atts['button_border_color_hover'] ? $atts['button_border_color_hover'] : 'transparent';

		$css = '';

		if ( $effect == 'color' ) {
			$css .= $cssID . ' .product-filter li a {color:' . $font_color . ';background-color:' . $button_bg_color . ';border-color:' . $button_border_color . ';}';
			$css .= $cssID . ' .product-filter li:hover a {color:' . $font_color_hover . ';background-color:' . $button_bg_color_hover . ';border-color:' . $button_border_color_hover . ';}';
			$css .= $cssID . ' .product-filter li a.active {color:' . $font_color_hover . ' !important;background-color:' . $button_bg_color_hover . ' !important;border-color:' . $button_border_color_hover . ' !important;}';
		}

		global $learts_shortcode_css;
		$learts_shortcode_css .= $css;
	}
}

// Mapping shortcode.
vc_map( array(
	'name'        => esc_html__( 'Product Tabs', 'learts-addons' ),
	'base'        => 'learts_product_tabs',
	'icon'        => 'learts-element-icon-product-tabs',
	'category'    => sprintf( esc_html__( 'by %s', 'learts-addons' ), LEARTS_ADDONS_THEME_NAME ),
	'description' => esc_html__( 'Product grid grouped by tabs', 'learts-addons' ),
	'params'      => array(

		array(
			'heading'     => esc_html__( 'Tabs', 'learts-addons' ),
			'description' => esc_html__( 'Select how to group products in tabs', 'learts-addons' ),
			'param_name'  => 'filter',
			'type'        => 'dropdown',
			'admin_label' => true,
			'value'       => array(
				esc_html__( 'Group by category', 'learts-addons' ) => 'category',
				esc_html__( 'Group by feature', 'learts-addons' )  => 'group',
			),
		),

		array(
			'heading'     => esc_html__( 'Tabs Effect', 'learts-addons' ),
			'description' => esc_html__( 'Select the way tabs load products', 'learts-addons' ),
			'param_name'  => 'filter_type',
			'type'        => 'dropdown',
			'value'       => array(
				esc_html__( 'Filter', 'learts-addons' )    => 'filter',
				esc_html__( 'Ajax Load', 'learts-addons' ) => 'ajax',
			),
		),

		array(
			'heading'    => esc_html__( 'Tabs Alignment', 'learts-addons' ),
			'param_name' => 'align',
			'type'       => 'dropdown',
			'value'      => array(
				esc_html__( 'Left', 'learts-addons' )   => 'left',
				esc_html__( 'Center', 'learts-addons' ) => 'center',
				esc_html__( 'Right', 'learts-addons' )  => 'right',
			),
		),

		array(
			'heading'    => esc_html__( 'Hover Effect', 'learts-addons' ),
			'param_name' => 'effect',
			'type'       => 'dropdown',
			'value'      => array(
				esc_html__( 'None', 'learts-addons' )      => '',
				esc_html__( 'Botanical', 'learts-addons' ) => 'botanical',
				esc_html__( 'Cloudy', 'learts-addons' )    => 'cloudy',
				esc_html__( 'Color', 'learts-addons' )     => 'color',
				esc_html__( 'Line', 'learts-addons' )     => 'line',
			),
			'std'        => '',
		),

		array(
			'type'        => 'checkbox',
			'param_name'  => 'display_star_stock',
			'value'       => array( esc_html__( 'Dispaly star rating and stock status in sale product', 'learts-addons' ) => 'yes' ),
			'std'         => 'no',
		),

		array(
			'group'      => esc_html__( 'Color', 'learts-addons' ),
			'type'       => 'colorpicker',
			'heading'    => esc_html__( 'Background button color', 'learts-addons' ),
			'param_name' => 'button_bg_color',
			'value'      => '#f5f5f5',
			'dependency' => array(
				'element' => 'effect',
				'value'   => 'color',
			),
		),
		array(
			'group'      => esc_html__( 'Color', 'learts-addons' ),
			'type'       => 'colorpicker',
			'heading'    => esc_html__( 'Background button color (on hover)', 'learts-addons' ),
			'param_name' => 'button_bg_color_hover',
			'value'      => '#f8796c',
			'dependency' => array(
				'element' => 'effect',
				'value'   => 'color',
			),
		),
		array(
			'group'      => esc_html__( 'Color', 'learts-addons' ),
			'type'       => 'colorpicker',
			'heading'    => esc_html__( 'Text button color', 'learts-addons' ),
			'param_name' => 'font_color',
			'value'      => '#333333',
			'dependency' => array(
				'element' => 'effect',
				'value'   => 'color',
			),
		),
		array(
			'group'      => esc_html__( 'Color', 'learts-addons' ),
			'type'       => 'colorpicker',
			'heading'    => esc_html__( 'Text button color (on hover)', 'learts-addons' ),
			'param_name' => 'font_color_hover',
			'value'      => '#ffffff',
			'dependency' => array(
				'element' => 'effect',
				'value'   => 'color',
			),
		),
		array(
			'group'      => esc_html__( 'Color', 'learts-addons' ),
			'type'       => 'colorpicker',
			'heading'    => esc_html__( 'Border button color', 'learts-addons' ),
			'param_name' => 'button_border_color',
			'value'      => '#f5f5f5',
			'dependency' => array(
				'element' => 'effect',
				'value'   => 'color',
			),
		),
		array(
			'group'      => esc_html__( 'Color', 'learts-addons' ),
			'type'       => 'colorpicker',
			'heading'    => esc_html__( 'Border button color (on hover)', 'learts-addons' ),
			'param_name' => 'button_border_color_hover',
			'value'      => '#f8796c',
			'dependency' => array(
				'element' => 'effect',
				'value'   => 'color',
			),
		),

		Learts_VC::get_param( 'product_cat_autocomplete',
			'',
			array(
				'element' => 'filter',
				'value'   => array( 'category' ),
			) ),

		array(
			'type'        => 'chosen',
			'heading'     => esc_html__( 'Tab', 'learts-addons' ),
			'description' => esc_html__( 'Select which tabs you want to show', 'learts-addons' ),
			'param_name'  => 'tabs',
			'options'     => array(
				'multiple' => true,
				'values'   => array(
					array(
						'label' => esc_html__( 'Featured Products', 'learts-addons' ),
						'value' => 'featured_products',
					),
					array( 'label' => esc_html__( 'New Products', 'learts-addons' ), 'value' => 'recent_products' ),
					array( 'label' => esc_html__( 'On Sale Products', 'learts-addons' ), 'value' => 'sale_products' ),
					array(
						'label' => esc_html__( 'Best-Selling Products (only Ajax Load)', 'learts-addons' ),
						'value' => 'best_selling_products',
					),
					array(
						'label' => esc_html__( 'Top Rated Products (only Ajax Load)', 'learts-addons' ),
						'value' => 'top_rated_products',
					),
				),
			),
			'dependency'  => array( 'element' => 'filter', 'value' => array( 'group' ) ),
		),

		array(
			'type'        => 'number',
			'param_name'  => 'number',
			'heading'     => esc_html__( 'Number', 'learts-addons' ),
			'description' => esc_html__( 'Total number of products will be display in single tab (-1 is all, limited to 1000)',
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
			'dependency'  => array( 'element' => 'data_source', 'value' => array( 'category' ) ),
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
		Learts_VC::get_param( 'el_class' ),
		// Data settings.
		Learts_VC::get_param( 'order_product', esc_html__( 'Data Settings', 'learts-addons' ) ),
		Learts_VC::get_param( 'order_way', esc_html__( 'Data Settings', 'learts-addons' ) ),
		Learts_VC::get_param( 'css' ),
	),
) );


//Filters For autocomplete param:
//For suggestion: vc_autocomplete_[shortcode_name]_[param_name]_callback
add_filter( 'vc_autocomplete_learts_product_tabs_exclude_callback',
	array(
		'Learts_VC',
		'product_id_callback',
	),
	10,
	1 );

add_filter( 'vc_autocomplete_learts_product_tabs_exclude_render',
	array(
		'Learts_VC',
		'product_id_render',
	),
	10,
	1 );
