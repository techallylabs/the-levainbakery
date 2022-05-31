<?php

Redux::setSection( learts_Redux::$opt_name, array(
	'title'      => esc_html__( 'General', 'learts' ),
	'id'         => 'section_general',
	'subsection' => true,
	'fields'     => array(
		array(
			'id'       => 'post_meta',
			'type'     => 'switch',
			'title'    => esc_html__( 'Post Meta', 'learts' ),
			'subtitle' => esc_html__( 'Turn on to display post meta on blog posts', 'learts' ),
			'default'  => true,
		),

		array(
			'id'       => 'post_meta_author',
			'type'     => 'switch',
			'title'    => esc_html__( 'Display Author', 'learts' ),
			'subtitle' => esc_html__( 'Display author on blog posts', 'learts' ),
			'default'  => true,
			'required'    => array(
				'post_meta',
				'=',
				true,
			),
		),

		array(
			'id'       => 'post_meta_categories',
			'type'     => 'switch',
			'title'    => esc_html__( 'Display Categories', 'learts' ),
			'subtitle' => esc_html__( 'Display categories on blog posts', 'learts' ),
			'default'  => true,
			'required'    => array(
				'post_meta',
				'=',
				true,
			),
		),
	),
) );
