// Notification
(
	function( $ ) {
		"use strict";

		var $body = $( 'body' );

		learts.notification = function() {

			var config = {
				location: 'tr',
				title   : '',
				size    : 'large',
			};

			var events = function() {

				$body.on( 'click', 'a.add_to_cart_button', function() {
					$( 'a.add_to_cart_button' ).removeClass( 'recent-added' );
					$( this ).addClass( 'recent-added' );

					if ( $( this ).is( '.product_type_variable, .isw-ready' ) ) {
						$( this ).addClass( 'loading' );
					}

				} );

				// On single product page
				$body.on( 'click', 'button.single_add_to_cart_button', function() {
					$( 'button.single_add_to_cart_button' ).removeClass( 'recent-added' );
					$( this ).addClass( 'recent-added' );
				} );

				$body.on( 'click', '.add_to_wishlist', function() {
					$( this ).addClass( 'loading' );
				} );
			};

			var addToCartNotification = function() {

				$body.on( 'added_to_cart', function() {

					$( '.add_to_cart_button.product_type_variable.isw-ready' ).removeClass( 'loading' );

					var $recentAdded = $( '.add_to_cart_button.recent-added, button.single_add_to_cart_button.recent-added' ),
						$img         = $recentAdded.closest( '.product-loop' ).find( 'img.wp-post-image' ),
						pName        = $recentAdded.attr( 'data-product_name' );

					// if add to cart from wishlist
					if ( ! $img.length ) {
						$img = $recentAdded.closest( '.wishlist_item' )
										   .find( '.wishlist_item_product_image img' );
					}

					// if add to cart from single product page
					if ( ! $img.length ) {
						$img = $recentAdded.closest( '.summary' )
										   .prev()
										   .find( '.woocommerce-main-image img' );
					}

					// reset state after 5 sec
					setTimeout( function() {
						$recentAdded.removeClass( 'added' ).removeClass( 'recent-added' );
						$recentAdded.next( '.added_to_cart' ).remove();
					}, 5000 );

					if ( typeof pName == 'undefined' || pName == '' ) {
						pName = $recentAdded.closest( '.summary' ).find( '.product_title' ).text().trim();
					}

					if ( typeof pName !== 'undefined' ) {

						config['message'] = (
												$img.length ? '<img src="' + $img.attr( 'src' ) + '"' + ' alt="' + pName + '" class="growl-thumb" />' : ''
											) + '<p class="growl-content">' + pName + ' ' + leartsConfigs.added_to_cart_notification_text + '&nbsp;<a href="' + leartsConfigs.wc_cart_url + '">' + leartsConfigs.view_cart_notification_text + '</a></p>';

					} else {
						config['message'] =
							leartsConfigs.added_to_cart_text + '&nbsp;<a href="' + leartsConfigs.wc_cart_url + '">' + leartsConfigs.view_cart_notification_text + '</a>';
					}

					$.growl.notice( config );
				} );
			};

			var addToWishlistNotification = function() {

				$body.on( 'added_to_wishlist', function() {

					$( '#yith-wcwl-popup-message' ).remove();

					config['message'] =
						'<p class="growl-content">' + leartsConfigs.added_to_wishlist_text + '&nbsp;<a href="' + leartsConfigs.wishlist_url + '">' + leartsConfigs.browse_wishlist_text + '</a></p>';

					$.growl.notice( config );
				} );
			};

			events();

			if ( leartsConfigs.shop_add_to_cart_notification_on ) {
				addToCartNotification();
			}

			if ( leartsConfigs.shop_wishlist_notification_on ) {
				addToWishlistNotification();
			}
		}
	}
)( jQuery );
