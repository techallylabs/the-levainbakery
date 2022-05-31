<?php
/**
 * Shortcode attributes
 *
 * @var $atts
 * @var $image
 * @var $title
 * @var $link
 * @var $loop
 * @var $auto_play
 * @var $auto_play_speed
 * @var $nav_type
 * @var $animation
 * @var $el_class
 * @var $css
 * Shortcode class
 * @var $this WPBakeryShortCode_Learts_Image_Carousel
 */

$atts = vc_map_get_attributes( $this->getShortcode(), $atts );
extract( $atts );

$img = false;
$id  = '';

$css_class = array(
	'tm-shortcode',
	'learts-image-carousel',
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


$images = (array) vc_param_group_parse_atts( $single_image );
?>

<div class="<?php echo esc_attr( trim( $css_class ) ); ?>" data-atts="<?php echo esc_attr( json_encode( $atts ) ); ?>">
	<div class="row image-items">
		<?php foreach ( $images as $single_image ) {
			$id ++;
			$css_id   = 'image-' . $id;
			$a_href   = '';
			$a_title  = '';
			$a_target = '';
			$a_rel    = '';
			if ( ! empty( $single_image['link'] ) && "||" !== $single_image['link'] && "|||" !== $single_image['link'] ) {
				$link = vc_build_link( $single_image['link'] );

				$a_href   = $link['url'];
				$a_title  = $link['title'];
				$a_target = $link['target'];
				$a_rel    = $link['rel'];
			}
			?>
			<?php if ( ( $single_image['image'] ) ) {
				$url = wp_get_attachment_image( $single_image['image'], 'full' );
			} ?>
			<div class="single-image"
			     id="<?php echo esc_attr( $css_id ); ?>">
				<div class="slide-wrapper">
					<div class="inner">
						<a href="<?php echo esc_url( $a_href ); ?>"
							<?php echo $a_title ? 'title="' . esc_attr( $a_title ) . '"' : ''; ?>
							<?php echo $a_target ? 'target="' . esc_attr( $a_target ) . '"' : ''; ?>
							<?php echo $a_rel ? 'rel="' . esc_attr( $a_rel ) . '"' : ''; ?>>
							<div class="image"><?php echo $url; ?></div>
							<h3 class="content-image">
								<?php if ( isset( $single_image['title'] ) && $single_image['title'] != '' ) {
									echo esc_attr( $single_image['title'] );
								} ?>
							</h3>
						</a>
					</div>

				</div>
			</div>
		<?php } ?>
	</div>
</div>





