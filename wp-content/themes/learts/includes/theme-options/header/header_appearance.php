<?php

Redux::setSection( learts_Redux::$opt_name,
	array(
		'title'      => esc_html__( 'Header Appearance', 'learts' ),
		'id'         => 'section_header_appearance',
		'subsection' => true,
		'fields'     => array(
			array(
				'id'       => 'header_white',
				'type'     => 'switch',
				'title'    => esc_html__( 'Header White mode', 'learts' ),
				'subtitle' => esc_html__( 'Make everything (such as text, icon, menu, etc...) on the header to white color. Only works when the "Header above the content" option (in Header > Header Layout) is enabled.',
					'learts' ),
				'default'  => false,
				'required' => array(
					array( 'header', '!=', 'menu-bottom' ),
				),
			),
			array(
				'id'       => 'header_bgcolor',
				'type'     => 'color',
				'title'    => esc_html__( 'Background Color', 'learts' ),
				'subtitle' => esc_html__( 'Pick a background color for the header', 'learts' ),
				'output'   => $learts_selectors['header_bgcolor'],
				'default'  => '#ffffff',
			),

			array(
				'id'       => 'header_bdcolor',
				'type'     => 'color',
				'title'    => esc_html__( 'Border Color', 'learts' ),
				'subtitle' => esc_html__( 'Pick a border color for the header', 'learts' ),
				'output'   => $learts_selectors['header_bdcolor'],
				'validate' => 'color',
				'default'  => 'transparent',
				'required' => array(
					array( 'header', '!=', 'vertical' ),
				),
			),

			array(
				'id'      => 'header_scheme',
				'type'    => 'button_set',
				'title'   => esc_html__( 'Header color scheme', 'learts' ),
				'options' => array(
					'dark'   => esc_html__( 'Dark', 'learts' ),
					'light'  => esc_html__( 'Light', 'learts' ),
				),
				'default' => 'light',
			),
		),
	) );
