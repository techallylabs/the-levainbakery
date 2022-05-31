<?php
/**
 * Template part for displaying single portfolio.
 *
 * @package learts
 */

$learts_link_project = get_post_meta( Learts_Helper::get_the_ID(), 'learts_link_project', true );
$header_classes      = array( 'entry-header' );
?>

<article <?php post_class(); ?>>

	<div class="row description-portfolio">
		<div class="col-xl-8 col-sm-12">
			<?php if ( ! post_password_required() && ! is_attachment() && has_post_thumbnail() ) { ?>
				<div class="entry-thumbnail">
					<div class="portfolio-media portfolio-thumb">
						<?php the_post_thumbnail( 'learts-single-thumb' ); ?>
					</div>
				</div>
			<?php } ?>
		</div>
		<div class="col-xl-4 col-sm-12">
			<div class="<?php echo implode( ' ', $header_classes ) ?>">
				<?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>
			</div>
			<div class="entry-description">
				<?php
				the_excerpt();
				wp_link_pages( array(
					'before'      => '<div class="page-links"><span class="page-links-title">' . esc_html__( 'Pages:',
							'learts' ) . '</span>',
					'after'       => '</div>',
					'link_before' => '<span>',
					'link_after'  => '</span>',
					'pagelink'    => '<span class="screen-reader-text">' . esc_html__( 'Page', 'learts' ) . ' </span>%',
				) );
				?>
			</div>
			<hr class="portfolio-single-hr">
			<div class="date"><?php esc_html_e( 'Date: ',
					'learts' ) ?><?php echo sprintf( '<span class="meta-date">%1$s - %2$s',
					get_the_time( ' h:i A' ),
					get_the_date() ); ?></span></div>

			<hr class="portfolio-single-hr">

			<!--Categories Portfolio-->
			<div class="categories"><?php esc_html_e( 'Categories:  ', 'learts' ) ?>
				<?php echo get_the_term_list( Learts_Helper::get_the_ID(), 'portfolio_category', '<span>', ',', '</span>' ); ?>
			</div>

			<hr class="portfolio-single-hr">

			<!--Tags Portfolio-->
			<div class="tags"><?php esc_html_e( 'Tags:  ', 'learts' ) ?>
				<?php echo get_the_term_list( Learts_Helper::get_the_ID(), 'portfolio_tags', '<span>', ',', '</span>' ); ?>
			</div>

			<hr class="portfolio-single-hr">

			<div class="link"><?php esc_html_e( 'Links:  ', 'learts' ) ?>
				<span class="meta-link"><a
						href="<?php echo esc_url( $learts_link_project ); ?>"><?php echo esc_attr( $learts_link_project ); ?></a></span>
			</div>

			<hr class="portfolio-single-hr">

			<div class="share"><?php esc_html_e( 'Share:  ', 'learts' ) ?>
				<ul class="list-inline share-list">
					<li class="list-inline-item">
						<div class="portfolio-share-buttons">
							<a class="hint--top facebook" aria-label="Facebook"
							   href="https://www.facebook.com/sharer/sharer.php?u=<?php the_permalink(); ?>"
							   onclick="window.open(this.href, '', 'menubar=no,toolbar=no,resizable=no,scrollbars=no,height=455,width=600'); return false;">
								<i class="fa fa-facebook"></i>
							</a>
							<a class="hint--top twitter" aria-label="Twitter"
							   href="https://twitter.com/home?status=Check%20out%20this%20article:%20<?php echo rawurlencode( the_title( '',
								   '',
								   false ) ); ?>%20-%20<?php the_permalink(); ?>"
							   onclick="window.open(this.href, '', 'menubar=no,toolbar=no,resizable=no,scrollbars=no,height=455,width=600'); return false;">
								<i class="fa fa-twitter"></i>
							</a>
							<?php $pin_image = wp_get_attachment_url( get_post_thumbnail_id( $post->ID ) ); ?>
							<a class="hint--top pinterest" aria-label="Pinterest" data-pin-do="skipLink"
							   href="https://pinterest.com/pin/create/button/?url=<?php the_permalink(); ?>&amp;media=<?php echo esc_url( $pin_image ); ?>&amp;description=<?php echo rawurlencode( the_title( '',
								   '',
								   false ) ); ?>"
							   onclick="window.open(this.href, '', 'menubar=no,toolbar=no,resizable=no,scrollbars=no,height=455,width=600'); return false;">
								<i class="fa fa-pinterest"></i>
							</a>
							<a class="hint--top google-plus" aria-label="Google Plus"
							   href="https://plus.google.com/share?url=<?php the_permalink(); ?>"
							   onclick="window.open(this.href, '', 'menubar=no,toolbar=no,resizable=no,scrollbars=no,height=455,width=600'); return false;">
								<i class="fa fa-google-plus"></i>
							</a>
							<a class="hint--top email" aria-label="Email"
							   href="mailto:<?php echo get_option( 'admin_email' ); ?>"><i
									class="fa fa-envelope-o"></i></a>
						</div>
					</li>
				</ul>
			</div>
		</div>
	</div>
	<hr class="portfolio-single-hr">
	<div class="row entry-content">
		<?php the_content(); ?>
	</div>
	<hr class="portfolio-single-hr">

</article>
