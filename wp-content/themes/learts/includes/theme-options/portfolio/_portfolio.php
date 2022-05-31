<?php

Redux::setSection( learts_Redux::$opt_name,

	array(
		'title'  => esc_html__( 'Portfolio', 'learts' ),
		'id'     => 'panel_portfolio',
		'icon'   => 'fa fa-briefcase',
		'fields' => array(

			array (
				'id'       => 'portfolio_style',
				'type'     => 'image_select',
				'title'    => esc_html__( 'Archive Portfolio Style', 'learts' ),
				'subtitle' => esc_html__( 'Select the display style.', 'learts' ),
				'options'  => array(
					'base'        => array(
						'title' => esc_html__( 'Basic Portfolio', 'learts' ),
						'img'   => LEARTS_ADMIN_IMAGES . DS . 'portfolio-1.png',
					),
					'inside-image'       => array(
						'title' => esc_html__( 'Inside Image', 'learts' ),
						'img'   => LEARTS_ADMIN_IMAGES . DS . 'portfolio-2.png',
					),
				),
				'default'  => 'base',
			),


			array(
				'id'       => 'portfolio_columns',
				'type'     => 'button_set',
				'title'    => esc_html__( 'Portfolio columns', 'learts' ),
				'subtitle' => esc_html__( 'How many column you want to show per row on the portfolio page?',
					'learts' ),
				'options'  => array(
					3 => '3',
					4 => '4',
					5 => '5',
				),
				'default'  => 3,
			),

			array(
				'id'       => 'portfolio_content_output',
				'type'     => 'button_set',
				'title'    => esc_html__( 'Archive Content Output', 'learts' ),
				'subtitle' => esc_html__( 'Select if you\'d like to output the content or excerpt on portfolio pages.',
					'learts' ),
				'options'  => array(
					'excerpt' => esc_html__( 'Excerpt', 'learts' ),
					'content' => esc_html__( 'Content', 'learts' ),
				),
				'default'  => 'excerpt',
			),

			array(
				'id'                => 'excerpt_length_portfolio',
				'type'              => 'text',
				'title'             => esc_html__( 'Excerpt Length', 'learts' ),
				'subtitle'          => sprintf( esc_html__( 'Controls the number of words of the post excerpt (from 0 to %s words)',
					'learts' ),
					apply_filters( 'learts_max_excerpt_length', 500 ) ),
				'default'           => 30,
				'display_value'     => 'label',
				'validate_callback' => 'learts_validate_excerpt_callback',
				'required'          => array(
					'portfolio_content_output',
					'=',
					'excerpt',
				),
			),

			array(
				'id'       => 'portfolio_pagination',
				'type'     => 'button_set',
				'title'    => esc_html__( 'Pagination Type', 'learts' ),
				'subtitle' => esc_html__( 'Select pagination type', 'learts' ),
				'options'  => array(
					'default'  => esc_html__( 'Default', 'learts' ),
					'more-btn' => esc_html__( 'Load More Button', 'learts' ),
					'infinite' => esc_html__( 'Infinite Scroll', 'learts' ),
				),
				'default'  => 'default',
			),


		),
	) );
