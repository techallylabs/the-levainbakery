<?php

Redux::setSection( learts_Redux::$opt_name,
	array(
		'title'  => esc_html__( 'Page Title', 'learts' ),
		'id'     => 'panel_page_title',
		'icon'   => 'fa fa-credit-card',
		'fields' => array(

			array(
				'id'       => 'page_title_on',
				'type'     => 'switch',
				'title'    => esc_html__( 'Page Title', 'learts' ),
				'subtitle' => esc_html__( 'Enabling this option will display the page title area.', 'learts' ),
				'default'  => true,
			),

			array(
				'id'       => 'page_meta',
				'type'     => 'switch',
				'title'    => esc_html__( 'Page Meta', 'learts' ),
				'subtitle' => esc_html__( 'Turn on to display post meta on pages (only works in pages)', 'learts' ),
				'default'  => false,
			),


			array(
				'id'      => 'disable_parallax',
				'type'    => 'switch',
				'title'   => esc_html__( 'Disable Parallax effect', 'learts' ),
				'default' => false,
			),

			array(
				'id'       => 'remove_whitespace',
				'type'     => 'switch',
				'title'    => esc_html__( 'Remove whitespace', 'learts' ),
				'subtitle' => esc_html__( 'Remove the whitespace below the header when the Page Title is turned off',
					'learts' ),
				'default'  => true,
				'required' => array(
					'page_title_on',
					'=',
					false,
				),
			),

			array(
				'id'      => 'page_title_style',
				'type'    => 'button_set',
				'title'   => esc_html__( 'Style', 'learts' ),
				'options' => array(
					'bg_color' => esc_html__( 'Background Color', 'learts' ),
					'bg_image' => esc_html__( 'Background Image', 'learts' ),
				),
				'default' => 'bg_image',
			),

			array(
				'id'      => 'page_title_height',
				'type'    => 'slider',
				'title'   => esc_html__( 'Page Title Height (in pixels)', 'learts' ),
				'default' => 380,
				'min'     => 100,
				'max'     => 500,
				'step'    => 10,
			),

			array(
				'id'          => 'page_title_text_color',
				'type'        => 'color',
				'transparent' => false,
				'title'       => esc_html__( 'Text Color', 'learts' ),
				'output'      => $learts_selectors['page_title_text_color'],
				'default'     => '#333333',
			),

			array(
				'id'          => 'page_subtitle_color',
				'type'        => 'color',
				'transparent' => false,
				'title'       => esc_html__( 'Subtitle Color', 'learts' ),
				'output'      => $learts_selectors['page_subtitle_color'],
				'default'     => '#ababab',
			),

			array(
				'id'          => 'page_title_bg_color',
				'type'        => 'color',
				'transparent' => false,
				'title'       => esc_html__( 'Background Color', 'learts' ),
				'output'      => $learts_selectors['page_title_bg_color'],
				'default'     => '#f7f7f7',
				'required'    => array(
					'page_title_style',
					'=',
					'bg_color',
				),
			),

			array(
				'id'       => 'page_title_overlay_color',
				'type'     => 'color_rgba',
				'title'    => esc_html__( 'Overlay Color', 'learts' ),
				'output'   => $learts_selectors['page_title_overlay_color'],
				'default'  => array(
					'color' => '#000000',
					'alpha' => 0.01,
				),
				'required' => array(
					'page_title_style',
					'=',
					'bg_image',
				),
			),

			array(
				'id'                    => 'page_title_bg_image',
				'type'                  => 'background',
				'title'                 => esc_html__( 'Background Image', 'learts' ),
				'background-color'      => false,
				'background-repeat'     => false,
				'background-attachment' => false,
				'background-position'   => false,
				'background-size'       => false,
				'default'               => array(
					'background-image' => LEARTS_IMAGES . '/page-title-bg.jpg',
				),
				'output'                => $learts_selectors['page_title_bg_image'],
				'required'              => array(
					'page_title_style',
					'=',
					'bg_image',
				),
			),
		),
	) );
