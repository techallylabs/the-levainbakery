<?php

Redux::setSection( learts_Redux::$opt_name,
	array(
		'title'      => esc_html__( 'Main Menu', 'learts' ),
		'id'         => 'section_site_menu',
		'subsection' => true,
		'fields'     => array(

			array(
				'id'       => 'site_menu_align',
				'type'     => 'button_set',
				'title'    => esc_html__( 'Main menu align', 'learts' ),
				'subtitle' => esc_html__( 'Set the text align for the main menu of your site', 'learts' ),
				'options'  => array(
					'left'   => esc_html__( 'Left', 'learts' ),
					'center' => esc_html__( 'Center', 'learts' ),
					'right'  => esc_html__( 'Right', 'learts' ),
				),
				'default'  => 'center',
				'required' => array(
					array( 'header', '=', 'base' ),
				),
			),

			array(
				'id'       => 'site_menu_style',
				'type'     => 'image_select',
				'title'    => esc_html__( 'Menu Style', 'learts' ),
				'options'  => array(
					'base'      => array(
						'title' => esc_html__( 'Base', 'learts' ),
						'img'   => LEARTS_ADMIN_IMAGES . DS . 'menu-base.png',
					),
					'separator' => array(
						'title' => esc_html__( 'With separator', 'learts' ),
						'img'   => LEARTS_ADMIN_IMAGES . DS . 'menu-separator.png',
					),
				),
				'default'  => 'base',
				'required' => array(
					array( 'header', '=', 'base' ),
				),
			),

			array(
				'id'       => 'site_menu_items_color',
				'type'     => 'link_color',
				'title'    => esc_html__( 'Menu Item Color', 'learts' ),
				'subtitle' => esc_html__( 'Pick color for the menu items', 'learts' ),
				'output'   => $learts_selectors['site_menu_items_color'],
				'active'   => false,
				'visited'  => false,
				'default'  => array(
					'regular' => '#7E7E7E',
					'hover'   => PRIMARY_COLOR,
				),
			),

			array(
				'id'       => 'site_menu_subitems_color',
				'type'     => 'link_color',
				'title'    => esc_html__( 'Menu Sub Item Color', 'learts' ),
				'subtitle' => esc_html__( 'Pick color for the menu sub items', 'learts' ),
				'output'   => $learts_selectors['site_menu_subitems_color'],
				'active'   => false,
				'visited'  => false,
				'default'  => array(
					'regular' => '#7E7E7E',
					'hover'   => PRIMARY_COLOR,
				),
			),

			array(
				'id'       => 'site_menu_bgcolor',
				'type'     => 'color',
				'title'    => esc_html__( 'Menu Background Color', 'learts' ),
				'subtitle' => esc_html__( 'Only works with Menu Bottom Header', 'learts' ),
				'output'   => $learts_selectors['site_menu_bgcolor'],
				'active'   => false,
				'visited'  => false,
				'default'  => '#ffffff',
				'required' => array(
					array( 'header', '=', array( 'menu-bottom' ) ),
				),
			),

			array(
				'id'       => 'site_menu_bdcolor',
				'type'     => 'color',
				'title'    => esc_html__( 'Menu Border Color', 'learts' ),
				'subtitle' => esc_html__( 'Only works with Menu Bottom Header', 'learts' ),
				'output'   => $learts_selectors['site_menu_bdcolor'],
				'active'   => false,
				'visited'  => false,
				'default'  => '#eeeeee',
				'required' => array(
					array( 'header', '=', array( 'menu-bottom' ) ),
				),
			),
		),
	) );
