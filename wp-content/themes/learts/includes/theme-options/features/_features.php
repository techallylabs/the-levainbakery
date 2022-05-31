<?php

Redux::setSection( learts_Redux::$opt_name,
	array(
		'title'  => esc_html__( 'Theme Features', 'learts' ),
		'id'     => 'panel_features',
		'icon'   => 'fa fa-puzzle-piece',
		'fields' => array(

			array(
				'id'       => 'coming_soon_mode_on',
				'type'     => 'switch',
				'title'    => esc_html__( 'Coming Soon Mode', 'learts' ),
				'subtitle' => wp_kses( sprintf( __( 'Work on your site in private while visitors see a “Coming Soon” or “Maintenance Mode” page. After enabling this feature, please select a page and choose Page Template as Coming Soon, like <a href="%s" target="_blank">this image</a>.',
					'learts' ),
					LEARTS_ADMIN_IMAGES . '/page-template-coming-soon.png' ),
					array(
						'a' => array(
							'href'   => array(),
							'target' => array(),
						),
					) ),
				'default'  => false,
			),

			array(
				'id'       => 'box_mode_on',
				'type'     => 'switch',
				'title'    => esc_html__( 'Box Mode', 'learts' ),
				'subtitle' => esc_html__( 'Enabling this option then your site will switch to box mode', 'learts' ),
				'default'  => false,
			),

			array(
				'id'       => 'site_bg',
				'type'     => 'background',
				'title'    => esc_html__( 'Site Background', 'learts' ),
				'subtitle' => esc_html__( 'Set background image or color for body. Only for boxed layout',
					'learts' ),
				'output'   => 'body',
				'default'  => array(
					'background-color' => '#eee',
				),
				'required' => array(
					array( 'box_mode_on', '=', true ),
				),
			),

			array(
				'id'       => 'back_to_top_on',
				'type'     => 'switch',
				'title'    => esc_html__( 'Back to top', 'learts' ),
				'subtitle' => esc_html__( 'Activating the back to top anchor will output a link that appears in the bottom corner of your site for users to click on that will return them to the top of your website.',
					'learts' ),
				'default'  => true,
			),

			array(
				'id'       => 'effect_snow_fall',
				'type'     => 'switch',
				'title'    => esc_html__( 'Falling Snow Effect', 'learts' ),
				'subtitle' => esc_html__('Let it snow on your website. Enjoy this holy season with a wonderful falling snow effect', 'learts'),
				'default'  => false,
			),

			array(
				'id'      => 'snow_image',
				'type'    => 'media',
				'title'   => esc_html__( 'Snow image', 'learts' ),
				'desc'    => esc_html__( 'Upload image for snow falling: png, jpg or gif file.', 'learts' ),
				'default' => array(
					'url' => LEARTS_IMAGES . '/flake.png',
				),
				'required' => array(
					array( 'effect_snow_fall', '=', true ),
				),
			),

			array(
				'id'       => 'single_nav_on',
				'type'     => 'switch',
				'title'    => esc_html__( 'Single Post / Product Navigation', 'learts' ),
				'subtitle' => esc_html__( 'Enabling this option will display the post navigation on single post or single product page.', 'learts' ),
				'default'  => true,
				'required'    => array(
					'breadcrumbs',
					'=',
					true,
				),
			),

			array(
				'id'       => 'minified_on',
				'type'     => 'switch',
				'title'    => esc_html__( 'Load Minified Scripts', 'learts' ),
				'subtitle' => esc_html__( 'Loading *.css & *.js files that have been minified will help you speed up the page load.', 'learts' ),
				'default'  => true,
			),
		),
	) );
