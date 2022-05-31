<?php

/**
 * Learts Menu Grid Shortcode
 *
 * @version 1.0
 * @package Learts
 */
class WPBakeryShortCode_Learts_Menu_Grid extends WPBakeryShortCode {

}

// Mapping shortcode.
vc_map( array(
	'name'     => esc_html__( 'Menu Grid', 'learts-addons' ),
	'base'     => 'learts_menu_grid',
	'icon'     => 'learts-element-icon-menu-grid',
	'category' => sprintf( esc_html__( 'by %s', 'learts-addons' ), LEARTS_ADDONS_THEME_NAME ),
	'description' => esc_html__( 'Display menu is grid format', 'learts-addons' ),
	'params'   => array(

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
				array(
					'heading'    => esc_html__( 'Display Badges', 'learts-addons' ),
					'type'       => 'dropdown',
					'param_name' => 'tags',
					'value'      => array(
						esc_html__( 'None', 'learts-addons' ) => 'none',
						esc_html__( 'New', 'learts-addons' )  => 'new',
						esc_html__( 'Hot', 'learts-addons' )  => 'hot',
					),
				),
			),
		),

		Learts_VC::get_param( 'columns' ),
		Learts_VC::get_param( 'el_class' ),
		Learts_VC::get_param( 'css' ),
	),
) );
