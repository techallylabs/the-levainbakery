<?php
/**
 * The template for displaying product content within loops
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/content-product.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @author  WooThemes
 * @package WooCommerce/Templates
 * @version 3.6.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

global $product;

// Ensure visibility.
if ( empty( $product ) || ! $product->is_visible() ) {
	return;
}

$classes = array(
	'product',
	'product-loop',
);

// Add 'new' class
$newdays = apply_filters( 'learts_shop_new_days', learts_get_option( 'shop_new_days' ) );

if ( class_exists( 'Redux' ) ) {
	$is_new  = time() - get_the_time( 'U', $post->ID ) < DAY_IN_SECONDS * $newdays;
} else {
	$is_new  = 10;
}

if ( $is_new ) {
	$classes[] = 'new';
}

$classes[] = Learts_Helper::get_grid_item_class( apply_filters( 'learts_shop_products_columns',
	array(
		'xs' => 1,
		'sm' => 2,
		'md' => 3,
		'lg' => 4,
		'xl' => intval( get_option( 'woocommerce_catalog_columns', 5 ) ),
	) ) );

$classes[] = apply_filters( 'learts_product_style', 'product-style--' . learts_get_option( 'product_style' ) );

$other_classes = apply_filters( 'learts_shop_products_classes', '' );
$classes[]     = $other_classes;

?>
<div <?php post_class( $classes ); ?>>
	<?php
	/**
	 * woocommerce_before_shop_loop_item hook.
	 *
	 * @hooked woocommerce_show_product_loop_sale_flash - 10
	 */
	do_action( 'woocommerce_before_shop_loop_item' );
	?>
	<div class="product-thumb">
		<?php

		/**
		 * woocommerce_before_shop_loop_item_title hook.
		 *
		 * @hooked woocommerce_template_loop_product_link_open - 5
		 * @hooked woocommerce_template_loop_product_thumbnail - 10
		 * @hooked woocommerce_template_loop_product_link_close - 15
		 */
		do_action( 'woocommerce_before_shop_loop_item_title' );

		?>
	</div>

	<div class="product-info">

		<div class="title-wrap">
			<?php

			/**
			 * woocommerce_shop_loop_item_title hook.
			 *
			 * @hooked woocommerce_template_loop_product_title - 10
			 */
			do_action( 'woocommerce_shop_loop_item_title' );
			?>
		</div>

		<?php
		/**
		 * woocommerce_after_shop_loop_item_title hook.
		 *
		 * @hooked woocommerce_template_loop_rating - 5
		 * @hooked woocommerce_template_loop_price - 10
		 */
		do_action( 'woocommerce_after_shop_loop_item_title' );
		?>

		<?php if ( learts_get_option( 'product_style' ) != 'button-hover' ) {
			woocommerce_template_loop_add_to_cart();
		} ?>

		<?php
		/**
		 * woocommerce_after_shop_loop_item hook.
		 *
		 * @hooked woocommerce_template_loop_rating - 5
		 * @hooked woocommerce_template_loop_product_link_close - 5
		 * @hooked woocommerce_template_loop_add_to_cart - 10
		 */
		do_action( 'woocommerce_after_shop_loop_item' );
		if ( class_exists( 'Insight_Swatches' ) ) {
			echo do_shortcode( '[insight_swatches]' );
		}

		$stock_quantity = $product->get_stock_quantity();
		?>
		<div class="wrap-rating">
			<?php echo wc_get_rating_html( $product->get_average_rating() ); ?>
		</div>
		<?php if ( $stock_quantity && $stock_quantity !== 0 ) {
			$sold        = get_post_meta( $post->ID, 'total_sales', true );
			if ($sold === '' ) {
				$sold = 0;
			}
			$stock_first = (int) ( $stock_quantity + $sold );
			$available   = (float) ( 100 / $stock_first ) * ( $stock_quantity );
			?>

			<div class="status-bar">
				<div class="sold-bar" style="width: <?php echo esc_attr( $available ) ?>%"></div>
			</div>
			<div class="product-stock-status">
				<div class="sold"><?php esc_html_e( 'Sold: ', 'learts' ); ?>
					<span><?php echo esc_attr( $sold ); ?><span>
				</div>
				<div class="available"><?php esc_html_e( 'Available: ', 'learts' ); ?>
					<span><?php echo esc_attr( $stock_quantity ); ?><span>
				</div>
			</div>
		<?php } ?>
	</div>

	<?php if ( learts_get_option( 'product_style' ) == 'button-hover' ) {
		woocommerce_template_loop_add_to_cart();
	} ?>
</div>

