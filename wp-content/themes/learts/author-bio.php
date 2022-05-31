<?php
/**
 * The template for displaying Author bios
 */

if ( ! learts_get_option( 'post_show_author_info' ) ) {
	return;
}

$author_description = get_the_author_meta( 'description' );

if ( ! empty( $author_description ) ) {
	?>

	<div class="author-info">
		<div class="author-avatar">
			<?php
			/**
			 * Filter the author bio avatar size.
			 *
			 * @since learts Thirteen 1.0
			 *
			 * @param int $size The avatar height and width size in pixels.
			 */
			$author_bio_avatar_size = apply_filters( 'learts_author_bio_avatar_size', 90 );
			echo get_avatar( get_the_author_meta( 'user_email' ), $author_bio_avatar_size );
			?>

			<?php
			$facebook  = get_the_author_meta( 'facebook' );
			$twitter   = get_the_author_meta( 'twitter' );
			$behance   = get_the_author_meta( 'behance' );
			$instagram = get_the_author_meta( 'instagram' );
			if ( $facebook || $twitter || $behance || $instagram ) : ?>
				<div class="author-social-networks">

					<?php if ( $facebook ) : ?>
						<a class="author-social social-facebook"
						   aria-label="<?php echo esc_html__( 'Facebook', 'learts' ) ?>"
						   href="<?php echo esc_url( $facebook ); ?>" target="_blank">
						</a>
					<?php endif; ?>

					<?php if ( $twitter ) : ?>
						<a class="author-social social-twitter"
						   aria-label="<?php echo esc_html__( 'Twitter', 'learts' ) ?>"
						   href="<?php echo esc_url( $twitter ); ?>" target="_blank">
						</a>
					<?php endif; ?>

					<?php if ( $behance ) : ?>
						<a class="author-social social-behance"
						   aria-label="<?php echo esc_html__( 'Linkedin', 'learts' ) ?>"
						   href="<?php echo esc_url( $behance ); ?>" target="_blank">
						</a>
					<?php endif; ?>

					<?php if ( $instagram ) : ?>
						<a class="author-social social-instagram"
						   aria-label="<?php echo esc_html__( 'Instagram', 'learts' ) ?>"
						   href="<?php echo esc_url( $instagram ); ?>" target="_blank">
						</a>
					<?php endif; ?>
				</div>
			<?php endif; ?>
		</div><!-- .author-avatar -->
		<div class="author-description">
			<div class="author-title">
				<a class="author-link"
				   href="<?php echo esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ); ?>"
				   rel="author"><?php echo get_the_author(); ?></a>
			</div>
			<p class="author-bio">
				<?php the_author_meta( 'description' ); ?>
			</p>
		</div><!-- .author-description -->
	</div><!-- .author-info -->

	<?php
}


