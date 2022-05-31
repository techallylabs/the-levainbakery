<?php

$html = 'class="isw-swatches isw-swatches--in-loop" ';

if ( has_post_thumbnail() ) {

	$srcset = wp_get_attachment_image_srcset( get_post_thumbnail_id(), 'shop_catalog' );
	$sizes  = wp_get_attachment_image_sizes( get_post_thumbnail_id(), 'shop_catalog' );


	$html .= 'data-srcset="' . $srcset . '" data-sizes="' . $sizes . '" data-product_id="' . get_the_ID() . '"';

	$available_variations = Insight_Attribute_Swatches::insight_sw_variations( $available_variations );
}

?>

<div <?php echo( $html ); ?>
	data-product_variations="<?php echo esc_attr( json_encode( $available_variations ) ) ?>"
	<?php echo( $lang !== false ? ' data-lang="' . ICL_LANGUAGE_CODE . '"' : '' ); ?>>

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

	foreach ( $isw_attributes as $attribute_idx => $options ) {

		if ( ! isset( $settings['isw_show_on_loop'][ $attribute_idx ] ) || ($is_loop && isset( $settings['isw_show_on_loop'][ $attribute_idx ] ) && $settings['isw_show_on_loop'][ $attribute_idx ] == 'no' )) {
			continue;
		}

		$term_sanitized = Insight_Attribute_Swatches_Utils::utf8_urldecode( $options );
		$v              = sanitize_title( $options );

		if ( ! isset( $variation_attributes[ $term_sanitized ] ) || ! is_array( $variation_attributes[ $term_sanitized ] ) ) {
			continue;
		}

		$curr['style']        = ( isset( $settings['isw_style'][ $attribute_idx ] ) ? $settings['isw_style'][ $attribute_idx ] : 'isw_text' );
		$curr['title']        = ( isset( $settings['isw_title'][ $attribute_idx ] ) ? $settings['isw_title'][ $attribute_idx ] : '' );
		$curr['desc']         = ( isset( $settings['isw_desc'][ $attribute_idx ] ) ? $settings['isw_desc'][ $attribute_idx ] : '' );
		$curr['show_on_loop'] = ( isset( $settings['isw_show_on_loop'][ $attribute_idx ] ) ? $settings['isw_show_on_loop'][ $attribute_idx ] : 'no' );
		$curr['custom']       = ( isset( $settings['isw_custom'][ $attribute_idx ] ) ? $settings['isw_custom'][ $attribute_idx ] : array() );
		$curr['tooltip']      = ( isset( $settings['isw_tooltip'][ $attribute_idx ] ) ? $settings['isw_tooltip'][ $attribute_idx ] : array() );

		if ( taxonomy_exists( $term_sanitized ) ) {
			$curr['terms'] = get_terms( $term_sanitized, array( 'hide_empty' => false ) );
		} else {
			if ( isset( $attributes[ $options ] ) ) {
				$custom_vals = array_map( 'trim', explode( WC_DELIMITER, $attributes[ $options ]['value'] ) );
				foreach ( $custom_vals as $cv ) {
					$curr['terms'][ $cv ]       = new stdClass();
					$curr['terms'][ $cv ]->name = ucfirst( $cv );
					$curr['terms'][ $cv ]->slug = $cv;
				}
			}
		}

		?>

		<div
			class="isw-swatch isw-swatch--<?php echo esc_attr( $curr['style'] ); ?>"
			data-attribute="<?php echo esc_attr( $options ); ?>">
			<?php

			switch ( $curr['style'] ) {
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
							aria-label="<?php echo esc_attr( $tooltip ); ?>">
							<img src="<?php echo $curr['custom'][ $b->slug ]; ?>" alt="<?php echo $b->name; ?>"/>
						</span>
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
							aria-label="<?php echo esc_attr( $tooltip ); ?>">
							<?php echo $curr['custom'][ $b->slug ]; ?>
						</span>
						<?php
					}
					break;
				case 'isw_text' :
				default:
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
			}
			?>

		</div>

	<?php } ?>

	<a class="reset_variations reset_variations--loop" href="#" style="display: none;"><?php esc_html_e( 'Clear', 'insight-core' ); ?></a>
</div>
