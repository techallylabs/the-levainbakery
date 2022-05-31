<?php

Redux::setSection( learts_Redux::$opt_name, array(
	'title'      => esc_html__( 'General', 'learts' ),
	'id'         => 'section_woo_general',
	'subsection' => true,
	'fields'     => array(

		array(
			'id'       => 'product_style',
			'type'     => 'image_select',
			'title'    => esc_html__( 'Product Item Style', 'learts' ),
			'subtitle' => esc_html__( 'Select product item style', 'learts' ),
			'options'  => array(
				'default'   => array(
					'title' => esc_html__( 'Default', 'learts' ),
					'img'   => LEARTS_ADMIN_IMAGES . DS . 'product-default.gif',
				),
				'button-hover'  => array(
					'title' => esc_html__( 'Button Hover', 'learts' ),
					'img'   => LEARTS_ADMIN_IMAGES . DS . 'product-button-hover.gif',
				),
			),
			'default'  => 'default',
		),

		// Badge
		array(
			'id'       => 'badge_section_start',
			'type'     => 'section',
			'title'    => esc_html__( 'Badge', 'learts' ),
			'subtitle' => esc_html__( '', 'learts' ),
			'indent'   => true,
		),

		array(
			'id'      => 'shop_new_badge_on',
			'type'    => 'switch',
			'title'   => esc_html__( 'Show "New" badge', 'learts' ),
			'default' => true,
		),

		array(
			'id'            => 'shop_new_days',
			'type'          => 'slider',
			'title'         => esc_html__( 'New Product with how many days?', 'learts' ),
			'min'           => 1,
			'max'           => 60,
			'default'       => 10,
			'display_value' => 'label',
		),

		array(
			'id'      => 'shop_hot_badge_on',
			'type'    => 'switch',
			'title'   => esc_html__( 'Show "Hot" badge', 'learts' ),
			'default' => true,
		),

		array(
			'id'      => 'shop_sale_badge_on',
			'type'    => 'switch',
			'title'   => esc_html__( 'Show "Sale" badge', 'learts' ),
			'default' => true,
		),

		array(
			'id'       => 'shop_sale_percent_on',
			'type'     => 'switch',
			'title'    => esc_html__( 'Show saved sale price percentage', 'learts' ),
			'subtitle' => wp_kses( sprintf( __( 'Show percentage instead of text on "Sale" label. Only available with Simple & External/Affiliate product type. You can see <a href="%s" target="_blank">here</a> for more information about product type.', 'learts' ), esc_url( 'https://docs.woocommerce.com/document/managing-products/#product-types' ) ), array(
				'a' => array(
					'href'   => array(),
					'target' => array(),
				),
			) ),
			'default'  => true,
		),

		array(
			'id'     => 'badge_section_end',
			'type'   => 'section',
			'indent' => false,
		),

		// Quick View
		array(
			'id'       => 'quickview_section_start',
			'type'     => 'section',
			'title'    => esc_html__( 'Quick View', 'learts' ),
			'subtitle' => esc_html__( '', 'learts' ),
			'indent'   => true,
		),

		array(
			'id'      => 'shop_quick_view_on',
			'type'    => 'switch',
			'title'   => esc_html__( 'Quick View', 'learts' ),
			'default' => true,
		),

		array(
			'id'       => 'animated_quick_view_on',
			'type'     => 'switch',
			'title'    => esc_html__( 'Animated Quick View', 'learts' ),
			'default'  => true,
			'required' => array(
				array(
					'shop_quick_view_on',
					'=',
					true,
				),
			),
		),

		array(
			'id'     => 'quickview_section_end',
			'type'   => 'section',
			'indent' => false,
		),

		// Buttons
		array(
			'id'       => 'buttons_section_start',
			'type'     => 'section',
			'title'    => esc_html__( 'Buttons', 'learts' ),
			'subtitle' => esc_html__( '', 'learts' ),
			'indent'   => true,
		),

		array(
			'id'       => 'shop_compare_on',
			'type'     => 'switch',
			'title'    => esc_html__( 'Show "Compare" button', 'learts' ),
			'subtitle' => wp_kses( sprintf( __( 'This feature requires <a href="%s" target="_blank">YITH WooCommerce Compare</a> plugin.', 'learts' ), esc_url( 'https://wordpress.org/plugins/yith-woocommerce-compare/' ) ), array(
				'a' => array(
					'href'   => array(),
					'target' => array(),
				),
			) ),
			'default'  => true,
		),

		array(
			'id'       => 'shop_wishlist_on',
			'type'     => 'switch',
			'title'    => esc_html__( 'Show "Wishlist" button', 'learts' ),
			'subtitle' => wp_kses( sprintf( __( 'This feature requires <a href="%s" target="_blank">YITH WooCommerce Wishlist</a> plugin.', 'learts' ), esc_url( 'https://wordpress.org/plugins/yith-woocommerce-wishlist/' ) ), array(
				'a' => array(
					'href'   => array(),
					'target' => array(),
				),
			) ),
			'default'  => true,
		),

		array(
			'id'       => 'animated_wishlist_on',
			'type'     => 'switch',
			'title'    => esc_html__( 'Animated "Wishlist" button', 'learts' ),
			'default'  => true,
			'required' => array(
				array(
					'shop_wishlist_on',
					'=',
					true,
				),
			),
		),

		array(
			'id'     => 'buttons_section_end',
			'type'   => 'section',
			'indent' => false,
		),

		// Notification
		array(
			'id'       => 'notification_section_start',
			'type'     => 'section',
			'title'    => esc_html__( 'Notification', 'learts' ),
			'subtitle' => esc_html__( '', 'learts' ),
			'indent'   => true,
		),

		array(
			'id'       => 'shop_add_to_cart_favico_on',
			'type'     => 'switch',
			'title'    => esc_html__( 'Enable "Add to cart" notification on favicon', 'learts' ),
			'subtitle' => esc_html__( 'Allows you to show number of cart item via favicon like Facebook', 'learts' ),
			'default'  => true,
		),

		array(
			'id'      => 'shop_add_to_cart_notification_on',
			'type'    => 'switch',
			'title'   => esc_html__( 'Enable "Add to cart" notification', 'learts' ),
			'default' => true,
		),

		array(
			'id'       => 'shop_wishlist_notification_on',
			'type'     => 'switch',
			'title'    => esc_html__( 'Enable "Add to wishlist" notification', 'learts' ),
			'subtitle' => wp_kses( sprintf( __( 'This feature requires <a href="%s" target="_blank">YITH WooCommerce Wishlist</a> plugin.', 'learts' ), esc_url( 'https://wordpress.org/plugins/yith-woocommerce-wishlist/' ) ), array(
				'a' => array(
					'href'   => array(),
					'target' => array(),
				),
			) ),
			'default'  => true,
		),

		array(
			'id'     => 'notification_section_end',
			'type'   => 'section',
			'indent' => false,
		),

		//Item Tag
		// Hot
		array(
			'id'       => 'hot_badge_section_start',
			'type'     => 'section',
			'title'    => esc_html__( '\'Hot\' tag', 'learts' ),
			'subtitle' => esc_html__( '', 'learts' ),
			'indent'   => true,
		),
		array(
			'id'       => 'hot_badge_color',
			'type'     => 'link_color',
			'title'    => esc_html__( 'Color', 'learts' ),
			'subtitle' => esc_html__( 'Pick color for the \'Hot\' tag items', 'learts' ),
			'output'   => $learts_selectors['hot_badge_color'],
			'active'   => false,
			'visited'  => false,
			'default'  => array(
				'regular' => '#ffffff',
				'hover'   => '#ffffff',
			),
		),
		array(
			'id'       => 'hot_badge_bgcolor',
			'type'     => 'color',
			'title'    => esc_html__( 'Background Color', 'learts' ),
			'subtitle' => esc_html__( 'Pick background color for the \'Hot\' tag items', 'learts' ),
			'output'   => $learts_selectors['hot_badge_bgcolor'],
			'default'  => '#c61932'
		),
		array(
			'id'     => 'hot_badge_section_end',
			'type'   => 'section',
			'indent' => false,
		),

		// New
		array(
			'id'       => 'new_badge_section_start',
			'type'     => 'section',
			'title'    => esc_html__( '\'New\' tag', 'learts' ),
			'subtitle' => esc_html__( '', 'learts' ),
			'indent'   => true,
		),
		array(
			'id'       => 'new_badge_color',
			'type'     => 'link_color',
			'title'    => esc_html__( 'Color', 'learts' ),
			'subtitle' => esc_html__( 'Pick color for the \'New\' tag items', 'learts' ),
			'output'   => $learts_selectors['new_badge_color'],
			'active'   => false,
			'visited'  => false,
			'default'  => array(
				'regular' => '#ffffff',
				'hover'   => '#ffffff',
			),
		),
		array(
			'id'       => 'new_badge_bgcolor',
			'type'     => 'color',
			'title'    => esc_html__( 'Background Color', 'learts' ),
			'subtitle' => esc_html__( 'Pick background color for the \'New\' tag items', 'learts' ),
			'output'   => $learts_selectors['new_badge_bgcolor'],
			'default'  => '#fbaf5d'
		),
		array(
			'id'     => 'new_badge_section_end',
			'type'   => 'section',
			'indent' => false,
		),

		// Sale
		array(
			'id'       => 'sale_badge_section_start',
			'type'     => 'section',
			'title'    => esc_html__( '\'Sale\' tag', 'learts' ),
			'subtitle' => esc_html__( '', 'learts' ),
			'indent'   => true,
		),
		array(
			'id'       => 'sale_badge_color',
			'type'     => 'link_color',
			'title'    => esc_html__( 'Color', 'learts' ),
			'subtitle' => esc_html__( 'Pick color for the \'Sale\' tag items', 'learts' ),
			'output'   => $learts_selectors['sale_badge_color'],
			'active'   => false,
			'visited'  => false,
			'default'  => array(
				'regular' => '#ffffff',
				'hover'   => '#ffffff',
			),
		),
		array(
			'id'       => 'sale_badge_bgcolor',
			'type'     => 'color',
			'title'    => esc_html__( 'Background Color', 'learts' ),
			'subtitle' => esc_html__( 'Pick background color for the \'Sale\' tag items', 'learts' ),
			'output'   => $learts_selectors['sale_badge_bgcolor'],
			'default'  => '#4accb0'
		),
		array(
			'id'     => 'sale_badge_section_end',
			'type'   => 'section',
			'indent' => false,
		),
	),
) );
