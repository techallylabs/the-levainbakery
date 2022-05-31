<?php

Redux::setSection( learts_Redux::$opt_name,
	array(
		'title'      => esc_html__( 'Top Bar', 'learts' ),
		'id'         => 'section_topbar',
		'subsection' => true,
		'fields'     => array(
			array(
				'id'       => 'header_topbar_column_notice',
				'type'     => 'info',
				'style'    => 'warning',
				'title'    => esc_html__( 'Note', 'learts' ),
				'desc'     => esc_html__( 'Does not work with the Vertical Header', 'learts' ),
				'required' => array(
					array( 'header', '=', 'vertical' ),
				),
			),
			array(
				'id'       => 'topbar_on',
				'type'     => 'switch',
				'title'    => esc_html__( 'Top bar', 'learts' ),
				'subtitle' => esc_html__( 'Enabling this option will display top area', 'learts' ),
				'default'  => false,
			),
			array(
				'id'       => 'topbar',
				'type'     => 'image_select',
				'title'    => esc_html__( 'Topbar Layout', 'learts' ),
				'subtitle' => esc_html__( 'Select your topbar layout', 'learts' ),
				'options'  => array(
					'switchers-left'      => array(
						'title' => esc_html__( 'Switchers on the left, social links on the right', 'learts' ),
						'img'   => LEARTS_ADMIN_IMAGES . DS . 'topbar-switcher-left.png',
					),
					'switchers-right'     => array(
						'title' => esc_html__( 'Switchers on the right, social links on the left', 'learts' ),
						'img'   => LEARTS_ADMIN_IMAGES . DS . 'topbar-switcher-right.png',
					),
					'only-text'           => array(
						'title' => esc_html__( 'The topbar has only text',
							'learts' ),
						'img'   => LEARTS_ADMIN_IMAGES . DS . 'topbar-only-text.png',
					),
					'text-menu-switchers' => array(
						'title' => esc_html__( 'The text align left, the menu topbar and switchers align right',
							'learts' ),
						'img'   => LEARTS_ADMIN_IMAGES . DS . 'text-menu-switchers.png',
					),
					'menu-text-switchers' => array(
						'title' => esc_html__( 'The text align center, the menu topbar align left and switchers align right',
							'learts' ),
						'img'   => LEARTS_ADMIN_IMAGES . DS . 'menu-text-switchers.png',
					),
				),
				'default'  => 'switchers-left',
			),
			array(
				'id'       => 'topbar_can_close',
				'type'     => 'switch',
				'title'    => esc_html__( 'Top bar can be close', 'learts' ),
				'subtitle' => esc_html__( 'Enabling this option if you want to show a close button on the top bar',
					'learts' ),
				'default'  => true,
				'required' => array(
					array( 'topbar', '==', array( 'only-text' ) ),
				),
			),
			array(
				'id'       => 'topbar_text',
				'type'     => 'textarea',
				'title'    => esc_html__( 'Text in the top bar', 'learts' ),
				'subtitle' => esc_html__( 'Insert the text you want to see in the top bar here. You can use HTML or shortcodes',
					'learts' ),
				'args'     => array(
					'textarea_rows' => 3,
				),
				'default'  => 'Order Online Call Us <a href="tel:0123456789">(0123) 456789</a>',
			),
			array(
				'id'            => 'topbar_height',
				'type'          => 'slider',
				'title'         => esc_html__( 'Top bar height', 'learts' ),
				'default'       => 44,
				'min'           => 30,
				'step'          => 1,
				'max'           => 60,
				'display_value' => 'label',
			),
			array(
				'id'      => 'topbar_width',
				'type'    => 'button_set',
				'title'   => esc_html__( 'Top bar width', 'learts' ),
				'options' => array(
					'standard'         => esc_html__( 'Standard', 'learts' ),
					'wide'             => esc_html__( 'Wide', 'learts' ),
					'full'             => esc_html__( 'Full width', 'learts' ),
					'full-no-paddings' => esc_html__( 'Full width (no paddings)', 'learts' ),

				),
				'default' => 'wide',
			),
			array(
				'id'       => 'topbar_social',
				'type'     => 'switch',
				'title'    => esc_html__( 'Social links', 'learts' ),
				'subtitle' => esc_html__( 'Enable / Disable the social links on the top bar', 'learts' ),
				'default'  => true,
				'required' => array(
					array( 'topbar', '!=', array( 'only-text' ) ),
				),
			),
			array(
				'id'       => 'topbar_menu',
				'type'     => 'switch',
				'title'    => esc_html__( 'Menu', 'learts' ),
				'subtitle' => esc_html__( 'Enable / Disable the top bar menu', 'learts' ),
				'default'  => true,
				'required' => array(
					array( 'topbar', '!=', array( 'only-text' ) ),
				),
			),
			array(
				'id'       => 'topbar_logged_in_menu',
				'type'     => 'select',
				'title'    => esc_html__( 'Top bar menu (for logged-in users)', 'learts' ),
				'subtitle' => esc_html__( 'Select a menu to display in the top bar for logged-in users', 'learts' ),
				'options'  => Learts_Helper::get_all_menus(),
				'default'  => 'none',
				'required' => array(
					array( 'topbar_menu', '=', array( true ) ),
					array( 'topbar', '!=', array( 'only-text' ) ),
				),
			),

			array(
				'id'      => 'topbar_scheme',
				'type'    => 'button_set',
				'title'   => esc_html__( 'Top bar color scheme', 'learts' ),
				'options' => array(
					'dark'   => esc_html__( 'Dark', 'learts' ),
					'light'  => esc_html__( 'Light', 'learts' ),
					'custom' => esc_html__( 'Custom', 'learts' ),

				),
				'default' => 'light',
			),
			array(
				'id'       => 'topbar_bgcolor',
				'type'     => 'color',
				'title'    => esc_html__( 'Top bar Color', 'learts' ),
				'subtitle' => esc_html__( 'Pick a background color for the top bar', 'learts' ),
				'output'   => $learts_selectors['topbar_bgcolor'],
				'default'  => '#72A499',
				'required' => array(
					array( 'topbar_scheme', '=', array( 'custom' ) ),
				),
			),
			array(
				'id'       => 'topbar_text_color',
				'type'     => 'color',
				'title'    => esc_html__( 'Text Color', 'learts' ),
				'subtitle' => esc_html__( 'Pick a color for the text in top bar', 'learts' ),
				'output'   => $learts_selectors['topbar_text_color'],
				'default'  => '#ffffff',
				'required' => array(
					array( 'topbar_scheme', '=', array( 'custom' ) ),
				),
			),
			array(
				'id'       => 'topbar_language_switcher_on',
				'type'     => 'switch',
				'title'    => esc_html__( 'Language Switcher', 'learts' ),
				'subtitle' => wp_kses( sprintf( __( 'Enable / Disable the Language Switcher in the top bar instead of the Language Menu. This feature requires <a href="%s" target="_blank">WPML</a> or <a href="%s" target="_blank">Polylang</a> plugin.',
					'learts' ),
					esc_url( 'https://wpml.org/' ),
					esc_url( 'https://wordpress.org/plugins/polylang/' ) ),
					array(
						'a' => array(
							'href'   => array(),
							'target' => array(),
						),
					) ),
				'desc'     => esc_html__( 'The switchers was customized to compatible with our theme', 'learts' ),
				'default'  => false,
				'required' => array(
					array( 'topbar', '!=', array( 'only-text' ) ),
				),
			),

			array(
				'id'       => 'topbar_currency_switcher_on',
				'type'     => 'switch',
				'title'    => esc_html__( 'Currency Switcher', 'learts' ),
				'subtitle' => wp_kses( sprintf( __( 'Enable / Disable the Currency Switcher in the top bar instead of the Currency Menu. This feature requires <a href="%s" target="_blank">WooCommerce Multilingual</a> or <a href="%s" target="_blank">WooCommerce Currency Switcher</a> plugin.',
					'learts' ),
					esc_url( 'https://wordpress.org/plugins/woocommerce-multilingual/' ),
					esc_url( 'https://wordpress.org/plugins/woocommerce-currency-switcher/' ) ),
					array(
						'a' => array(
							'href'   => array(),
							'target' => array(),
						),
					) ),
				'desc'     => esc_html__( 'The switchers was customized to compatible with our theme', 'learts' ),
				'default'  => false,
				'required' => array(
					array( 'topbar', '!=', array( 'only-text' ) ),
				),
			),
		),
	) );
