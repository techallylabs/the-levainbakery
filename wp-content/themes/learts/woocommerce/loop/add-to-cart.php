<?php
/**
 * Loop Add to Cart
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/loop/add-to-cart.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see           https://docs.woocommerce.com/document/template-structure/
 * @author        WooThemes
 * @package       WooCommerce/Templates
 * @version       3.3.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

global $product;
?>
<div class="learts-product-buttons">
	<?php echo Learts_Woo::quick_view_button();

	$label        = 'View Product';
	$availability = $product->get_availability();
	$stock_status = $availability['class'];
	if ( $product->is_type( 'variable' ) ) {
		$label = esc_html__('Select options','learts');
	};
	if ( ! $product->is_type( 'variable' ) ) {
		$label = esc_html__('Add to cart','learts');

	};
	if ( $product->is_type( 'external' ) ) {
		$label = esc_html__('View External Product','learts');

	};
	if ( $stock_status === 'out-of-stock' ) {
		$label = esc_html__('View Product','learts');
	}
	?>

	<button class="add-to-cart-btn hint--top hint-bounce"
	        aria-label="<?php echo esc_attr( $label ) ?>">
		<?php
		echo apply_filters( 'woocommerce_loop_add_to_cart_link',
			sprintf( '<a href="%s" data-quantity="%s" class="%s" %s>%s</a>',
				esc_url( $product->add_to_cart_url() ),
				esc_attr( isset( $args['quantity'] ) ? $args['quantity'] : 1 ),
				esc_attr( isset( $args['class'] ) ? $args['class'] : 'button' ),
				isset( $args['attributes'] ) ? wc_implode_html_attributes( $args['attributes'] ) : '',
				esc_html( $product->add_to_cart_text() ) ),
			$product,
			$args );
		?>
	</button>

	<?php echo Learts_Woo::compare_button(); ?>
</div>

