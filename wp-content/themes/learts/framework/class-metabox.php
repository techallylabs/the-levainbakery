<?php

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Add Insight
 *
 * @package   InsightFramework
 */
if ( ! class_exists( 'Learts_Metabox' ) ) {

	class Learts_Metabox {

		private $prefix = 'learts_';
		private $transfer_options = array();

		/**
		 * Insight_Metabox constructor.
		 */
		public function __construct() {

			add_action( 'wp', array( $this, 'modify_global_settings' ), 10 );

			// Use CMB2 Meta box for taxonomies & terms
			add_action( 'cmb2_init', array( $this, 'page_meta_boxes' ) );
			add_action( 'cmb2_init', array( $this, 'portfolio_meta_boxes' ) );
			add_action( 'cmb2_init', array( $this, 'post_meta_boxes' ) );
			add_action( 'cmb2_init', array( $this, 'product_meta_boxes' ) );
			add_action( 'cmb2_init', array( $this, 'testimonial_meta_boxes' ) );
			add_action( 'cmb2_init', array( $this, 'post_category_meta_boxes' ) );
			add_action( 'cmb2_init', array( $this, 'product_category_meta_boxes' ) );
		}

		/**
		 * Meta boxes for page
		 */
		public function page_meta_boxes() {

			$offcanvas_fields = $menu_fields = $page_title_fields = $breadcrumbs_fields = $footer_fields = array();

			$page_sidebar_config = $this->redux2metabox( 'page_sidebar_config' );

			$logo            = learts_get_option( 'logo' );
			$logo_alt        = learts_get_option( 'logo_alt' );
			$logo_mobile     = learts_get_option( 'logo_mobile' );
			$logo_mobile_alt = learts_get_option( 'logo_mobile_alt' );

			$logo_fields = array(

				array(
					'name'    => esc_html__( 'Custom Logo', 'learts' ),
					'id'      => $this->prefix . 'custom_logo',
					'type'    => 'select',
					'desc'    => esc_html__( 'Use custom logo on this page.', 'learts' ),
					'options' => array(
						'on' => esc_html__( 'Yes', 'learts' ),
						''   => esc_html__( 'No', 'learts' ),
					),
					'default' => '',
				),
				array(
					'name'    => esc_html__( 'Logo Image', 'learts' ),
					'id'      => $this->prefix . 'logo',
					'type'    => 'file',
					'default' => ( isset( $logo['url'] ) && $logo['url'] ) ? $logo['url'] : '',
				),
				array(
					'name'    => esc_html__( 'Logo Retina Image', 'learts' ),
					'id'      => $this->prefix . 'logo_retina',
					'type'    => 'file',
					'default' => ( isset( $logo['url'] ) && $logo['url'] ) ? $logo['url'] : '',
				),
				array(
					'name'    => esc_html__( 'Alternative Logo Image', 'learts' ),
					'id'      => $this->prefix . 'logo_alt',
					'desc'    => esc_html__( 'for the header above the content', 'learts' ),
					'type'    => 'file',
					'default' => ( isset( $logo_alt['url'] ) && $logo_alt['url'] ) ? $logo_alt['url'] : '',
				),
				array(
					'name'    => esc_html__( 'Logo in mobile devices', 'learts' ),
					'id'      => $this->prefix . 'logo_mobile',
					'type'    => 'file',
					'default' => ( isset( $logo_mobile['url'] ) && $logo_mobile['url'] ) ? $logo_mobile['url'] : '',
				),
				array(
					'name'    => esc_html__( 'Logo in mobile devices', 'learts' ),
					'id'      => $this->prefix . 'logo_mobile_alt',
					'type'    => 'file',
					'default' => ( isset( $logo_mobile_alt['url'] ) && $logo_mobile_alt['url'] ) ? $logo_mobile_alt['url'] : '',
				),
			);

			$header_transfer_options = array(
				'header_overlap',
				'sticky_header',
			);

			$offcanvas_transfer_options = array(
				'offcanvas_button_on',
				'offcanvas_action',
				'offcanvas_position',
				'offcanvas_button_color',
			);

			$menu_transfer_options = array(
				'site_menu_align',
				'site_menu_hover_style',
				'site_menu_items_color',
				'site_menu_subitems_color',
				'site_menu_bgcolor',
				'site_menu_bdcolor',
				'mobile_menu_button_color',
			);

			$page_title_fields = array(
				// custom page title
				array(
					'name' => esc_html__( 'Custom Page Title', 'learts' ),
					'id'   => $this->prefix . 'custom_page_title',
					'type' => 'text',
				),
				// custom subtitle
				array(
					'name' => esc_html__( 'Sub Title', 'learts' ),
					'id'   => $this->prefix . 'subtitle',
					'type' => 'text',
				),
			);

			$page_title_transfer_options = array(
				'page_title_on',
				'disable_parallax',
				'remove_whitespace',
				'page_title_style',
				'page_title_text_color',
				'page_subtitle_color',
				'page_title_bg_color',
				'page_title_overlay_color',
				'page_title_bg_image',
			);

			$breadcrumbs_transfer_options = array(
				'breadcrumbs',
			);

			// Footer
			$footer_fields[] = array(
				'name'    => esc_html__( 'Footer Visibility', 'learts' ),
				'id'      => $this->prefix . 'disable_footer',
				'type'    => 'select',
				'desc'    => esc_html__( 'Enable or disable footer on this page.', 'learts' ),
				'options' => array(
					''   => esc_html__( 'Enable', 'learts' ),
					'on' => esc_html__( 'Disable', 'learts' ),
				),
				'default' => '',
			);

			$footer_transfer_options = array(
				'footer_layout',
				'footer_width',
				'footer_color_scheme',
				'footer_copyright_bgcolor',
				'footer_copyright_color',
				'footer_copyright_link_color',
				'footer_copyright',
			);

			foreach ( $header_transfer_options as $option ) {
				$header_fields[] = $this->redux2metabox( $option );
			}

			$header_fields[] = array(
				'name'    => esc_html__( 'Top slider', 'learts' ),
				'desc'    => esc_html__( 'Display slive on top page', 'learts' ),
				'id'      => 'revolution_slider',
				'type'    => 'select',
				'default' => '',
				'options' => Learts_Helper::get_rev_sliders(),
			);

			foreach ( $offcanvas_transfer_options as $option ) {
				$offcanvas_fields[] = $this->redux2metabox( $option );
			}

			$menu_fields[] = array(
				'name' => esc_html__( 'Disable Menu', 'learts' ),
				'desc' => esc_html__( 'Disable Menu on this page', 'learts' ),
				'id'   => $this->prefix . 'disable_site_menu',
				'type' => 'checkbox',
			);

			foreach ( $page_title_transfer_options as $option ) {
				$page_title_fields[] = $this->redux2metabox( $option );
			}

			foreach ( $breadcrumbs_transfer_options as $option ) {
				$breadcrumbs_fields[] = $this->redux2metabox( $option );
			}

			$box_options = array(
				'id'           => $this->prefix . 'page_meta_box',
				'title'        => esc_html__( 'Page Settings (custom metabox from theme)', 'learts' ),
				'object_types' => array( 'page' ),
			);

			// tabs
			$tabs = array(
				'config' => $box_options,
				'layout' => 'vertical',
				'tabs'   => array(),
			);

			// logo
			$tabs['tabs'][] = array(
				'id'     => 'tab1',
				'title'  => esc_html__( 'Custom Logo', 'learts' ),
				'fields' => $logo_fields,
			);

			// Header
			$tabs['tabs'][] = array(
				'id'     => 'tab2',
				'title'  => esc_html__( 'Header', 'learts' ),
				'fields' => $header_fields,


			);

			// Off-Canvas Sidebar
			$offcanvas_fields[] = array(
				'name'    => esc_html__( 'Custom off-canvas sidebar', 'learts' ),
				'id'      => $this->prefix . 'offcanvas_custom_sidebar',
				'type'    => 'select',
				'options' => Learts_Helper::get_registered_sidebars( true ),
				'default' => 'sidebar-offcanvas',
			);

			$tabs['tabs'][] = array(
				'id'     => 'tab3',
				'title'  => esc_html__( 'Off-Canvas Sidebar', 'learts' ),
				'fields' => $offcanvas_fields,
			);

			// Menu
			$tabs['tabs'][] = array(
				'id'     => 'tab4',
				'title'  => esc_html__( 'Menu', 'learts' ),
				'fields' => $menu_fields,
			);

			// Page title
			$tabs['tabs'][] = array(
				'id'     => 'tab5',
				'title'  => esc_html__( 'Page Title', 'learts' ),
				'fields' => $page_title_fields,
			);

			// Breadcrumb
			$tabs['tabs'][] = array(
				'id'     => 'tab6',
				'title'  => esc_html__( 'Breadcrumbs', 'learts' ),
				'fields' => $breadcrumbs_fields,
			);

			// Sidebar
			$tabs['tabs'][] = array(
				'id'     => 'tab7',
				'title'  => esc_html__( 'Page Sidebar Options', 'learts' ),
				'fields' => array(
					$page_sidebar_config,

					// Custom sidebar.
					array(
						'name'     => esc_html__( 'Custom sidebar for this page', 'learts' ),
						'id'       => $this->prefix . 'page_custom_sidebar',
						'type'     => 'select',
						'options'  => Learts_Helper::get_registered_sidebars(),
						'multiple' => false,
						'default'  => 'sidebar',
					),
				),
			);

			$tabs['tabs'][] = array(
				'id'     => 'tab8',
				'title'  => esc_html__( 'Footer', 'learts' ),
				'fields' => $footer_fields,
			);

			// Page Meta
			$tabs['tabs'][] = array(
				'id'     => 'tab9',
				'title'  => esc_html__( 'Page Meta', 'learts' ),
				'fields' => array(
					// Extra Page Class.
					array(
						'name' => esc_html__( 'Page extra class name', 'learts' ),
						'id'   => $this->prefix . 'page_extra_class',
						'type' => 'text',
						'desc' => esc_html__( 'If you wish to add extra classes to the body class of the page (for custom css use), then please add the class(es) here.',
							'learts' ),
					),
				),
			);

			$cmb = new_cmb2_box( $box_options );

			$cmb->add_field( array(
				'id'   => $this->prefix . 'page_tabs',
				'type' => 'tabs',
				'tabs' => $tabs,
			) );

			$this->transfer_options = array_merge( $this->transfer_options,
				$header_transfer_options,
				$offcanvas_transfer_options,
				$menu_transfer_options,
				$page_title_transfer_options,
				$breadcrumbs_transfer_options,
				$footer_transfer_options,
				array(
					'page_sidebar_config',
				) );
		}

		/**
		 * Meta boxes for posts
		 */
		public function post_meta_boxes() {

			$breadcrumbs_fields = array();

			$post_sidebar_config = $this->redux2metabox( 'post_sidebar_config' );

			$post_fields = array(
				// Show the post title on top
				array(
					'name'        => esc_html__( 'Display the Post title on top', 'learts' ),
					'id'          => $this->prefix . 'post_title_on_top',
					'type'        => 'checkbox',
					'description' => esc_html__( 'Enabling this option will display the title of this post on top',
						'learts' ),
				),
				// custom page title
				array(
					'name' => esc_html__( 'Custom Page Title', 'learts' ),
					'id'   => $this->prefix . 'custom_page_title',
					'type' => 'text',
				),
				// custom subtitle
				array(
					'name' => esc_html__( 'Sub Title', 'learts' ),
					'id'   => $this->prefix . 'subtitle',
					'type' => 'text',
				),
			);

			$post_transfer_options = array(
				'page_title_on',
				'disable_parallax',
				'remove_whitespace',
				'page_title_style',
				'page_title_text_color',
				'page_subtitle_color',
				'page_title_bg_color',
				'page_title_overlay_color',
				'page_title_bg_image',
			);

			$breadcrumbs_transfer_options = array(
				'breadcrumbs',
			);

			foreach ( $post_transfer_options as $option ) {
				$post_fields[] = $this->redux2metabox( $option );
			}

			foreach ( $breadcrumbs_transfer_options as $option ) {
				$breadcrumbs_fields[] = $this->redux2metabox( $option );
			}

			$box_options = array(
				'id'           => $this->prefix . 'post_meta_box',
				'title'        => esc_html__( 'Post Settings (custom metabox from theme)', 'learts' ),
				'object_types' => array( 'post' ),
			);

			// tabs
			$tabs = array(
				'config' => $box_options,
				'layout' => 'vertical',
				'tabs'   => array(),
			);

			$tabs['tabs'][] = array(
				'id'     => 'tab1',
				'title'  => esc_html__( 'Page Title', 'learts' ),
				'fields' => $post_fields,
			);

			$tabs['tabs'][] = array(
				'id'     => 'tab2',
				'title'  => esc_html__( 'Breadcrumbs', 'learts' ),
				'fields' => $breadcrumbs_fields,
			);

			$tabs['tabs'][] = array(
				'id'     => 'tab3',
				'title'  => esc_html__( 'Sidebar Options', 'learts' ),
				'fields' => array(
					$post_sidebar_config,

					// Custom sidebar.
					array(
						'name'     => esc_html__( 'Custom sidebar for this post', 'learts' ),
						'id'       => $this->prefix . 'post_custom_sidebar',
						'type'     => 'select',
						'options'  => Learts_Helper::get_registered_sidebars(),
						'multiple' => false,
						'default'  => 'sidebar',
					),
				),
			);

			$cmb = new_cmb2_box( $box_options );

			$cmb->add_field( array(
				'id'   => $this->prefix . 'post_tabs',
				'type' => 'tabs',
				'tabs' => $tabs,
			) );

			$this->transfer_options = array_merge( $this->transfer_options,
				$post_transfer_options,
				$breadcrumbs_transfer_options,
				array(
					'post_sidebar_config',
				) );
		}

		/**
		 * Meta boxes for portfolio
		 */
		public function portfolio_meta_boxes() {

			$offcanvas_fields = $menu_fields = $page_title_fields = $breadcrumbs_fields = $footer_fields = array();

			$page_sidebar_config = $this->redux2metabox( 'page_sidebar_config' );

			$logo            = learts_get_option( 'logo' );
			$logo_alt        = learts_get_option( 'logo_alt' );
			$logo_mobile     = learts_get_option( 'logo_mobile' );
			$logo_mobile_alt = learts_get_option( 'logo_mobile_alt' );

			$logo_fields = array(

				array(
					'name'    => esc_html__( 'Custom Logo', 'learts' ),
					'id'      => $this->prefix . 'custom_logo',
					'type'    => 'select',
					'desc'    => esc_html__( 'Use custom logo on this page.', 'learts' ),
					'options' => array(
						'on' => esc_html__( 'Yes', 'learts' ),
						''   => esc_html__( 'No', 'learts' ),
					),
					'default' => '',
				),
				array(
					'name'    => esc_html__( 'Logo Image', 'learts' ),
					'id'      => $this->prefix . 'logo',
					'type'    => 'file',
					'default' => ( isset( $logo['url'] ) && $logo['url'] ) ? $logo['url'] : '',
				),
				array(
					'name'    => esc_html__( 'Logo Retina Image', 'learts' ),
					'id'      => $this->prefix . 'logo_retina',
					'type'    => 'file',
					'default' => ( isset( $logo['url'] ) && $logo['url'] ) ? $logo['url'] : '',
				),
				array(
					'name'    => esc_html__( 'Alternative Logo Image', 'learts' ),
					'id'      => $this->prefix . 'logo_alt',
					'desc'    => esc_html__( 'for the header above the content', 'learts' ),
					'type'    => 'file',
					'default' => ( isset( $logo_alt['url'] ) && $logo_alt['url'] ) ? $logo_alt['url'] : '',
				),
				array(
					'name'    => esc_html__( 'Logo in mobile devices', 'learts' ),
					'id'      => $this->prefix . 'logo_mobile',
					'type'    => 'file',
					'default' => ( isset( $logo_mobile['url'] ) && $logo_mobile['url'] ) ? $logo_mobile['url'] : '',
				),
				array(
					'name'    => esc_html__( 'Logo in mobile devices', 'learts' ),
					'id'      => $this->prefix . 'logo_mobile_alt',
					'type'    => 'file',
					'default' => ( isset( $logo_mobile_alt['url'] ) && $logo_mobile_alt['url'] ) ? $logo_mobile_alt['url'] : '',
				),
			);

			$header_transfer_options = array(
				'header_overlap',
				'sticky_header',
			);

			$offcanvas_transfer_options = array(
				'offcanvas_button_on',
				'offcanvas_action',
				'offcanvas_position',
				'offcanvas_button_color',
			);

			$menu_transfer_options = array(
				'site_menu_align',
				'site_menu_hover_style',
				'site_menu_items_color',
				'site_menu_subitems_color',
				'site_menu_bgcolor',
				'site_menu_bdcolor',
				'mobile_menu_button_color',
			);

			$page_title_fields = array(
				// custom page title
				array(
					'name' => esc_html__( 'Custom Page Title', 'learts' ),
					'id'   => $this->prefix . 'custom_page_title',
					'type' => 'text',
				),
				// custom subtitle
				array(
					'name' => esc_html__( 'Sub Title', 'learts' ),
					'id'   => $this->prefix . 'subtitle',
					'type' => 'text',
				),
			);

			$page_title_transfer_options = array(
				'page_title_on',
				'disable_parallax',
				'remove_whitespace',
				'page_title_style',
				'page_title_text_color',
				'page_subtitle_color',
				'page_title_bg_color',
				'page_title_overlay_color',
				'page_title_bg_image',
			);

			$breadcrumbs_transfer_options = array(
				'breadcrumbs',
			);

			// Footer
			$footer_fields[] = array(
				'name'    => esc_html__( 'Footer Visibility', 'learts' ),
				'id'      => $this->prefix . 'disable_footer',
				'type'    => 'select',
				'desc'    => esc_html__( 'Enable or disable footer on this page.', 'learts' ),
				'options' => array(
					''   => esc_html__( 'Enable', 'learts' ),
					'on' => esc_html__( 'Disable', 'learts' ),
				),
				'default' => '',
			);

			$footer_transfer_options = array(
				'footer_layout',
				'footer_width',
				'footer_color_scheme',
				'footer_copyright_bgcolor',
				'footer_copyright_color',
				'footer_copyright_link_color',
				'footer_copyright',
			);

			foreach ( $header_transfer_options as $option ) {
				$header_fields[] = $this->redux2metabox( $option );
			}

			$header_fields[] = array(
				'name'    => esc_html__( 'Top slider', 'learts' ),
				'desc'    => esc_html__( 'Display slive on top page', 'learts' ),
				'id'      => 'revolution_slider',
				'type'    => 'select',
				'default' => '',
				'options' => Learts_Helper::get_rev_sliders(),
			);

			foreach ( $offcanvas_transfer_options as $option ) {
				$offcanvas_fields[] = $this->redux2metabox( $option );
			}

			$menu_fields[] = array(
				'name' => esc_html__( 'Disable Menu', 'learts' ),
				'desc' => esc_html__( 'Disable Menu on this page', 'learts' ),
				'id'   => $this->prefix . 'disable_site_menu',
				'type' => 'checkbox',
			);

			foreach ( $page_title_transfer_options as $option ) {
				$page_title_fields[] = $this->redux2metabox( $option );
			}

			foreach ( $breadcrumbs_transfer_options as $option ) {
				$breadcrumbs_fields[] = $this->redux2metabox( $option );
			}

			$box_options = array(
				'id'           => $this->prefix . 'portfolio_meta_box',
				'title'        => esc_html__( 'Page Settings (custom metabox from theme)', 'learts' ),
				'object_types' => array( 'portfolio' ),
			);

			// tabs
			$tabs = array(
				'config' => $box_options,
				'layout' => 'vertical',
				'tabs'   => array(),
			);

			// logo
			$tabs['tabs'][] = array(
				'id'     => 'tab1',
				'title'  => esc_html__( 'Custom Logo', 'learts' ),
				'fields' => $logo_fields,
			);

			// Header
			$tabs['tabs'][] = array(
				'id'     => 'tab2',
				'title'  => esc_html__( 'Header', 'learts' ),
				'fields' => $header_fields,


			);

			// Off-Canvas Sidebar
			$offcanvas_fields[] = array(
				'name'    => esc_html__( 'Custom off-canvas sidebar', 'learts' ),
				'id'      => $this->prefix . 'offcanvas_custom_sidebar',
				'type'    => 'select',
				'options' => Learts_Helper::get_registered_sidebars( true ),
				'default' => 'sidebar-offcanvas',
			);

			$tabs['tabs'][] = array(
				'id'     => 'tab3',
				'title'  => esc_html__( 'Off-Canvas Sidebar', 'learts' ),
				'fields' => $offcanvas_fields,
			);

			// Menu
			$tabs['tabs'][] = array(
				'id'     => 'tab4',
				'title'  => esc_html__( 'Menu', 'learts' ),
				'fields' => $menu_fields,
			);

			// Page title
			$tabs['tabs'][] = array(
				'id'     => 'tab5',
				'title'  => esc_html__( 'Page Title', 'learts' ),
				'fields' => $page_title_fields,
			);

			// Breadcrumb
			$tabs['tabs'][] = array(
				'id'     => 'tab6',
				'title'  => esc_html__( 'Breadcrumbs', 'learts' ),
				'fields' => $breadcrumbs_fields,
			);

			// Sidebar
			$tabs['tabs'][] = array(
				'id'     => 'tab7',
				'title'  => esc_html__( 'Page Sidebar Options', 'learts' ),
				'fields' => array(
					$page_sidebar_config,

					// Custom sidebar.
					array(
						'name'     => esc_html__( 'Custom sidebar for this page', 'learts' ),
						'id'       => $this->prefix . 'page_custom_sidebar',
						'type'     => 'select',
						'options'  => Learts_Helper::get_registered_sidebars(),
						'multiple' => false,
						'default'  => 'sidebar',
					),
				),
			);

			$tabs['tabs'][] = array(
				'id'     => 'tab8',
				'title'  => esc_html__( 'Footer', 'learts' ),
				'fields' => $footer_fields,
			);

			// Page Meta
			$tabs['tabs'][] = array(
				'id'     => 'tab9',
				'title'  => esc_html__( 'Page Meta', 'learts' ),
				'fields' => array(
					// Extra Page Class.
					array(
						'name' => esc_html__( 'Page extra class name', 'learts' ),
						'id'   => $this->prefix . 'page_extra_class',
						'type' => 'text',
						'desc' => esc_html__( 'If you wish to add extra classes to the body class of the page (for custom css use), then please add the class(es) here.',
							'learts' ),
					),

					array(
						'name' => esc_html__( 'Link project', 'learts' ),
						'id'   => $this->prefix . 'link_project',
						'type' => 'text',
						'desc' => esc_html__( 'Show link URL in the description of project.',
							'learts' ),
					),
				),
			);

			$cmb = new_cmb2_box( $box_options );

			$cmb->add_field( array(
				'id'   => $this->prefix . 'page_tabs',
				'type' => 'tabs',
				'tabs' => $tabs,
			) );

			$this->transfer_options = array_merge( $this->transfer_options,
				$header_transfer_options,
				$offcanvas_transfer_options,
				$menu_transfer_options,
				$page_title_transfer_options,
				$breadcrumbs_transfer_options,
				$footer_transfer_options,
				array(
					'page_sidebar_config',
				) );
		}

		/**
		 * Meta boxes for Testimonials
		 *
		 * @return array
		 */
		public function testimonial_meta_boxes() {

			new_cmb2_box( array(
				'id'           => $this->prefix . 'testimonial_meta_box',
				'title'        => esc_html__( 'Testimonial Settings (custom metabox from theme)', 'learts' ),
				'object_types' => array( 'testimonial' ),
				'fields'       => array(
					array(
						'name' => __( 'Testimonial Cite', 'learts' ),
						'id'   => $this->prefix . 'testimonial_cite',
						'desc' => __( 'Enter the cite name for the testimonial.', 'learts' ),
						'type' => 'text',
					),

					array(
						'name' => __( 'Testimonial Cite Subtext', 'learts' ),
						'id'   => $this->prefix . 'testimonial_cite_subtext',
						'desc' => __( 'Enter the cite subtext for the testimonial (optional).', 'learts' ),
						'type' => 'text',
					),

					array(
						'name' => __( 'Testimonial Cite Image', 'learts' ),
						'desc' => __( 'Enter the cite image for the testimonial (optional).', 'learts' ),
						'id'   => $this->prefix . 'testimonial_cite_image',
						'type' => 'file',
					),

                    array(
                        'name' => __( 'Testimonial Rating Star', 'learts' ),
                        'id'   => $this->prefix . 'testimonial_rating_star',
                        'desc' => __( 'Enter the star for the testimonial rating', 'learts' ),
                        'type' => 'text',
                        'default'=>"5"
                    ),

                    array(
                        'name' => __( 'Testimonial Rating Title', 'learts' ),
                        'id'   => $this->prefix . 'testimonial_rating_title',
                        'desc' => __( 'Enter the title for the testimonial rating', 'learts' ),
                        'type' => 'text',
                        'default'=>"Very good !!"
                    ),
				),
			) );

		}

		/**
		 * Meta boxes for product
		 */
		public function product_meta_boxes() {

			$product_sidebar_config = $this->redux2metabox( 'product_sidebar_config' );

			$product_title_fields = array(
				// Show the post title on top
				array(
					'name'        => esc_html__( 'Display the Product title on top', 'learts' ),
					'id'          => $this->prefix . 'product_title_on_top',
					'type'        => 'checkbox',
					'description' => esc_html__( 'Enabling this option will display the title of this product on top',
						'learts' ),
				),
				// custom page title
				array(
					'name' => esc_html__( 'Custom Page Title', 'learts' ),
					'id'   => $this->prefix . 'custom_page_title',
					'type' => 'text',
				),
				// custom subtitle
				array(
					'name' => esc_html__( 'Sub Title', 'learts' ),
					'id'   => $this->prefix . 'subtitle',
					'type' => 'text',
				),
			);

			$product_fields = array(

				// Hide Related Products.
				array(
					'name'    => esc_html__( 'Hide Related Products', 'learts' ),
					'id'      => $this->prefix . 'hide_related_products',
					'type'    => 'checkbox',
					'desc'    => esc_html__( 'Check to hide related products on this page', 'learts' ),
					'default' => false,
				),

				// Instagram hash tag.
				array(
					'name' => esc_html__( 'Instagram Hashtag', 'learts' ),
					'id'   => $this->prefix . 'product_hashtag',
					'type' => 'text',
					'desc' => __( 'Enter hashtag will be used to display images from Instagram. (For example: <strong>#women</strong>)',
						'learts' ),
				),
			);

			$product_360 = array(
				// Show 360 product
				array(
					'name'        => esc_html__( 'Enable 360 product', 'learts' ),
					'id'          => $this->prefix . 'product_360_on',
					'type'        => 'checkbox',
					'description' => esc_html__( 'Enable 360 product displaying', 'learts' ),
				),

				// Select number of frames the image sprite.
				array(
					'name'        => esc_html__( 'Frames', 'learts' ),
					'id'          => $this->prefix . 'product_360_numbers',
					'type'        => 'select',
					'default'     => '8',
					'options'     => array(
						'8'  => esc_html__( '8 frames', 'learts' ),
						'16' => esc_html__( '16 frames', 'learts' ),
						'24' => esc_html__( '24 frames', 'learts' ),
					),
					'description' => esc_html__( 'Choose number of product???s frames. Eg: Select 16 frames if your product has 16 images',
						'learts' ),
				),

				// Image of 360 product
				array(
					'name'        => esc_html__( 'Upload product review image', 'learts' ),
					'id'          => $this->prefix . 'product_360_image_review',
					'type'        => 'file',
					'description' => esc_html__( 'Upload product???s review image which is shown at the beginning',
						'learts' ),
				),
				array(
					'name'        => esc_html__( 'Upload product 360 image', 'learts' ),
					'id'          => $this->prefix . 'product_360_image',
					'type'        => 'file',
					'description' => sprintf( wp_kses( __( 'Upload your 360 product???s image. You can read  <a href="%s" target="_blank">this blog</a> to know how to create a 360 product???s image',
						'learts' ),
						array(
							'a' => array(
								'href'   => array(),
								'target' => array(),
							),
						) ),
						esc_url( 'https://www.ecwid.com/blog/guide-to-360-product-photography.html' ) ),
				),
			);

			$product_transfer_options = array(
				'show_featured_images',
				'product_thumbnails_position',
				'product_page_layout',
				'product_bgcolor',
			);

			$product_title_transfer_options = array(
				'page_title_on',
				'disable_parallax',
				'remove_whitespace',
				'page_title_style',
				'page_title_text_color',
				'page_subtitle_color',
				'page_title_bg_color',
				'page_title_overlay_color',
				'page_title_bg_image',
			);

			$breadcrumbs_transfer_options = array(
				'breadcrumbs',
			);

			foreach ( $product_transfer_options as $option ) {
				$product_fields[] = $this->redux2metabox( $option, '', true );
			}

			foreach ( $product_title_transfer_options as $option ) {
				$product_title_fields[] = $this->redux2metabox( $option );
			}

			foreach ( $breadcrumbs_transfer_options as $option ) {
				$breadcrumbs_fields[] = $this->redux2metabox( $option );
			}

			$box_options = array(
				'id'           => $this->prefix . 'product_meta_box',
				'title'        => esc_html__( 'Product Settings (custom metabox from theme)', 'learts' ),
				'object_types' => array( 'product' ),
			);

			// tabs
			$tabs = array(
				'config' => $box_options,
				'layout' => 'vertical',
				'tabs'   => array(),
			);

			$tabs['tabs'][] = array(
				'id'     => 'tab1',
				'title'  => esc_html__( 'General', 'learts' ),
				'fields' => $product_fields,
			);

			$tabs['tabs'][] = array(
				'id'     => 'tab2',
				'title'  => esc_html__( 'Page Title', 'learts' ),
				'fields' => $product_title_fields,
			);

			$tabs['tabs'][] = array(
				'id'     => 'tab3',
				'title'  => esc_html__( 'Breadcrumbs', 'learts' ),
				'fields' => $breadcrumbs_fields,
			);

			$tabs['tabs'][] = array(
				'id'     => 'tab4',
				'title'  => esc_html__( 'Sidebar Options', 'learts' ),
				'fields' => array(
					$product_sidebar_config,

					// Custom sidebar.
					array(
						'name'     => esc_html__( 'Custom sidebar for this product', 'learts' ),
						'id'       => $this->prefix . 'product_custom_sidebar',
						'type'     => 'select',
						'options'  => Learts_Helper::get_registered_sidebars(),
						'multiple' => false,
						'default'  => 'sidebar-shop',
					),
				),
			);

			$tabs['tabs'][] = array(
				'id'     => 'tab5',
				'title'  => esc_html__( '360 Product', 'learts' ),
				'fields' => $product_360,
			);

			$cmb = new_cmb2_box( $box_options );

			$cmb->add_field( array(
				'id'   => $this->prefix . 'product_tabs',
				'type' => 'tabs',
				'tabs' => $tabs,
			) );

			$this->transfer_options = array_merge( $this->transfer_options,
				$product_transfer_options,
				$product_title_transfer_options,
				$breadcrumbs_transfer_options,
				array(
					'product_sidebar_config',
				) );

		}

		/**
		 * Meta boxes for post category
		 */
		public function post_category_meta_boxes() {

			$archive_fields = array();

			$archive_transfer_options = array(
				'archive_display_type',
				'page_title_on',
				'disable_parallax',
				'remove_whitespace',
				'page_title_style',
				'page_title_text_color',
				'page_title_bg_color',
				'page_title_overlay_color',
				'page_title_bg_image',
			);

			foreach ( $archive_transfer_options as $option ) {
				$archive_fields[] = $this->redux2metabox( $option );
			}

			new_cmb2_box( array(
				'id'           => $this->prefix . 'post_categories_meta_box',
				'title'        => esc_html__( 'Category Meta Box', 'learts' ),
				'object_types' => array( 'term' ),
				'taxonomies'   => array( 'category' ),
				'fields'       => $archive_fields,
			) );

			$this->transfer_options = array_merge( $this->transfer_options,
				$archive_transfer_options );
		}

		/**
		 * Meta box for product category
		 */
		public function product_category_meta_boxes() {

			$archive_fields = array(
				array(
					'name' => esc_html__( 'Thumbnail for Masonry layout', 'learts' ),
					'desc' => esc_html__( 'Use for \'Product Categories\' shortcode (WPBakery Page Builder)',
						'learts' ),
					'id'   => $this->prefix . 'product_cat_thumbnail_masonry',
					'type' => 'file',
				),
			);

			$archive_transfer_options = array(
				'page_title_on',
				'disable_parallax',
				'remove_whitespace',
				'page_title_style',
				'page_title_text_color',
				'page_title_bg_color',
				'page_title_overlay_color',
				'page_title_bg_image',
			);

			foreach ( $archive_transfer_options as $option ) {
				$archive_fields[] = $this->redux2metabox( $option );
			}

			new_cmb2_box( array(
				'id'           => $this->prefix . 'product_categories_meta_box',
				'title'        => esc_html__( 'Product Category Meta Box', 'learts' ),
				'object_types' => array( 'term' ),
				'taxonomies'   => array( 'product_cat' ),
				'fields'       => $archive_fields,
			) );

			$this->transfer_options = array_merge( $this->transfer_options,
				$archive_transfer_options );

		}

		/**
		 * Convert function from redux to CMB2
		 *
		 * @param string $field   field slug in Redux options
		 * @param string $type    field type
		 * @param string $default default value
		 * @param array  $unset   unset options
		 *
		 * @return array  $cmb_field  CMB compatible field config array
		 */
		private function redux2metabox( $field, $type = '', $default = '', $unset = array() ) {

			if ( ! class_exists( 'Redux' ) ) {

				return array(
					'id'      => '',
					'type'    => '',
					'name'    => '',
					'desc'    => '',
					'options' => '',
					'std'     => 'default',
					'default' => 'default',
				);
			}

			$field = Redux::getField( learts_Redux::$opt_name, $field );

			$options = $settings = array();

			switch ( $field['type'] ) {

				case 'image_select':

					$type    = $type ? $type : 'select';
					$default = $default ? $default : 'default';

					$options = ( ! empty( $field['options'] ) ) ? array_merge( array(
						'default' => array(
							'title' => esc_html__( 'Default',
								'learts' ),
						),
					),
						$field['options'] ) : array();
					foreach ( $options as $key => $option ) {
						$options[ $key ] = ( isset( $options[ $key ]['alt'] ) ) ? $options[ $key ]['alt'] : $options[ $key ]['title'];

						foreach ( $unset as $u ) {
							unset( $options[ $u ] );
						}
					}

					break;

				case 'button_set':

					$type    = $type ? $type : 'select';
					$default = $default ? $default : 'default';

					$options['default'] = esc_html__( 'Default', 'learts' );
					foreach ( $field['options'] as $key => $value ) {
						$options[ $key ] = $value;

						foreach ( $unset as $u ) {
							unset( $options[ $u ] );
						}
					}

					break;

				case 'select':

					$type    = $type ? $type : 'select';
					$default = $default ? $default : 'default';

					$options['default'] = esc_html__( 'Default', 'learts' );

					foreach ( $field['options'] as $key => $value ) {
						$options[ $key ] = $value;

						foreach ( $unset as $u ) {
							unset( $options[ $u ] );
						}
					}

					break;

				case 'switch':

					$type    = $type ? $type : 'select';
					$default = $default ? $default : 'default';

					$options['default'] = esc_html__( 'Default', 'learts' );
					$options['on']      = esc_html__( 'On', 'learts' );
					$options['off']     = esc_html__( 'Off', 'learts' );

					break;

				case 'slider':

					$type = 'slider';

					$settings = array(
						'min'  => isset( $field['min'] ) ? $field['min'] : 0,
						'max'  => isset( $field['max'] ) ? $field['max'] : 100,
						'step' => isset( $field['step'] ) ? $field['step'] : 1,
					);

					$default = learts_get_option( $field['id'] );

					break;

				case 'color':

					$type = 'colorpicker';

					if ( learts_get_option( $field['id'] ) == 'transparent' ) {
						$default = '';
					} else {
						$default = learts_get_option( $field['id'] );
					}

					break;

				case 'link_color':

					$type = 'colorpicker';

					if ( learts_get_option( $field['id'] ) == 'transparent' ) {
						$default = '';
					} else {
						$default = learts_get_option( $field['id'] );

						if ( is_array( $default ) && isset( $default['regular'] ) ) {
							$default = $default['regular'];
						} else {
							$default = '';
						}
					}

					break;

				case 'color_rgba':

					$type = 'rgba_colorpicker';
					$val  = learts_get_option( $field['id'] );

					if ( isset( $val['color'] ) && $val['color'] ) {
						$default = Learts_Helper::hex2rgba( $val['color'],
							( isset( $val['alpha'] ) && $val['alpha'] ) ? $val['alpha'] : 0 );
					}

					break;

				case 'media':


					$type = 'file';
					$val  = learts_get_option( $field['id'] );

					if ( isset( $val['url'] ) && $val['url'] ) {
						$default = $val['url'];
					}

					break;

				case 'background':

					$type = 'file';

					if ( isset( $field['default']['background-image'] ) && $field['default']['background-image'] ) {
						$default = $field['default']['background-image'];
					}

					break;

				default:
					$type    = $type ? $type : $field['type'];
					$default = $default ? $default : learts_get_option( $field['id'] );

					break;
			}

			$mb_field = array_merge( array(
				'id'      => $this->prefix . $field['id'],
				'type'    => $type,
				'name'    => $field['title'],
				'desc'    => isset( $field['subtitle'] ) ? $field['subtitle'] : '',
				'options' => $options,
				'default' => $default,
			),
				$settings );

			return $mb_field;
		}

		/**
		 * Modify global $learts_options variables
		 */
		public function modify_global_settings() {

			global $learts_options;

			if ( ! empty( $this->transfer_options ) ) {
				foreach ( $this->transfer_options as $field ) {
					$meta = get_post_meta( Learts_Helper::get_the_ID(), $this->prefix . $field, true );

					if ( isset( $meta ) && $meta != '' && $meta != 'inherit' && $meta != 'default' ) {

						if ( $meta == 'on' ) {
							$meta = true;
						} elseif ( $meta == 'off' ) {
							$meta = false;
						}

					} else {
						if ( isset( $learts_options[ $field ] ) ) {
							$meta = $learts_options[ $field ];
						}
					}

					$learts_options[ $field ] = $meta;
				}
			}
		}

	}

	new Learts_Metabox();
}
