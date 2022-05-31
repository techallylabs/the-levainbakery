<?php
/**
 * Shortcode attributes
 *
 * @var $atts
 * @var $frames
 * @var $image_preview
 * @var $image_360
 * @var $animation
 * @var $el_class
 * @var $css
 * Shortcode class
 * @var $this WPBakeryShortCode_Learts_Image_360
 */

$atts = vc_map_get_attributes( $this->getShortcode(), $atts );
extract( $atts );

$css_class = array(
	'tm-shortcode',
	'learts-image-360',
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
$image_preview_src = wp_get_attachment_image_src( $image_preview, 'full' );
$image_360_src = wp_get_attachment_image_src( $image_360, 'full' );
?>

<div class="<?php echo esc_attr( trim( $css_class ) ); ?>">
	<div class="row">
		<div class="cd-product-viewer-wrapper has-<?php echo esc_attr( $frames ) ?>-frames"
		     data-frame="<?php echo esc_attr( $frames ); ?>" data-friction="0.1">
			<div class="product-viewer">
				<?php if ( $image_360_src[0] && $image_preview_src[0] ) { ?>
					<img src="<?php echo esc_attr( $image_preview_src[0] ); ?>" alt="Product Preview">
					<div
						style="background: url('<?php echo esc_attr( $image_360_src[0] ); ?>') no-repeat center center;"
						class="product-sprite"
						data-image="<?php echo esc_attr( $image_360_src[0] ); ?>"></div>
				<?php } ?>
			</div>
		</div>
	</div>
</div>





