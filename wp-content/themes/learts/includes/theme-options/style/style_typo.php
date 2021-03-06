<?php


Redux::setSection( learts_Redux::$opt_name, array(
	'title'            => esc_html__( 'Typography', 'learts' ),
	'id'               => 'section_typo',
	'subsection'       => true,
	'customizer_width' => '450px',
	'fields'           => array(
		array(
			'id'          => 'primary_font',
			'type'        => 'typography',
			'title'       => esc_html__( 'Primary Font', 'learts' ),
			'all_styles'  => true,
			'google'      => true,
			'font-backup' => true,
			'output'      => $learts_selectors['primary_font'],
			'units'       => 'px',
			'subtitle'    => esc_html__( 'These settings control the typography for all body text.', 'learts' ),
			'text-align'  => false,
			'default'     => array(
				'font-family' => 'Futura',
				'font-backup' => 'Arial, Helvetica, sans-serif',
				'font-size'   => '18px',
				'line-height' => '32px',
				'font-weight' => '400',
				'color'       => '#696969',
			),

		),
		array(
			'id'          => 'secondary_font',
			'type'        => 'typography',
			'title'       => esc_html__( 'Secondary Font', 'learts' ),
			'all_styles'  => true,
			'google'      => true,
			'font-backup' => true,
			'output'      => $learts_selectors['secondary_font'],
			'units'       => 'px',
			'font-size'   => false,
			'line-height' => false,
			'color'       => false,
			'text-align'  => false,
			'default'     => array(
				'font-family' => 'Playfair Display',
				'google'      => true,
				'font-backup' => '\'Times New Roman\', Times,serif',
				'font-weight' => '700',
				'font-style'  => 'normal',
			),
		),

		array(
			'id'          => 'tertiary_font',
			'type'        => 'typography',
			'title'       => esc_html__( 'Tertiary Font', 'learts' ),
			'all_styles'  => true,
			'google'      => true,
			'font-backup' => true,
			'output'      => $learts_selectors['tertiary_font'],
			'units'       => 'px',
			'font-size'   => false,
			'line-height' => false,
			'color'       => false,
			'text-align'  => false,
			'default'     => array(
				'font-family' => 'Yellowtail',
				'google'      => true,
				'font-backup' => 'Arial, Helvetica, sans-serif',
				'font-weight' => '700',
				'font-style'  => 'normal',
			),
		),

		array(
			'id'          => 'heading_font',
			'type'        => 'typography',
			'title'       => esc_html__( 'Heading Font', 'learts' ),
			'subtitle'    => esc_html__( 'Set you typography options for titles (From h1 to h6 HTML tags)', 'learts' ),
			'google'      => true,
			'font-backup' => true,
			'font-size'   => false,
			'line-height' => false,
			'text-align'  => false,
			'color'       => true,
			'all_styles'  => true,
			'output'      => $learts_selectors['heading_font'],
			'units'       => 'px',
			'default'     => array(
				'font-family' => 'Marcellus',
				'font-backup' => 'Arial, Helvetica, sans-serif',
				'font-weight' => '500',
				'color'       => '#333333',
			),
		),
	),
) );
