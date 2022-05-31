<?php
/**
 * Shortcode attributes
 *
 * @var $atts
 * @var $animation
 * @var $placeholder_text
 * @var $button_text
 * @var $list_id
 * @var $color_scheme
 * @var $placeholder_color
 * @var $placeholder_color_focus
 * @var $input_color
 * @var $input_color_focus
 * @var $input_border_color
 * @var $input_border_color_focus
 * @var $input_bg_color
 * @var $input_bg_color_focus
 * @var $button_text_color
 * @var $button_text_color_hover
 * @var $button_bg_color
 * @var $button_bg_color_hover
 * @var $max_width
 * @var $el_class
 * @var $css
 * @var $css_id
 *
 * Shortcode class
 * @var $this WPBakeryShortCode_Learts_MailChimp
 */

$atts = vc_map_get_attributes( $this->getShortcode(), $atts );
extract( $atts );

$el_class = $this->getExtraClass( $el_class );

$animation_classes = $this->getCSSAnimation( $animation );

$css_class = array(
	'tm-shortcode',
	'learts-mailchimp',
	$animation_classes,
	'color-scheme--' . $color_scheme,
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

$css_id = uniqid( 'learts-mailchimp-' );

if ( $color_scheme == 'custom' ) {
	$this->shortcode_css( $css_id );
}

$api_key = Learts_Addons::get_option( 'mailchimp_api_key' );

if ( ! $api_key ) {
	printf( wp_kses( __( 'Please add MailChimp API Key in <a href="%s" target="_blank">Theme Options > API Integrations</a>',
		'learts-addons' ),
		array(
			'strong' => array(),
			'a'      => array(
				'href'   => array(),
				'target' => array(),
			),
		) ),
		esc_url( add_query_arg( array(
			'page' => 'learts_options',
			'tab'  => '38',
		),
			admin_url() ) ) );
} else {

	?>

	<div class="<?php echo esc_attr( trim( $css_class ) ) ?>" id="<?php echo esc_attr( $css_id ); ?>">
		<?php if ( $list_id ) { ?>
			<p class="mailchimp-message"></p>
			<form method="POST" class="mailchimp-form">
				<input type="email" name="mailchimp-email" class="mailchimp-email"
				       placeholder="<?php echo esc_attr( $placeholder_text ) ?>">
				<input type="hidden" name="mailchimp-list-id" class="mailchimp-list-id"
				       value="<?php echo esc_attr( $list_id ); ?>">
				<button type="submit" class="mailchimp-button"><?php echo '' . $button_text ?></button>
			</form>
		<?php } else { ?>
			<p class="mailchimp-message error"><?php esc_html_e( 'Please select at least one list.', 'learts-addons' ) ?></p>
		<?php } ?>
	</div>
<?php } ?>
