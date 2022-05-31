<?php

Redux::setSection( learts_Redux::$opt_name, array(
	'title'  => esc_html__( 'Social', 'learts' ),
	'id'     => 'panel_social',
	'icon'   => 'fa fa-share-alt',
	'fields' => array(
		array(
			'id'       => 'tooltip',
			'type'     => 'switch',
			'title'    => esc_html__( 'Display tooltip', 'learts' ),
			'subtitle' => esc_html__( 'Enabling tooltip for the social icons when hover.', 'learts' ),
			'default'  => true,
		),

		array(
			'id'       => 'social_open_in_new_tab',
			'type'     => 'switch',
			'title'    => esc_html__( 'Open link in new tab', 'learts' ),
			'default'  => false,
		),

		array
		(
			'id'         => 'social_links',
			'type'       => 'repeater',
			'title'      => esc_html__( 'Social Items', 'learts' ),
			'item_name'  => esc_html__( 'Items', 'learts' ),
			'bind_title' => 'icon',
			'fields'     => array(

				array(
					'id'      => 'icon',
					'title'   => esc_html__( 'Icon', 'learts' ),
					'type'    => 'select',
					'desc'    => esc_html__( 'Select a social network to automatically add its icon', 'learts' ),
					'options' => Learts_Helper::social_icons(),
					'default' => 'none',
				),

				array(
					'id'          => 'icon_class',
					'title'       => esc_html__( 'Custom Icon', 'learts' ),
					'type'        => 'text',
					'placeholder' => esc_html__( 'Font Awesome Class', 'learts' ),
					'desc'        => wp_kses( sprintf( __( 'Use your custom icon. You can find Font Awesome icon class <a target="_blank" href="%s">here</a>.', 'learts' ), 'https://fontawesome.io/cheatsheet/' ), array(
						'a' => array(
							'href'   => array(),
							'target' => array(),
						),
					) ),
				),

				array(
					'id'          => 'url',
					'type'        => 'text',
					'title'       => esc_html__( 'Link (URL)', 'learts' ),
					'placeholder' => esc_html__( 'https://', 'learts' ),
					'desc'        => esc_html__( 'Add an URL to your social network', 'learts' ),
				),

				array(
					'id'          => 'title',
					'title'       => esc_html__( 'Title', 'learts' ),
					'type'        => 'text',
					'placeholder' => esc_html__( 'Title', 'learts' ),
					'desc'        => esc_html__( 'Insert your custom title here', 'learts' ),
				),

				array(
					'id'    => 'custom_class',
					'title' => esc_html__( 'Custom CSS class', 'learts' ),
					'type'  => 'text',
					'desc'  => esc_html__( 'Insert your custom CSS class here', 'learts' ),
				),
			),
		),
	),
) );
