(function ($) {
    "use strict";

    $('.isw_items').sortable({
        cursor: 'move',
        stop: function (event, ui) {

            var count = $('.isw_items > .isw_element').length;

            if (count > 0) {
                var i = 0;
                $('.isw_items > .isw_element').each(function () {
                    var $el = $(this);
                    var curr = $el.attr('data-id');

                    $el.find('[name]').each(function () {
                        var attr = $(this).attr('name');
                        $(this).attr('name', attr.replace('[' + curr + ']', '[' + i + ']'))
                    });

                    $el.attr('data-id', i);

                    i++;
                });

            }

        }
    });

    var ajax_loading = false;

    $(document).on('click', '.isw_add_new_btn', function () {

        if (ajax_loading === true) {
            return false;
        }

        ajax_loading = true;

        var $button = $(this);
        $button.addClass('loading').attr("disabled", true);

        var $curr_el = $(this).parent().next();
        var $curr = $curr_el.find('.isw_element').length;


        var curr_data = {
            action: 'insight_sw_get_fields'
        };

        $.post(isw.ajax, curr_data, function (response) {
            if (response) {

                response = response.replace(/\%%/g, $curr);

                var adv_ui = '<div class="isw_element" data-id="' + $curr + '">' +
                    '<div class="isw_element_heading">' +
                    '<a href="#" class="isw_attribute_title">Add New Attribute</a>' +
                    '<a href="#" class="isw_remove"><i class="dashicons dashicons-trash"></i></a>' +
                    '<a href="#" class="isw_reorder"><i class="dashicons dashicons-move"></i></a>' +
                    '<a href="#" class="isw_slidedown"><i class="dashicons dashicons-arrow-down-alt2"></i></a>' +
                    '<div class="isw_clear"></div></div><div class="isw_element_content">' + response + '</div></div>';

                var curr_append = $curr_el.append(adv_ui);

                curr_append.find('.isw_element[data-id="' + $curr + '"] .isw_slidedown').trigger('click');

                ajax_loading = false;

                $button.removeClass('loading').attr("disabled", false);

            } else {

                alert('Error!');
                ajax_loading = false;

            }
        });

        return false;

    });

    $(document).on('click', '.isw_slidedown', function () {
        var $el_content = $(this).closest('.isw_element').find('.isw_element_content');
        var $icon = $(this).find('i');

        if ($icon.hasClass('dashicons-arrow-down-alt2')) {
            $icon.removeClass('dashicons-arrow-down-alt2').addClass('dashicons-arrow-up-alt2');
            $el_content.slideDown();
        }
        else {
            $icon.removeClass('dashicons-arrow-up-alt2').addClass('dashicons-arrow-down-alt2');
            $el_content.slideUp();
        }

        return false;
    });

    $(document).on('click', '.isw_attribute_title', function () {
        $(this).parent().find('.isw_slidedown').trigger('click');

        return false;
    });

    $(document).on('click', '.isw_reorder', function () {
        return false;
    });

    $(document).on('click', '.isw_remove', function () {

        if (confirm("Do you want to remove this swatch ?")) {
            $(this).closest('.isw_element ').remove();

            var count = $('.isw_items > .isw_element').length;

            if (count > 0) {
                var i = 0;
                $('.isw_items > .isw_element').each(function () {
                    var curr_el = $(this);
                    var curr = curr_el.attr('data-id');

                    curr_el.find('[name]').each(function () {
                        var attr = $(this).attr('name');
                        $(this).attr('name', attr.replace('[' + curr + ']', '[' + i + ']'))
                    });

                    curr_el.attr('data-id', i);

                    i++;
                });

            }
        }
        return false;
    });

    $(document).on('change', '.isw_attr_select', function () {

        var $el = $(this).closest('.isw_element');
        var id = $el.attr('data-id');

        var taxonomy = $el.find('select[name^="isw_attr"] option:selected').attr('value');
        var isw_style = $el.find('select[name^="isw_style"] option:selected').attr('value');

        if (taxonomy == '') {
            alert('Please select attribute.');
            $el.find('.isw_terms').html('');
            return false;
        }

        if ($el.parent().find('select[name^="isw_attr"] option[value="' + taxonomy + '"]:selected').length > 1) {
            alert('You have already set this attribute style.');
            $el.find('.isw_terms').html('');
            $el.find('select[name^="isw_attr"] option:first').prop('selected', true);
            return false;
        }

        $el.block({
            message: null,
            overlayCSS: {
                background: '#fff',
                opacity: 0.6
            }
        });

        var curr_data = {
            action: 'insight_sw_get_terms',
            taxonomy: taxonomy,
            style: isw_style
        };

        $.post(isw.ajax, curr_data, function (response) {
            if (response) {
                response = response.replace(/\%%/g, id);
                $el.find('.isw_terms').html(response);

                $el.find('.isw_color').each(function () {
                    $(this).wpColorPicker({
                        defaultColor: true,
                        hide: true
                    });
                });

                $el.unblock();

            } else {
                alert('Error!');
            }
        });

    });

    $(document).on('click', '.isw_upload_media', function () {

        var frame;
        var el = $(this);
        var curr = el.parent().prev();

        if (frame) {
            frame.open();
            return;
        }

        frame = wp.media({
            title: el.data('choose'),
            button: {
                text: el.data('update'),
                close: false
            }
        });

        frame.on('select', function () {

            var attachment = frame.state().get('selection').first();
            frame.close();
            curr.find('input').val(attachment.attributes.url);

        });

        frame.open();

        return false;
    });

    $('#isw_manager .isw_color').each(function () {
        $(this).wpColorPicker({
            defaultColor: true,
            hide: true
        });
    });

})(jQuery);