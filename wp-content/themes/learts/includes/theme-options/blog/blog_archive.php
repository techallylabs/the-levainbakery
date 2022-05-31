<?php

Redux::setSection( learts_Redux::$opt_name,
	array(
		'title'      => esc_html__( 'Blog Archive', 'learts' ),
		'id'         => 'section_blog_archive',
		'subsection' => true,
		'fields'     => array(

			array(
				'id'       => 'archive_sidebar_config',
				'type'     => 'image_select',
				'title'    => esc_html__( 'Archive Sidebar Position', 'learts' ),
				'subtitle' => esc_html__( 'Controls the position of sidebars for the archive pages.', 'learts' ),
				'options'  => array(
					'left'  => array(
						'title' => esc_html__( 'Left', 'learts' ),
						'img'   => LEARTS_ADMIN_IMAGES . DS . '2cl.png',
					),
					'no'    => array(
						'title' => esc_html__( 'Disable', 'learts' ),
						'img'   => LEARTS_ADMIN_IMAGES . DS . '1c.png',
					),
					'right' => array(
						'title' => esc_html__( 'Right', 'learts' ),
						'img'   => LEARTS_ADMIN_IMAGES . DS . '2cr.png',
					),
				),
				'default'  => 'right',
			),

			array(
				'id'       => 'archive_sidebar',
				'type'     => 'select',
				'title'    => esc_html__( 'Archive Sidebar', 'learts' ),
				'subtitle' => esc_html__( 'Choose the sidebar for archive pages.', 'learts' ),
				'data'     => 'sidebars',
				'default'  => 'sidebar',
				'required' => array(
					array( 'archive_sidebar_config', '!=', 'no' ),
				),
			),


			array(
				'id'       => 'archive_display_type',
				'type'     => 'button_set',
				'title'    => esc_html__( 'Archive Display Type', 'learts' ),
				'subtitle' => esc_html__( 'Select the display type.', 'learts' ),
				'options'  => array(
					'standard'  => esc_html__( 'Standard', 'learts' ),
					'grid'      => esc_html__( 'Grid', 'learts' ),
					'list'      => esc_html__( 'List', 'learts' ),
					'masonry'   => esc_html__( 'Masonry', 'learts' ),
				),
				'default'  => 'standard',
			),

			array(
				'id'       => 'archive_content_output',
				'type'     => 'button_set',
				'title'    => esc_html__( 'Archive Content Output', 'learts' ),
				'subtitle' => esc_html__( 'Select if you\'d like to output the content or excerpt on archive pages.',
					'learts' ),
				'options'  => array(
					'excerpt' => esc_html__( 'Excerpt', 'learts' ),
					'content' => esc_html__( 'Content', 'learts' ),
				),
				'default'  => 'excerpt',
			),

			array(
				'id'                => 'excerpt_length',
				'type'              => 'text',
				'title'             => esc_html__( 'Excerpt Length', 'learts' ),
				'subtitle'          => sprintf( esc_html__( 'Controls the number of words of the post excerpt (from 0 to %s words)',
					'learts' ),
					apply_filters( 'learts_max_excerpt_length', 500 ) ),
				'default'           => 30,
				'display_value'     => 'label',
				'validate_callback' => 'learts_validate_excerpt_callback',
				'required'          => array(
					'archive_content_output',
					'=',
					'excerpt',
				),
			),

			array(
				'id'       => 'archive_pagination',
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

if ( ! function_exists( 'learts_validate_excerpt_callback' ) ) {
	function learts_validate_excerpt_callback( $field, $value, $existing_value ) {
		$error = false;

		if ( ! is_numeric( $value ) ) {

			$error = true;

			$value        = $existing_value;
			$field['msg'] = esc_html__( 'You must provide a numerical value for this option.', 'learts' );

		} elseif ( $value < 0 || $value > apply_filters( 'learts_max_excerpt_length', 500 ) ) {

			$error = true;

			$value        = $existing_value;
			$field['msg'] = sprintf( esc_html__( 'The excerpt length must be from 0 to %s words.', 'learts' ),
				apply_filters( 'learts_max_excerpt_length', 500 ) );
		}

		$return['value'] = $value;

		if ( $error ) {
			$return['error'] = $field;
		}

		return $return;
	}
}

