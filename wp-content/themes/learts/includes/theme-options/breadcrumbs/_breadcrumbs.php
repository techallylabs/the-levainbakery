<?php

Redux::setSection( learts_Redux::$opt_name, array(
	'title'  => esc_html__( 'Breadcrumbs', 'learts' ),
	'id'     => 'panel_breadcrumbs',
	'icon'   => 'fa fa-angle-double-right',
	'fields' => array(

		array(
			'id'       => 'breadcrumbs',
			'type'     => 'switch',
			'title'    => esc_html__( 'Breadcrumbs', 'learts' ),
			'subtitle' => esc_html__( 'Displays a full chain of links to the current page.', 'learts' ),
			'default'  => true,
		),

		array(
			'id'          => 'breadcrumbs_text_color',
			'type'        => 'color',
			'transparent' => false,
			'title'       => esc_html__( 'Breadcrumbs Text Color', 'learts' ),
			'output'      => $learts_selectors['breadcrumbs_text_color'],
			'default'     => '#333333',
		),

		array(
			'id'          => 'breadcrumbs_seperator_color',
			'type'        => 'color',
			'transparent' => false,
			'title'       => esc_html__( 'Breadcrumbs Separator Color', 'learts' ),
			'output'      => $learts_selectors['breadcrumbs_seperator_color'],
			'default'     => '#333333',
		),

		array(
			'id'          => 'breadcrumbs_link_color',
			'type'        => 'color',
			'transparent' => false,
			'title'       => esc_html__( 'Breadcrumbs Link Color', 'learts' ),
			'output'      => $learts_selectors['breadcrumbs_link_color'],
			'default'     => '#999999',
		),

		array(
			'id'          => 'breadcrumbs_link_color_hover',
			'type'        => 'color',
			'transparent' => false,
			'title'       => esc_html__( 'Breadcrumbs Link Color: Hover', 'learts' ),
			'output'      => $learts_selectors['breadcrumbs_link_color_hover'],
			'default'     => '#999999',
		),
	),
) );
