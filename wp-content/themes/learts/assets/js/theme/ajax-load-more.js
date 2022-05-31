// AJAX load more post / product
(
	function( $ ) {
		"use strict";

		learts.ajaxLoadMore = function() {

			var $body = $('body'),
				$switcherFiveColumns  = $( '.ti-layout-grid4-alt' ),
				$switcherFourColumns  = $( '.ti-layout-grid3-alt' ),
				$switcherThreeColumns = $( '.ti-layout-grid2-alt' ),
				$columnSwitcher       = leartsConfigs.categories_columns,
				$activeCol            = $( '.col-switcher' ).find( '.active' ),
				$col                  = $activeCol.attr( 'data-col' );

			if ( $col == 5 ) {
				$columnSwitcher = 5;
			}

			if ( $col == 4 ) {
				$columnSwitcher = 4;
			}

			if ( $col == 3 ) {
				$columnSwitcher = 3;
			}

			$switcherFiveColumns.on( 'click', function( e ) {
				e.preventDefault();
				$columnSwitcher = 5;
			} );

			$switcherFourColumns.on( 'click', function( e ) {
				e.preventDefault();
				$columnSwitcher = 4;
			} );

			$switcherThreeColumns.on( 'click', function( e ) {
				e.preventDefault();
				$columnSwitcher = 3;
			} );

			var _loadMoreBtns = [].slice.call( document.querySelectorAll( '.learts-loadmore-btn' ) ),
				loading       = false,
				loadPosts     = function( _el ) {

					var _loadMoreWrap  = _el.parentNode,
						atts           = JSON.parse( _loadMoreWrap.getAttribute( 'data-atts' ) ),
						btn_columns    = parseInt( atts.columns ),
						posts_per_page = parseInt( atts.posts_per_page ),
						total 		   = parseInt(atts.total),
						view           = atts.view,
						_wrapContainer = document.querySelector( atts.container ),
						pAttr          = JSON.parse( _wrapContainer.getAttribute( 'data-atts' ) ),
						filter         = _loadMoreWrap.getAttribute( 'data-filter' ),
						column_loadmore_post = 1;
					if(  leartsConfigs.archive_display_type && leartsConfigs.archive_display_type == 'list' ) {
						column_loadmore_post = 1;
					}

					if( leartsConfigs.archive_display_type && leartsConfigs.archive_display_type == 'grid' || leartsConfigs.archive_display_type == 'masonry' || leartsConfigs.archive_display_type == 'full-grid' ) {

						column_loadmore_post = 2;

						if ( $( '.inner-page-wrap' ).hasClass('has-no-sidebars') ) {
							column_loadmore_post = 3;
						}
					}

					if(  leartsConfigs.portfolio_columns && leartsConfigs.portfolio_columns == '3' ) {
						column_loadmore_post = 3;
					}

					if(  leartsConfigs.portfolio_columns && leartsConfigs.portfolio_columns == '4' ) {
						column_loadmore_post = 4;
					}

					if(  leartsConfigs.portfolio_columns && leartsConfigs.portfolio_columns == '5' ) {
						column_loadmore_post = 5;
					}

					var 	data           = {
							action        : 'learts_ajax_load_more',
							post_type     : atts.post_type,
							posts_per_page: posts_per_page,
							category      : filter,
							view          : view,
							img_size      : atts.img_size,
							exclude       : []
						};

					if ( atts.post_type == 'product' ) {
						data.columns = ($columnSwitcher && $body.hasClass('post-type-archive-product')) ? $columnSwitcher : btn_columns;
					} else {
						data.columns = column_loadmore_post;
					}

					var _wrapper = null;

					if ( atts.post_type == 'post' ) {

						if ( _loadMoreWrap.classList.contains( 'learts-pagination' ) ) {
							_wrapper = _wrapContainer;
						} else {
							_wrapper = _wrapContainer.querySelector( '.posts' );
						}
					}

					if ( atts.post_type == 'portfolio' ) {

						if ( _loadMoreWrap.classList.contains( 'learts-pagination' ) ) {
							_wrapper = _wrapContainer;
						} else {
							_wrapper = _wrapContainer.querySelector( '.portfolios' );
						}
					}

					if ( atts.post_type == 'product' ) {

						if ( _loadMoreWrap.classList.contains( 'woocommerce-pagination' ) ) {
							_wrapper = _wrapContainer;
						} else {
							_wrapper = _wrapContainer.querySelector( '.products' );
						}
					}

					if ( _wrapper == null || _loadMoreWrap.classList.contains( 'hidden' ) ) {
						return;
					}

					if ( atts.post_type == 'post' || atts.post_type === 'portfolio' ) {

						if ( pAttr != null ) {

							data.filter = pAttr.filter;
							data.columns = pAttr.columns;

							if ( pAttr.filter == 'category' ) {
								data.cat_slugs = pAttr.cat_slugs;
							}

							if ( pAttr.filter == 'tag' ) {
								data.tag_slugs = pAttr.tag_slugs;
							}

							if ( atts.orderby !== null ) {
								data.orderby = pAttr.orderby;
							}

							if ( atts.order !== null ) {
								data.order = pAttr.order;
							}
						}
					}

					if ( atts.post_type == 'product' ) {

						if ( pAttr == null ) { // on product category page
							if ( atts.data_source == null || atts.data_source === 'undefined' ) {
								data.data_source = _loadMoreWrap.getAttribute( 'data-filter' );
							} else {
								data.data_source = atts.data_source;
								data.category = atts.category;
								data.include_children = true;
							}

							if ( atts.data_source == 'categories' ) {
								data.data_source = atts.data_source;
								data.category = atts.category;
								data.include_children = true;
							}

							if ( atts.data_source == 'filter' ) {
								data.data_source = atts.data_source;
								data.tax_array = atts.tax_array;
							}

							if ( atts.orderby !== null ) {
								data.orderby = atts.orderby;
							}

							if ( atts.order !== null ) {
								data.order = atts.order;
							}

						} else {
							data.data_source = pAttr.data_source;

							if ( pAttr.data_source == 'category' ) {
								data.category = _loadMoreWrap.getAttribute( 'data-filter' );
								data.include_children = pAttr.include_children == 'yes';
							}else{
								var arr_filter = ['featured_products','sale_products','best_selling_products','top_rated_products','recent_products'];
								if( typeof pAttr.data_source !== 'undefined' ){
									data.data_source = _loadMoreWrap.getAttribute( 'data-filter' );
								}
							}

							if ( pAttr.data_source == 'product_attribute' ) {
								data.attribute = pAttr.attribute;
								data.filter = pAttr.filter;
							}

							if ( pAttr.data_source == 'categories' ) {
								data.product_cat_slugs = pAttr.product_cat_slugs;
								data.include_children = pAttr.include_children == 'yes';
							}

							if ( pAttr.orderby !== null ) {
								data.orderby = pAttr.orderby;
							}

							if ( pAttr.order !== null ) {
								data.order = pAttr.order;
							}
						}
					}

					//exclude queried posts
					var className = '.product-loop';

					if ( atts.post_type === 'post' ) {
						className = '.post-item';
					}

					if ( atts.post_type === 'portfolio' ) {
						className = '.portfolio-item';
					}

					var _posts = [].slice.call( _wrapper.querySelectorAll( className ) );

					_posts.forEach( function( post ) {
						var productID = post.className.match( /post-\d+/gi )[0];
						productID = productID.replace( 'post-', '' );

						data.exclude.push( productID );
					} );


					$.ajax( {
						method    : 'POST',
						url       : leartsConfigs.ajax_url,
						data      : data,
						beforeSend: function() {
							loading = true;
							_loadMoreWrap.classList.add( 'loading' );
						},
						success   : function( response ) {

							if ( response ) {

								var iso = Isotope.data( _wrapper );

								$( _wrapper ).append( $( response ) );

								imagesLoaded( _wrapper, function() {

									var _items = [].slice.call( _wrapper.querySelectorAll( '.adding-item' ) ),
										gridFx = new GridLoaderFx( _wrapper, '.adding-item' );

									if ( atts.post_type == 'post' ) {
										learts.fitVideo();
										learts.thumbGallery();
									}

									if ( atts.post_type == 'product' ) {
										learts.reInitSwatches();
									}

									if ( iso != null ) {
										_items.forEach( function( item ) {
											iso.appended( item );
										} );
									}

									_items.forEach( function( item ) {
										item.classList.remove( 'adding-item' );
									} );

									if ( _items.length < posts_per_page && _items.length !== 0 ) {
										_loadMoreWrap.classList.add( 'hidden' );
									}

									var c = $( _loadMoreWrap ).parent();
									var a = c.find( '.product-filter li a.active' ).attr( 'data-page' );
									var k = a ++;
									if ( a ) {
										atts.paged = a;
									} else {
										atts.paged ++;
									}

									if ( atts.paged >= total - 1 ) {
										$('.learts-loadmore-wrap').addClass('hidden');
									}

									c.find( '.product-filter li a.active' ).attr( 'data-page', a );
									_loadMoreWrap.setAttribute( 'data-atts', JSON.stringify( atts ) );
								} );

							} else {
								_loadMoreWrap.classList.add( 'hidden' );
							}

							_loadMoreWrap.classList.remove( 'loading' );
							loading = false;
						},
						error     : function( error ) {
							console.log( error );
						}
					} );
				};

			_loadMoreBtns.forEach( function( _btn ) {

				_btn.addEventListener( 'click', function( e ) {
					e.preventDefault();

					loadPosts( _btn );
				} );

				if ( _btn.classList.contains( 'load-on-scroll' ) ) {

					var _loadMoreWrap = _btn.parentNode;

					$( window ).on( 'scroll', function() {

						if ( $( '.learts-loadmore-wrap' ).length ) {

							if ( $( _loadMoreWrap ).offset().top <= $( this ).scrollTop() + $( this )
									.height() && ! loading ) {
								loadPosts( _btn );
							}
						}
					} )
				}

			} );
		};

	}
)( jQuery );
