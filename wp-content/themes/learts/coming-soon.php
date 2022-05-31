<?php

/* Template name: Coming Soon */

global $learts_options;

$learts_options['header'] = 'split';

get_header(); ?>
<div class="coming-soon-content container" role="main">
	<div class="inner-page-wrap row">
		<?php while ( have_posts() ) : the_post(); ?>
			<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
				<?php the_content(); ?>
			</article><!-- #post -->
		<?php endwhile; ?>
	</div>

</div><!-- .site-content -->

<?php get_footer(); ?>
