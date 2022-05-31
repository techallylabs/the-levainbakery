<?php
/**
 * Shortcode attributes
 *
 * @var $atts
 * @var $align
 * @var $animation
 * @var $image
 * @var $content
 * @var $position
 * @var $fullwidth
 * @var $style
 * @var $display_button
 * @var $link
 * @var $display_border
 * @var $hover_style
 * @var $animation
 * @var $el_class
 * @var $css
 * Shortcode class
 * @var $this WPBakeryShortCode_Learts_Simple_Banner
 */

$atts = vc_map_get_attributes( $this->getShortcode(), $atts );
extract( $atts );

$el_class = $this->getExtraClass( $el_class );

$css_class = array(
	'tm-shortcode',
	'learts-simple-banner',
	'hover-' . $hover_style,
	'style-' . $style,
	$this->getCSSAnimation( $animation ),
	$el_class,
	vc_shortcode_custom_css_class( $css ),
);

//parse link
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

if ( $animation !== '' ) {
	$css_class .= 'tm-animation ' . $animation;
}

if ( $image ) {
	$image_attr = wp_get_attachment_image_src( $image, 'full' );
}

if ( $display_border == 'yes' ) {
	$css_class .= ' has-border';
}

?>

<div class="<?php echo esc_attr( trim( $css_class ) ) ?>">
	<?php if ( $link != '' ) { ?>
		<a href="<?php echo esc_url( $a_href ); ?> "
		   title="<?php echo esc_attr( $a_title ); ?>"
		   target="<?php echo esc_attr( $a_target ? $a_target : '_self' ); ?>"
		   rel="<?php echo esc_attr( $a_rel ); ?>"></a>
	<?php } ?>
	<?php if ( $style == 'text' ) { ?>
		<div class="content">
			<p class="text"><?php echo '' . $content ?></p>
			<?php if ( $link && $display_button == 'yes' ) { ?>
				<div class="button-banner"><a href="<?php echo esc_url( $a_href ); ?> "
				                              title="<?php echo esc_attr( $a_title ); ?>"
				                              target="<?php echo esc_attr( $a_target ? $a_target : '_self' ); ?>"
				                              rel="<?php echo esc_attr( $a_rel ); ?>">
						<?php echo esc_attr( $a_title ); ?></a>
				</div>
			<?php } ?>
		</div>

	<?php } else { ?>
		<img src="<?php echo esc_attr( $image_attr[0] ); ?>"/>
		<div
			class="content <?php echo( ( $fullwidth === 'yes' ) ? 'content-fullwidth' : '' ); ?> align-<?php echo esc_attr( $align ) ?> position-<?php echo esc_attr( $position ) ?>">
			<?php echo '' . $content ?>
			<?php if ( $link != '' && $display_button == 'yes' ) { ?>
				<a class="button-banner" href="<?php echo esc_url( $a_href ); ?> "
				   title="<?php echo esc_attr( $a_title ); ?>"
				   target="<?php echo esc_attr( $a_target ? $a_target : '_self' ); ?>"
				   rel="<?php echo esc_attr( $a_rel ); ?>">
					<?php echo esc_attr( $a_title ); ?></a>
			<?php } ?>
		</div>
	<?php } ?>
</div>
