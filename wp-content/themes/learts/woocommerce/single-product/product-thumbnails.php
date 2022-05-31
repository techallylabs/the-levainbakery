<?php
/**
 * Single Product Thumbnails
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/product-thumbnails.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see           https://docs.woocommerce.com/document/template-structure/
 * @author        WooThemes
 * @package       WooCommerce/Templates
 * @version       3.5.1
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

global $post, $product, $woocommerce;

// Product page layout
$product_page_layout = get_post_meta( Learts_Helper::get_the_ID(), 'learts_product_page_layout', true );
$thumbnails_position = get_post_meta( Learts_Helper::get_the_ID(), 'learts_product_thumbnails_position', true );

if ( $product_page_layout == 'default' || ! $product_page_layout ) {
	$product_page_layout = learts_get_option( 'product_page_layout' );
}

if ( $thumbnails_position == 'default' || ! $thumbnails_position ) {
	$thumbnails_position = learts_get_option( 'product_thumbnails_position' );
}

$attachment_ids = $product->get_gallery_image_ids();

$carousel_settings = array(
	'accessibility' => false,
	'focusOnSelect' => true,
	'variableWidth' => true,
	'slidesToShow'  => 3,
	'infinite'      => true,
	'asNavFor'      => '.woocommerce-product-gallery__slider',
);

if ( is_rtl() ) {
	$carousel_settings['rtl'] = true;
}

$carousel_settings = apply_filters( 'learts_single_product_thumbnails_carousel_settings', $carousel_settings );

if ( $thumbnails_position == 'left' ) {
	$carousel_settings['slidesToShow']    = 3;
	$carousel_settings['vertical']        = true;
	$carousel_settings['verticalSwiping'] = true;
	$carousel_settings['rtl']             = false;
	$carousel_settings['adaptiveHeight']  = true;
	$carousel_settings['variableWidth']   = false;
}

if ( $attachment_ids && has_post_thumbnail() ) { ?>
	<div class="thumbnails thumbnails-slider"
	     data-carousel="<?php echo esc_attr( json_encode( $carousel_settings ) ) ?>"><?php

		if ( $product_page_layout != 'sticky' && $product_page_layout != 'sticky-fullwidth' ) {

			$main_attachment_id = get_post_thumbnail_id( $post->ID );
			$single_image       = wp_get_attachment_image_src( $main_attachment_id, 'shop_single' );
			$full_size_image    = wp_get_attachment_image_src( $main_attachment_id, 'full' );
			$placeholder        = has_post_thumbnail() ? 'with-images' : 'without-images';

			$attributes = array(
				'title'                   => get_post_field( 'post_title', $main_attachment_id ),
				'data-caption'            => get_post_field( 'post_excerpt', $main_attachment_id ),
				'data-src'                => $full_size_image[0],
				'data-large_image'        => $full_size_image[0],
				'data-large_image_width'  => $full_size_image[1],
				'data-large_image_height' => $full_size_image[2],
			);

			$size = 'shop_thumbnail';
			if ( $product_page_layout == 'fullwidth' ) {
				$size = array(
					intval( get_option( 'shop_thumbnail_image_size', array( 'width' => 163 ) )['width'] ) * 1.25,
					intval( get_option( 'shop_thumbnail_image_size', array( 'height' => 219 ) )['height'] ) * 1.25,
				);
			} elseif ( $product_page_layout == 'sticky' ) {
				$size = 'shop_single';
			}

			$html = '<a href="' . esc_url( $full_size_image[0] ) . '">';
			$html .= get_the_post_thumbnail( $post->ID,
				apply_filters( 'learts_single_product_large_thumbnail_size', $size ),
				$attributes );
			$html .= '</a>';

			echo apply_filters( 'woocommerce_single_product_image_thumbnail_html',
				$html,
				get_post_thumbnail_id( $post->ID ) );
		}

		foreach ( $attachment_ids as $attachment_id ) {

			$size = 'shop_thumbnail';
			if ( $product_page_layout == 'fullwidth' ) {
				$size = array(
					intval( get_option( 'shop_thumbnail_image_size', array( 'width' => 163 ) )['width'] ) * 1.25,
					intval( get_option( 'shop_thumbnail_image_size', array( 'height' => 219 ) )['height'] ) * 1.25,
				);
			} elseif ( $product_page_layout == 'sticky-fullwidth' ) {
				$size = array(
					intval( get_option( 'shop_single_image_size', array( 'width' => 675 ) )['width'] ) * 1.25,
					intval( get_option( 'shop_single_image_size', array( 'height' => 910 ) )['height'] ) * 1.25,
				);
			} elseif ( $product_page_layout == 'sticky' ) {
				$size = 'shop_single';
			}

			$full_size_image = wp_get_attachment_image_src( $attachment_id, 'full' );
			$single_image    = wp_get_attachment_image_src( $attachment_id, 'shop_single' );
			$thumbnail       = wp_get_attachment_image_src( $attachment_id, $size );
			$image_single    = wp_get_attachment_image_src( $attachment_id,
				apply_filters( 'learts_single_product_large_thumbnail_size', 'shop_single' ) );

			$attributes = array(
				'title'                   => get_post_field( 'post_title', $attachment_id ),
				'data-caption'            => get_post_field( 'post_excerpt', $attachment_id ),
				'data-src'                => $full_size_image[0],
				'data-large_image'        => $full_size_image[0],
				'data-large_image_width'  => $full_size_image[1],
				'data-large_image_height' => $full_size_image[2],
			);

			$html = '<a href="' . esc_url( $full_size_image[0] ) . '">';
			$html .= wp_get_attachment_image( $attachment_id,
				apply_filters( 'learts_single_product_small_thumbnail_size', $size ),
				false,
				$attributes );
			$html .= '</a>';

			echo apply_filters( 'woocommerce_single_product_image_thumbnail_html', $html, $attachment_id );
		}

		?></div>
	<?php
}
