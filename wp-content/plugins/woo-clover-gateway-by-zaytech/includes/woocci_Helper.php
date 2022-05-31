<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Provides static methods as helpers.
 *
 * @since 1.0.0
 */
class Woocci_Helper {
    public static function woocci_get_wc_order_notes( $order_id){
        //make sure it's a number
        $order_id = intval($order_id);
        //get the post
        $post = get_post($order_id);
        //if there's no post, return as error
        if (!$post) return '';

        return $post->post_excerpt;
    }
    /**
     * Localize messages based on code
     *
     * @since 1.2.1
     * @return array
     */
    public static function get_localized_messages() {
        return apply_filters(
            'wocci_localized_messages',
            array(
                'invalid_number'           => __( 'The card number is not a valid credit card number.', 'zaytech_woocci' ),
                'invalid_expiry_month'     => __( 'The card\'s expiration month is invalid.', 'zaytech_woocci' ),
                'invalid_expiry_year'      => __( 'The card\'s expiration year is invalid.', 'zaytech_woocci' ),
                'invalid_cvc'              => __( 'The card\'s security code is invalid.', 'zaytech_woocci' ),
                'incorrect_number'         => __( 'The card number is incorrect.', 'zaytech_woocci' ),
                'incomplete_number'        => __( 'The card number is incomplete.', 'zaytech_woocci' ),
                'incomplete_cvc'           => __( 'The card\'s security code is incomplete.', 'zaytech_woocci' ),
                'incomplete_expiry'        => __( 'The card\'s expiration date is incomplete.', 'zaytech_woocci' ),
                'expired_card'             => __( 'The card has expired.', 'zaytech_woocci' ),
                'incorrect_cvc'            => __( 'The card\'s security code is incorrect.', 'zaytech_woocci' ),
                'incorrect_zip'            => __( 'The card\'s zip code failed validation.', 'zaytech_woocci' ),
                'invalid_expiry_year_past' => __( 'The card\'s expiration year is in the past', 'zaytech_woocci' ),
                'card_declined'            => __( 'The card was declined.', 'zaytech_woocci' ),
                'missing'                  => __( 'There is no card on a customer that is being charged.', 'zaytech_woocci' ),
                'processing_error'         => __( 'An error occurred while processing the card.', 'zaytech_woocci' ),
                'invalid_request_error'    => __( 'Unable to process this payment, please try again or use alternative method.', 'zaytech_woocci' ),
                'invalid_sofort_country'   => __( 'The billing country is not accepted by SOFORT. Please try another country.', 'zaytech_woocci' ),
                'email_invalid'            => __( 'Invalid email address, please correct and try again.', 'zaytech_woocci' ),
            )
        );
    }
}
