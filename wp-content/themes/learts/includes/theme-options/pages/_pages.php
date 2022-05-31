<?php

Redux::setSection( learts_Redux::$opt_name,
	array(
		'title'  => __( 'Pages', 'learts' ),
		'id'     => 'panel_pages',
		'icon'   => 'fa fa-file-text-o',
		'fields' => array(

			array(
				'id'       => 'page_sidebar_config',
				'type'     => 'image_select',
				'title'    => esc_html__( 'Page Sidebar Position', 'learts' ),
				'subtitle' => esc_html__( 'Controls the position of sidebars for page.', 'learts' ),
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
				'id'       => 'search_sidebar_config',
				'type'     => 'image_select',
				'title'    => esc_html__( 'Search Sidebar Position', 'learts' ),
				'subtitle' => esc_html__( 'Controls the position of sidebars for search result page.', 'learts' ),
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
				'default'  => 'right',
			),

			array(
				'id'       => 'search_sidebar',
				'type'     => 'select',
				'title'    => esc_html__( 'Search Sidebar', 'learts' ),
				'subtitle' => esc_html__( 'Choose the sidebar for search result page.', 'learts' ),
				'data'     => 'sidebars',
				'default'  => 'sidebar',
				'required' => array(
					array( 'search_sidebar_config', '!=', 'no' ),
				),
			),

			array(
				'id'       => '404_bg',
				'type'     => 'background',
				'title'    => esc_html__( '404 Background', 'learts' ),
				'subtitle' => esc_html__( 'Set background image or color for 404 page.', 'learts' ),
				'output'   => array( '.area-404' ),
				'default'  => array(
					'background-image'      => LEARTS_IMAGES . DS . '404-bg.jpg',
					'background-repeat'     => 'no-repeat',
					'background-size'       => 'cover',
					'background-attachment' => 'inherit',
					'background-position'   => 'center center',
				),
			),

		),
	) );
