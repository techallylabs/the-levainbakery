<?php

$orig_post = $post;
global $post;

$categories = get_the_category( $post->ID );

$number_of_related = 2;  // Number of related posts that will be shown.

$classes   = array( 'post-related' );
$classes[] = 'col-xs-12 col-sm-6';

if ( $categories ) {

	$category_ids = array();

	foreach ( $categories as $individual_category ) {
		$category_ids[] = $individual_category->term_id;
	}
}

$args = array(
	'category__in'        => $category_ids,
	'post__not_in'        => array( $post->ID ),
	'posts_per_page'      => $number_of_related,
	'ignore_sticky_posts' => 1,
	'orderby'             => 'rand',
);

$query = new WP_Query( $args );
if ( $query->have_posts() ) : ?>

	<div class="related-posts">
		<div class="row">
			<?php while ( $query->have_posts() ) : $query->the_post(); ?>
				<article id="post-<?php the_ID(); ?>" <?php post_class( $classes ); ?>>

					<?php if ( ! post_password_required() && ! is_attachment() && has_post_thumbnail() ) { ?>
						<div class="entry-thumbnail">
							<div class="post-media post-thumb">
								<a href="<?php echo esc_url( get_permalink() ); ?>"><?php the_post_thumbnail( 'learts-related-thumb' ); ?></a>
							</div>
						</div>
					<?php } ?>

					<div class="entry-body">
						<div class="entry-header">
							<?php if ( learts_get_option( 'post_meta' ) ) {
								echo Learts_Templates::post_meta();
							} ?>

							<?php the_title( sprintf( '<h2 class="entry-title"><a href="%s" rel="bookmark">',
								esc_url( get_permalink() ) ),
								'</a></h2>' ); ?>
						</div>
					</div>

				</article>
			<?php endwhile; ?>
		</div>
	</div>

	<?php
endif;

$post = $orig_post;
wp_reset_postdata();
