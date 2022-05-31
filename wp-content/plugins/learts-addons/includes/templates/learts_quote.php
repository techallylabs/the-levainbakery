<?php
/**
 * Shortcode attributes
 *
 * @var $atts
 * @var $animation
 * @var $color_quote
 * @var $heading
 * @var $content
 * @var $link
 * @var $style
 * @var $el_class
 * @var $css
 *
 * Shortcode class
 * @var $this WPBakeryShortCode_Learts_Quote
 */
$atts = vc_map_get_attributes( $this->getShortcode(), $atts );
extract( $atts );

$el_class = $this->getExtraClass( $el_class );

$animation_classes = $this->getCSSAnimation( $animation );

$css_class = array(
	'tm-shortcode',
	'learts-quote',
	$animation_classes,
	$el_class,
	vc_shortcode_custom_css_class( $css ),
);

// parse banner link
$a_href   = '';
$a_title  = '';
$a_target = '';
$a_rel    = '';
if ( ! empty( $link ) && "||" !== $link && "|||" !== $link ) {
	$link = vc_build_link( $link );

	$a_href   = $link['url'];
	$a_title  = $link['title'];
	$a_target = $link['target'];
	$a_rel    = $link['rel'];
}

$css_class = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG,
	implode( ' ', $css_class ),
	$this->settings['base'],
	$atts );

$class = '';
if ( $style == 'left' ) {
	$class = 'button-align-left';
} else {
	$class = 'button-align-right';
}
$css_id = Learts_VC::get_learts_shortcode_id( 'learts-quote' );
$this->shortcode_css( $css_id );
?>
<div class="<?php echo esc_attr( trim( $css_class ) ); ?>" id="<?php echo esc_attr( $css_id ); ?>">

	<div class="<?php echo esc_attr( $class ); ?>">
		<h3 class="title-quote"><?php echo esc_attr( $heading ); ?></h3>
		<p class="content-quote"><?php echo wp_kses_post( $content ); ?></p>
		<a class="button-link btn-only-text btn-separator <?php echo esc_attr( $class ); ?>"
		   href="<?php echo esc_url( $a_href ); ?>"
			<?php echo esc_attr($a_title) ? 'title="' . esc_attr( $a_title ) . '"' : ''; ?>
			<?php echo esc_attr($a_target) ? 'target="' . esc_attr( $a_target ) . '"' : ''; ?>
			<?php echo esc_attr($a_rel) ? 'rel="' . esc_attr( $a_rel ) . '"' : ''; ?>>
			<?php echo esc_attr( $a_title ); ?>
		</a>
	</div>
</div>

