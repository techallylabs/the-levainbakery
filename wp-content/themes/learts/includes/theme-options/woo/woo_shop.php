<?php

Redux::setSection( learts_Redux::$opt_name,
	array(
		'title'      => esc_html__( 'Shop Page', 'learts' ),
		'id'         => 'section_shop',
		'subsection' => true,
		'fields'     => array(

			array(
				'id'       => 'shop_sidebar_config',
				'type'     => 'image_select',
				'title'    => esc_html__( 'Shop sidebar position', 'learts' ),
				'subtitle' => esc_html__( 'Controls the position of sidebars for the shop pages.', 'learts' ),
				'options'  => array(
					'left'  => array(
						'title' => esc_html__( 'Left', 'learts' ),
						'img'   => LEARTS_ADMIN_IMAGES . DS . '2cl.png',
					),
					'no'    => array(
						'title' => esc_html__( 'Disable', 'learts' ),
						'img'   => LEARTS_ADMIN_IMAGES . DS . '1c.png',
					),
					'right' => array(
						'title' => esc_html__( 'Right', 'learts' ),
						'img'   => LEARTS_ADMIN_IMAGES . DS . '2cr.png',
					),
				),
				'default'  => 'no',
			),

			array(
				'id'       => 'shop_sidebar',
				'type'     => 'select',
				'title'    => esc_html__( 'Shop Sidebar', 'learts' ),
				'subtitle' => esc_html__( 'Choose the sidebar for archive pages.', 'learts' ),
				'data'     => 'sidebars',
				'default'  => 'sidebar-shop',
				'required' => array(
					array( 'shop_sidebar_config', '!=', 'no' ),
				),

			),

			array(
				'id'       => 'full_width_shop',
				'type'     => 'button_set',
				'title'    => esc_html__( 'Shop Layout', 'learts' ),
				'subtitle' => esc_html__( 'Set the layout for shop', 'learts' ),
				'options'  => array(
					'basic'      => esc_html__( 'Basic', 'learts' ),
					'full-width' => esc_html__( 'Full width', 'learts' ),
					'no-space'   => esc_html__( 'Full width and no space', 'learts' ),
				),
				'default'  => 'basic',
			),

			array(
				'id'       => 'categories_columns',
				'type'     => 'button_set',
				'title'    => esc_html__( 'Product columns', 'learts' ),
				'subtitle' => esc_html__( 'How many products you want to show per row on the shop page?',
					'learts' ),
				'options'  => array(
					3 => '3',
					4 => '4',
					5 => '5',
				),
				'default'  => 5,
			),

			array(
				'id'      => 'shop_ajax_on',
				'type'    => 'switch',
				'title'   => esc_html__( 'Enable AJAX functionality on shop', 'learts' ),
				'default' => true,
			),

			array(
				'id'       => 'shop_pagination',
				'type'     => 'button_set',
				'title'    => esc_html__( 'Pagination Type', 'learts' ),
				'subtitle' => esc_html__( 'Select pagination type', 'learts' ),
				'options'  => array(
					'default'  => esc_html__( 'Default', 'learts' ),
					'more-btn' => esc_html__( 'Load More Button', 'learts' ),
					'infinite' => esc_html__( 'Infinite Scroll', 'learts' ),
				),
				'default'  => 'default',
				'required' => array(
					array( 'shop_ajax_on', '=', true ),
				),
			),
		),
	) );
