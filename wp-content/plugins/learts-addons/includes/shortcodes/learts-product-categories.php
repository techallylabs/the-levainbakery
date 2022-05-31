<?php

/**
 * learts Posts Product Categories Shortcode
 *
 * @version 1.0
 * @package learts
 */
class WPBakeryShortCode_Learts_Product_Categories extends WPBakeryShortCode {

	public function __construct( $settings ) {
		parent::__construct( $settings );

		add_filter( 'learts_shop_categories_columns', array(
			$this,
			'product_cat_columns',
		) );

		remove_action( 'woocommerce_before_subcategory_title', 'woocommerce_subcategory_thumbnail' );
		add_action( 'woocommerce_before_subcategory_title', array(
			$this,
			'subcategory_thumbnail',
		) );
	}

	public function subcategory_thumbnail( $category ) {

		$atts = $this->getAtts();

		$image_size  = 'full';
		$meta_option = 'thumbnail_id';

		if ( isset( $atts['item_style'] ) && $atts['item_style'] == 'black-overlay' ) {
			$image_size  = apply_filters( 'learts_product_cat_thumbnail_black_overlay_image_size', array(
				640,
				460
			) );
		}

		if ( isset( $atts['layout'] ) && $atts['layout'] == 'masonry' ) {
			$meta_option = 'learts_product_cat_thumbnail_masonry_id';
		}

		$thumbnail_id = get_term_meta( $category->term_id, $meta_option, true );

		if ( $thumbnail_id ) {
			$image = wp_get_attachment_image_src( $thumbnail_id, $image_size );
			$image = $image[0];
		} else {
			$image = wc_placeholder_img_src();
		}

		if ( $image ) {
			// Prevent esc_url from breaking spaces in urls for image embeds
			// Ref: https://core.trac.wordpress.org/ticket/23605
			$image = str_replace( ' ', '%20', $image );

			echo '<img src="' . esc_url( $image ) . '" alt="' . esc_attr( $category->name ) . '" />';
		}
	}

	public function product_cat_columns() {

		global $woocommerce_loop;

		$atts = $this->getAtts();
		$col  = isset( $atts['columns'] ) ? $atts['columns'] : 1;

		if ( isset( $atts['layout'] ) && $atts['layout'] == 'masonry' ) {

			$loop = intval( $woocommerce_loop['loop'] ) + 1;
			$index = $loop % 4;

			switch ( $index ) {
				case 1:
					$col = 2;
					break;
				case 2:
					$col = 2;
					break;
				case 3:
					$col = 4;
					break;
				default:
					$col = 4;
					break;
			}

			$atts['columns'] = 4;
		}

		return array(
			'xl' => $col,
			'lg' => ( $col == 1 ) ? 1 : 3,
			'md' => ( $col == 1 ) ? 1 : 2,
			'sm' => 1,
		);
	}
}

vc_map( array(
	'name'        => esc_html__( 'Product Categories', 'learts-addons' ),
	'base'        => 'learts_product_categories',
	'icon'        => 'learts-element-icon-products-categories',
	'class'       => '',
	'category'    => sprintf( esc_html__( 'by %s', 'learts-addons' ), LEARTS_ADDONS_THEME_NAME ),
	'description' => esc_html__( 'Product categories grid', 'learts-addons' ),
	'params'      => array(
		Learts_VC::get_param( 'product_cat_autocomplete' ),
		array(
			'type'       => 'checkbox',
			'param_name' => 'hide_count',
			'value'      => array( esc_html__( 'Hide product count', 'learts-addons' ) => 'yes' ),
		),
		array(
			'group'      => esc_html__( 'Data Settings', 'learts-addons' ),
			'type'       => 'dropdown',
			'heading'    => esc_html__( 'Order by', 'learts-addons' ),
			'param_name' => 'orderby',
			'value'      => array(
				esc_html__( 'As slugs provided order', 'learts-addons' ) => 'include',
				esc_html__( 'Name', 'learts-addons' )                    => 'name',
				esc_html__( 'Slug', 'learts-addons' )                    => 'slug',
				esc_html__( 'ID', 'learts-addons' )                      => 'term_id',
			),
		),
		Learts_VC::get_param( 'order_way', esc_html__( 'Data Settings', 'learts-addons' ) ),
		array(
			'type'        => 'dropdown',
			'heading'     => esc_html__( 'Layout', 'learts-addons' ),
			'value'       => 4,
			'param_name'  => 'layout',
			'admin_label' => true,
			'description' => esc_html__( 'Try out our creative styles for categories block', 'learts-addons' ),
			'value'       => array(
				esc_html__( 'Default (Grid)', 'learts-addons' ) => 'default',
				esc_html__( 'Carousel', 'learts-addons' )       => 'carousel',
			),
		),
		array(
			'type'        => 'dropdown',
			'heading'     => esc_html__( 'Item style', 'learts-addons' ),
			'value'       => 4,
			'param_name'  => 'item_style',
			'admin_label' => true,
			'description' => esc_html__( 'Select style for items in categories block', 'learts-addons' ),
			'value'       => array(
				esc_html__( 'Style 1 - Item with bottom content', 'learts-addons' ) => 'bottom-content',
				esc_html__( 'Style 2 - Black overlay on mouse over', 'learts-addons' )  => 'black-overlay',
			),
			'dependency'  => array(
				'element'            => 'layout',
				'value_not_equal_to' => array( 'masonry' ),
			),
		),
		array(
			'type'       => 'dropdown',
			'heading'    => esc_html__( 'Space between items', 'learts-addons' ),
			'param_name' => 'spacing',
			'value'      => array(
				50,
				45,
				40,
				35,
				25,
				20,
				15,
				10,
				5,
				0,
			),
		),
		array(
			'type'        => 'dropdown',
			'heading'     => esc_html__( 'Number of items to show', 'learts-addons' ),
			'param_name'  => 'number_of_items_to_show',
			'value'       => array(
				1,
				2,
				3,
				4,
				5,
				6,
				7,
				8,
			),
			'std'         => 3,
			'dependency'  => array(
				'element' => 'layout',
				'value'   => array( 'carousel' ),
			),
			'description' => esc_html__( 'Set numbers of slides you want to display at the same time on the container for carousel mode.', 'learts-addons' ),
		),
		array(
			'type'        => 'dropdown',
			'heading'     => esc_html__( 'Columns', 'learts-addons' ),
			'value'       => 4,
			'param_name'  => 'columns',
			'save_always' => true,
			'description' => esc_html__( 'How much columns grid', 'learts-addons' ),
			'value'       => array(
				'1' => 1,
				'2' => 2,
				'3' => 3,
				'4' => 4,
				'5' => 5,
				'6' => 6,
			),
			'std'         => 3,
			'dependency'  => array(
				'element'            => 'layout',
				'value_not_equal_to' => array( 'carousel', 'masonry' ),
			),
		),
		array(
			'type'       => 'checkbox',
			'param_name' => 'loop',
			'value'      => array( esc_html__( 'Enable carousel loop mode', 'learts-addons' ) => 'yes' ),
			'std'        => 'yes',
			'dependency' => array(
				'element' => 'layout',
				'value'   => array( 'carousel' ),
			),
		),
		array(
			'type'       => 'checkbox',
			'param_name' => 'auto_play',
			'value'      => array( esc_html__( 'Enable carousel autolay', 'learts-addons' ) => 'yes' ),
			'dependency' => array(
				'element' => 'layout',
				'value'   => array( 'carousel' ),
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
				'element' => 'layout',
				'value'   => array( 'carousel' ),
			),
		),

		array(
			'type'        => 'checkbox',
			'param_name'  => 'center_mode',
			'description' => esc_html__( 'Enable centered view with partial prev/next slides. Use with odd numbered Slides Per Views counts.', 'learts-addons' ),
			'value'       => array( esc_html__( 'Center Mode', 'learts-addons' ) => 'yes' ),
			'dependency'  => array(
				'element' => 'layout',
				'value'   => array( 'carousel' ),
			),
		),
		array(
			'type'        => 'textfield',
			'heading'     => esc_html__( 'Center Padding', 'learts-addons' ),
			'param_name'  => 'center_padding',
			'description' => esc_html__( 'Side padding when in center mode (px or %)', 'learts-addons' ),
			'value'       => '100px',
			'dependency'  => array(
				'element' => 'center_mode',
				'value'   => array( 'yes' ),
			),
		),
		array(
			'type'       => 'checkbox',
			'param_name' => 'hide_empty',
			'std'        => 'yes',
			'value'      => array( esc_html__( 'Hide empty categories', 'learts-addons' ) => 'yes' ),
		),
		Learts_VC::get_param( 'animation' ),
		Learts_VC::get_param( 'el_class' ),
		Learts_VC::get_param( 'css' ),
	),
) );
