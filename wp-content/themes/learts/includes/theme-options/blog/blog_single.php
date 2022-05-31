<?php

Redux::setSection( learts_Redux::$opt_name,
	array(
		'title'      => esc_html__( 'Blog Single Post', 'learts' ),
		'id'         => 'section_single_post',
		'subsection' => true,
		'fields'     => array(

			array(
				'id'       => 'post_sidebar_config',
				'type'     => 'image_select',
				'title'    => esc_html__( 'Post Sidebar', 'learts' ),
				'subtitle' => esc_html__( 'Controls the position of sidebars for the single post pages.', 'learts' ),
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
				'id'       => 'post_sidebar',
				'type'     => 'select',
				'title'    => esc_html__( 'Single Post Sidebar', 'learts' ),
				'subtitle' => esc_html__( 'Choose the sidebar for single post pages.', 'learts' ),
				'data'     => 'sidebars',
				'default'  => 'sidebar',
				'required' => array(
					array( 'post_sidebar_config', '!=', 'no' ),
				),
			),

			array(
				'id'       => 'post_show_tags',
				'type'     => 'switch',
				'title'    => esc_html__( 'Tags', 'learts' ),
				'subtitle' => esc_html__( 'Turn on to display the tags.', 'learts' ),
				'default'  => true,
			),

			array(
				'id'       => 'post_show_author_info',
				'type'     => 'switch',
				'title'    => esc_html__( 'Author Info Box', 'learts' ),
				'subtitle' => esc_html__( 'Turn on to display the author info box below posts.', 'learts' ),
				'default'  => true,
			),

			array(
				'id'       => 'post_show_related',
				'type'     => 'switch',
				'title'    => esc_html__( 'Related Posts', 'learts' ),
				'subtitle' => esc_html__( 'Turn on to display related posts.', 'learts' ),
				'default'  => true,
			),

			array(
				'id'       => 'post_show_share',
				'type'     => 'switch',
				'title'    => esc_html__( 'Social Sharing Box', 'learts' ),
				'subtitle' => esc_html__( 'Turn on to display the social sharing box.', 'learts' ),
				'default'  => true,
			),

			array(
				'id'       => 'post_share_links',
				'type'     => 'checkbox',
				'title'    => esc_html__( 'Share the post on ', 'learts' ),
				'options'  => array(
					'facebook'  => '<i class="fa fa-facebook"></i>&nbsp;&nbsp;' . esc_html__( 'Facebook', 'learts' ),
					'twitter'   => '<i class="fa fa-twitter"></i>&nbsp;&nbsp;' . esc_html__( 'Twitter', 'learts' ),
					'google'    => '<i class="fa fa-google-plus"></i>&nbsp;&nbsp;' . esc_html__( 'Google+', 'learts' ),
					'pinterest' => '<i class="fa fa-pinterest"></i>&nbsp;&nbsp;' . esc_html__( 'Pinterest', 'learts' ),
					'email'     => '<i class="fa fa-envelope-o"></i>&nbsp;&nbsp;' . esc_html__( 'Email', 'learts' ),
				),
				'default'  => array(
					'facebook'  => '1',
					'twitter'   => '1',
					'google'    => '1',
					'pinterest' => '1',
					'email'     => '1',
				),
				'required' => array(
					array( 'post_show_share', '=', array( true ) ),
				),
			),

		),
	) );
