<?php
/**
 * Add to wishlist template
 *
 * @author  Your Inspiration Themes
 * @package YITH WooCommerce Wishlist
 * @version 2.0.0
 */

if ( ! defined( 'YITH_WCWL' ) ) {
	exit;
} // Exit if accessed directly

global $product;
?>

<div
	class="yith-wcwl-add-to-wishlist add-to-wishlist-<?php echo esc_attr( $product_id ) ?>">
	<?php if ( ! ( $disable_wishlist && ! is_user_logged_in() ) ): ?>
		<div
			class="hint--bounce yith-wcwl-add-button <?php echo is_product() ? 'hint--top' : 'hint--left'; ?> <?php echo esc_attr( $exists && ! $available_multi_wishlist ) ? 'hide' : 'show' ?><?php echo( learts_get_option( 'animated_wishlist_on' ) ? ' animated-wishlist' : '' ); ?>"
			aria-label="<?php esc_attr_e( 'Add to wishlist', 'learts' ) ?>"
			style="display:<?php echo esc_attr( $exists && ! $available_multi_wishlist ) ? 'none' : 'block' ?>"><?php yith_wcwl_get_template( 'add-to-wishlist-' . $template_part . '.php',
				$atts ); ?>
		</div>

		<div class="hint--bounce yith-wcwl-wishlistaddedbrowse hide <?php echo is_product() ? 'hint--top' : 'hint--left'; ?>"
		     aria-label="<?php echo esc_attr( $browse_wishlist_text ) ?>"
		     style="display:none;">
			<span class="feedback"><?php echo '' . $product_added_text ?></span>
			<a href="<?php echo esc_url( $wishlist_url ) ?>" rel="nofollow">
				<?php echo apply_filters( 'yith-wcwl-browse-wishlist-label', $browse_wishlist_text ) ?>
			</a>
		</div>

		<div
			class="hint--bounce yith-wcwl-wishlistexistsbrowse <?php echo is_product() ? 'hint--top' : 'hint--left'; ?> <?php echo esc_attr( $exists && ! $available_multi_wishlist ) ? 'show' : 'hide' ?>"
			aria-label="<?php echo esc_attr( $browse_wishlist_text ) ?>"
			style="display:<?php echo esc_attr( $exists && ! $available_multi_wishlist ) ? 'block' : 'none' ?>">
			<span class="feedback"><?php echo '' . $already_in_wishslist_text ?></span>
			<a href="<?php echo esc_url( $wishlist_url ) ?>" rel="nofollow">
				<?php echo apply_filters( 'yith-wcwl-browse-wishlist-label', $browse_wishlist_text ) ?>
			</a>
		</div>

		<div class="yith-wcwl-wishlistaddresponse"></div>
	<?php else: ?>
		<a href="<?php echo esc_url( add_query_arg( array(
			'wishlist_notice' => 'true',
			'add_to_wishlist' => $product_id,
		),
			get_permalink( wc_get_page_id( 'myaccount' ) ) ) ) ?>" rel="nofollow"
		   class="hint--left hint--bounce <?php echo str_replace( 'add_to_wishlist', '', $link_classes ) ?>"
		   aria-label="<?php echo esc_attr( $label ); ?>"
		><?php echo '' . $icon ?><?php echo '' . $label ?>
		</a>
	<?php endif; ?>

</div>
