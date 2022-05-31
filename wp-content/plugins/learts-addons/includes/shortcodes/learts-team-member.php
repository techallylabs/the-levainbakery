<?php

/**
 * ThemeMove Team Member Shortcode
 *
 * @version 1.0
 * @package Learts
 */
class WPBakeryShortCode_Learts_Team_Member extends WPBakeryShortCode {

	public function getSocialLinks( $atts ) {
		$social_links     = preg_split( '/\s+/', $atts['social_links'] );
		$social_links_arr = array();

		foreach ( $social_links as $social ) {
			$pieces = explode( '|', $social );
			if ( count( $pieces ) == 2 ) {
				$key                      = $pieces[0];
				$link                     = $pieces[1];
				$social_links_arr[ $key ] = $link;
			}
		}

		return $social_links_arr;
	}

}

// Mapping shortcode.
vc_map( array(
	'name'        => esc_html__( 'Team Member', 'learts-addons' ),
	'base'        => 'learts_team_member',
	'icon'        => 'learts-element-icon-team-member',
	'category'    => sprintf( esc_html__( 'by %s', 'learts-addons' ), LEARTS_ADDONS_THEME_NAME ),
	'description' => esc_html__( 'Display team members project', 'learts-addons' ),
	'params'      => array(
		array(
			'type'        => 'attach_image',
			'heading'     => esc_html__( 'Image', 'learts-addons' ),
			'param_name'  => 'image',
			'value'       => '',
			'description' => esc_html__( 'Select an image from media library.', 'learts-addons' ),
		),
		array(
			'type'        => 'textfield',
			'heading'     => esc_html__( 'Name', 'learts-addons' ),
			'param_name'  => 'name',
			'admin_label' => true,
		),
		array(
			'type'        => 'textfield',
			'heading'     => esc_html__( 'Role', 'learts-addons' ),
			'param_name'  => 'role',
			'description' => esc_html__( 'Add a role. E.g. CEO of ThemeMove', 'learts-addons' ),
			'admin_label' => true,
		),
		array(
			'type'       => 'textarea',
			'heading'    => esc_html__( 'Biography', 'learts-addons' ),
			'param_name' => 'biography',
		),
		Learts_VC::get_param( 'el_class' ),
		array(
			'group'      => esc_html__( 'Social', 'learts-addons' ),
			'type'       => 'checkbox',
			'param_name' => 'link_new_page',
			'value'      => array( esc_html__( 'Open links in new tab', 'learts-addons' ) => 'yes' ),
		),
		array(
			'group'      => esc_html__( 'Social', 'learts-addons' ),
			'type'       => 'social_links',
			'heading'    => esc_html__( 'Social links', 'learts-addons' ),
			'param_name' => 'social_links',
		),
		Learts_VC::get_param( 'css' ),
	),
) );

