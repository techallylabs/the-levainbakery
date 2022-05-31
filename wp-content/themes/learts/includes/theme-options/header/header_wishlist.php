<?php

if ( ! class_exists( 'YITH_WCWL' ) ) {
	return;
}

Redux::setSection( learts_Redux::$opt_name,
	array(
		'title'      => esc_html__( 'Wishlist', 'learts' ),
		'id'         => 'section_header_wishlist',
		'subsection' => true,
		'fields'     => array(
			array(
				'id'       => 'wishlist_on',
				'type'     => 'switch',
				'title'    => esc_html__( 'Enable Wishlist', 'learts' ),
				'subtitle' => esc_html__( 'Enable / Disable the wishlist icon in the header', 'learts' ),
				'default'  => false,
			),
			array(
				'id'      => 'wishlist_icon',
				'type'    => 'button_set',
				'title'   => esc_html__( 'Wishlist Icon', 'learts' ),
				'options' => array(
					'fas fa-heart'         => '<i class="ion-android-favorite"></i>&nbsp;&nbsp;' . esc_html__( 'Heart',
							'learts' ),
					'fal fa-heart' => '<i class="ion-android-favorite-outline"></i>&nbsp;&nbsp;' . esc_html__( 'Heart Outline',
							'learts' ),
				),
				'default' => 'ion-android-favorite',
			),
			array(
				'id'      => 'wishlist_add_to_cart_on',
				'type'    => 'switch',
				'title'   => esc_html__( 'Show Add To Cart Button', 'learts' ),
				'default' => true,
			),
			array(
				'id'      => 'wishlist_target',
				'type'    => 'switch',
				'title'   => esc_html__( 'Open Wishlist page in a new tab', 'learts' ),
				'default' => false,
			),
			array(
				'id'          => 'wishlist_icon_color',
				'type'        => 'color',
				'transparent' => false,
				'title'       => esc_html__( 'Wishlist Icon Color', 'learts' ),
				'subtitle'    => esc_html__( 'Pick a color for the wishlist icon', 'learts' ),
				'output'      => $learts_selectors['wishlist_icon_color'],
				'validate'    => 'color',
				'default'     => PRIMARY_COLOR,
			),
			array(
				'id'          => 'wishlist_count_color',
				'type'        => 'color',
				'transparent' => false,
				'title'       => esc_html__( 'Wishlist Item Count Color', 'learts' ),
				'subtitle'    => esc_html__( 'Pick a color for the text of wishlist widget', 'learts' ),
				'output'      => $learts_selectors['wishlist_count_color'],
				'validate'    => 'color',
				'default'     => '#ffffff',
			),
			array(
				'id'          => 'wishlist_count_bg_color',
				'type'        => 'color',
				'transparent' => false,
				'title'       => esc_html__( 'Wishlist Item Count Background Color', 'learts' ),
				'subtitle'    => esc_html__( 'Pick a background color for the item count of wishlist widget',
					'learts' ),
				'output'      => $learts_selectors['wishlist_count_bg_color'],
				'validate'    => 'color',
				'default'     => '#f8796c',
			),
			array(
				'id'          => 'wishlist_count_bd_color',
				'type'        => 'color',
				'transparent' => false,
				'title'       => esc_html__( 'Wishlist Item Count Border Color', 'learts' ),
				'subtitle'    => esc_html__( 'Pick a border color for the item count of wishlist widget',
					'learts' ),
				'output'      => $learts_selectors['wishlist_count_bd_color'],
				'validate'    => 'color',
				'default'     => '#f8796c',
			),
		),
	) );
