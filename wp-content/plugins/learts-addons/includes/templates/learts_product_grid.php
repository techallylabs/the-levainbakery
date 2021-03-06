<?php
/**
 * Shortcode attributes
 *
 * @var $atts
 * @var $animation
 * @var $data_source
 * @var $product_cat_ids
 * @var $product_cat_slugs
 * @var $include_children
 * @var $product_ids
 * @var $display_star_stock
 * @var $product_attribute
 * @var $product_term
 * @var $product_style
 * @var $number
 * @var $columns
 * @var $exclude
 * @var $img_size
 * @var $pagination_type
 * @var $orderby
 * @var $order
 * @var $el_class
 * @var $css
 *
 * Shortcode class
 * @var $this WPBakeryShortCode_Learts_Products_Grid
 */
$atts = vc_map_get_attributes( $this->getShortcode(), $atts );
extract( $atts );

$el_class = $this->getExtraClass( $el_class );

$css_class = array(
	'tm-shortcode',
	'learts-product-grid',
	$this->getCSSAnimation( $animation ),
	$el_class,
	vc_shortcode_custom_css_class( $css ),
);

$css_class = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG,
	implode( ' ', $css_class ),
	$this->settings['base'],
	$atts );

if ( $data_source == 'categories' && empty( $product_cat_slugs ) ) {
	return;
}

if ( $animation !== '' ) {
	$css_class .= 'tm-animation ' . $animation;
}

if ( $display_star_stock === 'yes' ) {
	$css_class .= 'has-star-stock-status';
}

$product_cat_slugs = explode( ',', $product_cat_slugs );

$product_cat_ids = array();

foreach ( $product_cat_slugs as $slug ) {

	$term = get_term_by( 'slug', $slug, 'product_cat' );

	if ( ! empty( $term ) ) {
		$product_cat_ids[] = $term->term_id;
	}
}

$categories = get_terms( array(
	'taxonomy' => 'product_cat',
	'orderby'  => 'include',
	'include'  => $product_cat_ids,
) );

if ( $number > 1000 ) {
	$number = 1000;
}

$css_id = Learts_VC::get_learts_shortcode_id( 'learts-product-grid' );

?>
<div class="<?php echo esc_attr( trim( $css_class ) ); ?>" id="<?php echo esc_attr( $css_id ); ?>"
     data-atts="<?php echo esc_attr( json_encode( $atts ) ) ?>">

	<div class="products-grid-content">
		<?php

		$atts['include_children'] = ( $atts['include_children'] == 'yes' );
		$product_loop             = Learts_Woo::get_products_by_datasource( $data_source, $atts );

		woocommerce_product_loop_start();

		while ( $product_loop->have_posts() ) {
			$product_loop->the_post();
			wc_get_template_part( 'content', 'product' );
		}

		wp_reset_postdata();

		woocommerce_product_loop_end();

		?>
	</div>

	<?php
	if ( $pagination_type && $product_loop->post_count >= $number ) {

		$load_more_atts = array(
			'container'      => '#' . $css_id,
			'post_type'      => 'product',
			'paged'          => 1,
			'posts_per_page' => $number,
			'columns'        => $columns,
		)
		?>
		<div class="learts-loadmore-wrap"
		     data-atts="<?php echo esc_attr( json_encode( $load_more_atts ) ); ?>">
			<span
				class="learts-loadmore-btn load-on-<?php echo esc_attr( $pagination_type == 'more-btn' ) ? 'click' : 'scroll'; ?>"><?php esc_html_e( 'Load More ...',
					'learts-addons' ); ?></span>
		</div>
	<?php } ?>

</div>
