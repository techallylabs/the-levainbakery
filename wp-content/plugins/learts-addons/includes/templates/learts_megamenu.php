<?php
/**
 * Shortcode attributes
 *
 * @var $atts
 * @var $title
 * @var $item_list
 * @var $source
 * @var $image
 * @var $link_image
 * @var $link
 * @var $title_item
 * @var $el_class
 * @var $css
 * Shortcode class
 * @var $this WPBakeryShortCode_Lezada_Simple_Banner
 */

$atts = vc_map_get_attributes( $this->getShortcode(), $atts );
extract( $atts );

$el_class = $this->getExtraClass( $el_class );

$item_list = (array) vc_param_group_parse_atts( $item_list );

$css_class = array(
	'tm-shortcode',
	'learts-megamenu',
	$el_class,
	vc_shortcode_custom_css_class( $css ),
);

$css_class = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG,
	implode( ' ', $css_class ),
	$this->settings['base'],
	$atts );
?>

<div class="<?php echo esc_attr( trim( $css_class ) ) ?>">
	<ul class="block-list">
		<li class="title"><?php echo esc_attr( $title ); ?></li>
		<?php $count = count( $item_list );
		for ( $i = 0; $i < $count; $i ++ ) {
			$link        = ( isset( $item_list[ $i ]["link"] ) && ( $item_list[ $i ]["link"] != '' ) ) ? vc_build_link( $item_list[ $i ]["link"] ) : '#';
			$link_url    = ( isset( $link['url'] ) && ( $link['url'] != '' ) ) ? $link['url'] : '#';
			$link_text   = ( isset( $link['title'] ) && ( $link['title'] != '' ) ) ? $link['title'] : esc_html__( 'Click Here',
				'learts-addons' );
			$link_target = ( isset( $link['target'] ) && ( $link['target'] != '' ) ) ? $link['target'] : '_self';
			$link_rel    = ( isset( $link['rel'] ) && ( $link['rel'] != '' ) ) ? $link['rel'] : 'nofollow';
			?>
			<li>
				<?php if ( $item_list[ $i ]["source"] == 'image_libary' ) {
					$image_attr = wp_get_attachment_image_src( $item_list[ $i ]["image"], 'full' ); ?>
					<img src="<?php echo esc_attr( $image_attr[0] ); ?>"/>
				<?php } else { ?>
					<img src="<?php echo  esc_attr($item_list[ $i ]["link_image"]); ?>" alt="image"/>
				<?php } ?>
				<a href="<?php echo esc_attr($link_url); ?>"
				   title="<?php echo esc_attr($link_text); ?>"
				   target="<?php echo esc_attr($link_target); ?>"
				   rel="<?php echo esc_attr($link_rel); ?>"><?php echo esc_attr($item_list[ $i ]["title_item"]); ?></a>
			</li>
			<?php
		} ?>
	</ul>
</div>
