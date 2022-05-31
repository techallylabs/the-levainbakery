<?php

$settings = array(

	array(
		'id'       => 'header_right_column_notice',
		'type'     => 'info',
		'style'    => 'warning',
		'title'    => esc_html__( 'Note', 'learts' ),
		'desc'     => esc_html__( 'Does not work with the Vertical Header', 'learts' ),
		'required' => array(
			array( 'header', '=', 'vertical' ),
		),
	),

	array(
		'id'            => 'right_column_width',
		'type'          => 'slider',
		'title'         => esc_html__( 'Header right column width', 'learts' ),
		'subtitle'      => esc_html__( 'Set width for icons area in the header (search, wishlist, shopping cart) (in %)',
			'learts' ),
		'default'       => 15,
		'min'           => 1,
		'max'           => 50,
		'step'          => 1,
		'display_value' => 'label',
		'required'      => array(
			array( 'header', '=', array( 'base' ) ),
		),
	),
);

if ( class_exists( 'WooCommerce' ) ) {


	$settings[] = array(
		'id'       => 'header_login',
		'type'     => 'switch',
		'title'    => esc_html__( 'Login link in header', 'learts' ),
		'subtitle' => sprintf( __( 'Show links to login/register or my accout page in the header. Please set up the My account page <a href="%s" target="_blank">here</a>.',
			'learts' ),
			admin_url() . 'admin.php?page=wc-settings&tab=account' ),
		'default'  => true,
		'required' => array(
			array( 'header', '!=', 'vertical' ),
		),
	);

	$settings[] = array(
		'id'       => 'header_login_position',
		'type'     => 'image_select',
		'title'    => esc_html__( 'Login link position', 'learts' ),
		'subtitle' => esc_html__( 'Select the header right column layout.',
			'learts' ),
		'options'  => array(
			'none' => array(
				'title' => esc_html__( 'None', 'learts' ),
				'img'   => LEARTS_ADMIN_IMAGES . DS . 'right-col-none.png',
			),
			'left'    => array(
				'title' => esc_html__( 'Left', 'learts' ),
				'img'   => LEARTS_ADMIN_IMAGES . DS . 'right-col-left.png',
			),
			'between' => array(
				'title' => esc_html__( 'Between', 'learts' ),
				'img'   => LEARTS_ADMIN_IMAGES . DS . 'right-col-between.png',
			),
		),
		'default'  => 'left',
		'required' => array(
			array( 'header', '!=', 'vertical' ),
			array( 'header_login', '=', true ),
		),
	);

	$settings[] = array(
		'id'       => 'header_login_icon',
		'type'     => 'switch',
		'title'    => esc_html__( 'Display login as icon', 'learts' ),
		'default'  => false,
	);

	$settings[] = array(
		'id'          => 'header_login_color',
		'type'        => 'color',
		'transparent' => false,
		'title'       => esc_html__( 'Login link Color', 'learts' ),
		'output'      => $learts_selectors['header_login_icon_color'],
		'validate'    => 'color',
		'default'     => '#333333',
		'required'    => array(
			array( 'header', '!=', 'vertical' ),
			array( 'header_login', '=', true ),
		),
	);
}

Redux::setSection( learts_Redux::$opt_name,
	array(
		'title'      => esc_html__( 'Header Right Column', 'learts' ),
		'id'         => 'section_header_right_column',
		'subsection' => true,
		'fields'     => $settings,
	) );
