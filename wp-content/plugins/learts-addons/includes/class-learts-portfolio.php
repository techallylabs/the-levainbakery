<?php
// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register portfolio support
 */
class Learts_Portfolio {

	private $post_type = 'portfolio';

	public function __construct() {
		add_action( 'init', array( $this, 'register_post_type' ), 1 );
		add_action( 'init', array( $this, 'register_taxonomy' ), 1 );
	}

	public function register_post_type() {

		if ( post_type_exists( $this->post_type ) ) {
			return;
		}

		register_post_type( $this->post_type,
			array(
				'labels'            => array(
					'name'               => __( 'Portfolio', 'learts-addons' ),
					'singular_name'      => __( 'Portfolio Item', 'learts-addons' ),
					'view_item'          => __( 'View Portfolios', 'learts-addons' ),
					'add_new_item'       => __( 'Add New Portfolio', 'learts-addons' ),
					'add_new'            => __( 'Add New', 'learts-addons' ),
					'new_item'           => __( 'Add New Portfolio Item', 'learts-addons' ),
					'edit_item'          => __( 'Edit Portfolio Item', 'learts-addons' ),
					'update_item'        => __( 'Update Portfolio', 'learts-addons' ),
					'all_items'          => __( 'All Portfolios', 'learts-addons' ),
					'parent_item_colon'  => __( 'Parent Portfolio Item:', 'learts-addons' ),
					'search_items'       => __( 'Search Portfolio', 'learts-addons' ),
					'not_found'          => __( 'No portfolio items found', 'learts-addons' ),
					'not_found_in_trash' => __( 'No portfolio items found in trash', 'learts-addons' ),
				),
				'public'            => true,
				'has_archive'       => true,
				'show_ui'           => true,
				'show_in_menu'      => true,
				'show_in_nav_menus' => true,
				'menu_icon'         => 'dashicons-portfolio',
				'rewrite'           => true,
				'supports'          => array(
					'title',
					'editor',
					'thumbnail',
					'custom-fields',
					'excerpt',
					'revisions',
				),
			) );
	}

	public function register_taxonomy() {

		register_taxonomy( 'portfolio_category',
			'portfolio',
			array(
				'hierarchical'      => true,
				'label'             => __( 'Categories Portfolio', 'learts-addons' ),
				'query_var'         => true,
				'rewrite'           => true,
				'show_admin_column' => true,
			) );

		register_taxonomy( 'portfolio_tags',
			'portfolio',
			array(
				'hierarchical'      => false,
				'label'             => __( 'Tags Portfolio', 'learts-addons' ),
				'query_var'         => true,
				'rewrite'           => true,
				'show_admin_column' => true,
			) );
	}
}

new Learts_Portfolio();
