<?php

Redux::setSection( learts_Redux::$opt_name,
	array(
		'title'      => esc_html__( 'Header Layout', 'learts' ),
		'id'         => 'section_header_layout',
		'subsection' => true,
		'fields'     => array(

			array(
				'id'       => 'header',
				'type'     => 'image_select',
				'title'    => esc_html__( 'Header Type', 'learts' ),
				'subtitle' => esc_html__( 'Select your header layout', 'learts' ),
				'options'  => array(
					'base'        => array(
						'title' => esc_html__( 'Basic Menu Header', 'learts' ),
						'img'   => LEARTS_ADMIN_IMAGES . DS . 'header-base.jpg',
					),
					'split'       => array(
						'title' => esc_html__( 'Split Header (logo in center of the menu)', 'learts' ),
						'img'   => LEARTS_ADMIN_IMAGES . DS . 'header-split.jpg',
					),
					'menu-bottom' => array(
						'title' => esc_html__( 'Menu Bottom Header #1', 'learts' ),
						'img'   => LEARTS_ADMIN_IMAGES . DS . 'header-menu-bottom.jpg',
					),
					'sub-menu-bottom' => array(
						'title' => esc_html__( 'Menu Bottom Header #2 ', 'learts' ),
						'img'   => LEARTS_ADMIN_IMAGES . DS . 'header-menu-bottom-2.jpeg',
					),
					'vertical-full'    => array(
						'title' => esc_html__( 'Vertical Full Menu Header', 'learts' ),
						'img'   => LEARTS_ADMIN_IMAGES . DS . 'header-vertical-full.jpg',
					),
				),
				'default'  => 'base',
			),

			array(
				'id'       => 'sticky_header',
				'type'     => 'switch',
				'title'    => esc_html__( 'Sticky Header', 'learts' ),
				'subtitle' => esc_html__( 'Enable / Disable sticky header option', 'learts' ),
				'default'  => true,
				'required' => array(
					array( 'header', '=', array( 'base', 'split', 'menu-bottom','sub-menu-bottom' ) ),
				),
			),

			array(
				'id'       => 'header_overlap',
				'type'     => 'switch',
				'title'    => esc_html__( 'Header above the content', 'learts' ),
				'default'  => false,
				'required' => array(
					array( 'header', '=', array( 'base', 'split' ) ),
				),
			),

			array(
				'id'       => 'header_layout_notice',
				'type'     => 'info',
				'style'    => 'warning',
				'title'    => esc_html__( 'Note', 'learts' ),
				'desc'     => __( 'Note: The width of the Split Header should be Wide or Full width',
					'learts' ),
				'required' => array(
					array( 'header', '=', 'split' ),
					array( 'header_width', '=', array( 'header_width', 'standard' ) ),
				),
			),

			array(
				'id'       => 'header_width',
				'type'     => 'button_set',
				'title'    => esc_html__( 'Header Width', 'learts' ),
				'options'  => array(
					'standard'         => esc_html__( 'Standard', 'learts' ),
					'wide'             => esc_html__( 'Wide', 'learts' ),
					'full'             => esc_html__( 'Full-width', 'learts' ),
					'full-no-paddings' => esc_html__( 'Full-width (no paddings)', 'learts' ),
				),
				'default'  => 'wide',
				'required' => array(
					array( 'header', '=', array( 'base', 'split', 'menu-bottom','sub-menu-bottom' ) ),
				),
			),

			array(
				'id'            => 'header_v_width',
				'type'          => 'slider',
				'title'         => esc_html__( 'Header Width', 'learts' ),
				'default'       => 120,
				'min'           => 80,
				'step'          => 1,
				'max'           => 500,
				'display_value' => 'label',
				'required'      => array(
					array( 'header', '=', array( 'vertical', 'vertical-full' ) ),
				),
			),

			array(
				'id'       => 'header_text',
				'type'     => 'textarea',
				'title'    => esc_html__( 'Header Text', 'learts' ),
				'subtitle' => esc_html__( 'Insert the text you want to see in the vertical header here. You can use HTML or shortcodes',
					'learts' ),
				'default'  => 'Copyright &copy; 2018',
				'args'     => array(
					'textarea_rows' => 2
				),
				'required'      => array(
					array( 'header', '=', array( 'vertical', 'vertical-full' ) ),
				),
			),

			array(
				'id'            => 'header_height',
				'type'          => 'slider',
				'title'         => esc_html__( 'Header Height', 'learts' ),
				'default'       => 100,
				'min'           => 60,
				'step'          => 1,
				'max'           => 150,
				'display_value' => 'label',
				'required'      => array(
					array( 'header', '=', array( 'base', 'split', 'menu-bottom','sub-menu-bottom' ) ),
				),
			),
		),
	) );
