<?php

Redux::setSection( learts_Redux::$opt_name,
	array(
		'title'  => esc_html__( 'Footer', 'learts' ),
		'id'     => 'panel_footer',
		'icon'   => 'fa fa-angle-double-down ',
		'fields' => array(

			array(
				'id'       => 'footer_layout',
				'type'     => 'image_select',
				'title'    => esc_html__( 'Footer columns', 'learts' ),
				'subtitle' => esc_html__( 'Choose number of columns to display in footer area', 'learts' ),
				'desc'     => wp_kses( sprintf( __( 'Note: Each column represents one Footer Sidebar in <a href="%s">Appearance -> Widgets</a> settings.',
					'learts' ),
					admin_url( 'widgets.php' ) ),
					wp_kses_allowed_html( 'post' ) ),
				'options'  => array(
					'1_12' => array(
						'title' => esc_html__( '1 Column', 'learts' ),
						'img'   => get_template_directory_uri() . '/assets/admin/images/footer_col_1.png',
					),
					'2_6'  => array(
						'title' => esc_html__( '2 Columns', 'learts' ),
						'img'   => get_template_directory_uri() . '/assets/admin/images/footer_col_2.png',
					),
					'3_4'  => array(
						'title' => esc_html__( '3 Columns', 'learts' ),
						'img'   => get_template_directory_uri() . '/assets/admin/images/footer_col_3.png',
					),
					'4_3'  => array(
						'title' => esc_html__( '4 Columns', 'learts' ),
						'img'   => get_template_directory_uri() . '/assets/admin/images/footer_col_4.png',
					),
				),
				'default'  => '2_6',
			),

			array(
				'id'      => 'footer_width',
				'type'    => 'button_set',
				'title'   => esc_html__( 'Footer Width', 'learts' ),
				'options' => array(
					'standard' => esc_html__( 'Standard', 'learts' ),
					'wide'     => esc_html__( 'Wide', 'learts' ),

				),
				'default' => 'standard',
			),

			array(
				'id'      => 'footer_color_scheme',
				'type'    => 'button_set',
				'title'   => esc_html__( 'Footer Color Scheme', 'learts' ),
				'options' => array(
					'light'  => esc_html__( 'Light', 'learts' ),
					'dark'   => esc_html__( 'Dark', 'learts' ),
					'custom' => esc_html__( 'Custom', 'learts' ),
				),
				'default' => 'light',
			),

			array(
				'id'      => 'footer-bg-color',
				'type'    => 'color',
				'title'   => esc_html__( 'Background color', 'learts' ),
				'output'  => $learts_selectors['bg_color_footer'],
				'default' => '#f8f8f8',

				'required'    => array(
					'footer_color_scheme',
					'=',
					'custom',
				),
			),
		),
	) );

require_once LEARTS_OPTIONS_DIR . DS . 'footer' . DS . 'footer_copyright.php';
