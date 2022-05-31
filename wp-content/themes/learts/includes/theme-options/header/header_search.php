<?php

$search_post_type = array(
	'post' => esc_html__( 'Post', 'learts' ),
);

if ( class_exists( 'WooCommerce' ) ) {
	$search_post_type['product'] = esc_html__( 'Product', 'learts' );
}

Redux::setSection( learts_Redux::$opt_name,
	array(
		'title'      => esc_html__( 'Search', 'learts' ),
		'id'         => 'section_header_search',
		'subsection' => true,
		'fields'     => array(
			array(
				'id'       => 'search_on',
				'type'     => 'switch',
				'title'    => esc_html__( 'Enable Search', 'learts' ),
				'subtitle' => esc_html__( 'Enable / Disable the search box', 'learts' ),
				'default'  => true,
			),

			array(
				'id'       => 'search_post_type',
				'type'     => 'button_set',
				'title'    => esc_html__( 'Search content type', 'learts' ),
				'subtitle' => esc_html__( 'Select content type you want to use in the search box', 'learts' ),
				'options'  => $search_post_type,
				'default'  => class_exists( 'WooCommerce' ) ? 'product' : 'post',
			),

			array(
				'id'       => 'search_by',
				'type'     => 'button_set',
				'title'    => esc_html__( 'Search product by', 'learts' ),
				'options'  => array(
					'title' => esc_html__( 'Title', 'learts' ),
					'sku'   => esc_html__( 'SKU', 'learts' ),
					'both'  => esc_html__( 'Both title & SKU', 'learts' ),
				),
				'default'  => 'both',
				'required' => array(
					array( 'search_post_type', '=', array( 'product' ) ),
				),
			),

			array(
				'id'       => 'search_categories_on',
				'type'     => 'switch',
				'title'    => esc_html__( 'Categories select box', 'learts' ),
				'subtitle' => esc_html__( 'Turn on this option if you want to show categories select box',
					'learts' ),
				'default'  => true,
			),
			array(
				'id'      => 'search_ajax_on',
				'type'    => 'switch',
				'title'   => esc_html__( 'Live Search', 'learts' ),
				'default' => true,
			),
			array(
				'id'            => 'search_min_chars',
				'type'          => 'slider',
				'title'         => esc_html__( 'Minimum number of characters', 'learts' ),
				'subtitle'      => esc_html__( 'Minimum number of characters required to trigger autosuggest',
					'learts' ),
				'min'           => 1,
				'max'           => 10,
				'step'          => 1,
				'default'       => 1,
				'display_value' => 'label',
				'required'      => array(
					array( 'search_ajax_on', '=', array( true ) ),
				),
			),
			array(
				'id'            => 'search_limit',
				'type'          => 'slider',
				'title'         => esc_html__( 'Maximum number of results', 'learts' ),
				'subtitle'      => esc_html__( 'Maximum number of results showed within the autosuggest box',
					'learts' ),
				'min'           => 1,
				'max'           => 20,
				'step'          => 1,
				'default'       => 6,
				'display_value' => 'label',
				'required'      => array(
					array( 'search_ajax_on', '=', array( true ) ),
				),
			),
			array(
				'id'       => 'search_excerpt_on',
				'type'     => 'switch',
				'title'    => esc_html__( 'Show excerpt', 'learts' ),
				'subtitle' => esc_html__( 'Show the excerpt of search result', 'learts' ),
				'default'  => false,
				'required' => array(
					array( 'search_ajax_on', '=', array( true ) ),
				),
			),
			array(
				'id'          => 'search_color',
				'type'        => 'color',
				'transparent' => false,
				'title'       => esc_html__( 'Search Icon Color', 'learts' ),
				'subtitle'    => esc_html__( 'Pick a color for the search icon', 'learts' ),
				'output'      => $learts_selectors['search_color'],
				'validate'    => 'color',
				'default'     => '#333333',
			),
		),
	) );
