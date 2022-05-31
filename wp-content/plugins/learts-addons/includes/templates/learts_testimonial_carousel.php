<?php
/**
 * Shortcode attributes
 *
 * @var $atts
 * @var $animation
 * @var $text_size
 * @var $text_color
 * @var $style_testimonial
 * @var $item_count
 * @var $items_to_show
 * @var $order
 * @var $category
 * @var $loop
 * @var $auto_play
 * @var $auto_play_speed
 * @var $nav_type
 * @var $el_class
 * @var $css
 * Shortcode class
 * @var $this WPBakeryShortCode_Learts_Testimonial_Carousel
 */
$atts = vc_map_get_attributes($this->getShortcode(), $atts);
extract($atts);

$el_class = $this->getExtraClass($el_class);

$animation_classes = $this->getCSSAnimation($animation);

$css_class = array(
    'tm-shortcode',
    'learts-testimonial-carousel',
    $el_class,
    $animation_classes,
    vc_shortcode_custom_css_class($css),
);

$css_class = apply_filters(VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG,
    implode(' ', $css_class),
    $this->settings['base'],
    $atts);

if ($animation !== '') {
    $css_class .= ' tm-animation ' . $animation . '';
}

// TESTIMONIAL QUERY SETUP.
$args = array(
    'orderby' => $order,
    'post_type' => 'testimonial',
    'post_status' => 'publish',
    'posts_per_page' => $item_count,
    'no_found_rows' => 1,
);

if ($category && $category != '0') {
    $args['tax_query'] = array(
        array(
            'taxonomy' => 'testimonial-category',
            'field' => 'slug',
            'terms' => $category,
        ),
    );
}

$testimonials = new WP_Query($args);


?>
<div class="<?php echo esc_attr(trim($css_class)); ?>"
     data-atts="<?php echo esc_attr(json_encode($atts)); ?>">

    <?php
    global $post;
    // TESTIMONIAL LOOP.
    while ($testimonials->have_posts()) : $testimonials->the_post();
        $testimonial_text = get_the_content();
        $testimonial_cite = get_post_meta($post->ID, 'learts_testimonial_cite', true);
        $testimonial_cite_subtext = get_post_meta($post->ID, 'learts_testimonial_cite_subtext', true);
        $testimonial_rating_star = get_post_meta($post->ID, 'learts_testimonial_rating_star', true);
        $testimonial_rating_title = get_post_meta($post->ID, 'learts_testimonial_rating_title', true);

        if ($style_testimonial == 'single') { ?>
            <div class="learts-testimonial-carousel__item single-testimonial">
                <div class="row">

                    <?php // Testimonial Image setup.
                    $testimonial_image = get_post_meta($post->ID,
                        'learts_testimonial_cite_image',
                        true);

                    if (!$testimonial_image) {
                        $testimonial_image_id = get_post_thumbnail_id();
                        $testimonial_image = wp_get_attachment_url($testimonial_image_id, 'full');
                    } ?>

                    <div class="testimonial-content col-xs-12">
                        <h3 class="learts-testimonial-carousel__text"><?php echo do_shortcode($testimonial_text) ?></h3>

                        <?php if ($testimonial_image) {
                            ?>
                            <div class="cite-image learts-testimonial-carousel__img"><img
                                        src="<?php echo esc_url($testimonial_image); ?>"
                                        alt="<?php echo esc_attr($testimonial_cite) ?>"/></div>
                        <?php } ?>

                        <div class="box-cite">
                            <div class="learts-testimonial-carousel__cite">
                                <?php echo esc_html($testimonial_cite); ?>
                            </div>
                            <?php if ($testimonial_cite_subtext != '') { ?>
                                <div
                                        class="learts-testimonial-carousel__sub-cite"><?php echo esc_html($testimonial_cite_subtext); ?></div>
                            <?php } ?>
                        </div>
                    </div>
                </div>
            </div>
        <?php }

        if ($style_testimonial == 'multiple') { ?>
            <div class="learts-testimonial-carousel__item multiple-testimonials">

                <div class="learts-testimonial-carousel__text"><?php echo do_shortcode($testimonial_text) ?></div>
                <?php
                $testimonial_image_id = get_post_meta($post->ID, 'learts_testimonial_cite_image_id', true);
                $testimonial_image = wp_get_attachment_image($testimonial_image_id,
                    'learts-testimonial-cite-image');

                if (!$testimonial_image) {
                    $testimonial_image_id = get_post_thumbnail_id();
                    $testimonial_image = wp_get_attachment_url($testimonial_image_id, 'full');
                }
                // Testimonial Image.
                if ($testimonial_image) {
                    ?>
                    <div class="cite-image learts-testimonial-carousel__img"><?php echo '' . $testimonial_image ?></div>
                <?php } ?>

                <div class="box-cite">
                    <div class="learts-testimonial-carousel__cite">
                        <?php echo esc_html($testimonial_cite); ?>
                    </div>
                    <?php if ($testimonial_cite_subtext != '') { ?>
                        <div
                                class="learts-testimonial-carousel__sub-cite"><?php echo esc_html($testimonial_cite_subtext); ?></div>
                    <?php } ?>
                </div>
            </div>
        <?php }

        if ($style_testimonial == 'multiple-star') { ?>
            <div class="learts-testimonial-carousel__item multiple-testimonials-with-star">

                <?php
                $testimonial_image_id = get_post_meta($post->ID, 'learts_testimonial_cite_image_id', true);
                $testimonial_image = wp_get_attachment_image($testimonial_image_id,
                    'full');

                if (!$testimonial_image) {
                    $testimonial_image_id = get_post_thumbnail_id();
                    $testimonial_image = wp_get_attachment_url($testimonial_image_id, 'full');
                }
                // Testimonial Image.
                if ($testimonial_image) {
                    ?>
                    <div class="cite-image learts-testimonial-carousel__img"><?php echo '' . $testimonial_image ?></div>
                <?php } ?>

                <div class="rating-wrap">
                                <?php Learts_VC::render_rating($testimonial_rating_star); ?>
                                <div class="learts-testimonial-carousel-rating-title">
                                    <?php echo esc_html($testimonial_rating_title); ?>
                                </div>
                            </div>

                <div class="learts-testimonial-carousel__text"><?php echo do_shortcode($testimonial_text) ?></div>

                <div class="box-cite">
                    <div class="learts-testimonial-carousel__cite">
                        <?php echo esc_html($testimonial_cite); ?>
                    </div>
                    <?php if ($testimonial_cite_subtext != '') { ?>
                        <div
                                class="learts-testimonial-carousel__sub-cite"><?php echo esc_html($testimonial_cite_subtext); ?></div>
                    <?php } ?>
                </div>
            </div>
        <?php } ?>               

        <?php
        if ($style_testimonial == 'modern-slide') { ?>
            <?php
            $testimonial_image_id = get_post_meta($post->ID, 'learts_testimonial_cite_image_id', true);
            $testimonial_image = wp_get_attachment_image_url($testimonial_image_id,
                'learts-testimonial-cite-image');

            if (!$testimonial_image) {
                $testimonial_image_id = get_post_thumbnail_id();
                $testimonial_image = wp_get_attachment_url($testimonial_image_id, 'full');
            }
            ?>
            <div class="learts-testimonial-carousel__item modern-slide-testimonials"
                <?php if ($testimonial_image) { ?>
                    data-image="<?php echo '' . $testimonial_image ?>"
                <?php } ?>
            >
                <div class="testimonial-wrap">
                    <div class="wrapper">
                        <div class="content-wrap">
                            <div class="rating-wrap">
                                <?php Learts_VC::render_rating($testimonial_rating_star); ?>
                                <div class="learts-testimonial-carousel-rating-title">
                                    <?php echo esc_html($testimonial_rating_title); ?>
                                </div>
                            </div>
                            <div class="learts-modern-slide__text"><?php echo do_shortcode($testimonial_text) ?></div>


                            <div class="box-cite">
                                <div class="learts-modern-slide__cite">
                                    <?php echo esc_html($testimonial_cite); ?>
                                </div>
                                <div class="devide">|</div>
                                <?php if ($testimonial_cite_subtext != '') { ?>
                                    <div
                                            class="learts-modern-slide__sub-cite"><?php echo esc_html($testimonial_cite_subtext); ?></div>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="test-icon">
                    <i class="fas fa-caret-down"></i>
                </div>
            </div>
        <?php }

        ?>
    <?php
    endwhile; ?>
    <?php

    wp_reset_postdata();
    ?>
</div>
