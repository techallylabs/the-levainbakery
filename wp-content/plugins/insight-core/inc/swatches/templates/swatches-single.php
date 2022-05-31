<?php

global $product;

$html = 'class="isw-swatches isw-swatches--in-single variations_form cart"';

do_action( 'woocommerce_before_add_to_cart_form' ); ?>

<form <?php echo( $html ); ?>
	method="post"
	enctype="multipart/form-data"
	data-product_id="<?php echo absint( get_the_ID() ); ?>"
	data-product_variations="<?php echo esc_attr( json_encode( $available_variations ) ) ?>"
	<?php echo( $lang !== false ? ' data-lang="' . ICL_LANGUAGE_CODE . '"' : '' ); ?>>

	<?php

	do_action( 'woocommerce_before_variations_form' );

	if ( empty( $available_variations ) && false !== $available_variations ) {
		?>
		<p class="stock out-of-stock"><?php esc_html__( 'This product is currently out of stock and unavailable.', 'insight-core' ) ?></p>
	<?php } else {
		?>
		<table class="variations" cellspacing="0">
			<tbody>
			<?php

			$isw_attributes = array(); // which settings have custom display
			$o_attributes   = array(); // which settings doesn't have custom display

			foreach ( $attributes as $attribute_name => $options ) {

				if ( $options['is_variation'] == '1' ) {
					if ( isset( $settings['isw_attr'] ) && is_array( $settings['isw_attr'] )
					     && in_array( Insight_Attribute_Swatches_Utils::utf8_urldecode( $attribute_name ), $settings['isw_attr'] )
					) {
						$isw_attributes[ array_search( Insight_Attribute_Swatches_Utils::utf8_urldecode( $attribute_name ), $settings['isw_attr'] ) ] = $attribute_name;
					} else {
						$o_attributes[ $attribute_name ] = $options['name'];
					}
				}
			}

			ksort( $isw_attributes );

			$isw_attributes = $isw_attributes + $o_attributes;

			$cnt = 0;
			foreach ( $isw_attributes as $attribute_idx => $attribute_name ) {

				$cnt ++;

				$term_sanitized = Insight_Attribute_Swatches_Utils::utf8_urldecode( $attribute_name );

				if ( ! isset( $variation_attributes[ $term_sanitized ] ) || ! is_array( $variation_attributes[ $term_sanitized ] ) ) {
					continue;
				}

				$curr['style']        = ( isset( $settings['isw_style'][ $attribute_idx ] ) ? $settings['isw_style'][ $attribute_idx ] : 'isw_default' );
				$curr['title']        = ( isset( $settings['isw_title'][ $attribute_idx ] ) ? $settings['isw_title'][ $attribute_idx ] : wc_attribute_label( $attribute_name ) );
				$curr['desc']         = ( isset( $settings['isw_desc'][ $attribute_idx ] ) ? $settings['isw_desc'][ $attribute_idx ] : '' );
				$curr['show_on_loop'] = ( isset( $settings['isw_show_on_loop'][ $attribute_idx ] ) ? $settings['isw_show_on_loop'][ $attribute_idx ] : 'no' );
				$curr['custom']       = ( isset( $settings['isw_custom'][ $attribute_idx ] ) ? $settings['isw_custom'][ $attribute_idx ] : array() );
				$curr['tooltip']      = ( isset( $settings['isw_tooltip'][ $attribute_idx ] ) ? $settings['isw_tooltip'][ $attribute_idx ] : array() );

				if ( taxonomy_exists( $term_sanitized ) ) {
					$curr['terms'] = get_terms( $term_sanitized, array( 'hide_empty' => false ) );
				} else {
					if ( isset( $attributes[ $attribute_name ] ) ) {
						$custom_vals = array_map( 'trim', explode( WC_DELIMITER, $attributes[ $attribute_name ]['value'] ) );
						foreach ( $custom_vals as $cv ) {
							$curr['terms'][ $cv ]       = new stdClass();
							$curr['terms'][ $cv ]->name = ucfirst( $cv );
							$curr['terms'][ $cv ]->slug = $cv;
						}
					}
				}

				?>
				<tr>
					<td class="label">
						<label
							for="<?php echo sanitize_title( $attribute_name ); ?>"><?php echo( $curr['title'] ? $curr['title'] : wc_attribute_label( $attribute_name ) ); ?>
							:</label>
					</td>
					<td class="value">
						<div
							class="isw-swatch isw-swatch--<?php echo esc_attr( $curr['style'] ); ?>"
							data-attribute="<?php echo esc_attr( $attribute_name ); ?>">
							<?php

							switch ( $curr['style'] ) {
								case 'isw_text' :
									foreach ( $curr['terms'] as $l => $b ) {

										if ( ! in_array( $b->slug, $variation_attributes[ $term_sanitized ] ) ) {
											continue;
										}

										if ( isset( $curr['tooltip'][ $b->slug ] ) && ! empty( $curr['tooltip'][ $b->slug ] ) ) {
											$tooltip = $curr['tooltip'][ $b->slug ];
										} else {
											$tooltip = $b->name;
										}
										?>
										<span
											class="isw-term hint--top hint--rounded hint--bounce"
											data-term="<?php echo esc_attr( $b->slug ); ?>"
											aria-label="<?php echo esc_attr( $tooltip ); ?>"><?php echo $b->name; ?></span>
										<?php
									}
									break;
								case 'isw_color':
									foreach ( $curr['terms'] as $l => $b ) {

										if ( ! in_array( $b->slug, $variation_attributes[ $term_sanitized ] ) ) {
											continue;
										}

										if ( isset( $curr['tooltip'][ $b->slug ] ) && ! empty( $curr['tooltip'][ $b->slug ] ) ) {
											$tooltip = $curr['tooltip'][ $b->slug ];
										} else {
											$tooltip = $b->name;
										}
										?>
										<span
											class="isw-term hint--top hint--rounded hint--bounce"
											data-term="<?php echo esc_attr( $b->slug ); ?>"
											style="background-color: <?php echo $curr['custom'][ $b->slug ]; ?>"
											aria-label="<?php echo esc_attr( $tooltip ); ?>"><?php echo $b->name; ?></span>
										<?php
									}
									break;
								case 'isw_image':
									foreach ( $curr['terms'] as $l => $b ) {

										if ( ! in_array( $b->slug, $variation_attributes[ $term_sanitized ] ) ) {
											continue;
										}

										if ( isset( $curr['tooltip'][ $b->slug ] ) && ! empty( $curr['tooltip'][ $b->slug ] ) ) {
											$tooltip = $curr['tooltip'][ $b->slug ];
										} else {
											$tooltip = $b->name;
										}
										?>
										<span
											class="isw-term hint--top hint--rounded hint--bounce"
											data-term="<?php echo esc_attr( $b->slug ); ?>"
											aria-label="<?php echo esc_attr( $tooltip ); ?>"><img
												src="<?php echo $curr['custom'][ $b->slug ]; ?>"
												alt="<?php echo $b->name; ?>"/></span>
										<?php

									}
									break;
								case 'isw_html':
									foreach ( $curr['terms'] as $l => $b ) {

										if ( ! in_array( $b->slug, $variation_attributes[ $term_sanitized ] ) ) {
											continue;
										}

										if ( isset( $curr['tooltip'][ $b->slug ] ) && ! empty( $curr['tooltip'][ $b->slug ] ) ) {
											$tooltip = $curr['tooltip'][ $b->slug ];
										} else {
											$tooltip = $b->name;
										}
										?>
										<span
											class="isw-term hint--top hint--rounded hint--bounce"
											data-term="<?php echo esc_attr( $b->slug ); ?>"
											aria-label="<?php echo esc_attr( $tooltip ); ?>"><?php echo $curr['custom'][ $b->slug ]; ?></span>
										<?php
									}
									break;
								default:
									break;
							}
							?>

						</div>
						<?php

						if ( isset( $_REQUEST[ 'attribute_' . sanitize_title( $attribute_name ) ] ) ) {
							$selected = $_REQUEST[ 'attribute_' . sanitize_title( $attribute_name ) ];
						} else if ( isset( $selected_attributes[ sanitize_title( $attribute_name ) ] ) ) {
							$selected = $selected_attributes[ sanitize_title( $attribute_name ) ];
						} else {
							$selected = '';
						}

						$args = array(
							'options'   => $variation_attributes[ $term_sanitized ],
							'attribute' => $attribute_name,
							'product'   => $product,
							'selected'  => $selected
						);

						if ( $curr['style'] == 'isw_default' ) {
							$args['class'] = 'isw-selectbox';
						}

						wc_dropdown_variation_attribute_options( $args );

						if ( $cnt == count( $isw_attributes ) ) {
							?>
							<a class="reset_variations" href="#"><?php esc_html_e( 'Clear', 'insight-core' ); ?></a>
							<?php
						}

						?>
					</td>
				</tr>
			<?php } ?>

			</tbody>
		</table>

		<div class="single_variation_wrap">
			<?php
			/**
			 * woocommerce_before_single_variation Hook
			 */
			do_action( 'woocommerce_before_single_variation' );

			/**
			 * woocommerce_single_variation hook. Used to output the cart button and placeholder for variation data.
			 *
			 * @since  2.4.0
			 * @hooked woocommerce_single_variation - 10 Empty div for variation data.
			 * @hooked woocommerce_single_variation_add_to_cart_button - 20 Qty and cart button.
			 */
			do_action( 'woocommerce_single_variation' );

			/**
			 * woocommerce_after_single_variation Hook
			 */
			do_action( 'woocommerce_after_single_variation' );
			?>
		</div>
		<?php do_action( 'woocommerce_after_add_to_cart_button' ); ?>
	<?php } ?>

	<?php do_action( 'woocommerce_after_variations_form' ); ?>

</form>

<?php do_action( 'woocommerce_after_add_to_cart_form' ); ?>
