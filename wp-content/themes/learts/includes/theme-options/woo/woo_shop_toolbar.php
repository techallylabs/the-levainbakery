<?php

Redux::setSection( learts_Redux::$opt_name,
	array(
		'title'      => esc_html__( 'Shop Toolbar', 'learts' ),
		'id'         => 'section_shop_toolbar',
		'subsection' => true,
		'fields'     => array(

			array(
				'id'       => 'shop_toolbar',
				'type'     => 'switch',
				'title'    => esc_html__( 'Shop Toolbar', 'learts' ),
				'subtitle' => esc_html__( 'Enable shop toolbar on the top of shop pages', 'learts' ),
				'default'  => true,
			),

			array(
				'id'       => 'product_tabs',
				'type'     => 'switch',
				'title'    => esc_html__( 'Product Tabs', 'learts' ),
				'subtitle' => esc_html__( 'Enable products tabs on the left', 'learts' ),
				'default'  => true,
				'required' => array(
					array( 'shop_toolbar', '=', true ),
				),
			),

			array(
				'id'       => 'product_tabs_type',
				'type'     => 'button_set',
				'title'    => esc_html__( 'Product Tabs Type', 'learts' ),
				'subtitle' => esc_html__( 'Select how to group products in tabs', 'learts' ),
				'options'  => array(
					'groups'     => esc_html__( 'Groups', 'learts' ),
					'categories' => esc_html__( 'Categories', 'learts' ),
					'tags'       => esc_html__( 'Tags', 'learts' ),
				),
				'default'  => 'groups',
				'required' => array(
					array( 'shop_toolbar', '=', true ),
					array( 'product_tabs', '=', true ),
				),
			),

			array(
				'id'       => 'product_tabs_groups',
				'type'     => 'checkbox',
				'title'    => esc_html__( 'Product Tabs Groups', 'learts' ),
				'options'  => array(
					'featured' => esc_html__( 'Hot Products', 'learts' ),
					'new'      => esc_html__( 'New Products', 'learts' ),
					'sale'     => esc_html__( 'Sale Products', 'learts' ),
				),
				'default'  => array(
					'featured' => '1',
					'new'      => '1',
					'sale'     => '1',
				),
				'required' => array(
					array( 'shop_toolbar', '=', true ),
					array( 'product_tabs', '=', true ),
					array( 'product_tabs_type', '=', 'groups' ),
				),
			),

			array(
				'id'       => 'product_tabs_categories',
				'type'     => 'text',
				'title'    => esc_html__( 'Categories', 'learts' ),
				'subtitle' => esc_html__( 'Enter category names, separate by commas. Leave empty to get all categories. Enter a number to get limit number of top categories.',
					'learts' ),
				'default'  => 3,
				'required' => array(
					array( 'shop_toolbar', '=', true ),
					array( 'product_tabs', '=', true ),
					array( 'product_tabs_type', '=', 'categories' ),
				),
			),

			array(
				'id'       => 'product_tabs_tags',
				'type'     => 'text',
				'title'    => esc_html__( 'Tags', 'learts' ),
				'subtitle' => esc_html__( 'Enter tag names. Separate by commas.', 'learts' ),
				'default'  => '',
				'required' => array(
					array( 'shop_toolbar', '=', true ),
					array( 'product_tabs', '=', true ),
					array( 'product_tabs_type', '=', 'tags' ),
				),
			),

			array(
				'id'       => 'product_filter',
				'type'     => 'switch',
				'title'    => esc_html__( 'Product Filter', 'learts' ),
				'subtitle' => esc_html__( 'Show filter widgets', 'learts' ),
				'default'  => true,
				'required' => array(
					array( 'shop_toolbar', '=', true ),
				),
			),

			array(
				'id'       => 'product_sorting',
				'type'     => 'switch',
				'title'    => esc_html__( 'Product Sort', 'learts' ),
				'subtitle' => esc_html__( 'Show the sort options instead of product count', 'learts' ),
				'default'  => true,
				'required' => array(
					array( 'shop_toolbar', '=', true ),
				),
			),

			array(
				'id'      => 'column_switcher',
				'type'    => 'switch',
				'title'   => esc_html__( 'Show column switcher', 'learts' ),
				'default' => true,
				'required' => array(
					array( 'shop_toolbar', '=', true ),
				),
			),
		),

	) );
