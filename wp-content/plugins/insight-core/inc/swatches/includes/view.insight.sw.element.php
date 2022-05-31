<?php

$atts = Insight_Attribute_Swatches::insight_sw_get_attributes();

$styles = array(
	'isw_default' => __( 'Default - Select Box (Only available in single product page)', 'insight-core' ),
	'isw_text'    => __( 'Plain Text', 'insight-core' ),
	'isw_color'   => __( 'Color', 'insight-core' ),
	'isw_image'   => __( 'Image', 'insight-core' ),
	'isw_html'    => __( 'HTML', 'insight-core' )
);

if ( isset( $settings['isw_attr'] ) && ! empty( $settings['isw_attr'] ) ) {
	for ( $i = 0; $i < count( $settings['isw_attr'] ); $i ++ ) {
		?>

		<div class="isw_element" data-id="<?php echo esc_attr( $i ); ?>">
			<div class="isw_element_heading">
				<a href="#"
				   class="isw_attribute_title"><?php echo wc_attribute_label( $settings['isw_attr'][ $i ] ) ?></a>
				<a href="#" class="isw_remove"><i class="dashicons dashicons-trash"></i></a>
				<a href="#" class="isw_reorder"><i class="dashicons dashicons-move"></i></a>
				<a href="#" class="isw_slidedown"><i class="dashicons dashicons-arrow-down-alt2"></i></a>
				<div class="isw_clear"></div>
			</div>
			<div class="isw_element_content">
				<label>
					<span><?php esc_html_e( 'Select Attribute', 'insight-core' ); ?></span>
					<select class="isw_attr_select isw_s_attribute" name="isw_attr[<?php echo esc_attr( $i ); ?>]">
						<option value=""><?php esc_html_e( 'Select Attribute', 'insight-core' ); ?></option>

						<?php
						foreach ( $atts as $k => $v ) {
							$label = wc_attribute_label( $v ); ?>

							<option
								value="<?php echo esc_attr( $v ); ?>" <?php selected( $settings['isw_attr'][ $i ], $v ); ?>> <?php echo '' . $label; ?></option>

						<?php } ?>

					</select>
				</label>

				<label>
					<span><?php esc_html_e( 'Custom Attribute Name', 'insight-core' ); ?></span>
					<input type="text" name="isw_title[<?php echo esc_attr( $i ); ?>]"
					       value="<?php echo esc_attr( $settings['isw_title'][ $i ] ); ?>"/>
				</label>

				<label>
					<span><?php esc_html_e( 'Description', 'insight-core' ); ?></span>
					<textarea
						name="isw_desc[<?php echo esc_attr( $i ); ?>]"><?php esc_html_e( $settings['isw_desc'][ $i ] ); ?></textarea>
				</label>

				<label>
					<span><?php esc_html_e( 'Select Attribute Style', 'insight-core' ); ?></span>
					<select class="isw_attr_select isw_s_style" name="isw_style[<?php echo esc_attr( $i ); ?>]">
						<?php
						foreach ( $styles as $k => $v ) { ?>
							<option
								value="<?php echo esc_attr( $k ); ?>" <?php selected( $settings['isw_style'][ $i ], $k ) ?>><?php esc_html_e( $v ); ?></option>
						<?php } ?>
					</select>
				</label>

				<label>
					<input type="checkbox" name="isw_show_on_loop[<?php echo esc_attr( $i ); ?>]"
						<?php echo( isset( $settings['isw_show_on_loop'][ $i ] ) && $settings['isw_show_on_loop'][ $i ] == 'yes' ? ' checked="checked"' : '' ) ?>>
					<span
						class="isw_checkbox_desc"><?php esc_html_e( 'Show on products loop', 'insight-core' ) ?></span>
				</label>


				<div class="isw_terms">
					<?php
					$curr_tax   = $settings['isw_attr'][ $i ];
					$curr_style = $settings['isw_style'][ $i ];

					$catalog_attrs = get_terms( $curr_tax, array( 'hide_empty' => false ) );

					if ( ! empty( $catalog_attrs ) && ! is_wp_error( $catalog_attrs ) ) {


						switch ( $curr_style ) {
							case 'isw_text' :

								foreach ( $catalog_attrs as $term ) {
									?>
									<div class="isw_term" data-term="<?php echo $term->slug; ?>">
									<span class="isw_option isw_option_plaintext">
										<em><?php echo $term->name . ' ' . __( 'Tooltip', 'insight-core' ); ?></em>
										<input
											type="text"
											name="isw_tooltip[<?php echo $i; ?>][<?php echo $term->slug; ?>]"
											value="<?php echo( isset( $settings['isw_tooltip'][ $i ][ $term->slug ] ) ? $settings['isw_tooltip'][ $i ][ $term->slug ] : '' ); ?>"/>
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
										       name="isw_term[<?php echo $i; ?>][<?php echo $term->slug; ?>]"
										       value="<?php echo( isset( $settings['isw_custom'][ $i ][ $term->slug ] ) ? $settings['isw_custom'][ $i ][ $term->slug ] : '' ); ?>"/>
									</span>
										<span class="isw_option">
										<em><?php echo $term->name . ' ' . __( 'Tooltip', 'insight-core' ); ?></em>
										<input type="text"
										       name="isw_tooltip[<?php echo $i; ?>][<?php echo $term->slug; ?>]"
										       value="<?php echo( isset( $settings['isw_tooltip'][ $i ][ $term->slug ] ) ? $settings['isw_tooltip'][ $i ][ $term->slug ] : '' ); ?>"/>
									</span>
									</div>
									<?php
								}

								break;

							case 'isw_image' :
								foreach ( $catalog_attrs as $term ) { ?>
									<div class="isw_term" data-term="<?php echo $term->slug; ?>">
									<span class="isw_option">
										<em><?php echo $term->name . ' ' . __( 'Image URL', 'insight-core' ); ?></em>
										<input type="text"
										       name="isw_term[<?php echo $i; ?>][<?php echo $term->slug; ?>]"
										       value="<?php echo( isset( $settings['isw_custom'][ $i ][ $term->slug ] ) ? $settings['isw_custom'][ $i ][ $term->slug ] : '' ); ?>"/>
									</span>
										<span class="isw_option isw_option_button">
										<em><?php _e( 'Add/Upload image', 'insight-core' ); ?></em>
										<a href="#"
										   class="isw_upload_media button"><?php _e( 'Image Gallery', 'insight-core' ); ?></a>
									</span>
										<span class="isw_option">
										<em><?php echo $term->name . ' ' . __( 'Tooltip', 'insight-core' ); ?></em>
										<input type="text"
										       name="isw_tooltip[<?php echo $i; ?>][<?php echo $term->slug; ?>]"
										       value="<?php echo( isset( $settings['isw_tooltip'][ $i ][ $term->slug ] ) ? $settings['isw_tooltip'][ $i ][ $term->slug ] : '' ); ?>"/>
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

										<textarea type="text"
										          name="isw_term[<?php echo $i; ?>][<?php echo $term->slug; ?>]"><?php echo $settings['isw_custom'][ $i ][ $term->slug ]; ?></textarea>
									</span>
										<span class="isw_option">
										<em><?php echo $term->name . ' ' . __( 'Tooltip', 'insight-core' ); ?></em>
										<input type="text"
										       name="isw_tooltip[<?php echo $i; ?>][<?php echo $term->slug; ?>]"
										       value="<?php echo( isset( $settings['isw_tooltip'][ $i ][ $term->slug ] ) ? $settings['isw_tooltip'][ $i ][ $term->slug ] : '' ); ?>"/>
									</span>
									</div>
									<?php
								}

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
					}

					?>
				</div>

			</div>
		</div>

		<?php
	} // end for
} // end if