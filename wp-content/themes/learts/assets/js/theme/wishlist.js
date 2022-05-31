// Wishlist
(function( $ ) {
	"use strict";

	var $window = $( window ),
		$body   = $( 'body' );

	learts.wishlist = function() {

		var $wishlist            = $( '.header-wishlist' ),
			$dropDown            = $wishlist.find( '.wishlist-dropdown-wrapper' ),
			updatingWishlist     = false,
			removeAfterAddToCart = false,
			undoTimeout;

		if ( ! $wishlist.length ) {
			return;
		}

		// Wishlist Fragments
		var wlFragments = function() {

			/* Storage Handling */
			var $supports_html5_storage,
				wl_hash_key   = 'learts_wl_hash',
				fragment_name = 'learts_wl_fragments';

			try {
				$supports_html5_storage = (
					'sessionStorage' in window && window.sessionStorage !== null
				);
				window.sessionStorage.setItem( 'learts', 'test' );
				window.sessionStorage.removeItem( 'learts' );
				window.localStorage.setItem( 'learts', 'test' );
				window.localStorage.removeItem( 'learts' );
			} catch ( err ) {
				$supports_html5_storage = false;
			}

			/* Wishlist session creation time to base expiration on */
			function set_wl_creation_timestamp() {
				if ( $supports_html5_storage ) {
					sessionStorage.setItem( 'learts_wl_created', (
						new Date()
					).getTime() );
				}
			}

			/** Set the wishlist hash in both session and local storage */
			function set_wl_hash( wl_hash ) {
				if ( $supports_html5_storage ) {
					localStorage.setItem( wl_hash_key, wl_hash );
					sessionStorage.setItem( wl_hash_key, wl_hash );
				}
			}

			var $fragment_refresh = {
				url    : leartsConfigs.ajax_url,
				type   : 'GET',
				data   : {
					action: 'learts_get_wishlist_fragments',
				},
				success: function( data ) {

					if ( data && data.fragments ) {

						$.each( data.fragments, function( key, value ) {
							$( key ).replaceWith( value );
						} );

						if ( $supports_html5_storage ) {
							sessionStorage.setItem( fragment_name, JSON.stringify( data.fragments ) );
							set_wl_hash( data.wl_hash );

							if ( data.wl_hash ) {
								set_wl_creation_timestamp();
							}
						}

						$( document.body ).trigger( 'wl_fragments_refreshed' );
					}
				},
				error  : function( error ) {
					console.log( error );
				},
			};

			/* Named callback for refreshing wishlist fragment */
			function refresh_wl_fragment() {
				$.ajax( $fragment_refresh );
			}

			/* Wishlist Handling */
			if ( $supports_html5_storage ) {

				var wl_timeout = null,
					day_in_ms  = 24 * 60 * 60 * 1000;

				$( document.body ).bind( 'wl_fragment_refresh updated_wc_div', function() {
					refresh_wl_fragment();
				} );

				$( document.body )
					.bind( 'added_to_wishlist removed_from_wishlist', function( event, fragments, cart_hash ) {
						var prev_wl_hash = sessionStorage.getItem( wl_hash_key );

						if ( prev_wl_hash === null || prev_wl_hash === undefined || prev_wl_hash === '' ) {
							set_wl_creation_timestamp();
						}

						sessionStorage.setItem( fragment_name, JSON.stringify( fragments ) );
						refresh_wl_fragment();

					} );

				$( document.body ).bind( 'wl_fragments_refreshed', function() {
					clearTimeout( wl_timeout );
					wl_timeout = setTimeout( refresh_wl_fragment, day_in_ms );
				} );

				// Refresh when storage changes in another tab
				$window.on( 'storage onstorage', function( e ) {
					if ( wl_hash_key === e.originalEvent.key && localStorage.getItem( wl_hash_key ) !== sessionStorage.getItem( wl_hash_key ) ) {
						refresh_wl_fragment();
					}
				} );

				try {

					var wl_fragments = JSON.parse( sessionStorage.getItem( fragment_name ) ),
						wl_hash      = sessionStorage.getItem( wl_hash_key ),
						cookie_hash  = Cookies.set( 'learts_wl_hash' ),
						wl_created   = sessionStorage.getItem( 'learts_wl_created' );

					if ( wl_hash === null || wl_hash === undefined || wl_hash === '' ) {
						wl_hash = '';
					}

					if ( cookie_hash === null || cookie_hash === undefined || cookie_hash === '' ) {
						cookie_hash = '';
					}

					if ( wl_hash && (
							wl_created === null || wl_created === undefined || wl_created === ''
						) ) {
						throw 'No wishlist_created';
					}

					if ( wl_created ) {
						var wl_expiration = 1 * cart_created + day_in_ms,
							timestamp_now = (
								new Date()
							).getTime();

						if ( cart_expiration < timestamp_now ) {
							throw 'Fragment expired';
						}

						wl_timeout = setTimeout( refresh_wl_fragment, (
							wl_expiration - timestamp_now
						) );
					}

					if ( wl_fragments && wl_fragments['div.widget_wishlist_content'] && wl_hash === cookie_hash ) {

						$.each( wl_fragments, function( key, value ) {
							$( key ).replaceWith( value );
						} );

						$( document.body ).trigger( 'wl_fragments_loaded' );

					} else {
						throw 'No fragment';
					}
				} catch ( err ) {
					refresh_wl_fragment();
				}
			} else {
				refresh_wl_fragment();
			}
		};

		var events = function() {

			$wishlist.on( 'click', '>.toggle', function( e ) {

				e.preventDefault();

				if ( ! $wishlist.hasClass( 'wishlist-open' ) ) {
					open();
				} else {
					close();
				}

			} );

			$body.on( 'click', '#page-container', function( e ) {

				var $target = $( e.target ).closest( '.header-wishlist' );

				if ( ! $target.length ) {
					close();
				}
			} );

			$body.on( 'added_to_wishlist wl_fragments_refreshed wl_fragments_loaded', function() {
				initUndo();
				initRemove();
				initAddToCart();

				// perfectScrollbar
				$wishlist.find( '.product_list_widget' ).perfectScrollbar( { suppressScrollX: true } );
			} );

			// re-calculate the top value of mobile menu when resize
			$window.on( 'resize', function() {
				learts.setTopValue( $dropDown );
			} );
		};

		var open = function() {

			$wishlist.addClass( 'wishlist-open' );
			$body.addClass( 'wishlist-opened' );

			$wishlist.find( '.close-btn' ).on( 'click', function( e ) {
				e.preventDefault();
				close();
			} );

			learts.setTopValue( $dropDown );
		};

		var close = function() {
			$wishlist.removeClass( 'wishlist-open' );
			$body.removeClass( 'wishlist-opened' );
		};

		var initRemove = function() {

			$wishlist.find( '.wishlist_item .remove' ).on( 'click', function( e ) {

				e.preventDefault();

				var $this         = $( this ),
					$item         = $this.closest( '.wishlist_item' ),
					product_id    = $item.data( 'product_id' ),
					wishlistID    = $item.data( 'wishlist_id' ),
					wishlistToken = $item.data( 'wishlist_token' ),
					data          = {
						remove_from_wishlist: product_id,
						wishlist_id         : wishlistID,
						wishlist_token      : wishlistToken,
					};

				request( 'remove', data, function() {

					resetUndo();

					$item.addClass( 'deleted' );

					if ( ! removeAfterAddToCart ) {
						$wishlist.find( '.undo' ).addClass( 'visible' );
					}

					// Update class for wishlist buttons
					var $wlButtons = $( '.yith-wcwl-add-to-wishlist.add-to-wishlist-' + product_id );

					if ( $wlButtons.length ) {
						$wlButtons.find( '.yith-wcwl-add-button' )
								  .show()
								  .removeClass( 'hide' )
								  .addClass( 'show' );
						$wlButtons.find( '.yith-wcwl-wishlistaddedbrowse' )
								  .hide()
								  .removeClass( 'show' )
								  .addClass( 'hide' );
						$wlButtons.find( '.yith-wcwl-wishlistexistsbrowse' )
								  .hide()
								  .removeClass( 'show' )
								  .addClass( 'hide' );

						$wlButtons.find( '.add_to_wishlist' ).removeClass( 'loading' );
					}

					// wait 8 seconds before completely remove the item
					undoTimeout = setTimeout( function() {
						resetUndo();
					}, 8000 );
				} );
			} );
		};

		var initUndo = function() {

			$wishlist.find( '.undo' ).on( 'click', 'a', function( e ) {

				e.preventDefault();

				if ( undoTimeout ) {
					clearInterval( undoTimeout );
				}

				var $item         = $wishlist.find( '.wishlist_item.deleted' ),
					product_id    = $item.data( 'product_id' ),
					wishlistID    = $item.data( 'wishlist_id' ),
					wishlistToken = $item.data( 'wishlist_token' ),
					data          = {
						add_to_wishlist: product_id,
						wishlist_id    : wishlistID,
						wishlist_token : wishlistToken,
					};

				$item.addClass( 'undo-deleted' )
					 .one( 'webkitAnimationEnd oanimationend msAnimationEnd animationend', function() {

						 if ( $wishlist.find( '.wishlist_item' ).length == 1 ) {
							 $wishlist.find( '.wishlist_empty_message' ).addClass( 'hidden' );
						 }

						 $( this )
							 .off( 'webkitAnimationEnd oanimationend msAnimationEnd animationend' )
							 .removeClass( 'deleted undo-deleted' )
							 .removeAttr( 'style' );

						 request( 'undo', data, function() {

							 resetUndo();

							 // Update class for wishlist buttons
							 var $wlButtons = $( '.yith-wcwl-add-to-wishlist.add-to-wishlist-' + product_id );

							 if ( $wlButtons.length ) {
								 $wlButtons.find( '.yith-wcwl-add-button' )
										   .show()
										   .removeClass( 'show' )
										   .addClass( 'hide' );
								 $wlButtons.find( '.yith-wcwl-wishlistaddedbrowse' )
										   .hide()
										   .removeClass( 'hide' )
										   .addClass( 'show' );
								 $wlButtons.find( '.yith-wcwl-wishlistexistsbrowse' )
										   .hide()
										   .removeClass( 'show' )
										   .addClass( 'hide' );
							 }
						 } );
					 } );
			} );
		};

		var initAddToCart = function() {

			$wishlist.find( '.add_to_cart_button.product_type_simple' ).on( 'click', function() {

				if ( $wishlist.find( '.remove_after_add_to_cart' ).length ) {

					removeAfterAddToCart = true;

					$( this ).closest( '.wishlist_item' ).find( '.remove' ).trigger( 'click' );
				}
			} );
		};

		var resetUndo = function() {

			if ( undoTimeout ) {
				clearInterval( undoTimeout );
			}

			$wishlist.find( '.undo' ).removeClass( 'visible' );
			$wishlist.find( '.wishlist_item.deleted' ).remove();
		};

		var request = function( type, item, callback ) {

			if ( updatingWishlist ) {
				return;
			}

			var action = '';

			if ( type == 'remove' ) {
				action = 'learts_remove_wishlist_item';
			} else if ( type == 'undo' ) {
				action = 'learts_undo_remove_wishlist_item';
			} else {
				return;
			}

			$dropDown.addClass( 'loading' );

			$.ajax( {
				type    : 'POST',
				dataType: 'json',
				url     : leartsConfigs.ajax_url,
				data    : {
					action: action,
					item  : item
				},
				success : function( response ) {

					if ( typeof response.success != 'undefined' && response.success == false ) {
						return false;
					}

					updateWishListFragments( type, response );

					clearInterval( undoTimeout );

					if ( typeof callback !== 'undefined' ) {
						callback( response );
					}

					$dropDown.removeClass( 'loading' );

					updatingWishlist = false;

					removeAfterAddToCart = false;
				},
				error   : function( error ) {
					console.log( error );
				},
			} );
		};

		var updateWishListFragments = function( action, data ) {

			if ( action === 'remove' || action === 'undo' ) {

				// just update wishlist count

				if ( typeof data.fragments !== 'undefined' ) {

					$.each( data.fragments, function( key, value ) {

						if ( key === 'tm-wishlist' ) {

							var $emptyMessage = $wishlist.find( '.wishlist_empty_message' ),
								$button       = $wishlist.find( '.btn-view-wishlist' );

							if ( action == 'remove' && value.count == 0 ) {
								$emptyMessage.removeClass( 'hidden' );
								$button.addClass( 'hidden' );
							} else if ( action == 'undo' && value.count == 1 ) {
								$button.removeClass( 'hidden' );

							}

							// update wishlist count
							$wishlist.find( '.wishlist-count' ).html( value.count );
						}
					} );
				}
			} else {
				$body.trigger( 'wl_fragment_refresh' );
			}

			$body.trigger( 'wl_fragment_refreshed' );
		};

		wlFragments();
		events();
	};
})( jQuery );
