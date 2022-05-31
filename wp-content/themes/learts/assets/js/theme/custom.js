// Video popup and gifplayer
(
	function ($) {
		"use strict";

		learts.customJS = function () {
			$('.button-video a').magnificPopup({
				type: 'iframe',
				mainClass: 'mfp-fade',
				removalDelay: 160,
				preloader: false,
				fixedContentPos: false
			});

			$('.video-lightbox-btn, .rs-button-video').magnificPopup({
				type: 'iframe',
				mainClass: 'mfp-fade',
				removalDelay: 160,
				preloader: false,
				fixedContentPos: false
			});

			$('.gif').gifplayer();

			$(".learts-image-gallery").lightGallery({
				selector: 'a',
				thumbnail: true,
				animateThumb: false,
				showThumbByDefault: false
			});

			$(".share-button").on('click', function () {
				var $parent = $(this).parents('.post-share');
				if ($parent.hasClass('active')) {
					$parent.removeClass('active');
				} else {
					$parent.addClass('active');
				}
			});

			$(".btn-pop-up").on('click', function () {
				var $parent = $('.rev_slider');
				var $tpRrightArrow = $parent.find('.tp-rightarrow');
				var $tpLeftArrow = $parent.find('.tp-leftarrow');

				$tpRrightArrow.on('click', function () {
					$parent.removeClass('active');
					$parent.removeClass('active-popup-1');
					$parent.removeClass('active-popup-2');
					$parent.removeClass('active-popup-3');
					$parent.removeClass('active-popup-4');
				});

				$tpLeftArrow.on('click', function () {
					$parent.removeClass('active');
					$parent.removeClass('active-popup-1');
					$parent.removeClass('active-popup-2');
					$parent.removeClass('active-popup-3');
					$parent.removeClass('active-popup-4');
				});

				if ($parent.hasClass('active')) {
					$parent.removeClass('active');
				} else {
					$parent.addClass('active');
				}
			});

			$(".title-vertical-menu").on('click', function () {
				var $verticalMenu = $('.learts-menu-vertical.display-dropdown');
				if ($verticalMenu.hasClass('active')) {
					$verticalMenu.removeClass('active');
				} else {
					$verticalMenu.addClass('active');
				}
			});

			$(".wc-proceed-to-checkout").find('.checkout-button').removeClass('alt');
			$(".wishlist_table").find('.ajax_add_to_cart').removeClass('alt');
			$(".wishlist_table").find('.ajax_add_to_cart').addClass('grey');

			$(".single-product").find('.yith-wcwl-add-button').removeClass('hint--left');
			$(".single-product").find('.yith-wcwl-add-button').addClass('hint--top');

			$(".single-product").find('.compare-btn').removeClass('hint--left');
			$(".single-product").find('.compare-btn').addClass('hint--top');

			$(".single-product").find('.yith-wcwl-wishlistexistsbrowse').removeClass('hint--left');
			$(".single-product").find('.yith-wcwl-wishlistexistsbrowse').addClass('hint--top');

			$(".single-product").find('.yith-wcwl-wishlistaddedbrowse').removeClass('hint--left');
			$(".single-product").find('.yith-wcwl-wishlistaddedbrowse').addClass('hint--top');

			$(".double-product").find('.product').removeClass('col-md-4 col-lg-3');
			$(".double-product").find('.product').addClass('col-md-6 col-lg-6');

			$('.related').find('.yith-wcwl-add-button').removeClass('hint--top');
			$('.related').find('.yith-wcwl-add-button').addClass('hint--left');

			$('.related').find('.yith-wcwl-wishlistaddedbrowse').removeClass('hint--top');
			$('.related').find('.yith-wcwl-wishlistaddedbrowse').addClass('hint--left');


			if ('1' === leartsConfigs.effect_snow_fall) {
				$('body').snowfall(
					{
						flakeCount: 50,
						flaklor: '#fff',
						flakeIndex: 999999,
						minSize: 10,
						maxSize: 40,
						minSpeed: 1,
						maxSpeed: 5,
						round: true,
						shadow: false,
						image: leartsConfigs.snow_image.url

					}
				);
			}
		};
	}
)(jQuery);
