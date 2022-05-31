<?php
/**
 * Shortcode attributes
 *
 * @var $atts
 * @var $image
 * @var $title
 * @var $content
 * @var $text_align_position
 * @var $title_color
 * @var $content_color
 * @var $link
 * @var $animation
 * @var $el_class
 * @var $css
 * Shortcode class
 * @var $this WPBakeryShortCode_Learts_Banner_Carousel
 */

$atts = vc_map_get_attributes( $this->getShortcode(), $atts );
extract( $atts );

$img = false;
$id = '';

$css_class = array(
	'tm-shortcode',
	'learts-banner-carousel',
	$el_class,
	vc_shortcode_custom_css_class( $css ),
);

$css_class = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG,
	implode( ' ', $css_class ),
	$this->settings['base'],
	$atts );

$animation_classes = $this->getCSSAnimation( $animation );

if ( $animation !== '' ) {
	$css_class .= ' tm-animation ' . $animation . '' . $animation_classes;
}

$banners = (array) vc_param_group_parse_atts( $single_banner );
?>

<div class="<?php echo esc_attr( trim( $css_class ) ); ?>">
	<div class="row banner-items">
		<?php foreach ( $banners as $single_banner ) {
			$id ++;
			$css_id = 'banner-' . $id;
			?>
			<?php if ( ( $single_banner['image'] ) ) {
				$url = wp_get_attachment_image( $single_banner['image'], 'full' );
			} ?>
			<div class="single-banner <?php echo $single_banner['text_align_position'] ?>" id="<?php echo esc_attr( $css_id ); ?>">
				<div class="slide-wrapper">
					<div class="inner">
						<div class="image"><?php echo $url; ?></div>
						<div class="content-banner">
							<div class="title"
							     style="color: <?php echo $single_banner['title_color'] ?>"><?php echo $single_banner['title'] ?></div>
							<div class="content"
							     style="color: <?php echo $single_banner['content_color'] ?>"><?php echo $single_banner['content'] ?></div>

							<?php
							$a_href   = '';
							$a_title  = '';
							$a_target = '';
							$a_rel    = '';
							if ( ! empty( $single_banner['link'] ) && "||" !== $single_banner['link'] && "|||" !== $single_banner['link'] ) {
								$link = vc_build_link( $single_banner['link'] );

								$a_href   = $link['url'];
								$a_title  = $link['title'];
								$a_target = $link['target'];
								$a_rel    = $link['rel'];
							}
							?>
							<?php if ( $a_href != '' ) { ?>
								<a class="banner-link button learts-button"
								   href="<?php echo esc_url( $a_href ); ?>"
									<?php echo $a_title ? 'title="' . esc_attr( $a_title ) . '"' : ''; ?>
									<?php echo $a_target ? 'target="' . esc_attr( $a_target ) . '"' : ''; ?>
									<?php echo $a_rel ? 'rel="' . esc_attr( $a_rel ) . '"' : ''; ?>><?php echo $a_title ?></a>
							<?php } ?>
						</div>
					</div>

				</div>
			</div>
		<?php } ?>
	</div>
</div>


