<?php

Redux::setSection( learts_Redux::$opt_name,
	array(
		'title'      => esc_html__( 'Header Left Column', 'learts' ),
		'id'         => 'section_header_left_column',
		'subsection' => true,
		'fields'     => array(
			
			array(
				'id'       => 'header_left_column_notice',
				'type'     => 'info',
				'style'    => 'success',
			),

			array(
				'id'       => 'header_left_column_content',
				'type'     => 'image_select',
				'title'    => esc_html__( 'Header Left Column Content', 'learts' ),
				'subtitle' => esc_html__( 'Select the content is displayed in the header left column layout. In menu bottom header #2, they are used as widgets on the right ',
					'learts' ),
				'options'  => array(
					'none' => array(
						'title' => esc_html__( 'None', 'learts' ),
						'img'   => LEARTS_ADMIN_IMAGES . DS . 'none.png',
					),
					'switchers' => array(
						'title' => esc_html__( 'Switchers', 'learts' ),
						'img'   => LEARTS_ADMIN_IMAGES . DS . 'left-col-switchers.png',
					),
					'social'    => array(
						'title' => esc_html__( 'Social Links', 'learts' ),
						'img'   => LEARTS_ADMIN_IMAGES . DS . 'left-col-social.png',
					),
					'widget'    => array(
						'title' => esc_html__( 'Widget', 'learts' ),
						'img'   => LEARTS_ADMIN_IMAGES . DS . 'left-col-widget.png',
					),
					'search'    => array(
						'title' => esc_html__( 'Search Widget', 'learts' ),
						'img'   => LEARTS_ADMIN_IMAGES . DS . 'search-widget.png',
					),
				),
				'default'  => 'widget',
			),

			array(
				'id'       => 'header_left_sidebar',
				'type'     => 'select',
				'title'    => esc_html__( 'Header Left Sidebar', 'learts' ),
				'data'     => 'sidebars',
				'default'  => 'sidebar-header-left',
				'required' => array(
					'header_left_column_content',
					'=',
					'widget',
				),
			),
		),
	) );
