<?php
/**
 * Shortcode attributes
 *
 * @var $atts
 * @var $animation
 * @var $style
 * @var $v_align
 * @var $title
 * @var $title_font_size
 * @var $title_font_color
 * @var $content
 * @var $content_font_size
 * @var $content_font_color
 * @var $link
 * @var $use_link_title
 * @var $link_color
 * @var $use_text
 * @var $text
 * @var $el_class
 * @var $type
 * @var $icon_fontawesome
 * @var $icon_openiconic
 * @var $icon_typicons
 * @var $icon_entypo
 * @var $icon_linecons
 * @var $icon_pe7stroke
 * @var $icon_font_size
 * @var $with_bg
 * @var $bg_shape
 * @var $icon_color
 * @var $icon_bgcolor
 * @var $css
 * @var $css_id
 * @var $full_height
 * Shortcode class
 * @var $this WPBakeryShortCode_Learts_Icon_Box
 */

$atts = vc_map_get_attributes( $this->getShortcode(), $atts );
extract( $atts );

$el_class = $this->getExtraClass( $el_class );

$animation_classes = $this->getCSSAnimation( $animation );

$css_class = array(
	'tm-shortcode',
	'learts-icon-box',
	$v_align,
	$style,
	$animation_classes,
	$el_class,
	vc_shortcode_custom_css_class( $css ),
);

$css_class = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG,
	implode( ' ', $css_class ),
	$this->settings['base'],
	$atts );

if ( $animation !== '' ) {
	$css_class .= ' tm-animation ' . $animation . '';
}

// Enqueue needed icon font.
vc_icon_element_fonts_enqueue( $type );
$iconClass = isset( ${"icon_" . $type} ) ? esc_attr( ${"icon_" . $type} ) : 'fa fa-adjust';

if ( ! empty( $link ) && "||" !== $link && "|||" !== $link ) {
	$link      = vc_build_link( $link );
	$link_text = '<a href="' . esc_attr( $link['url'] ) . '"' . ( $link['target'] ? ' target="' . esc_attr( $link['target'] ) . '"' : '' ) . ( $link['title'] ? ' title="' . esc_attr( $link['title'] ) . '"' : '' ) . 'rel="' . $link['rel'] . '">' . ( $link['title'] ? $link['title'] : '' ) . '</a>';
}

$css_id        = uniqid( 'tm-icon-box-' );
$shortcode_css = $this->shortcode_css( $css_id );

if ( empty( $title ) && empty( $content ) ) {
	$css_class .= ' only-icon';
}
if ( 'yes' == $full_height ) {
	$css_class .= ' full-height-icon-box';
}
?>
<div class="<?php echo esc_attr( trim( $css_class ) ); ?>  id="<?php echo esc_attr( $css_id ); ?> ">

<?php if ( 'left' == $style || 'center' == $style ) { ?>

	<div style="font-size:<?php echo esc_attr( $icon_font_size ) . 'px'; ?>"
	     class="tm-icon-box__icon<?php echo esc_attr( $with_bg ? ' with-bg' : '' ); ?><?php echo esc_attr( $with_bg ? ' ' . $bg_shape : '' ); ?> ">
		<?php if ( 'yes' == $use_link_title && ! empty( $link ) && "||" !== $link && "|||" !== $link ) { ?>
		<a href="<?php echo esc_url( $link['url'] ); ?>" target="<?php echo esc_attr( $link['target'] ); ?>"
		   rel="<?php echo esc_attr( $link['rel'] ); ?>"
		   title="<?php echo esc_html( $title ); ?>">
			<?php } ?>
			<?php if ( 'yes' == $use_text ) { ?>
				<span><?php echo '' . $text; ?></span>
			<?php } else { ?>
				<i style="color: <?php echo esc_attr( $icon_color ); ?>;background-color: <?php echo esc_attr( $icon_bgcolor ); ?>;"
				   class="<?php echo esc_attr( $iconClass ); ?>"></i>
			<?php } ?>
			<?php if ( 'yes' == $use_link_title && ! empty( $link ) && "||" !== $link && "|||" !== $link ) { ?>
		</a>
	<?php } ?>
	</div>

<?php } ?>

<?php if ( $title || $content ) { ?>
	<div class="tm-icon-box__content">
		<?php if ( $title ) { ?>
			<div class="title"
			     style="color: <?php echo esc_attr( $title_font_color ) ?>; font-size:<?php echo esc_attr( $title_font_size ) . 'px'; ?>">
				<?php if ( 'yes' == $use_link_title && ! empty( $link ) && "||" !== $link && "|||" !== $link ) { ?>
				<a href="<?php echo esc_url( $link['url'] ); ?>"
				   target="<?php echo esc_attr( $link['target'] ); ?>" rel="<?php echo esc_attr( $link['rel'] ); ?>"
				   title="<?php echo esc_attr( $title ); ?>">
					<?php
					$title = ( $link['title'] ? $link['title'] : $title );
					} ?>
					<?php echo '' . $title; ?>
					<?php if ( 'yes' == $use_link_title && ! empty( $link ) && "||" !== $link && "|||" !== $link ) { ?>
				</a>
			<?php } ?>
			</div>
		<?php } ?>
		<?php if ( $content ) { ?>
			<div class="description"
			     style="color: <?php echo esc_attr( $content_font_color ) ?>; font-size:<?php echo esc_attr( $content_font_size ) . 'px'; ?>"><?php echo '' . $content; ?></div>
		<?php } ?>
		<?php if ( 'yes' != $use_link_title && isset( $link_text ) && $link_text ) { ?>
			<p class="subtext"><?php echo '' . $link_text; ?></p>
		<?php } ?>
	</div>
<?php } ?>

<?php if ( 'right' == $style ) { ?>

	<div style="font-size:<?php echo esc_attr( $icon_font_size ) . 'px'; ?>"
	     class="tm-icon-box__icon<?php echo esc_attr( $with_bg ? ' with-bg' : '' ); ?><?php echo esc_attr( $with_bg ? ' ' . $bg_shape : '' ); ?>">
		<?php if ( 'yes' == $use_link_title && ! empty( $link ) && "||" !== $link && "|||" !== $link ) { ?>
		<a href="<?php echo esc_url( $link['url'] ); ?>" target="<?php echo esc_attr( $link['target'] ); ?>"
		   rel="<?php echo esc_attr( $link['rel'] ); ?>"
		   title="<?php echo esc_attr( $title ); ?>">
			<?php } ?>
			<?php if ( 'yes' == $use_text ) { ?>
				<bannerspans><?php echo '' . $text; ?></bannerspans>
			<?php } else { ?>
				<i style="color: <?php echo esc_attr( $icon_color ); ?>;background-color: <?php echo esc_attr( $icon_bgcolor ); ?>;"
				   class="<?php echo esc_attr( $iconClass ); ?>"></i>
			<?php } ?>
			<?php if ( 'yes' == $use_link_title && ! empty( $link ) && "||" !== $link && "|||" !== $link ) { ?>
		</a>
	<?php } ?>
	</div>

<?php } ?>
</div>
