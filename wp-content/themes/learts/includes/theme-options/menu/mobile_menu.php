<?php

Redux::setSection( learts_Redux::$opt_name, array(
	'title'      => esc_html__( 'Mobile Menu', 'learts' ),
	'id'         => 'section_mobile_menu',
	'subsection' => true,
	'fields'     => array(
		array(
			'id'       => 'mobile_menu_social',
			'type'     => 'switch',
			'title'    => esc_html__( 'Social in the mobile menu', 'learts' ),
			'subtitle' => esc_html__( 'Enable / Disable social in the mobile menu', 'learts' ),
			'default'  => true,
		),
		array(
			'id'          => 'mobile_menu_button_color',
			'type'        => 'color',
			'transparent' => false,
			'title'       => esc_html__( 'Mobile Menu button color', 'learts' ),
			'output'      => $learts_selectors['mobile_menu_button_color'],
			'validate'    => 'color',
			'default'     => '#666666',
		),
	),
) );
