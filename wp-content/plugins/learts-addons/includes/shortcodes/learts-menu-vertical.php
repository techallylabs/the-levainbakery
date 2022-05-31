<?php

/**
 * ThemeMove Menu Vertical Shortcode
 *
 * @version 1.0
 * @package Learts
 */
class WPBakeryShortCode_Learts_Menu_Vertical extends WPBakeryShortCode {

}

// Mapping shortcode.
vc_map( array(
	'name'        => esc_html__( 'Menu Vertical', 'learts-addons' ),
	'base'        => 'learts_menu_vertical',
	'icon'        => 'learts-element-icon-menu-vertical',
	'category'    => sprintf( esc_html__( 'by %s', 'learts-addons' ), LEARTS_ADDONS_THEME_NAME ),
	'description' => esc_html__( 'Display menu is vertical format', 'learts-addons' ),
	'params'      => array(
		array(
			'type'        => 'textfield',
			'heading'     => esc_html__( 'Title', 'learts-addons' ),
			'param_name'  => 'title',
			'admin_label' => true,
		),

		array(
			'type'       => 'checkbox',
			'param_name' => 'display_dropdown',
			'value'      => array( esc_html__( 'Display dropdown', 'learts-addons' ) => 'yes' ),
		),

		array(
			'group'      => esc_html__( 'Item', 'learts-addons' ),
			'type'       => 'param_group',
			'heading'    => esc_html__( 'Item', 'learts-addons' ),
			'param_name' => 'item_list',
			'params'     => array(
				array(
					'heading'    => esc_html__( 'Display image', 'learts-addons' ),
					'type'       => 'dropdown',
					'param_name' => 'source',
					'value'      => array(
						esc_html__( 'Image from libary', 'learts-addons' ) => 'image_libary',
						esc_html__( 'Link image', 'learts-addons' )        => 'external_link',
					),
				),

				array(
					'type'        => 'attach_image',
					'heading'     => esc_html__( 'Image', 'learts-addons' ),
					'param_name'  => 'image',
					'value'       => '',
					'description' => esc_html__( 'Select an image from media library.', 'learts-addons' ),
					'dependency'  => array(
						'element' => 'source',
						'value'   => 'image_libary',
					),
				),

				array(
					'type'       => 'textfield',
					'heading'    => esc_html__( 'URL Image', 'learts-addons' ),
					'param_name' => 'link_image',
					'dependency' => array(
						'element' => 'source',
						'value'   => 'external_link',
					),
				),

				array(
					'type'        => 'vc_link',
					'heading'     => esc_html__( 'Custom Link', 'learts-addons' ),
					'param_name'  => 'link',
					'admin_label' => true,
				),
				array(
					'type'        => 'textfield',
					'heading'     => esc_html__( 'Title of Item', 'learts-addons' ),
					'param_name'  => 'title_item',
					'admin_label' => true,
				),
			),
		),

		Learts_VC::get_param( 'el_class' ),
		Learts_VC::get_param( 'css' ),
	),
) );
