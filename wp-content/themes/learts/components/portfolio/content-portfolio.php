<?php
$portfolio_style          = learts_get_option( 'portfolio_style' );
$content_output           = learts_get_option( 'archive_content_output' );
$portfolio_columns        = learts_get_option( 'portfolio_columns' );
$portfolio_content_output = learts_get_option( 'portfolio_content_output' );
$excerpt_length_portfolio = learts_get_option( 'excerpt_length_portfolio' );
$portfolio_pagination     = learts_get_option( 'portfolio_pagination' );

$classes = array( 'portfolio-item' );

$classes[] = 'style-' . $portfolio_style;

$classes[] = Learts_Helper::get_grid_item_class( apply_filters( 'learts_portfolio_columns',
	array(
		'xs' => 1,
		'sm' => 2,
		'md' => 3,
		'lg' => 4,
		'xl' => $portfolio_columns,
	) ) );
?>


<article id="portfolio-<?php the_ID(); ?>" <?php post_class( $classes ); ?>>

	<?php if ( ! post_password_required() && ! is_attachment() && has_post_thumbnail() ) { ?>
		<div class="entry-thumbnail">
			<div class="portfolio-media portfolio-thumb">
				<a href="<?php echo esc_url( get_permalink() ); ?>"><?php the_post_thumbnail( 'learts-portfolio-grid' ); ?></a>
			</div>
		</div>
	<?php } ?>
	<div class="entry-body">

		<div class="entry-header">
			<?php the_title( sprintf( '<h2 class="entry-title"><a href="%s" rel="bookmark">',
				esc_url( get_permalink() ) ),
				'</a></h2>' ); ?>
		</div>


		<div class="entry-content">
			<?php if ( $portfolio_content_output == 'content' ) {
				echo Learts_Templates::get_the_content_with_formatting();
			} else {
				echo Learts_Templates::excerpt( $excerpt_length_portfolio );
			} ?>
		</div>

		<div class="entry-aux">
			<?php
			wp_link_pages( array(
				'before'      => '<div class="page-links"><span class="page-links-title">' . esc_html__( 'Pages:',
						'learts' ) . '</span>',
				'after'       => '</div>',
				'link_before' => '<span>',
				'link_after'  => '</span>',
				'pagelink'    => '<span class="screen-reader-text">' . esc_html__( 'Page', 'learts' ) . ' </span>%',
			) );
			?>

			<?php if ( $portfolio_content_output == 'excerpt' ) { ?>
				<a class="readmore-button"
				   href="<?php the_permalink( get_the_ID() ) ?>"><?php esc_html_e( 'Read more', 'learts' ); ?></a>
			<?php } ?>
		</div>

</article>