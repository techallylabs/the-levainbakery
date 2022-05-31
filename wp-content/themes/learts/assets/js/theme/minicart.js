// Minicart
(function( $ ) {
	"use strict";

		var $window   = $( window ),
			$document = $( document ),
			$body     = $( 'body' );

		learts.miniCart = function() {

			var $minicart        = $( '.header-minicart' ),
				$dropDown        = $minicart.find( '.minicart-dropdown-wrapper' ),
				itemsCount       = 0,
				updatingMiniCart = false,
				favicon,
				undoTimeout,
				minicart_opened  = Cookies.get( 'learts_minicart_favico_opened' );

			if ( leartsConfigs.shop_add_to_cart_favico_on ) {
				favicon = new Favico( {
					animation: 'none',
					bgColor  : leartsConfigs.shop_favico_badge_bg_color,
					textColor: leartsConfigs.shop_favico_badge_text_color,
				} );
			}

			var events = function() {

				var initEvents = function() {
					initRemove();
					initUndo();

					itemsCount = parseInt( $minicart.first()
													.find( '.minicart-count' )
													.text() );

					// perfectScrollbar
					$minicart.find( '.product_list_widget' ).perfectScrollbar( { suppressScrollX: true } );

					if ( minicart_opened == 'yes' ) {
						favicon.badge( 0 );
					}
				};

				$minicart.on( 'click', '>.toggle', function( e ) {

					e.preventDefault();

					if ( ! $minicart.hasClass( 'minicart-open' ) ) {
						open();
					} else {
						close();
					}

				} );

				$body.on( 'click', '#page-container', function( e ) {

					var $target = $( e.target ).closest( '.header-minicart' );

					if ( ! $target.length ) {
						close();
					}
				} );

				// Trigger  fragments refreshed event
				updateCartFragments( 'refresh' );

				$body.on( 'added_to_cart wc_fragments_refreshed wc_fragments_loaded', function() {
					initEvents();

					// favico notification
					if ( leartsConfigs.shop_add_to_cart_favico_on && minicart_opened != 'yes' ) {
						favicon.badge( itemsCount );
					}
				} );

				$body.on( 'added_to_cart', function() {

					// favico notification
					if ( leartsConfigs.shop_add_to_cart_favico_on ) {
						favicon.badge( itemsCount );
						Cookies.set( 'learts_minicart_favico_opened', 'no', {
							expires: 1,
							path   : '/'
						} );
					}
				} );

				// When Compare iframe closed
				$document.on( 'cbox_closed', function() {
					updateCartFragments( 'refresh' );
				} );

				// re-calculate the top value of mobile menu when resize
				$window.on( 'resize', function() {
					learts.setTopValue( $dropDown );
				} );
			};

			var open = function() {

				$minicart.addClass( 'minicart-open' );
				$body.addClass( 'minicart-opened' );

				$minicart.find( '.close-btn' ).on( 'click', function( e ) {
					e.preventDefault();
					close();
				} );

				learts.setTopValue( $dropDown );

				// favico notification
				if ( leartsConfigs.shop_add_to_cart_favico_on ) {
					favicon.badge( 0 );
					Cookies.set( 'learts_minicart_favico_opened', 'yes', {
						expires: 1,
						path   : '/'
					} );
				}
			};

			var close = function() {
				$minicart.removeClass( 'minicart-open' );
				$body.removeClass( 'minicart-opened' );
			};

			var initRemove = function() {

				$minicart.find( '.woocommerce-mini-cart-item .remove' ).on( 'click', function( e ) {

					e.preventDefault();

					var $this         = $( this ),
						cart_item_key = $this.attr( 'data-cart_item_key' ),
						$item         = $this.closest( '.woocommerce-mini-cart-item' );

					request( 'remove', cart_item_key, function() {

						resetUndo();

						$item.addClass( 'deleted' );

						$minicart.find( '.undo' ).addClass( 'visible' );

						// wait 8 seconds before completely remove the items
						undoTimeout = setTimeout( function() {
							resetUndo();
						}, 8000 );
					} );
				} );
			};

			var initUndo = function() {

				$minicart.find( '.undo' ).on( 'click', 'a', function( e ) {

					e.preventDefault();

					if ( undoTimeout ) {
						clearInterval( undoTimeout );
					}

					var $item         = $minicart.find( '.woocommerce-mini-cart-item.deleted' ),
						cart_item_key = $item.find( '.remove' ).data( 'cart_item_key' );

					if ( $minicart.find( '.woocommerce-mini-cart-item' ).length == 1 ) {
						$minicart.find( '.woocommerce-mini-cart__empty-message' ).addClass( 'hidden' );
					}

					$item.addClass( 'undo-deleted' )
						 .one( 'webkitAnimationEnd oanimationend msAnimationEnd animationend', function() {
							 $( this )
								 .off( 'webkitAnimationEnd oanimationend msAnimationEnd animationend' )
								 .removeClass( 'deleted undo-deleted' )
								 .removeAttr( 'style' );

							 request( 'undo', cart_item_key, function() {
								 resetUndo();
							 } );
						 } );
				} );
			};

			var resetUndo = function() {

				if ( undoTimeout ) {
					clearInterval( undoTimeout );
				}

				$minicart.find( '.undo' ).removeClass( 'visible' );
				$minicart.find( '.woocommerce-mini-cart-item.deleted' ).remove();
			};

			var request = function( type, cart_item_key, callback ) {

				if ( updatingMiniCart ) {
					return;
				}

				var action    = '';

				if ( type == 'remove' ) {
					action = 'learts_remove_cart_item';
				} else if ( type == 'undo' ) {
					action = 'learts_undo_remove_cart_item';
				} else {
					return;
				}

				$dropDown.addClass( 'loading' );

				updatingMiniCart = true;

				$.ajax( {
					type    : 'POST',
					dataType: 'json',
					url     : leartsConfigs.ajax_url,
					data    : {
						action: action,
						item  : cart_item_key
					},
					success : function( response ) {

						updateCartFragments( type, response );

						clearInterval( undoTimeout );

						if ( typeof callback !== 'undefined' ) {
							callback( response );
						}

						$dropDown.removeClass( 'loading' );

						updatingMiniCart = false;
					},
					error   : function( error ) {
						console.log( error );
					},
				} );
			};

			var updateCartFragments = function( action, data ) {

				if ( action === 'remove' || action === 'undo' ) {

					// just update cart count & cart total, don't update the product list
					if ( typeof data.fragments !== 'undefined' ) {

						$.each( data.fragments, function( key, value ) {

							if ( key === 'tm-minicart' ) {

								var $emptyMessage    = $minicart.find( '.woocommerce-mini-cart__empty-message' ),
									$total           = $minicart.find( '.woocommerce-mini-cart__total' ),
									$buttons         = $minicart.find( '.woocommerce-mini-cart__buttons' ),
									$minicartMessage = $minicart.find( '.minicart-message' );

								if ( action == 'remove' && value.count == 0 ) {
									$emptyMessage.removeClass( 'hidden' );
									$total.addClass( 'hidden' );
									$buttons.addClass( 'hidden' );
									$minicartMessage.addClass( 'hidden' );
								} else if ( action == 'undo' && value.count == 1 ) {
									$total.removeClass( 'hidden' );
									$buttons.removeClass( 'hidden' );
									$minicartMessage.removeClass( 'hidden' );
								}

								// update cart count
								$minicart.find( '.minicart-count' ).html( value.count );

								// update cart total
								$minicart.find( '.woocommerce-mini-cart__total .woocommerce-Price-amount' )
										 .html( value.total );
							}
						} );
					}

					// if you are in the Cart page, trigger wc_update_cart event
					if ( $body.hasClass( 'woocommerce-cart' ) ) {
						$body.trigger( 'wc_update_cart' );
					}
				}

				$body.trigger( 'wc_fragments_refreshed' );
			};

			events();
		};
	})( jQuery );
