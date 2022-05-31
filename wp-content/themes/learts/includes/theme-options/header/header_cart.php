<?php

if ( ! class_exists( 'WooCommerce' ) ) {
	return;
}

Redux::setSection( learts_Redux::$opt_name,
	array(
		'title'      => esc_html__( 'Shopping Cart', 'learts' ),
		'id'         => 'section_header_cart',
		'subsection' => true,
		'fields'     => array(
			array(
				'id'       => 'minicart_on',
				'type'     => 'switch',
				'title'    => esc_html__( 'Enable Shopping Cart', 'learts' ),
				'subtitle' => esc_html__( 'Enable / Disable the shopping cart', 'learts' ),
				'default'  => true,
			),
			array(
				'id'       => 'minicart_message',
				'type'     => 'textarea',
				'title'    => esc_html__( 'Shopping Cart Message', 'learts' ),
				'subtitle' => esc_html__( 'Insert the text you want to see in the shopping cart here.', 'learts' ),
				'default'  => __( 'Free Shipping on All Orders Over $100!', 'learts' ),
			),
			array(
				'id'       => 'minicart_message_pos',
				'type'     => 'button_set',
				'title'    => esc_html__( 'Message Position', 'learts' ),
				'subtitle' => esc_html__( 'Set the position for shopping cart message in the dropdown', 'learts' ),
				'options'  => array(
					'top'    => esc_html__( 'Top', 'learts' ),
					'bottom' => esc_html__( 'Bottom', 'learts' ),
				),
				'default'  => 'bottom',
			),
			array(
				'id'      => 'minicart_icon',
				'type'    => 'button_set',
				'title'   => esc_html__( 'Shopping Cart Icon', 'learts' ),
				'options' => array(
					'fas fa-shopping-cart' => '<i class="ion-ios-cart"></i>&nbsp;&nbsp;' . esc_html__( 'Cart',
							'learts' ),
					'fal fa-shopping-cart' => '<i class="ion-ios-cart-outline"></i>&nbsp;&nbsp;' . esc_html__( 'Cart Outline',
							'learts' ),
				),
				'default' => 'fal fa-shopping-cart',
			),
			array(
				'id'          => 'minicart_icon_color',
				'type'        => 'color',
				'transparent' => false,
				'title'       => esc_html__( 'Cart Icon Color', 'learts' ),
				'subtitle'    => esc_html__( 'Pick a color for the shopping cart icon', 'learts' ),
				'output'      => $learts_selectors['minicart_icon_color'],
				'default'     => '#333333',
			),
			array(
				'id'          => 'minicart_count_color',
				'type'        => 'color',
				'transparent' => false,
				'title'       => esc_html__( 'Cart Item Count Color', 'learts' ),
				'subtitle'    => esc_html__( 'Pick a color for the text of shopping cart', 'learts' ),
				'output'      => $learts_selectors['minicart_count_color'],
				'default'     => '#ffffff',
			),
			array(
				'id'          => 'minicart_count_bg_color',
				'type'        => 'color',
				'transparent' => false,
				'title'       => esc_html__( 'Cart Item Count Background Color', 'learts' ),
				'subtitle'    => esc_html__( 'Pick a background color for the item count of shopping cart',
					'learts' ),
				'output'      => $learts_selectors['minicart_count_bg_color'],
				'validate'    => 'color',
				'default'     => '#f8796c',
			),
			array(
				'id'          => 'minicart_count_bd_color',
				'type'        => 'color',
				'transparent' => false,
				'title'       => esc_html__( 'Cart Item Count Border Color', 'learts' ),
				'subtitle'    => esc_html__( 'Pick a border color for the item count of shopping cart',
					'learts' ),
				'output'      => $learts_selectors['minicart_count_bd_color'],
				'validate'    => 'color',
				'default'     => '#f8796c',
			),
		),
	) );
