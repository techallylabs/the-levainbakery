<?php
/**
 * Insight Attribute Swatches Settings Class
 */

if ( ! class_exists( 'Insight_Attribute_Swatches_Settings' ) ) {

	class Insight_Attribute_Swatches_Settings {

		function __construct() {
			add_action( 'init', array( &$this, 'init' ) );
		}

		public function init() {
			add_action( 'admin_enqueue_scripts', array( &$this, 'insight_sw_scripts' ) );

			add_filter( 'woocommerce_settings_tabs_array', array( &$this, 'insight_sw_add_settings_tab' ), 50 );
			add_action( 'woocommerce_settings_tabs_insight_sw', array( &$this, 'insight_sw_settings_tabs' ) );
			add_action( 'woocommerce_update_options_insight_sw', array( &$this, 'insight_sw_save_settings' ) );
			add_action( 'wp_ajax_insight_sw_get_fields', array( &$this, 'insight_sw_get_fields' ) );
			add_action( 'wp_ajax_insight_sw_get_terms', array( &$this, 'insight_sw_get_terms' ) );
		}

		/**
		 * Enqueue styles & scripts
		 *
		 * @param $settings_tabs
		 */
		public function insight_sw_scripts( $settings_tabs ) {
			if ( isset( $_GET['page'], $_GET['tab'] ) && ( $_GET['page'] == 'wc-settings' || $_GET['page'] == 'woocommerce_settings' ) && $_GET['tab'] == 'insight_sw' ) {

				wp_enqueue_style( 'isw-style', INSIGHT_SW_URL . 'assets/css/admin.css', INSIGHT_SW_VERSION );

				wp_enqueue_script( 'isw-admin', INSIGHT_SW_URL . 'assets/js/admin.min.js', array(
					'jquery',
					'jquery-ui-core',
					'jquery-ui-sortable'
				), INSIGHT_SW_VERSION, true );

				$curr_args = array(
					'ajax' => admin_url( 'admin-ajax.php' ),
				);
				wp_localize_script( 'isw-admin', 'isw', $curr_args );

				if ( function_exists( 'wp_enqueue_media' ) ) {
					wp_enqueue_media();
				}

				wp_enqueue_style( 'wp-color-picker' );
				wp_enqueue_script( 'wp-color-picker' );
			}
		}

		/**
		 * Add setting tabs to WooCommerce Settings page
		 *
		 * @param $settings_tabs
		 *
		 * @return mixed
		 */
		public function insight_sw_add_settings_tab( $settings_tabs ) {
			$settings_tabs['insight_sw'] = __( 'Attribute Swatches', 'isw' );

			return $settings_tabs;
		}

		/**
		 * Get all settings
		 */
		public function insight_sw_settings_tabs() {
			woocommerce_admin_fields( $this->insight_sw_get_settings( 'get' ) );
		}

		/**
		 * Get setting from DB
		 *
		 * @param string $action
		 *
		 * @return mixed|void
		 */
		public function insight_sw_get_settings( $action = 'get' ) {

			$settings = array();

			if ( $action == 'get' ) {
				?>
				<div id="isw_manager" class="isw_manager">
					<h3><?php _e( 'Attribute Swatches Manager', 'insight-core' ); ?></h3>
					<p><?php _e( 'Use the drag and drop manager bellow to customize attribute swatches.', 'insight-core' ); ?></p>
					<div class="isw_fields">
						<a href="#"
						   class="isw_add_new_btn button-primary"><?php _e( 'Add New Attribute Swatch', 'insight-core' ); ?></a>
					</div>
					<div class="isw_items">
						<?php

						$lang = Insight_Attribute_Swatches::insight_sw_wpml_lang();

						if ( isset( $_POST['isw_attr'] ) ) {

							$isw_attrs = array();
							for ( $i = 0; $i < count( $_POST['isw_attr'] ); $i ++ ) {

								if ( $_POST['isw_attr'][ $i ] !== '' ) {

									$isw_attrs['isw_attr'][ $i ]         = $_POST['isw_attr'][ $i ];
									$isw_attrs['isw_title'][ $i ]        = stripslashes( $_POST['isw_title'][ $i ] );
									$isw_attrs['isw_desc'][ $i ]         = stripslashes( $_POST['isw_desc'][ $i ] );
									$isw_attrs['isw_style'][ $i ]        = $_POST['isw_style'][ $i ];
									$isw_attrs['isw_show_on_loop'][ $i ] = isset( $_POST['isw_show_on_loop'][ $i ] ) ? 'yes' : 'no';

									switch ( $isw_attrs['isw_style'][ $i ] ) {

										case 'isw_text' :
											foreach ( $_POST['isw_tooltip'][ $i ] as $k => $v ) {
												$isw_attrs['isw_tooltip'][ $i ][ $k ] = $v;
											}
											break;

										case 'isw_color' :
											foreach ( $_POST['isw_term'][ $i ] as $k => $v ) {
												$isw_attrs['isw_custom'][ $i ][ $k ] = $v;
											}
											foreach ( $_POST['isw_tooltip'][ $i ] as $k => $v ) {
												$isw_attrs['isw_tooltip'][ $i ][ $k ] = $v;
											}
											break;

										case 'isw_image' :
											foreach ( $_POST['isw_term'][ $i ] as $k => $v ) {
												$isw_attrs['isw_custom'][ $i ][ $k ] = $v;
											}
											foreach ( $_POST['isw_tooltip'][ $i ] as $k => $v ) {
												$isw_attrs['isw_tooltip'][ $i ][ $k ] = $v;
											}
											break;

										case 'isw_html' :
											foreach ( $_POST['isw_term'][ $i ] as $k => $v ) {
												$isw_attrs['isw_custom'][ $i ][ $k ] = stripslashes( $v );
											}
											foreach ( $_POST['isw_tooltip'][ $i ] as $k => $v ) {
												$isw_attrs['isw_tooltip'][ $i ][ $k ] = $v;
											}
											break;

										default :
											break;

									}
								}
							}

							$settings = $isw_attrs;

						} else {
							if ( $lang === false ) {
								$settings = get_option( 'isw_settings', '' );
							} else {
								$settings = get_option( 'isw_settings_' . $lang, '' );
							}
						}

						if ( $settings == '' ) {
							$settings = array();
						}

						if ( ! empty( $settings ) ) {
							require_once( INSIGHT_SW_PATH . 'includes/view.insight.sw.element.php' );
						}
						?>
					</div>
				</div>
				<?php
			}

			return apply_filters( 'insight_sw_settings', $settings );
		}

		/**
		 * Save settings
		 */
		public function insight_sw_save_settings() {

			if ( isset( $_POST['isw_attr'] ) ) {

				$isw_attrs = array();

				for ( $i = 0; $i < count( $_POST['isw_attr'] ); $i ++ ) {

					if ( $_POST['isw_attr'][ $i ] !== '' ) {

						$isw_attrs['isw_attr'][ $i ]         = $_POST['isw_attr'][ $i ];
						$isw_attrs['isw_title'][ $i ]        = stripslashes( $_POST['isw_title'][ $i ] );
						$isw_attrs['isw_desc'][ $i ]         = stripslashes( $_POST['isw_desc'][ $i ] );
						$isw_attrs['isw_style'][ $i ]        = $_POST['isw_style'][ $i ];
						$isw_attrs['isw_show_on_loop'][ $i ] = isset( $_POST['isw_show_on_loop'][ $i ] ) ? 'yes' : 'no';

						switch ( $isw_attrs['isw_style'][ $i ] ) {

							case 'isw_text' :
								foreach ( $_POST['isw_tooltip'][ $i ] as $k => $v ) {
									$isw_attrs['isw_tooltip'][ $i ][ $k ] = $v;
								}
								break;

							case 'isw_color' :
								foreach ( $_POST['isw_term'][ $i ] as $k => $v ) {
									$isw_attrs['isw_custom'][ $i ][ $k ] = $v;
								}
								foreach ( $_POST['isw_tooltip'][ $i ] as $k => $v ) {
									$isw_attrs['isw_tooltip'][ $i ][ $k ] = $v;
								}
								break;

							case 'isw_image' :
								foreach ( $_POST['isw_term'][ $i ] as $k => $v ) {
									$isw_attrs['isw_custom'][ $i ][ $k ] = $v;
								}
								foreach ( $_POST['isw_tooltip'][ $i ] as $k => $v ) {
									$isw_attrs['isw_tooltip'][ $i ][ $k ] = $v;
								}
								break;

							case 'isw_html' :
								foreach ( $_POST['isw_term'][ $i ] as $k => $v ) {
									$isw_attrs['isw_custom'][ $i ][ $k ] = stripslashes( $v );
								}
								foreach ( $_POST['isw_tooltip'][ $i ] as $k => $v ) {
									$isw_attrs['isw_tooltip'][ $i ][ $k ] = $v;
								}
								break;

							default :
								break;

						}
					}
				}
			} else {
				$isw_attrs = array();
			}

			woocommerce_update_options( $this->insight_sw_get_settings( 'update' ) );

			$get_language = Insight_Attribute_Swatches::insight_sw_wpml_lang();

			if ( $get_language === false ) {
				update_option( 'isw_settings', $isw_attrs );
			} else {
				update_option( 'isw_settings_' . $get_language, $isw_attrs );
			}

		}

		/**
		 * Get fields
		 */
		public function insight_sw_get_fields() {

			$attributes = Insight_Attribute_Swatches::insight_sw_get_attributes();

			$html = '';

			$html .= '<label><span>' . __( 'Select Attribute', 'insight-core' ) . '</span> <select class="isw_attr_select isw_s_attribute" name="isw_attr[%%]">';

			$html .= '<option value="">' . __( 'Select Attribute', 'insight-core' ) . '</option>';

			foreach ( $attributes as $k => $v ) {
				$curr_label = wc_attribute_label( $v );
				$html .= '<option value="' . $v . '">' . $curr_label . '</option>';
			}

			$html .= '</select></label>';

			$html .= '<label><span>' . __( 'Override Attribute Name', 'insight-core' ) . '</span> <input type="text" name="isw_title[%%]" /></label>';

			$html .= '<label><span>' . __( 'Add Attribute Description', 'insight-core' ) . '</span> <textarea name="isw_desc[%%]"></textarea></label>';

			$html .= '<label><span>' . __( 'Select Attribute Style', 'insight-core' ) . '</span> <select class="isw_attr_select isw_s_tyle" name="isw_style[%%]">';

			$styles = array(
				'isw_default' => __( 'Default - Select Box (Only available in single product page)', 'insight-core' ),
				'isw_text'    => __( 'Plain Text', 'insight-core' ),
				'isw_color'   => __( 'Color', 'insight-core' ),
				'isw_image'   => __( 'Image', 'insight-core' ),
				'isw_html'    => __( 'HTML', 'insight-core' )
			);

			$c = 0;
			foreach ( $styles as $k => $v ) {
				$html .= '<option value="' . $k . '" ' . ( $c == 0 ? ' selected="selected"' : '' ) . '>' . $v . '</option>';
				$c ++;
			}

			$html .= '</select></label>';

			$html .= '<label><input type="checkbox" name="isw_show_on_loop[%%]" /> <span class="isw_checkbox_desc">' . esc_html__( 'Show on products loop', 'insight-core' ) . '</span></label>';

			$html .= '<div class="isw_terms">';

			$html .= '</div>';

			die( $html );
			exit;

		}

		/**
		 * Get all variable terms
		 */
		public function insight_sw_get_terms() {

			$curr_tax   = ( isset( $_POST['taxonomy'] ) ? $_POST['taxonomy'] : '' );
			$curr_style = ( isset( $_POST['style'] ) ? $_POST['style'] : '' );

			if ( $curr_tax == '' || $curr_style == '' ) {
				die();
				exit;
			}

			$catalog_attrs = get_terms( $curr_tax, array( 'hide_empty' => false ) );

			if ( ! empty( $catalog_attrs ) && ! is_wp_error( $catalog_attrs ) ) {

				ob_start();

				switch ( $curr_style ) {

					case 'isw_text' :

						foreach ( $catalog_attrs as $term ) { ?>
							<div class="isw_term" data-term="<?php echo $term->slug; ?>">
								<span class="isw_option isw_option_plaintext">
									<em><?php echo $term->name . ' ' . __( 'Tooltip', 'insight-core' ); ?></em>
									<input type="text" name="isw_tooltip[%%][<?php echo $term->slug; ?>]""/>
								</span>
							</div>
						<?php }

						break;

					case 'isw_color' :

						foreach ( $catalog_attrs as $term ) { ?>
							<div class="isw_term" data-term="<?php echo $term->slug; ?>">
								<span class="isw_option isw_option_color">
									<em><?php echo $term->name . ' ' . __( 'Color', 'insight-core' ); ?></em>
									<input class="isw_color" type="text"
									       name="isw_term[%%][<?php echo $term->slug; ?>]" value="#cccccc"/>
								</span>
								<span class="isw_option">
									<em><?php echo $term->name . ' ' . __( 'Tooltip', 'insight-core' ); ?></em>
									<input type="text" name="isw_tooltip[%%][<?php echo $term->slug; ?>]""/>
								</span>
							</div>
						<?php }

						break;

					case 'isw_image' :

						foreach ( $catalog_attrs as $term ) {

							?>
							<div class="isw_term" data-term="<?php echo $term->slug; ?>">
								<span class="isw_option">
									<em><?php echo $term->name . ' ' . __( 'Image URL', 'insight-core' ); ?></em> <input
										type="text" name="isw_term[%%][<?php echo $term->slug; ?>]"/>
								</span>
								<span class="isw_option isw_option_button">
									<em><?php _e( 'Add/Upload image', 'insight-core' ); ?></em>
									<a href="#"
									   class="isw_upload_media button"><?php _e( 'Image Gallery', 'insight-core' ); ?></a>
								</span>
								<span class="isw_option">
									<em><?php echo $term->name . ' ' . __( 'Tooltip', 'insight-core' ); ?></em>
									<input type="text" name="isw_tooltip[%%][<?php echo $term->slug; ?>]""/>
								</span>
							</div>
							<?php
						}

						break;

					case 'isw_html' :

						foreach ( $catalog_attrs as $term ) { ?>
							<div class="isw_term" data-term="<?php echo $term->slug; ?>">
								<span class="isw_option isw_option_text">
									<em><?php echo $term->name . ' ' . __( 'HTML', 'insight-core' ); ?></em>
									<textarea type="text" name="isw_term[%%][<?php echo $term->slug; ?>]"></textarea>
								</span>
								<span class="isw_option">
									<em><?php echo $term->name . ' ' . __( 'Tooltip', 'insight-core' ); ?></em>
									<input type="text" name="isw_tooltip[%%][<?php echo $term->slug; ?>]"/>
								</span>
							</div>
						<?php }

						break;

					case 'isw_default' :
						?>
						<div class="isw_selectbox"><i class="dashicons dashicons-warning"></i>
							<span><?php _e( 'This style has no extra settings!', 'insight-core' ); ?></span>
						</div>
						<?php
						break;

					default :
						break;

				}

				$html = ob_get_clean();

				die( $html );
				exit;

			} else {
				die();
				exit;
			}

		}
	}
}

new Insight_Attribute_Swatches_Settings();