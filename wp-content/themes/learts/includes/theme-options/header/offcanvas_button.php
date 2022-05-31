<?php

Redux::setSection( learts_Redux::$opt_name,
	array(
		'title'      => esc_html__( 'Off-Canvas Sidebar', 'learts' ),
		'id'         => 'section_off_canvas',
		'subsection' => true,
		'fields'     => array(
			array(
				'id'       => 'header_offcanvas_column_notice',
				'type'     => 'info',
				'style'    => 'warning',
				'title'    => esc_html__( 'Note', 'learts' ),
				'desc'     => esc_html__( 'Does not work with the Vertical Header', 'learts' ),
				'required' => array(
					array( 'header', '=', 'vertical' ),
				),
			),
			array(
				'id'       => 'offcanvas_button_on',
				'type'     => 'switch',
				'title'    => esc_html__( 'Off-canvas Button', 'learts' ),
				'subtitle' => esc_html__( 'Turn on / off off-canvas button', 'learts' ),
				'default'  => false,
			),
			array(
				'id'      => 'offcanvas_action',
				'type'    => 'button_set',
				'title'   => esc_html__( 'Action', 'learts' ),
				'options' => array(
					'sidebar' => esc_html__( 'Open Sidebar', 'learts' ),
					'menu'    => esc_html__( 'Open Full-screen Menu', 'learts' ),
				),
				'default'  => 'sidebar',
			),
			array(
				'id'       => 'offcanvas_sidebar',
				'type'     => 'select',
				'title'    => esc_html__( 'Select Sidebar', 'learts' ),
				'subtitle' => esc_html__( 'Choose the custom off-canvas sidebar.', 'learts' ),
				'data'     => 'sidebars',
				'default'  => 'sidebar-offcanvas',
				'required' => array(
					array( 'offcanvas_action', '=', array( 'sidebar' ) ),
				),
			),
			array(
				'id'       => 'offcanvas_notice',
				'type'     => 'info',
				'style'    => 'warning',
				'title'    => esc_html__( 'Note', 'learts' ),
				'desc'     => __( 'Note: Please add a menu to the Full-screen Menu location on Appearance >> Menus page', 'learts' ),
				'required' => array(
					array( 'offcanvas_action', '=', array( 'menu' ) ),
				),
			),
			array(
				'id'      => 'offcanvas_position',
				'type'    => 'button_set',
				'title'   => esc_html__( 'Position', 'learts' ),
				'options' => array(
					'left'  => esc_html__( 'Left', 'learts' ),
					'right' => esc_html__( 'Right', 'learts' ),
				),
				'default' => 'left',
			),
			array(
				'id'          => 'offcanvas_button_color',
				'type'        => 'color',
				'transparent' => false,
				'title'       => esc_html__( 'Off-canvas button color', 'learts' ),
				'output'      => $learts_selectors['offcanvas_button_color'],
				'validate'    => 'color',
				'default'     => PRIMARY_COLOR,
			),
		),
	) );
