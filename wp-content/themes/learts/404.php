<?php
/**
 * The main template file.
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists.
 *
 * @link    https://codex.wordpress.org/Template_Hierarchy
 *
 * @package learts
 */

global $learts_options;

$learts_options['offcanvas_button_on']        = true;
$learts_options['offcanvas_action']           = 'menu';
$learts_options['sticky_header']              = false;
$learts_options['topbar_on']                  = false;
$learts_options['header']                     = 'split';
$learts_options['search_on']                  = true;
$learts_options['wishlist_on']                = true;
$learts_options['minicart_on']                = true;


get_header(); ?>

	<div id="main" class="site-content" role="main">

		<div class="area-404">
			<div class="container">
				<div class="area-404__content row flex-items-xs-middle">
					<div class="col-xs-12 col-xl-6 col-404">
						<h1 class="area-404__heading"><?php esc_html_e( 'Oops!', 'learts' ); ?></h1>
						<h1 class="area-404__sub-heading"><?php esc_html_e( 'Page not found!', 'learts' ); ?></h1>
						<p class="area-404__content-heading"><?php esc_html_e( "You could either go back or go to homepage", 'learts' ); ?>
						</p>
						<div class="content-404-links">
							<?php echo sprintf( '<a class="content-404-back button button-grey" href="%s">%s</a>', esc_url( site_url( '/' ) ), esc_html__( 'Go back', 'learts' ) ); ?>
							<?php echo sprintf( '<a class="content-404-home button" href="%s">%s</a>', esc_url( home_url( '/' )), esc_html__( 'Homepage', 'learts' ) ); ?>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

<?php
get_footer();
