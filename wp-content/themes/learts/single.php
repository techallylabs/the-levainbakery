<?php
/**
 * The template for displaying all single posts.
 *
 * @link    https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package learts
 */

get_header(); ?>

<?php
// Sidebar config.
$post_sidebar_config   = learts_get_option( 'post_sidebar_config' );
$post_show_social      = learts_get_option( 'post_show_social' );
$post_show_author_info = learts_get_option( 'post_show_author_info' );
$post_show_related     = learts_get_option( 'post_show_related' );

if ( $post_sidebar_config == 'left' ) {
	$page_wrap_class = 'has-sidebar-left row';
	$content_class   = 'col-xs-12 col-md-8 col-lg-9';
} else if ( $post_sidebar_config == 'right' ) {
	$page_wrap_class = 'has-sidebar-right row';
	$content_class   = 'col-xs-12 col-md-8 col-lg-9';
} else {
	$page_wrap_class = 'has-no-sidebars row';
	$content_class   = 'col-xs-12';
}

$sidebar = Learts_Helper::get_active_sidebar();

if ( ! $sidebar ) {
	$page_wrap_class = 'has-no-sidebars row';
	$content_class   = 'col-xs-12';
}
?>
<div class="container">

	<div class="inner-page-wrap <?php echo esc_attr( $page_wrap_class ); ?>">
		<div class="content <?php echo esc_attr( $content_class ); ?>" role="main">
			<?php while ( have_posts() ) : the_post(); ?>

				<div class="entry-content">

					<?php if ( get_post_type() === 'portfolio' ) {
						get_template_part( 'components/portfolio/content', 'single-portfolio' );
						get_template_part( 'components/portfolio/content', 'related-portfolio' );
					} else {
						get_template_part( 'components/post/content', 'single' );

						get_template_part( 'author-bio' ); ?>

						<?php
						if ( $post_show_related ) {
							get_template_part( 'components/post/content', 'related' );
						}
					} ?>
					<?php
					// If comments are open or we have at least one comment, load up the comment template.
					if ( comments_open() || get_comments_number() ) :
						comments_template();
					endif;
					?>

				</div>
				<!-- .entry-content -->
			<?php endwhile; // end of the loop. ?>
		</div>
		<?php get_sidebar(); ?>
	</div>
</div>
<?php get_footer(); ?>
