// Back To Top
(
	function( $ ) {
		"use strict";

		var $window = $( window );

		learts.backToTop = function() {

			var $backToTop = $( '.back-to-top' );

			$window.scroll( function() {

				if ( $window.scrollTop() > 100 ) {
					$backToTop.addClass( 'show' );
				} else {
					$backToTop.removeClass( 'show' );
				}
			} );

			$backToTop.on( 'click', function( e ) {
				e.preventDefault();
				$( 'html, body' ).animate( { scrollTop: 0 }, 600 );
			} );
		}


		var $scroll = $( '.button-scroll' );

		$scroll.on( 'click', function() {
			if (this.hash !== "") {
				event.preventDefault();

				var hash = this.hash;

				$('html, body').animate({
					scrollTop: $(hash).offset().top
				}, 800, function(){
					window.location.hash = hash;
				});
			}
		} );
	}
)( jQuery );
