'use strict';
var leartsAddons;
(function ($) {
    leartsAddons = (function () {
        return {

            init: function () {
                this.testimonialCarousel();
            },

            testimonialCarousel: function () {
                /**
                 * Testimonial Shortcode
                 */
                $('.learts-testimonial-carousel').each(function () {

                    var $this = $(this),
                        atts = JSON.parse($this.attr('data-atts'));

                    if (atts == null) {
                        return;
                    }

                    if (typeof atts.auto_play_speed === 'undefined' || isNaN(atts.auto_play_speed)) {
                        atts.auto_play_speed = 5;
                    }

                    var configs = {
                        accessibility: false,
                        infinite: (atts.loop == 'yes'),
                        autoplay: (atts.auto_play == 'yes'),
                        // pauseOnHover: false,
                        // pauseOnFocus: false,
                        autoplaySpeed: parseInt(atts.auto_play_speed) * 1000,
                        slidesToShow: parseInt(atts.items_to_show),
                        slidesToScroll: 1,
                        speed: 1000,
                        responsive: [{
                            breakpoint: 768,
                            settings: {
                                slidesToShow: 1,
                            },
                        },],
                    };

                    if ( leartsConfigs.isRTL ) {
                        configs.rtl = true;
                    }

                    if ('modern-slide' === atts.style_testimonial) {
                        configs.dots = true;
                        configs.customPaging = function (slider, i) {
                            // this example would render "tabs" with titles
                            return '<img class="tab" src="' + $(slider.$slides[i]).data('image') + '" >';
                        };
                        configs.dotsClass = 'slick-dots modern-slide-dots';
                    }

                    if (!atts.nav_type) {
                        configs.arrows = false;
                        configs.dots = false;
                    } else {
                        if (atts.nav_type == 'dots') {
                            configs.arrows = false;
                            configs.dots = true;
                        }
                        if (atts.nav_type == 'both') {
                            configs.arrows = true;
                            configs.dots = true;
                        }
                    }

                    $this.slick(configs);
                });
            }
        };
    }());
})(jQuery);

jQuery(document).ready(function ($) {
    leartsAddons.init();
});
