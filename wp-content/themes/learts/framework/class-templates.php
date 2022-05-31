<?php

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Custom template tags for this theme.
 *
 * Eventually, some of the functionality here could be replaced by core features.
 *
 * @package   InsightFramework
 */
if ( ! class_exists( 'Learts_Templates' ) ) {

	class Learts_Templates {

		/**
		 * Insight_Templates constructor.
		 */
		public function __construct() {
			add_action( 'learts_base_loop', array( $this, 'base_loop' ) );
		}

		public function base_loop() {

			$display_type = learts_get_option( 'archive_display_type' );

			if ( isset( $_GET['grid'] ) ) {
				$display_type = 'grid';
			} elseif ( isset( $_GET['masonry'] ) ) {
				$display_type = 'masonry';
			} elseif ( isset( $_GET['list'] ) ) {
				$display_type     = 'list';
				$sidebar['class'] = '';
			} elseif ( isset( $_GET['fullgrid'] ) ) {
				$display_type = 'full-grid';
			} elseif ( isset( $_GET['standard'] ) ) {
				$display_type = 'standard';
			}

			$args        = array(
				'post_type' => 'post',
			);
			$this->query = new WP_Query( $args );

			if ( is_category() ) {
				$term_id      = get_category( get_query_var( 'cat' ) )->term_id;
				$term_slug    = get_category( get_query_var( 'cat' ) )->slug;
				$display_type = get_term_meta( $term_id, 'learts_archive_display_type', true );

				if ( $display_type == 'default' || ! $display_type ) {
					$display_type = learts_get_option( 'archive_display_type' );
				}

				$atts['filter']    = 'category';
				$atts['cat_slugs'] = $term_slug;
			}

			if ( is_tag() ) {
				$tag = get_queried_object();

				$atts['filter']    = 'tag';
				$atts['cat_slugs'] = $tag->slug;
			}

			$container_class = 'posts-wrapper';
			$container_class .= ' ' . $display_type . '-container';

			?>

			<?php if ( have_posts() ) : ?>
				<div class="<?php echo esc_attr( $container_class ); ?> row">

					<?php $index = 0; ?>
					<?php while ( have_posts() ) :
						the_post();
						$index ++; ?>
						<?php if ( get_post_type() == 'portfolio' ) {
						get_template_part( 'components/portfolio/content-portfolio' );
					} else { ?>
						<?php if ( $display_type == 'full-grid' ) : ?>
							<?php if ( $index == 1 ) : ?>
								<div class="col-xs-12 post-fullwidth">
									<?php get_template_part( 'components/post/content-fullwidth',
										get_post_format() ); ?>
								</div>
							<?php else :
								get_template_part( 'components/post/content', get_post_format() );
							endif; ?>
						<?php else :
							get_template_part( 'components/post/content', get_post_format() );
						endif; ?>
					<?php } ?>

					<?php endwhile; ?>

				</div>

				<?php $this->pagination(); ?>

			<?php else : ?>
				<?php get_template_part( 'components/post/content', 'none' ); ?>
			<?php endif; ?>

			<?php
		}

		public static function post_meta( $atts = array() ) {

			ob_start();

			global $post;
			$author_id = $post->post_author;

			$default = apply_filters( 'learts_post_meta',
				array(
					'author'   => 0,
					'cats'     => 0,
					'date'     => 1,
					'tags'     => 0,
					'comments' => 1,
					'sticky'   => 0,
				) );

			$atts = wp_parse_args( $atts, $default );
			?>
			<div class="entry-meta meta-post">
				<?php if ( get_post_type() === 'post' || get_post_type() === 'page' ) : ?>

					<?php // Is sticky. ?>

					<?php if ( is_sticky() && $atts['sticky'] ) : ?>
						<span class="meta-featured-post"><?php esc_html_e( 'Featured', 'learts' ) ?></span>
					<?php endif; ?>

					<?php // Author. ?>

					<?php if ( learts_get_option( 'post_meta_author' ) ) {

						if ( $atts['author'] && $author_id ) { ?>
							<span class="meta-author byline">
								<span class="vcard author">
							<?php esc_html_e( 'By', 'learts' ) ?>
									<a href="<?php echo esc_url( get_author_posts_url( $author_id ) ); ?>"
									   rel="author" class="url fn n"><?php echo the_author_meta( 'display_name',
											$author_id ); ?></a>
							</span>
						</span>
						<?php }
					} ?>

					<?php // Date. ?>

					<?php if ( $atts['date'] ) :
						?>

						<span class="meta-date"><a
								href="<?php echo get_permalink(); ?>"><?php echo esc_html( get_the_date() ); ?></a></span>
					<?php endif; ?>

					<?php // Tags. ?>

					<?php if ( get_the_tag_list( '', ', ' ) && $atts['tags'] ) : ?>
						<span class="meta-tags"><?php echo get_the_tag_list( '', ', ' ); ?></span>
					<?php endif; ?>

					<?php // Comment Counter. ?>

					<?php if ( $atts['comments'] && get_comments_number() != 0 && comments_open() ) : ?>
						<span class="meta-comments"><a
								href="<?php echo get_permalink() . '#comments'; ?>"><?php printf( _n( '%s Comment',
									'%s Comments',
									get_comments_number(),
									'learts' ),
									number_format_i18n( get_comments_number() ) ); ?></a></span>
					<?php endif; ?>

					<?php // Post View. ?>
					<?php if ( function_exists( 'the_views' ) ) { ?>
						<span class="post-view"><?php the_views(); ?></span>
					<?php } ?>
				<?php endif; ?>
			</div>
			<?php
			return ob_get_clean();
		}

		/**
		 * Language Switcher
		 *
		 * @return string
		 */
		public static function language_switcher() {

			ob_start();

			$language_switcher_on = learts_get_option( 'topbar_language_switcher_on' );
			if ( isset( $_GET['header'] ) ) {
				$language_switcher_on = 'on';
			}

			if ( ! $language_switcher_on ) {
				return;
			}

			if ( has_nav_menu( 'language_switcher' ) && ! class_exists( 'PolyLang' ) && ! class_exists( 'SitePress' ) ) {
				wp_nav_menu( array(
					'theme_location'  => 'language_switcher',
					'menu_id'         => 'language-switcher-menu',
					'container_class' => 'switcher-menu language-switcher-menu',
					'fallback_cb'     => false,
					'walker'          => new learts_Walker_Nav_Menu(),
				) );
			}

			// Polylang
			if ( class_exists( 'PolyLang' ) ) {
				echo self::polylang_switcher();
			}

			// WPML
			if ( class_exists( 'SitePress' ) && function_exists( 'wpml_get_active_languages_filter' ) ) {
				echo self::wpml_switcher();
			}

			return ob_get_clean();
		}

		/**
		 * Polylang Language Switcher
		 *
		 * @return string
		 */
		private static function polylang_switcher() {

			ob_start();

			if ( function_exists( 'pll_languages_list' ) ) :

				$langs = pll_languages_list();

				if ( ! empty( $langs ) ) :

					$args = array(
						'dropdown' => 1,
						'raw'      => 1,
					);

					if ( function_exists( 'pll_the_languages' ) ) :

						$langs = pll_the_languages( $args );
						$html = '';

						if ( ! empty ( $langs ) ) {
							foreach ( $langs as $l ) {

								if ( $l['current_lang'] ) {
									$html .= '<option selected="selected"';
								} else {
									$html .= '<option';
								}

								// show flag
								$html .= ' data-imagesrc="' . esc_url( $l['flag'] ) . '"';

								// add link
								$html .= ' value="' . esc_url( $l['url'] ) . '"';

								// language name
								$html .= '>' . $l['name'];

								$html .= '</option>';
							}

							$html = apply_filters( 'learts_polylang_switcher', $html );
						}
						?>
						<div class="switcher language-switcher polylang-switcher">
							<select name="polylang-switcher" id="polylang-switcher">
								<?php echo '' . $html; ?>
							</select>
						</div>
						<?php
					endif;
				endif;
			endif;

			do_action( 'learts_polylang_switcher' );

			return ob_get_clean();
		}

		/**
		 * WMPL Language Switcher
		 *
		 * @return string
		 */
		private static function wpml_switcher() {

			ob_start();

			global $sitepress;

			$settings    = $sitepress->get_settings();
			$flag_enable = isset( $settings['icl_lso_flags'] ) ? $settings['icl_lso_flags'] : 0;
			$select_type = isset( $settings['icl_lang_sel_type'] ) ? $settings['icl_lang_sel_type'] : 'dropdown';

			// get all avaiable languages
			$langs = wpml_get_active_languages_filter( 'skip_missing=0&orderby=code' );

			$html = '';
			if ( ! empty( $langs ) ) {

				foreach ( $langs as $l ) {

					if ( 'dropdown' == $select_type ) {

						if ( $l['active'] ) {
							$html .= '<option selected="selected"';
						} else {
							$html .= '<option';
						}

						// show flag
						if ( $flag_enable && $l['country_flag_url'] ) {
							$html .= ' data-imagesrc="' . esc_url( $l['country_flag_url'] ) . '"';
						}

						// add link
						$html .= ' value="' . esc_url( $l['url'] ) . '">';

						// language name
						if ( function_exists( 'wpml_display_language_names_filter' ) ) {
							$html .= wpml_display_language_names_filter( $l['native_name'], $l['translated_name'] );
						}

						$html .= '</option>';
					}

					if ( 'list' == $select_type ) {
						$html .= '<li><a href="' . esc_url( $l['url'] ) . '">';

						// show flag
						if ( $flag_enable && $l['country_flag_url'] ) {
							$html .= '<img src="' . esc_url( $l['country_flag_url'] ) . '" alt="image"/>';
						}

						// language name
						if ( function_exists( 'wpml_display_language_names_filter' ) ) {
							$html .= wpml_display_language_names_filter( $l['native_name'], $l['translated_name'] );
						}

						$html .= '</a></li>';
					}


					$html = apply_filters( 'learts_wpml_switcher', $html );
				}
			}
			?>
			<div
				class="switcher language-switcher wpml-switcher<?php echo esc_attr( $flag_enable ? ' show-flag' : '' ); ?>">
				<?php
				// Drop down style
				if ( 'dropdown' == $select_type ) : ?>
					<select name="wpml-switcher" id="wpml-switcher">
						<?php echo '' . $html; ?>
					</select>
					<?php
				endif;

				// List style
				if ( 'list' == $select_type ) : ?>
					<ul id="language-switcher-menu" class="menu">
						<?php echo '' . $html; ?>
					</ul>
					<?php
				endif;
				?>
			</div>
			<?php

			do_action( 'learts_wpml_switcher' );

			return ob_get_clean();
		}

		/**
		 * Currency switcher
		 *
		 * @return string
		 */
		public static function currency_switcher() {

			ob_start();

			if ( ! class_exists( 'WooCommerce' ) ) {
				return '';
			}

			$currency_switcher_on = learts_get_option( 'topbar_currency_switcher_on' );

			if ( isset( $_GET['header'] ) ) {
				$currency_switcher_on = 'on';
			}

			if ( ! $currency_switcher_on ) {
				return;
			}

			if ( has_nav_menu( 'currency_switcher' ) && ! class_exists( 'WOOCS' ) && ! class_exists( 'woocommerce_wpml' ) ) {
				wp_nav_menu( array(
					'theme_location'  => 'currency_switcher',
					'menu_id'         => 'currency-switcher-menu',
					'container_class' => 'switcher-menu currency-switcher-menu',
					'fallback_cb'     => false,
					'walker'          => new learts_Walker_Nav_Menu(),
				) );
			}

			// if install both WOOCS and woocommerce_wpml
			if ( class_exists( 'WOOCS' ) && class_exists( 'woocommerce_wpml' ) ) {
				return '';
			}

			// WOOCS
			if ( class_exists( 'WOOCS' ) ) {
				echo self::woocs_switchers();
			}

			if ( class_exists( 'WCML_Multi_Currency' ) ) {
				echo self::woo_wpml_switcher();
			}

			return ob_get_clean();
		}

		/**
		 * WooCommerce Currency Switcher
		 *
		 * @return string
		 */
		private static function woocs_switchers() {

			if ( get_option( 'woocs_restrike_on_checkout_page' ) == 1 && is_checkout() ) {
				return '';
			}

			ob_start();

			global $WOOCS, $wp;

			$currencies       = $WOOCS->get_currencies();
			$show_money_signs = get_option( 'woocs_show_money_signs', 1 );
			$show_flags       = get_option( 'woocs_show_flags', 1 );
			$current_currency = $WOOCS->current_currency;
			$empty_flat       = ( plugin_dir_url( 'woocommerce-currency-switcher' ) . 'woocommerce-currency-switcher/img/no_flag.png' );
			$dash             = apply_filters( 'learts_woocs_switchers_dash', ' - ' );

			$html = '';

			foreach ( $currencies as $key => $currency ) {

				$html .= '<option ';

				// show flag
				if ( $show_flags ) {
					$html .= ' data-imagesrc="' . esc_url( $currency['flag'] ? $currency['flag'] : $empty_flat ) . '"';
				}

				$html .= ' value="' . esc_url( home_url( add_query_arg( array(),
						$wp->request ) ) ) . '?currency=' . $key . '"' . selected( $current_currency,
						$key,
						false ) . '>';

				if ( $show_money_signs && in_array( $currency['position'], array( 'left', 'left_space' ) ) ) {
					$html .= $currency['symbol'] . $dash;
				}

				$html .= $currency['name'];

				if ( $show_money_signs && in_array( $currency['position'], array( 'right', 'right_space' ) ) ) {
					$html .= $dash . $currency['symbol'];
				}

				$html .= '</option>';
			}

			$html = apply_filters( 'learts_woocs_switchers', $html );
			?>

			<div class="switcher currency-switcher woocs-switcher">
				<select name="wcml-switcher" id="woocs-switcher">
					<?php echo '' . $html; ?>
				</select>
			</div>

			<?php

			return ob_get_clean();
		}

		/**
		 * Woocommerce WMPL Currency Switcher
		 *
		 * @return string
		 */
		private static function woo_wpml_switcher() {

			ob_start();

			global $sitepress, $woocommerce_wpml;


			$wcml_settings = $woocommerce_wpml->get_settings();

			if ( ! $wcml_settings['enable_multi_currency'] ) {
				return '';
			}

			$switcher_style = apply_filters( 'learts_currency_switcher_style', 'dropdown' );
			$format         = apply_filters( 'learts_currency_template', '%symbol% - %code%' );
			$wc_currencies  = get_woocommerce_currencies(); // default Woo currencies

			if ( ! isset( $wcml_settings['currencies_order'] ) ) {
				$currencies = $woocommerce_wpml->multi_currency->get_currency_codes();
			} else {
				$currencies = $wcml_settings['currencies_order'];
			}

			$html = '';

			foreach ( $currencies as $c ) {

				if ( $woocommerce_wpml->settings['currency_options'][ $c ]['languages'][ $sitepress->get_current_language() ] == 1 ) {
					$currency_format = preg_replace( array(
						'#%name%#',
						'#%symbol%#',
						'#%code%#',
					),
						array(
							$wc_currencies[ $c ],
							get_woocommerce_currency_symbol( $c ),
							$c,
						),
						$format );

					// Dropdown style
					if ( 'dropdown' == $switcher_style ) {

						if ( $c == $woocommerce_wpml->multi_currency->get_client_currency() ) {
							$html .= '<option selected="selected"';
						} else {
							$html .= '<option';
						}

						// add value
						$html .= ' value="' . esc_attr( $c ) . '"';

						// show text
						$html .= '>' . $currency_format;


						$html .= '</option>';
					}

					// List style
					if ( 'list' == $switcher_style ) {
						$html .= '<li><a href="javascript:void(0)" data-currency="' . esc_attr( $c ) . '">';
						// show text
						$html .= $currency_format;
						$html .= '</a></li>';
					}
				}
			}

			$html = apply_filters( 'learts_woo_wpml_switcher', $html );

			?>
			<div class="switcher currency-switcher wcml-switcher">
				<?php

				// Dropdown style
				if ( 'dropdown' == $switcher_style ) :
					?>
					<select name="wcml-switcher" id="wcml-switcher">
						<?php echo '' . $html; ?>
					</select>

					<?php
				endif;

				// List style
				if ( 'list' == $switcher_style ) : ?>
					<ul id="currency-switcher-menu" class="menu">
						<?php echo '' . $html; ?>
					</ul>
					<?php
				endif;
				?>
			</div>
			<?php

			return ob_get_clean();
		}

		public static function excerpt( $limit ) {
			$excerpt = wp_trim_words( get_the_excerpt(), $limit );
			$excerpt = preg_replace( '`\[[^\]]*\]`', '', $excerpt );

			return '<p>' . $excerpt . '</p>';
		}

		public static function get_the_content_with_formatting() {
			$content = get_the_content();
			$content = apply_filters( 'the_content', $content );
			$content = str_replace( ']]>', ']]&gt;', $content );

			return $content;
		}

		public static function social_links( $classes = array() ) {

			ob_start();

			// Get social links from Redux
			$icons           = learts_get_option( 'icon' );
			$icon_classes    = learts_get_option( 'icon_class' );
			$urls            = learts_get_option( 'url' );
			$titles          = learts_get_option( 'title' );
			$custom_classes  = learts_get_option( 'custom_class' );
			$tooltip         = learts_get_option( 'tooltip' );
			$open_in_new_tab = learts_get_option( 'social_open_in_new_tab' );
			$labels          = Learts_Helper::social_icons( false );

			$social_links = array();

			if ( ! empty( $icons ) ) {
				for ( $i = 0; $i < count( $icons ); $i ++ ) {
					if ( ! empty( $icons[ $i ] ) ) {
						$social_links[ $i ]['icon'] = $icons[ $i ];
					}
				}
			}

			if ( ! empty( $icon_classes ) ) {
				for ( $i = 0; $i < count( $icon_classes ); $i ++ ) {
					if ( ! empty( $icon_classes[ $i ] ) ) {
						$social_links[ $i ]['icon_class'] = $icon_classes[ $i ];
					}
				}
			}

			if ( ! empty( $urls ) ) {
				for ( $i = 0; $i < count( $urls ); $i ++ ) {
					if ( ! empty( $urls[ $i ] ) ) {
						$social_links[ $i ]['url'] = $urls[ $i ];
					}
				}
			}

			if ( ! empty( $titles ) ) {
				for ( $i = 0; $i < count( $titles ); $i ++ ) {
					if ( ! empty( $titles[ $i ] ) ) {
						$social_links[ $i ]['title'] = $titles[ $i ];
					}
				}
			}

			if ( ! empty( $custom_classes ) ) {
				for ( $i = 0; $i < count( $custom_classes ); $i ++ ) {
					if ( ! empty( $custom_classes[ $i ] ) ) {
						$social_links[ $i ]['custom_class'] = $custom_classes[ $i ];
					}
				}
			}

			// Now let's render HTML
			if ( ! empty( $social_links ) ) :
				array_unshift( $classes, 'social-links' );
				?>
				<ul class="<?php echo esc_attr( implode( ' ', $classes ) ); ?>">
					<?php foreach ( $social_links as $link ) :

						$li_classes = array();
						$tooltip_label = '';

						if ( isset( $link['title'] ) && ! empty( $link['title'] ) ) {
							$li_classes[] = 'has-title';
						}

						if ( $tooltip ) {
							$li_classes[] = 'hint--top hint--bounce';
						}

						if ( isset( $link['custom_class'] ) && ! empty( $link['custom_class'] ) ) {
							$li_classes[] = esc_attr( $link['custom_class'] );
						}

						if ( isset( $labels[ $link['icon'] ] ) ) {
							$tooltip_label = $labels[ $link['icon'] ];
						}

						if ( isset( $link['title'] ) && ! empty( $link['title'] ) ) {
							$tooltip_label = $link['title'];
						}

						?>
						<li class="<?php echo implode( ' ', $li_classes ); ?>"
						    aria-label="<?php echo esc_attr( $tooltip_label ); ?>">
							<?php if ( isset( $link['url'] ) && ! empty( $link['url'] ) ) : ?><a
								href="<?php echo esc_url_raw( $link['url'] ); ?>"
								target="<?php echo esc_attr( $open_in_new_tab ) ? '_blank' : '_self'; ?>"><?php endif; ?>
								<?php if ( isset( $link['icon'] ) && ! empty( $link['icon'] ) && ( ! isset( $link['icon_class'] ) || empty( $link['icon_class'] ) ) ) : ?>
									<i class="fa fa-<?php echo esc_attr( $link['icon'] ); ?>" aria-hidden="true"></i>
								<?php elseif ( isset( $link['icon_class'] ) && ! empty( $link['icon_class'] ) ) : ?>
									<i class="fa <?php echo esc_attr( $link['icon_class'] ); ?>" aria-hidden="true"></i>
								<?php endif; ?>
								<?php if ( isset( $link['title'] ) && ! empty( $link['title'] ) ) : ?>
									<span class="title"><?php echo esc_html( $link['title'] ); ?></span>
								<?php endif; ?>
								<?php if ( isset( $link['url'] ) && ! empty( $link['url'] ) ) : ?></a><?php endif; ?>
						</li>
					<?php endforeach; ?>
				</ul>
				<?php
			endif;

			return ob_get_clean();

		}

		/**
		 * Display navigation to next/previous set of posts when applicable.
		 */
		private function pagination() {

			global $wp_query, $wp_rewrite;

			// Don't print empty markup if there's only one page.
			if ( $wp_query->max_num_pages < 2 ) {
				return;
			}

			$paged        = get_query_var( 'paged' ) ? intval( get_query_var( 'paged' ) ) : 1;
			$pagenum_link = wp_kses_post( get_pagenum_link() );
			$query_args   = array();
			$url_parts    = explode( '?', $pagenum_link );

			if ( isset( $url_parts[1] ) ) {
				wp_parse_str( $url_parts[1], $query_args );
			}

			$pagenum_link = esc_url( remove_query_arg( array_keys( $query_args ), $pagenum_link ) );
			$pagenum_link = trailingslashit( $pagenum_link ) . '%_%';

			$format = $wp_rewrite->using_index_permalinks() && ! strpos( $pagenum_link,
				'index.php' ) ? 'index.php/' : '';
			$format .= $wp_rewrite->using_permalinks() ? user_trailingslashit( $wp_rewrite->pagination_base . '/%#%',
				'paged' ) : '?paged=%#%';

			// Set up paginated links.
			$links                      = paginate_links( array(
				'base'      => $pagenum_link,
				'format'    => $format,
				'total'     => $wp_query->max_num_pages,
				'current'   => $paged,
				'add_args'  => array_map( 'urlencode', $query_args ),
				'prev_text' => 'prev',
				'next_text' => 'next',
				'type'      => 'list',
				'end_size'  => 3,
				'mid_size'  => 3,
			) );

			if ( $links ) :

				$post_type = 'post';
				$pagination             = learts_get_option( 'archive_pagination' );
				$display_type           = learts_get_option( 'archive_display_type' );
				$archive_sidebar_config = learts_get_option( 'archive_sidebar_config' );

				switch ( $display_type ) {

					case 'grid':
						$img_size = 'learts-post-grid';
						break;

					case 'list':
						$img_size = 'learts-post-list';
						break;

					case 'standard':
					default:
						$img_size = 'learts-single-thumb';
						break;

				}

				if ( $display_type == 'standard' || $display_type == 'list' ) {
					$columns = 1;
				} else {
					$columns = ( $archive_sidebar_config == 'no' ) ? 3 : 2;
				}

				if ( get_post_type() == 'portfolio' ):
					$post_type  = 'portfolio';
					$pagination = learts_get_option( 'portfolio_pagination' );
					$columns    = learts_get_option( 'portfolio_columns' );
				endif;

				if ( $pagination == 'default' ) :

					?>
					<div class="learts-pagination posts-pagination">
						<?php echo wp_kses_post( $links ); ?>
					</div><!-- .pagination -->
					<?php
				else:
					 $load_more_atts = array(
						'container'      => '.posts-wrapper',
						'post_type'      => $post_type,
						'paged'          => 1,
						'posts_per_page' => get_option( 'posts_per_page' ),
						'columns'        => $columns,
						'view'           => $display_type,
						'img_size'       => $img_size,
					);

					?>
					<div class="learts-loadmore-wrap learts-pagination posts-pagination"
					     data-atts="<?php echo esc_attr( json_encode( $load_more_atts ) ); ?>">
						<a href="#"
						   class="button learts-loadmore-btn load-on-<?php echo esc_attr( $pagination == 'more-btn' ) ? 'click' : 'scroll'; ?>">
							<?php esc_html_e( 'more', 'learts' ); ?></a>
					</div>
					<?php
				endif;
			endif;
		}

		public static function time( $type = 'post' ) {
			return get_comment_date() . " " . __( 'at', 'learts' ) . " " . get_comment_time();
		}

		public static function comments( $comment, $args, $depth ) {
			$GLOBALS['comment'] = $comment;

			?>
			<li <?php comment_class(); ?> id="comment-<?php comment_ID() ?>">

				<div class="thecomment">

					<div class="author-avatar">
						<?php echo get_avatar( $comment, $args['avatar_size'] ); ?>
					</div>

					<div class="comment-text">
						<div class="author"><?php echo get_comment_author_link(); ?></div>
						<?php if ( $comment->comment_approved == '0' ) : ?>
							<em><i class="fa fa-flag-o"></i> <?php esc_html_e( 'Comment awaiting approval',
									'learts' ); ?>
							</em>
							<br/>
						<?php endif; ?>
						<?php comment_text(); ?>
						<div class="comment-actions">
							<span class="date"><?php echo Learts_Templates::time( 'comment' ); ?></span>
							<span class="action">
								<?php comment_reply_link( array_merge( $args,
									array(
										'depth'      => $depth,
										'max_depth'  => $args['max_depth'],
										'reply_text' => esc_html__( 'Reply', 'learts' ),
									) ) ); ?>
								<?php edit_comment_link( esc_html__( 'Edit', 'learts' ) ); ?>
							</span>
						</div>
					</div>

				</div>


			</li>

			<?php
		}

		/**
		 * Mobile Menu
		 */
		public static function mobile_menu() {

			if ( learts_Coming_Soon::is_coming_soon_page() ) {
				return '';
			}

			ob_start();
			?>
			<div class="site-mobile-menu">
				<?php

				$args = array(
					'theme_location' => 'mobile_menu',
					'menu_id'        => 'site-mobile-menu',
				);

				if ( class_exists( 'learts_Walker_Nav_Menu' ) && has_nav_menu( 'primary' ) ) {
					$args['walker'] = new learts_Walker_Nav_Menu();
				}

				?>

				<form role="search" method="get" id="mobile-searchform"
				      action="<?php echo esc_url( home_url( '/' ) ); ?>">
					<input type="text" value="" name="s"
					       placeholder="<?php esc_html_e( 'Search&hellip;', 'learts' ); ?>"/>
					<input type="hidden" name="post_type"
					       value="<?php echo esc_attr( learts_get_option( 'search_post_type' ) ); ?>"/>
					<button type="submit" class="search-submit"><?php esc_html_e( 'Search', 'learts' ); ?></button>
				</form>

				<?php
				wp_nav_menu( $args );

				if ( learts_get_option( 'header_login' ) ) {
					echo self::header_block_header_login();
				}

				if ( learts_get_option( 'mobile_menu_social' ) ) {
					echo self::social_links();
				}

				?>
			</div>

			<?php
			return ob_get_clean();

		}

		/**
		 * Go to top
		 *
		 * @return string|void
		 */
		public static function back_to_top() {

			if ( ! learts_get_option( 'back_to_top_on' ) ) {
				return;
			}

			ob_start();
			?>

			<a href="#" class="back-to-top"><i
					class="far fa-chevron-up"></i><span><?php esc_html_e( 'Back to top', 'learts' ); ?></span></a>
			<?php

			return ob_get_clean();
		}

		/**
		 * Cookie notice
		 *
		 * @return string|void
		 */
		public static function cookie_notice() {

			if ( ! learts_get_option( 'cookie_on' ) ) {
				return;
			}

			ob_start();

			if ( ! isset( $_COOKIE['learts_cookie_notice_accepted'] ) || $_COOKIE['learts_cookie_notice_accepted'] != 'yes' ) :
				?>
				<div class="cookie-wrapper"
				     data-expires="<?php echo esc_attr( learts_get_option( 'cookie_expires' ) ); ?>">
					<div class="cookie-inner">
						<div class="cookie-message">
							<?php echo do_shortcode( learts_get_option( 'cookie_message' ) ); ?>
						</div>
						<div class="cookie-buttons">
							<a href="#"
							   class="cookie-btn cookie-accept-btn"><i class="ti-close"></i></a>
						</div>
					</div>
				</div>
				<?php
			endif;

			return ob_get_clean();
		}

		/**---------------------------------------------------------------------/
		 * Header Blocks
		 * ---------------------------------------------------------------------*/
		public static function header_offcanvas_btn() {

			if ( ! learts_get_option( 'offcanvas_button_on' ) ) {
				return;
			}

			ob_start();

			$offcanvas_position = learts_get_option( 'offcanvas_position' );
			?>
			<div class="offcanvas-btn hidden-lg-down on-<?php echo esc_attr( $offcanvas_position ); ?>">
				<a href="#"><span class="open-nav">
						<div class="icon-inner"><span></span><span></span><span></span></div>
					</span></a>
			</div>
			<?php

			return ob_get_clean();
		}

		public static function header_offcanvas() {

			if ( ! learts_get_option( 'offcanvas_button_on' ) || learts_get_option( 'header' ) == 'vertical' ) {
				return '';
			}

			ob_start();

			$offcanvas_position = learts_get_option( 'offcanvas_position' );
			$offcanvas_action   = learts_get_option( 'offcanvas_action' );
			$offcanvas_sidebar  = learts_get_option( 'offcanvas_sidebar' );

			if ( is_page() ) {
				$offcanvas_sidebar = get_post_meta( Learts_Helper::get_the_ID(),
					'learts_offcanvas_custom_sidebar',
					true );
			}

			if ( ! $offcanvas_sidebar || $offcanvas_sidebar == 'default' ) {
				$offcanvas_sidebar = learts_get_option( 'offcanvas_sidebar' );
			}

			$classes = array();

			if ( $offcanvas_action == 'sidebar' ) {
				$classes[] = 'offcanvas-sidebar';
			} else {
				$classes[] = 'offcanvas-menu';
			}

			$classes[] = 'on-' . $offcanvas_position;
			$classes[] = 'action-' . $offcanvas_action;
			$classes[] = 'widget-area';

			?>
			<div class="<?php echo implode( ' ', $classes ); ?>">
				<a href="#" class="offcanvas-close">Close</a>
				<?php if ( $offcanvas_action == 'sidebar' ) { ?>
					<div class="offcanvas-sidebar-inner">
						<?php dynamic_sidebar( $offcanvas_sidebar ); ?>
					</div>
				<?php } ?>
				<?php
				if ( $offcanvas_action == 'menu' ) {
					$args = array(
						'theme_location'  => 'full_screen',
						'container_class' => 'offcanvas-menu-wrapper',
					);
					wp_nav_menu( $args );
				} ?>
			</div>
			<?php
			return ob_get_clean();
		}

		public static function header_block_logo() {

			ob_start();

			$id = Learts_Helper::get_the_ID();

			$header         = learts_get_option( 'header' );
			$header_overlap = learts_get_option( 'header_overlap' );
			$header_scheme  = learts_get_option( 'header_scheme' );

			$custom_logo = get_post_meta( $id, 'learts_custom_logo', true );

			$logo        = $o_logo = $o_logo_mobile = $logo_mobile = LEARTS_IMAGES . '/logo.png';
			$logo_retina = LEARTS_IMAGES . '/logo-retina.png';
			$logo_alt    = $logo_mobile_alt = LEARTS_IMAGES . '/logo-alt.png';

			$logo_uploaded            = learts_get_option( 'logo' );
			$logo_retina_uploaded     = learts_get_option( 'logo_retina' );
			$logo_alt_uploaded        = learts_get_option( 'logo_alt' );
			$logo_mobile_uploaded     = learts_get_option( 'logo_mobile' );
			$logo_mobile_alt_uploaded = learts_get_option( 'logo_mobile_alt' );

			if ( isset( $logo_uploaded['url'] ) && $logo_uploaded['url'] ) {
				$logo = $o_logo = is_ssl() ? str_replace( 'http:',
					'https:',
					$logo_uploaded['url'] ) : $logo_uploaded['url'];
			}

			if ( isset( $logo_retina_uploaded['url'] ) && $logo_retina_uploaded['url'] ) {
				$logo_retina = $logo_retina_uploaded['url'];
			}

			if ( isset( $logo_alt_uploaded['url'] ) && $logo_alt_uploaded['url'] ) {
				$logo_alt = $logo_alt_uploaded['url'];
			}

			if ( isset( $logo_mobile_uploaded['url'] ) && $logo_mobile_uploaded['url'] ) {
				$logo_mobile = $o_logo_mobile = $logo_mobile_uploaded['url'];
			}

			if ( isset( $logo_mobile_alt_uploaded['url'] ) && $logo_mobile_alt_uploaded['url'] ) {
				$logo_mobile_alt = $logo_mobile_alt_uploaded['url'];
			}

			if ( is_singular( 'page' ) && $custom_logo == 'on' ) { // On single page
				$logo            = $o_logo = get_post_meta( $id, 'learts_logo', true );
				$logo_alt        = get_post_meta( $id, 'learts_logo_alt', true );
				$logo_mobile     = get_post_meta( $id, 'learts_logo_mobile', true );
				$logo_mobile_alt = get_post_meta( $id, 'learts_logo_mobile_alt', true );
			}

			if ( $header_overlap && $header != 'menu-bottom' ) {
				$logo        = $logo_alt;
				$logo_mobile = $logo_mobile_alt;
			}

			if ( $header_scheme == 'dark' || isset( $_GET['header-cheme-dark'] ) ) {
				$logo        = $logo_alt;
				$logo_mobile = $logo_alt;
			}

			$logo_img = '<img src="' . $logo . '" srcset="' . $logo_retina . ' 2x" alt="' . get_bloginfo( 'name' ) . '" class="logo-desktop hidden-lg-down"';
			if ( $header_overlap && $header != 'menu-bottom' && $header != 'menu-bottom-wide' ) {
				$logo_img .= ' data-o_logo="' . $o_logo . '" />';
			} else {
				$logo_img .= ' />';
			}

			$logo_mobile_img = '<img src="' . $logo_mobile . '" alt="' . get_bloginfo( 'name' ) . '" class="logo-mobile hidden-xl-up" data-o_logo="' . $o_logo_mobile . '" />';

			?>
			<div class="site-logo">
				<a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home">
					<?php echo '' . $logo_img; ?>
					<?php echo '' . $logo_mobile_img; ?>
				</a>
			</div>
			<?php

			return ob_get_clean();
		}

		public static function header_block_site_menu() {

			ob_start();

			$header = learts_get_option( 'header' );
			$align  = learts_get_option( 'site_menu_align' );
			$style  = learts_get_option( 'site_menu_style' );

			if ( $header != 'base' ) {
				$align = 'center';
			}

			if ( $header != 'base' ) {
				$style = '';
			}

			if ( $header == 'vertical' ) {
				echo '<div class="hamburger-icon"><div class="hamburger-inner"></div></div>';
			}

			$menu_disabled = get_post_meta( Learts_Helper::get_the_ID(),
					'learts_disable_site_menu',
					true ) == 'on' || is_404();

			$menu_classes   = array();
			$menu_classes[] = 'menu-align-' . $align;
			$menu_classes[] = $menu_disabled ? 'menu-disabled' : '';
			$menu_classes[] = $style ? 'menu-style-' . $style : '';

			?>
			<div class="site-menu hidden-lg-down <?php echo implode( ' ', $menu_classes ) ?>">

				<?php

				if ( ! $menu_disabled && ! learts_Coming_Soon::is_coming_soon_page() ) {

					if ( $header == 'vertical' || $header == 'vertical-full' ) {
						$args = array(
							'theme_location' => 'full_screen',
							'link_before'    => '<span class="menu-item-text">',
							'link_after'     => '</span>',
						);
					} else {
						$args = array(
							'theme_location' => 'primary',
							'link_before'    => '<span class="menu-item-text">',
							'link_after'     => '</span>',
						);
					}

					if ( class_exists( 'learts_Walker_Nav_Menu' ) && has_nav_menu( 'primary' ) && $header != 'vertical' ) {
						$args['walker'] = new learts_Walker_Nav_Menu();
					} elseif ( $header == 'vertical' ) {
						$args['container_class'] = 'vertical-menu-wrapper';
					}

					wp_nav_menu( $args );
				}
				?>

			</div>
			<?php

			return ob_get_clean();
		}

		public static function header_block_search() {

			if ( ! learts_get_option( 'search_on' ) || learts_get_option( 'search_on' ) == 'false' ) {
				return '';
			}

			ob_start();

			?>
			<div class="header-search">
				<a href="#" class="toggle">
					<i class="fal fa-search" aria-hidden="true"></i>
					<span><?php esc_attr_e( 'Search', 'learts' ) ?></span>
				</a>
			</div>

			<?php

			return ob_get_clean();
		}

		public static function search_form( $search_type = '' ) {

			ob_start();

			$post_type     = learts_get_option( 'search_post_type' );
			$categories_on = learts_get_option( 'search_categories_on' );
			$ajax_search   = learts_get_option( 'search_ajax_on' );
			$min_chars     = learts_get_option( 'search_min_chars' );

			$classes = array( 'search-form' );

			if ( $categories_on ) {
				$classes[] = ' has-categories-select';
			}

			if ( $ajax_search ) {
				$classes[] = ' ajax-search-form';
			}

			$place_holder = esc_html__( 'Search Products...', 'learts' );

			if ( $post_type == 'post' ) {
				$place_holder = esc_html__( 'Search Posts...', 'learts' );
			}

			?>
			<div
				class="search-form-wrapper<?php echo esc_attr( $search_type == 'inline-search' ? ' header-search' : '' ); ?>">
				<?php

				if ( $search_type == 'inline-search' ) {
					echo self::search_form_inline( $classes, $place_holder, $categories_on, $post_type );
				} else {
					echo self::search_form_default( $classes, $place_holder, $categories_on, $post_type );
				}
				?>
				<p class="search-description">
					<span><?php echo sprintf( esc_html__( '# Type at least %s %s to search', 'learts' ),
							$min_chars,
							_n( 'character', 'characters', $min_chars, 'learts' ) ); ?></span>
					<span><?php esc_html_e( '# Hit enter to search or ESC to close', 'learts' ); ?></span>
				</p>
				<div class="search-results-wrapper">
					<p class="ajax-search-notice"></p>
				</div>
				<div class="btn-search-close btn--hidden">
					<i class="ti-close"></i>
				</div>
			</div>
			<?php

			return ob_get_clean();
		}

		public static function search_form_default( $classes, $place_holder, $categories_on, $post_type ) {

			ob_start();

			?>

			<form action="<?php echo esc_url( home_url( '/' ) ); ?>" method="get" role="search"
			      class="<?php echo implode( ' ', $classes ); ?>">

				<input name="s" class="search-input" type="text" value="<?php echo get_search_query() ?>"
				       placeholder="<?php echo esc_attr( $place_holder ); ?>" autocomplete="off"/>

				<?php if ( $categories_on ) {
					$args = array(
						'show_option_all' => esc_html__( 'All Categories', 'learts' ),
						'hierarchical'    => 1,
						'class'           => 'search-select',
						'echo'            => 1,
						'value_field'     => 'slug',
						'selected'        => 1,
					);

					$search_child_cats = apply_filters( 'learts_search_child_cats', true );
					if ( ! $search_child_cats ) {
						$args['parent'] = 0;
					}

					if ( class_exists( 'WooCommerce' ) && 'product' == $post_type ) {
						$args['taxonomy'] = 'product_cat';
						$args['name']     = 'product_cat';

						wp_dropdown_categories( $args );
					} else {
						wp_dropdown_categories( $args );
					}
				} ?>

				<button type="submit" id="search-btn"
				        title="<?php esc_attr_e( 'Search', 'learts' ); ?>"><?php esc_html_e( 'Search',
						'learts' ); ?></button>
				<input type="hidden" name="post_type" value="<?php echo esc_attr( $post_type ); ?>"/>
			</form>

			<?php


			return ob_get_clean();
		}

		public static function search_form_inline( $classes, $place_holder, $categories_on, $post_type ) {

			ob_start();

			?>

			<form action="<?php echo esc_url( home_url( '/' ) ); ?>" method="get" role="search"
			      class="<?php echo implode( ' ', $classes ); ?>">

				<div class="form-row">
					<?php if ( $categories_on ) {
						$args = array(
							'show_option_all' => esc_html__( 'All Categories', 'learts' ),
							'hierarchical'    => 1,
							'class'           => 'search-select',
							'echo'            => 1,
							'value_field'     => 'slug',
							'selected'        => 1,
						);

						$search_child_cats = apply_filters( 'learts_search_child_cats', true );
						if ( ! $search_child_cats ) {
							$args['parent'] = 0;
						}

						if ( class_exists( 'WooCommerce' ) && 'product' == $post_type ) {
							$args['taxonomy'] = 'product_cat';
							$args['name']     = 'product_cat';

							wp_dropdown_categories( $args );
						} else {
							wp_dropdown_categories( $args );
						}
					} ?>

					<input name="s" class="search-input" type="text" value="<?php echo get_search_query() ?>"
					       placeholder="<?php echo esc_attr( $place_holder ); ?>" autocomplete="off"/>
				</div>

				<button type="submit" id="search-btn"
				        title="<?php esc_attr_e( 'Search', 'learts' ); ?>"><?php esc_html_e( 'Search',
						'learts' ); ?></button>
				<input type="hidden" name="post_type" value="<?php echo esc_attr( $post_type ); ?>"/>
			</form>

			<?php


			return ob_get_clean();
		}

		public static function header_block_wishlist() {

			if ( ! class_exists( 'YITH_WCWL' ) || ! learts_get_option( 'wishlist_on' ) || learts_get_option( 'wishlist_on' ) == 'false' ) {
				return;
			}

			ob_start();

			$wishlist_icon = learts_get_option( 'wishlist_icon' );

			$wl_count = YITH_WCWL()->count_products();

			?>
			<div class="header-wishlist">
				<a href="#" class="toggle" aria-label="<?php esc_attr_e( 'Wishlist', 'learts' ) ?>">
					<span class="wishlist-count"><?php echo esc_html( $wl_count ); ?></span>
					<i class="<?php echo esc_attr( $wishlist_icon ) ?>" aria-hidden="true"></i>
				</a>
				<div class="wishlist-dropdown-wrapper">
					<div class="wishlist-dropdown widget_wishlist_content">
					</div>
				</div>
			</div>
			<?php

			return ob_get_clean();
		}

		public static function header_block_cart() {

			if ( ! class_exists( 'WooCommerce' ) || ! learts_get_option( 'minicart_on' ) ) {
				return;
			}

			ob_start();

			$cart_icon = learts_get_option( 'minicart_icon' );

			?>
			<div class="header-minicart">
				<a href="#" class="toggle" aria-label="<?php esc_attr_e( 'Shopping Cart', 'learts' ) ?>">
					<i class="<?php echo esc_attr( $cart_icon ) ?>" aria-hidden="true"></i>
					<span class="minicart-text">
						<?php echo Learts_Woo::get_cart_count(); ?>
						<span class="minicart-title"><?php esc_html_e( 'My Cart', 'learts' ) ?></span>
						<?php echo Learts_Woo::get_cart_total(); ?>
					</span>
				</a>
				<div class="minicart-dropdown-wrapper">
					<div class="widget woocommerce widget_shopping_cart">
						<div class="widget_shopping_cart_content"></div>
					</div>
				</div>
			</div>
			<?php

			return ob_get_clean();
		}

		public static function header_block_mobile_btn() {

			if ( learts_Coming_Soon::is_coming_soon_page() ) {
				return;
			}

			ob_start();

			?>
			<div class="mobile-menu-btn hidden-xl-up">
				<svg xmlns="https://www.w3.org/2000/svg" viewBox="0 0 800 600">
					<path d="M300,220 C300,220 520,220 540,220 C740,220 640,540 520,420 C440,340 300,200 300,200"
					      id="top"></path>
					<path d="M300,320 L540,320" id="middle"></path>
					<path d="M300,210 C300,210 520,210 540,210 C740,210 640,530 520,410 C440,330 300,190 300,190"
					      id="bottom" transform="translate(480, 320) scale(1, -1) translate(-480, -318) "></path>
				</svg>
			</div>
			<?php

			return ob_get_clean();
		}

		public static function header_block_header_login() {

			if ( ! learts_get_option( 'header_login' ) || learts_get_option( 'header_login' ) == 'false' ) {
				return;
			}

			ob_start();

			$text = is_user_logged_in() ? esc_html__( 'My Account', 'learts' ) : esc_html__( 'Log in',
				'learts' );

			if ( learts_get_option( 'header_login_position' ) == 'between' ) {
				$text = is_user_logged_in() ? esc_html__( 'My Account', 'learts' ) : esc_html__( 'Log in / Register',
					'learts' );
			}

			$class = '';
			if ( learts_get_option( 'header_login_icon' ) == '1' ) {
				$class = 'login-icon';
			}

			?>
			<div class="header-login <?php echo esc_attr( $class ) ?>">
				<a href="<?php echo get_permalink( get_option( 'woocommerce_myaccount_page_id' ) ); ?>"><i
						class="fal fa-user"></i><span><?php echo esc_html( $text ); ?></span></a>
			</div>
			<?php
			return ob_get_clean();
		}

		/**
		 * Generate page title.
		 */
		public static function page_title() {

			$page_title_on    = learts_get_option( 'page_title_on' );
			$page_title_style = learts_get_option( 'page_title_style' );

			// Custom for post category
			if ( is_category() ) {
				$term_id          = get_category( get_query_var( 'cat' ) )->term_id;
				$page_title_on    = get_term_meta( $term_id, 'learts_page_title_on', true );
				$page_title_style = get_term_meta( $term_id, 'learts_page_title_style', true );

				if ( $page_title_on == 'default' || ! $page_title_on ) {
					$page_title_on = learts_get_option( 'page_title_on' );
				}

				if ( $page_title_on == 'off' ) {
					$page_title_on = false;
				}

				if ( $page_title_style == 'default' || ! $page_title_style ) {
					$page_title_style = learts_get_option( 'page_title_style' );
				}
			}

			// Custom for product category
			if ( is_tax( 'product_cat' ) ) {
				$term_id          = get_term_by( 'slug', get_query_var( 'product_cat' ), 'product_cat' )->term_id;
				$page_title_on    = get_term_meta( $term_id, 'learts_page_title_on', true );
				$page_title_style = get_term_meta( $term_id, 'learts_page_title_style', true );

				if ( $page_title_on == 'default' || ! $page_title_on ) {
					$page_title_on = learts_get_option( 'page_title_on' );
				}

				if ( $page_title_on == 'off' ) {
					$page_title_on = false;
				}

				if ( $page_title_style == 'default' || ! $page_title_style ) {
					$page_title_style = learts_get_option( 'page_title_style' );
				}
			}

			// Check if page header style is set to hidden.
			if ( is_404() || learts_Coming_Soon::is_coming_soon_page() ) {
				$page_title_on = false;
			}

			// Apply filters and return
			$page_title_on = apply_filters( 'learts_page_title_on', $page_title_on );

			ob_start();

			$classes   = array( 'page-title' );
			$classes[] = 'page-title-' . $page_title_style;

			if ( class_exists( 'WooCommerce' ) && ( is_shop() || is_product_taxonomy() ) && learts_get_option( 'shop_toolbar' ) ) {
				$classes[] = 'page-title-shop';
			}

			if ( learts_get_option( 'disable_parallax' ) ) {
				$classes[] = 'no-parallax';
			}

			if ( ! $page_title_on && learts_get_option( 'shop_toolbar' ) == '1' ) {
				$classes[] = 'no-page-title';
			}

			if ( $page_title_on || ( ! $page_title_on && learts_get_option( 'shop_toolbar' ) == '1' ) ) {
				do_action( 'learts_before_page_title' );
				?>
				<div class="<?php echo esc_attr( implode( ' ', $classes ) ); ?>">
					<div class="container">
						<div class="row">
							<div class="col-xs-12">
								<h1 class="entry-title">
									<?php if ( ( get_post_type() == 'portfolio' && is_archive() ) ) {
										echo esc_html__( 'Portfolio', 'learts' );
									} else {
										echo Learts_Helper::get_the_title();
									} ?>

								</h1>

								<?php
								if ( is_page() && learts_get_option( 'page_meta' ) ) {
									echo Learts_Templates::post_meta( array(
										'author' => 1,
										'cats'   => 1,
									) );
								}
								?>
								<?php if ( $sub_title = self::page_subtitle() ) : ?>
									<span class="page-subtitle"><?php echo '' . $sub_title ?></span>
								<?php endif; ?>
								<div class="site-breadcrumbs">
									<?php echo self::breadcrumbs(); ?>
								</div>
							</div>
						</div>
					</div>
				</div>
				<?php
				do_action( 'learts_after_page_title' );
			}

			return ob_get_clean();
		}

		/**
		 * Returns page subheading.
		 *
		 * @since 1.0
		 */
		public static function page_subtitle() {

			// Subheading is NULL by default.
			$subheading = '';

			// Posts & Pages.
			if ( $meta = get_post_meta( Learts_Helper::get_the_ID(), 'learts_subtitle', true ) ) {
				$subheading = $meta;
			}

			// Search.
			if ( is_search() ) {
				$subheading = esc_html__( 'You searched for:',
						'learts' ) . ' &quot;' . esc_html( get_search_query( false ) ) . '&quot;';
			}

			// Author.
			if ( is_author() ) {
				$subheading = esc_html__( 'This author has written',
						'learts' ) . ' ' . get_the_author_posts() . ' ' . esc_html__( 'articles', 'learts' );
			}

			// Archives.
			if ( is_tag() || is_category() || is_author() ) {
				$subheading = get_the_archive_description();
			}

			// Post Archive
			if ( is_post_type_archive() ) {
				$subheading = get_the_archive_description();
			}

			if ( function_exists( 'is_shop' ) && is_shop() && get_option( 'woocommerce_shop_page_id' ) == Learts_Helper::get_the_ID() ) {
				$subheading = get_post_meta( Learts_Helper::get_the_ID(), 'learts_subtitle', true );
			}

			// All other Taxonomies.
			if ( is_tax() ) {
				$subheading = term_description();
			}

			// Apply filters and return.
			return apply_filters( 'learts_post_subheading', $subheading );

		}

		public static function breadcrumbs( $args = '' ) {

			$breadcrumbs_on = learts_get_option( 'breadcrumbs' );

			if ( 'off' === $breadcrumbs_on ) {
				$breadcrumbs_on = false;
			}

			if ( ! $breadcrumbs_on ) {
				return;
			}

			ob_start();

			if ( function_exists( 'insight_core_breadcrumb' ) ) {
				insight_core_breadcrumb( $args );
			}

			return ob_get_clean();
		}

		/**
		 * Page header product nav
		 *
		 * @return string|void
		 */
		public static function single_navigation() {

			if ( ! is_singular( 'post' ) && ! is_singular( 'product' ) ) {
				return;
			}

			if ( ! learts_get_option( 'single_nav_on' ) ) {
				return;
			}

			ob_start();

			if ( is_singular( 'post' ) ) {
				$taxonomy = 'category';
			} elseif ( is_singular( 'product' ) ) {
				$taxonomy = 'product_cat';
			}

			$next_post = get_next_post( true, '', $taxonomy );
			$prev_post = get_previous_post( true, '', $taxonomy );

			$image_size   = 'learts-single-navigation';
			$next_product = false;
			$prev_product = false;

			if ( is_singular( 'product' ) ) {
				$image_size = 'shop_thumbnail';
			}

			if ( $next_post ) {
				$next_title     = get_the_title( $next_post );
				$next_link      = get_permalink( $next_post );
				$next_thumbnail = get_the_post_thumbnail( $next_post, $image_size );

				if ( function_exists( 'wc_get_product' ) ) {
					$next_product = wc_get_product( $next_post->ID );
				}
			}

			if ( $prev_post ) {
				$prev_title     = get_the_title( $prev_post );
				$prev_link      = get_permalink( $prev_post );
				$prev_thumbnail = get_the_post_thumbnail( $prev_post, $image_size );

				if ( function_exists( 'wc_get_product' ) ) {
					$prev_product = wc_get_product( $prev_post->ID );
				}
			}

			?>

			<?php if ( $next_post || $prev_post ) : ?>
				<div class="single-nav-wrapper">
					<?php if ( $next_post ) : ?>
						<div class="single-nav single-nav__prev-item">
							<?php next_post_link( '%link',
								'<i class="ion-ios-arrow-thin-left" aria-hidden="true"></i>',
								true,
								'',
								$taxonomy ); ?>
							<div class="item-wrapper">
								<?php if ( $next_thumbnail ) : ?>
									<div class="thumbnail"><a
											href="<?php echo esc_url( $next_link ); ?>"><?php echo wp_kses_post( $next_thumbnail ); ?></a>
									</div>
								<?php endif; ?>
								<div class="details">
									<?php echo( sprintf( '<a href="%s" class="title">%s</a>',
										esc_url( $next_link ),
										$next_title ) ); ?>
									<?php if ( is_singular( 'post' ) ) : ?>
										<?php echo sprintf( '<span class="meta-date"><a href="%1$s">%2$s</a>',
											esc_url( get_permalink() ),
											get_the_date() ); ?></span>
									<?php endif; ?>
									<?php if ( is_singular( 'product' ) && $next_product ) : ?>
										<span
											class="price"><?php echo wp_kses_post( $next_product->get_price_html() ); ?></span>
									<?php endif; ?>
								</div>
							</div>
						</div>
					<?php endif; ?>
					<?php if ( $prev_post ) : ?>
						<div class="single-nav single-nav__next-item">
							<?php previous_post_link( '%link',
								'<i class="ion-ios-arrow-thin-right" aria-hidden="true"></i>',
								true,
								'',
								$taxonomy ); ?>
							<div class="item-wrapper">
								<?php if ( $prev_thumbnail ) : ?>
									<div class="thumbnail"><a
											href="<?php echo esc_url( $prev_link ); ?>"><?php echo wp_kses_post( $prev_thumbnail ); ?></a>
									</div>
								<?php endif; ?>
								<div class="details">
									<?php echo( sprintf( '<a href="%s" class="title">%s</a>',
										$prev_link,
										$prev_title ) ); ?>
									<?php if ( is_singular( 'post' ) ) : ?>
										<?php echo sprintf( '<span class="meta-date"><a href="%1$s">%2$s</a>',
											esc_url( get_permalink() ),
											get_the_date() ); ?></span>
									<?php endif; ?>
									<?php if ( is_singular( 'product' ) && $prev_product ) : ?>
										<span
											class="price"><?php echo wp_kses_post( $prev_product->get_price_html() ); ?></span>
									<?php endif; ?>
								</div>
							</div>
						</div>
					<?php endif; ?>
				</div>
				<?php
			endif;

			return ob_get_clean();
		}
	}

	new Learts_Templates();

}
