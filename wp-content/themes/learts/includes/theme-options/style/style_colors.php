<?php

Redux::setSection( learts_Redux::$opt_name, array(
	'title'            => __( 'Colors', 'learts' ),
	'id'               => 'section_colors',
	'subsection'       => true,
	'customizer_width' => '450px',
	'fields'           => array(
		array(
			'id'    => 'info_color',
			'type'  => 'info',
			'style' => 'warning',
			'title' => esc_html__( 'IMPORTANT NOTE', 'learts' ),
			'icon'  => 'el-icon-info-sign',
			'desc'  => esc_html__( 'This tab contains general color options. Additional color options for specific areas, can be found within other tabs. Example: For menu color options go to the menu tab.', 'learts' ),
		),

		array(
			'id'          => 'primary_color',
			'type'        => 'color',
			'transparent' => false,
			'title'       => esc_html__( 'Primary Color', 'learts' ),
			'default'     => PRIMARY_COLOR,
			'validate'    => 'color',
			'output'      => $learts_selectors['primary_color'],
		),

		array(
			'id'          => 'secondary_color',
			'type'        => 'color',
			'transparent' => false,
			'title'       => esc_html__( 'Secondary Color', 'learts' ),
			'default'     => SECONDARY_COLOR,
			'validate'    => 'color',
			'output'      => $learts_selectors['secondary_color'],
		),

		array(
			'id'          => 'tertiary_color',
			'type'        => 'color',
			'transparent' => false,
			'title'       => esc_html__( 'Tertiary Color', 'learts' ),
			'default'     => TERTIARY_COLOR,
			'validate'    => 'color',
			'output'      => $learts_selectors['tertiary_color'],
		),

		array(
			'id'          => 'link_color',
			'type'        => 'color',
			'transparent' => false,
			'title'       => esc_html__( 'Links Color', 'learts' ),
			'subtitle'    => esc_html__( 'Controls the color of all text links.', 'learts' ),
			'default'     => SECONDARY_COLOR,
			'validate'    => 'color',
			'output'      => $learts_selectors['link_color'],
		),
		array(
			'id'          => 'link_hover_color',
			'type'        => 'color',
			'transparent' => false,
			'title'       => esc_html__( 'Links Hover Color', 'learts' ),
			'subtitle'    => esc_html__( 'Controls the color of all text links when hover.', 'learts' ),
			'default'     => PRIMARY_COLOR,
			'validate'    => 'color',
			'output'      => $learts_selectors['link_hover_color'],
		),
		array(
			'id'          => 'bg_full_color',
			'type'        => 'color',
			'transparent' => false,
			'title'       => esc_html__( 'Background Color Menu Full Screen', 'learts' ),
			'subtitle'    => esc_html__( 'Controls the background color of menu-full-screen', 'learts' ),
			'default'     => '#F4EDE7',
			'validate'    => 'color',
			'output'      => $learts_selectors['bg_full_color'],
		),
	),
) );
