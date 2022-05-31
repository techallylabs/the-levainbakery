<?php

if ( ! class_exists( 'learts_Instagram_Widget' ) ) {

	add_action( 'widgets_init', 'load_learts_Instagram_Widget' );

	function load_learts_Instagram_Widget() {
		register_widget( 'learts_Instagram_Widget' );
	}

	/**
	 * Instagram Widget by ThemeMove.
	 */
	class learts_Instagram_Widget extends WP_Widget {

		private $sizes = array(
			'lg' => 'Large',
			'md' => 'Medium',
			'sm' => 'Small',
			'xs' => 'Extra small',
		);

		private $icons = array(
			'lg' => 'fa-desktop',
			'md' => 'fa-tablet fa-rotate-270',
			'sm' => 'fa-tablet',
			'xs' => 'fa-mobile',
		);

		private $responsive_data = array();

		private $responsive = '';

		private $item_width_list = array();

		/**
		 * Register widget with WordPress.
		 */
		function __construct() {
			parent::__construct( 'tm_instagram',
				'&#x1f4f7; &nbsp;' . esc_html__( 'Learts Instagram Feed', 'learts' ),
				array(
					'description' => esc_html__( 'Displays latest Instagram photos. Developed by ThemeMove.',
						'learts' ),
				) );

			$this->item_width_list = array(
				esc_html__( '1', 'learts' )  => 12,
				esc_html__( '2', 'learts' )  => 6,
				esc_html__( '3', 'learts' )  => 4,
				esc_html__( '4', 'learts' )  => 3,
				esc_html__( '6', 'learts' )  => 2,
				esc_html__( '12', 'learts' ) => 1,
			);
		}

		function widget( $args, $instance ) {
			extract( $args );

			$title               = isset( $instance['title'] ) ? $instance['title'] : '';
			$username            = isset( $instance['username'] ) ? $instance['username'] : '';
			$number_items        = isset( $instance['number_items'] ) ? $instance['number_items'] : '';
			$item_width_lg       = isset( $instance['item_width_lg'] ) ? $instance['item_width_lg'] : '';
			$item_width_md       = isset( $instance['item_width_md'] ) ? $instance['item_width_md'] : '';
			$item_width_sm       = isset( $instance['item_width_sm'] ) ? $instance['item_width_sm'] : '';
			$item_width_xs       = isset( $instance['item_width_xs'] ) ? $instance['item_width_xs'] : '';
			$hidden_lg           = isset( $instance['hidden_lg'] ) ? $instance['hidden_lg'] : '';
			$hidden_md           = isset( $instance['hidden_md'] ) ? $instance['hidden_md'] : '';
			$hidden_sm           = isset( $instance['hidden_sm'] ) ? $instance['hidden_sm'] : '';
			$hidden_xs           = isset( $instance['hidden_xs'] ) ? $instance['hidden_xs'] : '';
			$gap                 = isset( $instance['gap'] ) ? $instance['gap'] : '';
			$show_likes_comments = isset( $instance['show_likes_comments'] ) ? $instance['show_likes_comments'] : '';
			$follow_text         = isset( $instance['follow_text'] ) ? $instance['follow_text'] : '';
			$link_new_tab        = isset( $instance['link_new_tab'] ) ? $instance['link_new_tab'] : '';
			$square_media        = isset( $instance['square_media'] ) ? $instance['square_media'] : '';

			$this->responsive_data = array(
				'lg'        => $item_width_lg,
				'md'        => $item_width_md,
				'sm'        => $item_width_sm,
				'xs'        => $item_width_xs,
				'hidden_lg' => $hidden_lg,
				'hidden_md' => $hidden_md,
				'hidden_sm' => $hidden_sm,
				'hidden_xs' => $hidden_xs,
			);

			$this->responsive = join( ' ', $this->responsive_data );

			echo '' . $args['before_widget'];

			$output = $title ? $args['before_title'] . $title . $args['after_title'] : '';

			$class = '';
			if ( '' != $this->responsive ) {
				$class .= $this->responsive;
			} else {
				$class = $item_width_md;
			}

			// if hidden on device, find [class*=col] and replace to '', use only hidden.
			$class_container = preg_replace( '/col\-(lg|md|sm|xs)[^\s]*/', '', $class );
			$output          .= '<div class="tm-instagram ' . trim( $class_container ) . '">';
			if ( ! empty( $username ) ) {

				$media_array = learts_Instagram::get_instance()
				                               ->scrape_instagram( $username, $number_items, $square_media );

				if ( is_wp_error( $media_array ) ) {
					$output .= '<div class="tm-instagram--error"><p>' . $media_array->get_error_message() . '</p></div>';
				} else {
					$output .= '<div class="tm-instagram-pics row" style="margin:0 ' . ( $gap / 2 * - 1 ) . 'px">';
					foreach ( $media_array as $item ) {
						$output .= '<div class="item ' . trim( $class ) . '" style="padding:0 ' . ( $gap / 2 ) . 'px ' . $gap . 'px ' . ( $gap / 2 ) . 'px;">';

						if ( 'on' == $show_likes_comments ) {
							$output .= '<div class="item-info" style="width:calc(100% - ' . $gap . 'px);left:' . ( $gap / 2 ) . 'px">';
							$output .= '<div class="likes"><a href="' . esc_url( $item['link'] ) . '" target="' . ( $link_new_tab == 'on' ? '_blank' : '_self' ) . '" title="' . esc_attr( $item['description'] ) . '"><span>' . $item['likes'] . '</span></a></div>';
							$output .= '<div class="comments"><a href="' . esc_url( $item['link'] ) . '" target="' . ( $link_new_tab == 'on' ? '_blank' : '_self' ) . '" title="' . esc_attr( $item['description'] ) . '"><span>' . $item['comments'] . '</span></a></div>';
							$output .= '</div>';
						}

						$output .= '<img src="' . esc_url( $item['thumbnail'] ) . '" alt="image" class="item-image"/>';
						if ( 'video' == $item['type'] ) {
							$output .= '<span class="play-button">' . esc_html__( 'Play', 'learts' ) . '</span>';
						}

						$output .= '<div class="overlay" title="' . esc_attr( $item['description'] ) . '">';
						$output .= '<a href="' . esc_url( $item['link'] ) . '" target="' . ( $link_new_tab == 'on' ? '_blank' : '_self' ) . '">View on Instagram</a>';
						$output .= '</div>';

						$output .= '</div>';
					}
					$output .= '</div>';


					if ( $follow_text ) {
						$output .= '<h4 class="tm-instagram-follow-links btn-dash"><a href="https://www.instagram.com/' . $username . '">' . $follow_text . '</a></h4>';
					}
				}
			}

			$output .= '</div>';

			echo '' . $output;

			echo '' . $args['after_widget'];
		}

		function update( $new_instance, $old_instance ) {
			$instance = $old_instance;

			$instance['title']               = strip_tags( $new_instance['title'] );
			$instance['username']            = strip_tags( $new_instance['username'] );
			$instance['number_items']        = strip_tags( $new_instance['number_items'] );
			$instance['item_width_lg']       = strip_tags( $new_instance['item_width_lg'] );
			$instance['item_width_md']       = strip_tags( $new_instance['item_width_md'] );
			$instance['item_width_sm']       = strip_tags( $new_instance['item_width_sm'] );
			$instance['item_width_xs']       = strip_tags( $new_instance['item_width_xs'] );
			$instance['hidden_lg']           = strip_tags( $new_instance['hidden_lg'] );
			$instance['hidden_md']           = strip_tags( $new_instance['hidden_md'] );
			$instance['hidden_sm']           = strip_tags( $new_instance['hidden_sm'] );
			$instance['hidden_xs']           = strip_tags( $new_instance['hidden_xs'] );
			$instance['gap']                 = strip_tags( $new_instance['gap'] );
			$instance['show_likes_comments'] = strip_tags( $new_instance['show_likes_comments'] );
			$instance['follow_text']         = $new_instance['follow_text'];
			$instance['link_new_tab']        = strip_tags( $new_instance['link_new_tab'] );
			$instance['square_media']        = strip_tags( $new_instance['square_media'] );


			$this->responsive_data = array(
				'lg'        => $new_instance['item_width_lg'],
				'md'        => $new_instance['item_width_md'],
				'sm'        => $new_instance['item_width_sm'],
				'xs'        => $new_instance['item_width_xs'],
				'hidden_lg' => $new_instance['hidden_lg'],
				'hidden_md' => $new_instance['hidden_md'],
				'hidden_sm' => $new_instance['hidden_sm'],
				'hidden_xs' => $new_instance['hidden_xs'],
			);

			return $instance;
		}

		function form( $instance ) {

			// set up default settings.
			$defaults = array(
				'title'               => '',
				'username'            => '',
				'number_items'        => '6',
				'item_width_lg'       => '',
				'item_width_md'       => 'col-md-4',
				'item_width_sm'       => '',
				'item_width_xs'       => '',
				'hidden_lg'           => '',
				'hidden_md'           => '',
				'hidden_sm'           => '',
				'hidden_xs'           => '',
				'gap'                 => '0',
				'show_likes_comments' => '',
				'follow_text'         => esc_html__( 'Visit us on Instagram', 'learts' ),
				'link_new_tab'        => '',
				'square_media'        => 'on',
			);

			$instance = wp_parse_args( (array) $instance, $defaults );

			$this->responsive_data = array(
				'lg'        => $instance['item_width_lg'],
				'md'        => $instance['item_width_md'],
				'sm'        => $instance['item_width_sm'],
				'xs'        => $instance['item_width_xs'],
				'hidden_lg' => $instance['hidden_lg'],
				'hidden_md' => $instance['hidden_md'],
				'hidden_sm' => $instance['hidden_sm'],
				'hidden_xs' => $instance['hidden_xs'],
			);
			?>

			<p>
				<label
					for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_html_e( 'Title',
						'learts' ) ?></label>
				<input type="text" class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"
				       name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>"
				       value="<?php echo esc_attr( $instance['title'] ); ?>"/>
			</p>
			<p>
				<label
					for="<?php echo esc_attr( $this->get_field_id( 'username' ) ); ?>"><?php esc_html_e( 'User Name',
						'learts' ) ?></label>
				<input type="text" class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'username' ) ); ?>"
				       name="<?php echo esc_attr( $this->get_field_name( 'username' ) ); ?>"
				       value="<?php echo esc_attr( $instance['username'] ); ?>"/>
			</p>
			<p>
				<label
					for="<?php echo esc_attr( $this->get_field_id( 'number_items' ) ); ?>"><?php esc_html_e( 'Number of items',
						'learts' ) ?></label>
				<input type="text" class="widefat"
				       id="<?php echo esc_attr( $this->get_field_id( 'usenumber_itemsrname' ) ); ?>"
				       name="<?php echo esc_attr( $this->get_field_name( 'number_items' ) ); ?>"
				       value="<?php echo esc_attr( $instance['number_items'] ); ?>"/>
			</p>
			<p>
				<label
					for="<?php echo esc_attr( $this->get_field_id( 'item_width_md' ) ); ?>"><?php esc_html_e( 'Number of items in a row',
						'learts' ); ?></label>

				<?php echo '' . $this->sizeControl( 'md' ); ?>
			</p>
			<p>
				<label
					for="<?php echo esc_attr( $this->get_field_id( 'gap' ) ); ?>"><?php esc_html_e( 'Gap (in pixels)',
						'learts' ) ?></label>
				<input type="text" class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'gap' ) ); ?>"
				       name="<?php echo esc_attr( $this->get_field_name( 'gap' ) ); ?>"
				       value="<?php echo esc_attr( $instance['gap'] ); ?>"/>
			</p>
			<p>
				<label
					for="<?php echo esc_attr( $this->get_field_id( 'follow_text' ) ); ?>"><?php esc_html_e( 'Text',
						'learts' ) ?></label>
				<input type="text" class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'follow_text' ) ); ?>"
				       name="<?php echo esc_attr( $this->get_field_name( 'follow_text' ) ); ?>"
				       value="<?php echo esc_attr( $instance['follow_text'] ); ?>"/>
				<small class="description"><?php esc_html_e( 'Leave empty to hide it', 'learts' ); ?></small>
			</p>
			<p>
				<input type="checkbox" id="<?php echo esc_attr( $this->get_field_id( 'link_new_tab' ) ); ?>"
				       name="<?php echo esc_attr( $this->get_field_name( 'link_new_tab' ) ); ?>" <?php checked( $instance['link_new_tab'],
					'on' ); ?> />
				<label
					for="<?php echo esc_attr( $this->get_field_id( 'link_new_tab' ) ); ?>"><?php esc_html_e( 'Open links in new tab',
						'learts' ) ?></label>
			</p>
			<p>
				<input type="checkbox" id="<?php echo esc_attr( $this->get_field_id( 'show_likes_comments' ) ); ?>"
				       name="<?php echo esc_attr( $this->get_field_name( 'show_likes_comments' ) ); ?>" <?php checked( $instance['show_likes_comments'],
					'on' ); ?> />
				<label
					for="<?php echo esc_attr( $this->get_field_id( 'show_likes_comments' ) ); ?>"><?php esc_html_e( 'Show likes and comments',
						'learts' ) ?></label>
			</p>
			<p>
				<input type="checkbox" id="<?php echo esc_attr( $this->get_field_id( 'square_media' ) ); ?>"
				       name="<?php echo esc_attr( $this->get_field_name( 'square_media' ) ); ?>" <?php checked( $instance['square_media'],
					'on' ); ?> />
				<label
					for="<?php echo esc_attr( $this->get_field_id( 'square_media' ) ); ?>"><?php esc_html_e( 'Show square media',
						'learts' ) ?></label>
			</p>
			<strong><?php esc_html_e( 'Responsiveness', 'learts' ); ?></strong>
			<table class="tm_table tm_insta-responsive-table">
				<tr>
					<th><?php esc_html_e( 'Device', 'learts' ); ?></th>
					<th><?php esc_html_e( 'Number of items in a row', 'learts' ); ?></th>
					<th><?php esc_html_e( 'Hide on device?', 'learts' ); ?></th>
				</tr>
				<?php foreach ( $this->sizes as $key => $size ) { ?>
					<tr class="tm_size-<?php echo esc_attr( $key ); ?>">
						<td class="tm_screen-size tm_screen-size--<?php echo esc_attr( $key ); ?>">
							<i class="fa <?php echo esc_attr( $this->icons[ $key ] ); ?>"
							   title="<?php echo esc_attr( $size ) ?>"></i>
						</td>
						<td class="tm_item-width"><?php echo '' . $this->sizeControl( $key, false ); ?></td>
						<td>
							<label>
								<input type="checkbox"
								       name="<?php echo esc_attr( $this->get_field_name( 'hidden_' . $key ) ); ?>"
								       value="<?php echo 'hidden-' . esc_attr( $key ) . '-up'; ?>" <?php echo esc_attr( $this->responsive_data[ 'hidden_' . $key ] != '' ? ' checked="true"' : "" ); ?>/>
							</label>
						</td>
					</tr>
				<?php } ?>
			</table>
			<?php
		}

		/**
		 * Render size control for widget
		 *
		 * @param      $size_key
		 * @param bool $enable_md : Display select control for medium devices
		 *
		 * @return string
		 */
		private function sizeControl( $size_key, $enable_md = true ) {

			if ( $size_key == 'md' && ! $enable_md ) {
				return '<span class="description">' . esc_html__( 'Default value from "Number of items in a row" attribute',
						'learts' ) . '</span>';
			}

			$empty_label = $size_key === 'xs' ? '' : esc_html__( 'Inherit from smaller', 'learts' );
			$output      = '<select' . ' name="' . esc_attr( $this->get_field_name( 'item_width_' . $size_key ) ) . '">';
			if ( $size_key != 'md' ) {
				$output .= '<option value="" style="color: #ccc;">' . $empty_label . '</option>';
			}
			foreach ( $this->item_width_list as $label => $width ) {
				$value  = 'col-' . $size_key . '-' . $width;
				$output .= '<option value="' . esc_attr( $value ) . '"' . ( in_array( $value,
						$this->responsive_data ) ? ' selected="true"' : '' ) . '>' . $label . '</option>';
			}
			$output .= '</select>';

			return $output;
		}
	} // end class
} // end if
