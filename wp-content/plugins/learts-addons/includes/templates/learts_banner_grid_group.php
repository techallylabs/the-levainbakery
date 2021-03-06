<?php
/**
 * Shortcode attributes
 *
 * @var $atts
 * @var $content
 * @var $style
 * @var $el_class
 * @var $css
 * Shortcode class
 * @var $this WPBakeryShortCode_Learts_Banner_Grid_Group
 */

$atts = vc_map_get_attributes( $this->getShortcode(), $atts );
extract( $atts );

$el_class = $this->getExtraClass( $el_class );

$css_class = array(
	'tm-shortcode',
	'learts-banner-grid-group',
	$style,
	$el_class,
	vc_shortcode_custom_css_class( $css ),
);

$css_class = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG,
	implode( ' ', $css_class ),
	$this->settings['base'],
	$atts );

?>

<div class="<?php echo esc_attr( trim( $css_class ) ); ?>">

	<?php echo do_shortcode( $content ); ?>

</div>
