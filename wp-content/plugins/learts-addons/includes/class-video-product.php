<?php
// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
if ( ! class_exists( 'Learts_Video_Product' ) ) {

	class Learts_Video_Product {

		public function __construct() {
			add_action( 'add_meta_boxes', array( $this, 'video_box' ) );
			add_action( 'save_post', array( $this, 'learts_save_video_box' ) );
		}

		public function video_box( $post_type ) {
			add_meta_box( 'some_meta_box_name',
				esc_html__( 'Featured Video', 'learts' ),
				array( $this, 'render_video_box' ),
				'product',
				'side',
				'low' );
		}

		public function render_video_box( $post ) {

			// Add an nonce field so we can check for it later.
			wp_nonce_field( 'learts_video_box', 'learts_video_box_nonce' );

			// Use get_post_meta to retrieve an existing value from the database.
			$learts_video_embed = maybe_unserialize( get_post_meta( $post->ID, '_learts_video_embed', true ) );

			$url = isset( $learts_video_embed['url'] ) ? $learts_video_embed['url'] : '';

			// Display the form, using the current value.
			?>
			<p style="display:none"><?php esc_html_e( 'Video URL', 'learts' ); ?></p>
			<br/>
			<textarea id="learts_video_embed_url" name="learts_video_embed[url]" style="width: 98%"
			          placeholder="Video URL"><?php echo esc_attr( $url, 'learts' ); ?></textarea>
			<?php
		}

		public function learts_save_video_box( $post_id ) {
			/*
			* We need to verify this came from the our screen and with proper authorization,
			* because save_post can be triggered at other times.
			*/

			// Check if our nonce is set.
			if ( ! isset( $_POST['learts_video_box_nonce'] ) ) {
				return $post_id;
			}

			$nonce = $_POST['learts_video_box_nonce'];

			// Verify that the nonce is valid.
			if ( ! wp_verify_nonce( $nonce, 'learts_video_box' ) ) {
				return $post_id;
			}

			/*
			  * If this is an autosave, our form has not been submitted,
			  * so we don't want to do anything.
			  */
			if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
				return $post_id;
			}

			// Check the user's permissions.
			if ( 'product' == $_POST['post_type'] ) {
				if ( ! current_user_can( 'edit_page', $post_id ) ) {
					return $post_id;
				}
			} else {
				if ( ! current_user_can( 'edit_post', $post_id ) ) {
					return $post_id;
				}
			}

			/* OK, it's safe for us to save the data now. */

			// Sanitize the user input.
			$learts_data = array_map( 'sanitize_text_field', $_POST['learts_video_embed'] );

			// Update the meta field.
			update_post_meta( $post_id, '_learts_video_embed', $learts_data );
		}

		public static function get_product_video() {
			$meta = get_post_meta( get_the_ID(), '_learts_video_embed', true );
			$url  = isset( $meta['url'] ) ? $meta['url'] : '';

			return $url;
		}
	}

	new Learts_Video_Product();
}
