<?php
/**
 * The Template for displaying product archives, including the main shop page which is a post type archive
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/archive-product.php.
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
 * @version       3.4.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

get_header( 'shop' );

// Sidebar config
$page_wrap_class = $content_class = '';
$sidebar_config  = learts_get_option( 'shop_sidebar_config' );
$full_width_shop = learts_get_option( 'full_width_shop' );
$column_switcher = learts_get_option( 'column_switcher' );
$breadcrumbs_on  = learts_get_option( 'breadcrumbs' );

if ( $sidebar_config == 'left' ) {
	$page_wrap_class = 'has-sidebar-left row';
	$content_class   = ( $full_width_shop != 'basic' ) ? 'col-xs-12 col-md-8 col-lg-10' : 'col-xs-12 col-md-8 col-lg-9';
} elseif ( $sidebar_config == 'right' ) {
	$page_wrap_class = 'has-sidebar-right row';
	$content_class   = ( $full_width_shop != 'basic' ) ? 'col-xs-12 col-md-8 col-lg-10' : 'col-xs-12 col-md-8 col-lg-9';
} else {
	$page_wrap_class = 'has-no-sidebars row';
	$content_class   = 'col-xs-12';
}

$sidebar = Learts_Helper::get_active_sidebar( true );

if ( ! $sidebar ) {
	$page_wrap_class = 'has-no-sidebars row';
	$content_class   = 'col-xs-12';
}

$class = '';
switch ( $full_width_shop ) {
	case 'full-width':
		$class = 'wide';
		break;
	case 'no-space':
		$class = 'wide shop-full-width-no-space';
		break;
	case 'basic':
		$class = '';
}
?>

<div class="container <?php echo esc_attr( $class ) ?>">
	<div class="inner-page-wrap <?php echo esc_attr( $page_wrap_class ); ?>">
		<div id="main" class="site-content <?php echo esc_attr( $content_class ); ?>" role="main">
			<?php
			/**
			 * woocommerce_before_main_content hook.
			 *
			 * @hooked woocommerce_output_content_wrapper - 10 (outputs opening divs for the content)
			 * @hooked woocommerce_breadcrumb - 20
			 */
			do_action( 'woocommerce_before_main_content' );
			?>

			<?php
			/**
			 * woocommerce_archive_description hook.
			 *
			 * @hooked woocommerce_taxonomy_archive_description - 10
			 * @hooked woocommerce_product_archive_description - 10
			 */
			do_action( 'woocommerce_archive_description' );
			?>

			<?php if ( have_posts() ) :
				/**
				 * Hook: woocommerce_before_shop_loop.
				 *
				 * @hooked wc_print_notices - 10
				 * @hooked woocommerce_result_count - 20
				 * @hooked woocommerce_catalog_ordering - 30
				 */
				do_action( 'woocommerce_before_shop_loop' );

				woocommerce_product_loop_start();

				if ( 'subcategories' !== woocommerce_get_loop_display_mode() ) {

					if ( wc_get_loop_prop( 'total' ) ) {
						while ( have_posts() ) {
							the_post();
							/**
							 * Hook: woocommerce_shop_loop.
							 *
							 * @hooked WC_Structured_Data::generate_product_data() - 10
							 */
							do_action( 'woocommerce_shop_loop' );

							wc_get_template_part( 'content', 'product' );
						}

					}
				}

				woocommerce_product_loop_end();

				/**
				 * Hook: woocommerce_after_shop_loop.
				 *
				 * @hooked woocommerce_pagination - 10
				 */
				do_action( 'woocommerce_after_shop_loop' );
				?>

			<?php else: ?>

				<?php
				/**
				 * woocommerce_no_products_found hook.
				 *
				 * @hooked wc_no_products_found - 10
				 */
				do_action( 'woocommerce_no_products_found' );
				?>

			<?php endif; ?>

			<?php
			/**
			 * woocommerce_after_main_content hook.
			 *
			 * @hooked woocommerce_output_content_wrapper_end - 10 (outputs closing divs for the content)
			 */
			do_action( 'woocommerce_after_main_content' );
			?>
		</div>
		<?php
		/**
		 * woocommerce_sidebar hook.
		 *
		 * @hooked woocommerce_get_sidebar - 10
		 */
		do_action( 'woocommerce_sidebar' );
		?>
	</div>
</div>

<?php get_footer( 'shop' ); ?>
