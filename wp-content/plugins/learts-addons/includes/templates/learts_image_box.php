<?php
/**
 * Shortcode attributes
 *
 * @var $atts
 * @var $animation
 * @var $image_box_align
 * @var $v_align
 * @var $title
 * @var $title_font_size
 * @var $title_font_color
 * @var $content
 * @var $content_font_size
 * @var $content_font_color
 * @var $link
 * @var $use_link_title
 * @var $link_color
 * @var $use_text
 * @var $text
 * @var $el_class
 * @var $type
 * @var $with_bg
 * @var $bg_shape
 * @var $css
 * @var $css_id
 * @var $image_display
 * @var $frames
 * Shortcode class
 * @var $this WPBakeryShortCode_Learts_Image_Box
 */

$atts = vc_map_get_attributes($this->getShortcode(), $atts);
extract($atts);

$el_class = $this->getExtraClass($el_class);

$animation_classes = $this->getCSSAnimation($animation);

$css_class = array(
    'tm-shortcode',
    'learts-image-box',
    $animation_classes,
    $el_class,
    vc_shortcode_custom_css_class($css),
);

$css_class = apply_filters(VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG,
    implode(' ', $css_class),
    $this->settings['base'],
    $atts);
if ($animation !== '') {
    $css_class .= ' tm-animation ' . $animation . '';
}

// Enqueue needed icon font.


if (!empty($link) && "||" !== $link && "|||" !== $link) {
    $link = vc_build_link($link);
    $link_text = '<a href="' . esc_attr($link['url']) . '"' . ($link['target'] ? ' target="' . esc_attr($link['target']) . '"' : '') . ($link['title'] ? ' title="' . esc_attr($link['title']) . '"' : '') . 'rel="' . $link['rel'] . '">' . ($link['title'] ? $link['title'] : '') . '</a>';
}

$css_id = uniqid('tm-icon-box-');
$shortcode_css = $this->shortcode_css($css_id);

if (empty($title) && empty($content)) {
    $css_class .= ' only-icon';
}
$image_display_src = wp_get_attachment_image_src($image_display, 'full');

?>
<div class="<?php echo esc_attr(trim($css_class)); ?>" id="<?php echo esc_attr($css_id); ?>">

    <?php if ('left' == $image_box_align) { ?>

    <div class="image-box-wrap align-left drawing-effect-wrapper">
        <div class="drawing-effect-inner">

        <?php if ('yes' == $use_link_title && !empty($link) && "||" !== $link && "|||" !== $link) { ?>
        <a href="<?php echo esc_attr($link['url']); ?>" target="<?php echo esc_html($link['target']); ?>"
           rel="<?php echo esc_attr($link['rel']); ?>"
           title="<?php echo esc_html($title); ?>">
            <?php } ?>
            <?php if ('yes' == $use_text) { ?>
                <span><?php echo '' . $text; ?></span>
            <?php } else { ?>

            <?php } ?>
            <?php if ('yes' == $use_link_title && !empty($link) && "||" !== $link && "|||" !== $link) { ?>

            <?php } ?>

            <?php } ?>


            <?php if ('center' == $image_box_align) { ?>

            <div class="image-box-wrap align-center drawing-effect-wrapper">
                <div class="drawing-effect-inner">
                <?php if ('yes' == $use_link_title && !empty($link) && "||" !== $link && "|||" !== $link) { ?>
                <a href="<?php echo esc_attr($link['url']); ?>" target="<?php echo esc_html($link['target']); ?>"
                   rel="<?php echo esc_attr($link['rel']); ?>"
                   title="<?php echo esc_html($title); ?>">
                    <?php } ?>
                    <?php if ('yes' == $use_text) { ?>
                        <span><?php echo '' . $text; ?></span>
                    <?php } else { ?>

                    <?php } ?>
                    <?php if ('yes' == $use_link_title && !empty($link) && "||" !== $link && "|||" !== $link) { ?>

                    <?php } ?>

                    <?php } ?>


                    <?php if ('right' == $image_box_align) { ?>

                    <div class="image-box-wrap align-right drawing-effect-wrapper">
                        <div class="drawing-effect-inner">
                        <?php if ('yes' == $use_link_title && !empty($link) && "||" !== $link && "|||" !== $link) { ?>
                        <a href="<?php echo esc_attr($link['url']); ?>"
                           target="<?php echo esc_html($link['target']); ?>"
                           rel="<?php echo esc_attr($link['rel']); ?>"
                           title="<?php echo esc_html($title); ?>">
                            <?php } ?>
                            <?php if ('yes' == $use_text) { ?>
                                <span><?php echo '' . $text; ?></span>
                            <?php } else { ?>

                            <?php } ?>
                            <?php if ('yes' == $use_link_title && !empty($link) && "||" !== $link && "|||" !== $link) { ?>

                            <?php } ?>

                            <?php } ?>


                            <?php if ($title || $content) { ?>
                            <div class="tm-image-box__content">
                                <div class="image-wrapper">
                                    <img src="<?php echo esc_attr($image_display_src[0]); ?>" alt="">
                                    <?php if ($title) { ?>
                                        <div class="title">
                                            <?php if ('yes' == $use_link_title && !empty($link) && "||" !== $link && "|||" !== $link) { ?>
                                            <a href="<?php echo esc_attr($link['url']); ?>"
                                               target="<?php echo esc_html($link['target']); ?>"
                                               rel="<?php echo esc_attr($link['rel']); ?>"
                                               title="<?php echo esc_attr($title); ?>">
                                                <?php
                                                $title = ($link['title'] ? $link['title'] : $title);
                                                } ?>
                                                <?php echo '' . $title; ?>
                                                <?php if ('yes' == $use_link_title && !empty($link) && "||" !== $link && "|||" !== $link) { ?>
                                            </a>
                                        <?php } ?>
                                        </div>
                                    <?php } ?>
                                    <?php if ($content) { ?>
                                        <div class="description"><?php echo do_shortcode($content); ?></div>
                                    <?php } ?>

                                </div>
                                <?php } ?>


                            </div>
                    </div>
            </div>
            </div>


