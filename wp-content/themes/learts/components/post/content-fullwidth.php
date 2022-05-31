<?php
$content_output   = learts_get_option( 'archive_content_output' );
$post_show_share  = learts_get_option( 'post_show_share' );
$post_share_links = learts_get_option( 'post_share_links' );
$excerpt_length   = learts_get_option( 'excerpt_length' );
$post_show_tags   = learts_get_option( 'post_show_tags' );

$no_share_links = true;

if ( is_array( $post_share_links ) ) {

	foreach ( $post_share_links as $link ) {
		if ( $link ) {
			$no_share_links = false;
		}
	}
}

$classes = array();

if ( isset( $shortcode_post_class ) ) {
	$classes[] = $shortcode_post_class;
}
?>

<article id="post-<?php the_ID(); ?>" <?php post_class( $classes ); ?>>

	<?php if ( ! post_password_required() && ! is_attachment() && has_post_thumbnail() ) { ?>
		<div class="entry-thumbnail">
			<div class="post-media post-thumb">
				<a href="<?php echo esc_url( get_permalink() ); ?>"><?php the_post_thumbnail( 'learts-single-thumb' ); ?></a>
			</div>
		</div>
	<?php } ?>
	<div class="entry-body">

		<?php if ( learts_get_option( 'post_meta_categories' ) ) { ?>
			<div class="entry-meta">
				<?php if ( get_the_category_list( ', ' ) ) { ?>
					<span class="meta-categories"><?php echo get_the_category_list( ' / ' ); ?></span>
				<?php } ?>
			</div>
		<?php } ?>

		<div class="entry-header">
			<?php the_title( sprintf( '<h2 class="entry-title"><a href="%s" rel="bookmark">',
				esc_url( get_permalink() ) ),
				'</a></h2>' ); ?>
		</div>

		<?php if ( learts_get_option( 'post_meta' ) ) {
			echo Learts_Templates::post_meta( array(
				'author' => 1,
				'cats'   => 1,
			) );
		} ?>


		<div class="entry-content">
			<?php if ( $content_output == 'content' ) {
				echo Learts_Templates::get_the_content_with_formatting();
			} else {
				echo Learts_Templates::excerpt( $excerpt_length );
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

			<?php if ( $content_output == 'excerpt' ) { ?>
				<a class="readmore-button"
				   href="<?php the_permalink( get_the_ID() ) ?>"><?php esc_html_e( 'Read more', 'learts' ); ?></a>
			<?php } ?>
		</div>

		<div class="row entry-footer flex-items-xs-middle">
			<?php if ( $post_show_tags ) { ?>
				<div
					class="post-tags col-xs-12 col-sm-6">
					<?php the_tags( '<ul class="tagcloud"><li class="tag-cloud__item">',
						'</li><li class="tag-cloud__item">',
						'</li></ul>' ); ?>
				</div>
			<?php } ?>

			<?php if ( $post_show_share && ! $no_share_links && is_array( $post_share_links ) ) { ?>
				<div
					class="post-share col-xs-12 col-sm-6 text-sm-right">
					<ul class="list-inline share-list">
						<li class="list-inline-item"><h3
								class="share-list__title"><?php echo esc_html__( 'Share this post:', 'learts' ); ?>
								<?php if ( $post_show_share ){ ?>
									<div class="post-share-buttons">
										<a href="https://www.facebook.com/sharer/sharer.php?u=<?php the_permalink(); ?>"
										   onclick="window.open(this.href, '', 'menubar=no,toolbar=no,resizable=no,scrollbars=no,height=455,width=600'); return false;">
											<i class="fa fa-facebook"></i>
										</a>
										<a href="https://twitter.com/home?status=Check%20out%20this%20article:%20<?php echo rawurlencode( the_title( '', '', false ) ); ?>%20-%20<?php the_permalink(); ?>"
										   onclick="window.open(this.href, '', 'menubar=no,toolbar=no,resizable=no,scrollbars=no,height=455,width=600'); return false;">
											<i class="fa fa-twitter"></i>
										</a>
										<?php $pin_image = wp_get_attachment_url( get_post_thumbnail_id( $post->ID ) ); ?>
										<a data-pin-do="skipLink"
										   href="https://pinterest.com/pin/create/button/?url=<?php the_permalink(); ?>&amp;media=<?php echo esc_url( $pin_image ); ?>&amp;description=<?php echo rawurlencode( the_title( '', '', false ) ); ?>"
										   onclick="window.open(this.href, '', 'menubar=no,toolbar=no,resizable=no,scrollbars=no,height=455,width=600'); return false;">
											<i class="fa fa-pinterest"></i>
										</a>
										<a href="https://plus.google.com/share?url=<?php the_permalink(); ?>"
										   onclick="window.open(this.href, '', 'menubar=no,toolbar=no,resizable=no,scrollbars=no,height=455,width=600'); return false;">
											<i class="fa fa-google-plus"></i>
										</a>
										<a href="mailto:<?php echo get_option( 'admin_email' ); ?>"><i class="fa fa-envelope-o"></i></a>
									</div>
								<?php }  ?>
							</h3></li>
					</ul>
				</div>
			<?php } ?>
		</div>

		<hr class="post-single-hr">

	</div>

</article>