<?php
// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register portfolio support
 */
class Learts_Testimonial {

	private $post_type = 'testimonial';
	private $taxonomy_type = 'testimonial-category';

	public function __construct() {
		$this->register_post_type();
		$this->register_taxonomy();
	}

	public function register_post_type() {

		if ( post_type_exists( $this->post_type ) ) {
			return;
		}

		register_post_type( $this->post_type,
			array(
				'labels'            => array(
					'name'               => esc_html__( 'Testimonials', 'learts-addons' ),
					'singular_name'      => esc_html__( 'Testimonial', 'learts-addons' ),
					'add_new'            => esc_html__( 'Add New', 'learts-addons' ),
					'add_new_item'       => esc_html__( 'Add New Testimonial', 'learts-addons' ),
					'edit_item'          => esc_html__( 'Edit Testimonial', 'learts-addons' ),
					'new_item'           => esc_html__( 'New Testimonial', 'learts-addons' ),
					'view_item'          => esc_html__( 'View Testimonial', 'learts-addons' ),
					'search_items'       => esc_html__( 'Search Testimonials', 'learts-addons' ),
					'not_found'          => esc_html__( 'No testimonials have been added yet', 'learts-addons' ),
					'not_found_in_trash' => esc_html__( 'Nothing found in Trash', 'learts-addons' ),
					'parent_item_colon'  => '',
				),
				'public'            => false,
				'has_archive'       => false,
				'show_ui'           => true,
				'show_in_menu'      => true,
				'show_in_nav_menus' => false,
				'menu_icon'         => 'dashicons-format-quote',
				'rewrite'           => false,
				'supports'          => array(
					'title',
					'editor',
					'custom-fields',
					'excerpt',
					'revisions',
				),
			) );
	}

	public function register_taxonomy() {

		register_taxonomy( $this->taxonomy_type,
			$this->post_type,
			array(
				'labels'            => esc_html__( 'Testimonial Categories', 'learts-addons' ),
				'hierarchical'      => true,
				'public'            => false,
				'show_ui'           => true,
				'show_admin_column' => true,
				'show_in_nav_menus' => false,
				'rewrite'           => false,
				'query_var'         => true,
			) );
	}
}

new Learts_Testimonial();
