<?php

if ( ! class_exists( 'InsightCore_Sidebars' ) ) {

	class InsightCore_Sidebars {

		function __construct() {

			add_action( 'admin_menu', array( $this, 'register_menu' ) );
			add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
			add_action( 'widgets_init', array( $this, 'widgets_init' ) );

			add_action( 'wp_ajax_add_sidebar', array( $this, 'add_sidebar' ) );
			add_action( 'wp_ajax_remove_sidebar', array( $this, 'remove_sidebar' ) );
		}

		/**
		 * Adds menu item to admin menu
		 *
		 * @return    void
		 */
		public function register_menu() {

			add_menu_page(
				'Sidebars',
				'Sidebars',
				'manage_options',
				'insight-core-sidebars',
				array( $this, 'page' ),
				'dashicons-align-right',
				20
			);
		}

		public function enqueue_scripts() {
			$screen = get_current_screen();
			if ( strpos( $screen->id, 'page_insight-core-sidebars' ) !== false ) {
				wp_enqueue_script( 'ic-sidebars', plugin_dir_url( __FILE__ ) . 'js/sidebars.js', array( 'jquery-core' ), INSIGHT_CORE_THEME_VERSION, true );
			}
		}

		public function add_sidebar() {
			$sidebars = $this->get_sidebars();
			$name     = str_replace( array( "\n", "\r", "\t" ), '', $_POST['sidebar_name'] );

			$sidebar_id = sanitize_title( $name );

			$response = array();

			if ( isset( $sidebars[ $sidebar_id ] ) ) {
				$response['status']   = false;
				$response['messages'] = esc_html__( 'Sidebar already exists, please use a different name.', 'insight-core' );
			} else {
				$sidebars[ $sidebar_id ] = $name;
				$this->update_sidebars( $sidebars );
				$response['status']   = true;
				$response['messages'] = $sidebar_id;
			}

			wp_send_json( $response );
		}

		public function remove_sidebar() {
			$sidebars      = $this->get_sidebars();
			$sidebar_class = $_POST['sidebar_class'];

			$response = array();

			if ( ! isset( $sidebars[ $sidebar_class ] ) ) {
				$response['status']   = false;
				$response['messages'] = esc_html__( 'Sidebar does not exist.', 'insight-core' );
			} else {
				unset( $sidebars[ $sidebar_class ] );
				$this->update_sidebars( $sidebars );

				$response['status']   = true;
				$response['messages'] = '';
			}

			wp_send_json( $response );
		}

		public function page() {
			?>
            <div class="wrap">
                <h2><?php _e( 'Sidebars', 'insight-core' ); ?></h2>
                <div style="width:600px;">
                    <table class="wp-list-table widefat striped" id="insight-core-sidebars-table">
                        <thead>
                        <tr>
                            <th><?php _e( 'Sidebar Name', 'insight-core' ); ?></th>
                            <th><?php _e( 'CSS Class', 'insight-core' ); ?></th>
                            <th><?php _e( 'Remove', 'insight-core' ); ?></th>
                        </tr>
                        </thead>
                        <tbody>
						<?php
						$sidebars = $this->get_sidebars();
						if ( is_array( $sidebars ) && ! empty( $sidebars ) ) {
							foreach ( $sidebars as $class => $sidebar ) {
								?>
                                <tr>
                                    <td><?php echo $sidebar; ?></td>
                                    <td><?php echo $class; ?></td>
                                    <td><a href="javascript:void(0);" class="insight-core-remove-sidebar"
                                           data-sidebar="<?php echo $class; ?>">
                                            <i class="dashicons dashicons-trash"></i>
                                            <?php _e( 'Remove', 'insight-core' ) ?>
                                        </a>
                                    </td>
                                </tr>
								<?php
							}
						} else { ?>
                            <tr>
                                <td colspan="3"><?php _e( 'No Sidebars defined', 'insight-core' ); ?></td>
                            </tr>
						<?php } ?>
                        </tbody>
                    </table>
                    <br/>
                    <form action="" method="POST" id="insight-core-sidebars-form">
                    <p>
                        <input type="text" name="sidebar_name" id="sidebar_name"
                               placeholder="<?php _e( 'Sidebar Name', 'insight-core' ); ?>" class="widefat"/>
                    </p>
                    <p>
                        <button class="button button-primary" id="insight-core-add-sidebar" type="submit">
                            <i class="dashicons dashicons-plus"></i><?php _e( 'Add New Sidebar', 'insight-core' ); ?>
                        </button>
                    </p>
                    </form>
                </div>
            </div>
			<?php
		}

		/**
		 * replaces array of sidebar names
		 */
		public function update_sidebars( $sidebar_array ) {
			update_option( 'insight_core_sidebars', $sidebar_array );
		}

		/**
		 * gets the generated sidebars
		 */
		public function get_sidebars() {
			$sidebars = get_option( 'insight_core_sidebars' );

			return $sidebars;
		}

		public function widgets_init() {

			$sidebars = $this->get_sidebars();

			if ( function_exists( 'register_sidebar' ) && is_array( $sidebars ) ) {
				foreach ( $sidebars as $sidebar ) {
					$sidebar_id = sanitize_title( $sidebar );
					register_sidebar( array(
						'name'          => $sidebar,
						'id'            => strtolower( $sidebar_id ),
						'before_widget' => '<div id="%1$s" class="widget %2$s">',
						'after_widget'  => '</div>',
						'before_title'  => '<h5 class="widget-title">',
						'after_title'   => '</h5>'
					) );
				}
			}
		}
	}

	new InsightCore_Sidebars();
}