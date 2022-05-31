<?php

$orig_post = $post;
global $post;

$categories = get_the_category( $post->ID );

$number_of_related = 3;  // Number of related posts that will be shown.

$classes   = array( 'post-related' );
$classes[] = 'col-xs-12 col-sm-4';

$args = array(
	'post__not_in'        => array( $post->ID ),
	'posts_per_page'      => $number_of_related,
	'post_type'           => 'portfolio',
	'ignore_sticky_posts' => 1,
	'orderby'             => 'rand',
);

$query = new WP_Query( $args );
if ( $query->have_posts() ) : ?>
	<h2 class="title-related-portfolio"><?php esc_html_e( 'Relate Portfolio', 'learts' ) ?></h2>
	<div class="related-portfolio">
		<div class="row">
			<?php while ( $query->have_posts() ) : $query->the_post(); ?>
				<article id="post-<?php the_ID(); ?>" <?php post_class( $classes ); ?>>

					<?php if ( ! post_password_required() && ! is_attachment() && has_post_thumbnail() ) { ?>
						<div class="entry-thumbnail">
							<div class="portfolio-media portfolio-thumb">
								<a href="<?php echo esc_url( get_permalink() ); ?>"><?php the_post_thumbnail( 'learts-related-thumb' ); ?></a>
							</div>
						</div>
					<?php } ?>

					<div class="entry-body">
						<div class="entry-header">
							<?php the_title( sprintf( '<h2 class="entry-title"><a href="%s" rel="bookmark">',
								esc_url( get_permalink() ) ),
								'</a></h2>' ); ?>
							<?php if(function_exists('the_views')) { the_views(); } ?>
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
