<?php
/**
 * Single Product Meta
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/meta.php.
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
 * @version       3.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}


$product_show_share  = learts_get_option( 'product_show_share' );
$product_share_links = learts_get_option( 'product_share_links' );


if ( ! is_array( $product_share_links ) ) {
	return;
}

$no_share_links = true;
foreach ( $product_share_links as $link ) {
	if ( $link ) {
		$no_share_links = false;
	}
}

global $product;
?>
<table class="product_meta">

	<?php do_action( 'woocommerce_product_meta_start' ); ?>

	<?php if ( wc_product_sku_enabled() && ( $product->get_sku() || $product->is_type( 'variable' ) ) ) : ?>

		<tr class="sku_wrapper">
			<td class="label"><?php esc_html_e( 'SKU', 'learts' ); ?></td>
			<td class="sku value"
			    itemprop="sku"><?php echo ( esc_attr($sku = $product->get_sku()) ) ? $sku : esc_html__( 'N/A', 'learts' ); ?></td>
		</tr>

	<?php endif; ?>

	<?php echo wc_get_product_category_list( $product->get_id(), ', ', '<tr class="posted_in"><td class="label">' . _n( 'Category', 'Categories', count( $product->get_category_ids() ), 'learts' ) . '</td><td class="value"> ', '</td></tr>' ); ?>

	<?php echo wc_get_product_tag_list( $product->get_id(), ', ', '<tr class="tagged_as">' . '<td class="label">' . _n( 'Tag', 'Tags', count( $product->get_tag_ids() ), 'learts' ) . '</td><td class="value"> ', '</td></tr>' ); ?>

	<?php do_action( 'woocommerce_product_meta_end' ); ?>

</table>
