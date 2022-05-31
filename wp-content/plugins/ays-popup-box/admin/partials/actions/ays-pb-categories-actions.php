<?php
$action = (isset($_GET['action'])) ? sanitize_text_field( $_GET['action'] ) : '';
$heading = '';
$loader_iamge = '';
$id = ( isset( $_GET['popup_category'] ) ) ? absint( intval( $_GET['popup_category'] ) ) : null;
$popup_category = array(
    'id'            => '',
    'title'         => '',
    'description'   => '',
    'published'     => ''
);
switch( $action ) {
    case 'add':
        $heading = __('Add new category', $this->plugin_name);
        $loader_iamge = "<span class='display_none'><img src=".AYS_PB_ADMIN_URL."/images/loaders/loading.gif></span>";
        break;
    case 'edit':
        $heading = __('Edit category', $this->plugin_name);
        $loader_iamge = "<span class='display_none'><img src=".AYS_PB_ADMIN_URL."/images/loaders/loading.gif></span>";
        $popup_category = $this->popup_categories_obj->get_popup_category( $id );
        break;
}
if( isset( $_POST['ays_submit'] ) ) {
    $_POST['id'] = $id;
    $result = $this->popup_categories_obj->add_edit_popup_category();
}
if(isset($_POST['ays_apply'])){
    $_POST["id"] = $id;
    $_POST['ays_change_type'] = 'apply';
    $this->popup_categories_obj->add_edit_popup_category();
}

// General Settings | options
$gen_options = ($this->settings_obj->ays_get_setting('options') === false) ? array() : json_decode( stripcslashes($this->settings_obj->ays_get_setting('options') ), true);

// WP Editor height
$pb_wp_editor_height = (isset($gen_options['pb_wp_editor_height']) && $gen_options['pb_wp_editor_height'] != '') ? absint( sanitize_text_field($gen_options['pb_wp_editor_height']) ) : 150 ;


?>
<div class="wrap">
    <div class="container-fluid">
        <h1><?php echo $heading; ?></h1>
        <hr/>
        <form class="ays-pb-category-form" id="ays-pb-category-form" method="post">
            <input type="hidden" class="pb_wp_editor_height" value="<?php echo $pb_wp_editor_height; ?>">
            <div class="form-group row">
                <div class="col-sm-2">
                    <label for='ays-title'>
                        <?php echo __('Title', $this->plugin_name); ?>
                        <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Title of the popup category',$this->plugin_name)?>">
                            <i class="ays_fa ays_fa-info-circle"></i>
                        </a>
                    </label>
                </div>
                <div class="col-sm-10">
                    <input class='ays-text-input' id='ays-title' name='ays_title' required type='text' value='<?php echo (stripslashes(esc_attr($popup_category['title']))); ?>'>
                </div>
            </div>

            <hr/>
            <div class='ays-field'>
                <label for='ays-description'>
                    <?php echo __('Description', $this->plugin_name); ?>
                    <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Provide more information about the popup category',$this->plugin_name)?>">
                        <i class="ays_fa ays_fa-info-circle"></i>
                    </a>
                </label>
                <?php
                $content = stripslashes( $popup_category['description'] );
                $editor_id = 'ays-description';
                $settings = array('editor_height'=>$pb_wp_editor_height,'textarea_name'=>'ays_description','editor_class'=>'ays-textarea');
                wp_editor($content, $editor_id, $settings);
                ?>
            </div>

            <hr/>
            <div class="form-group row">
                <div class="col-sm-2">
                    <label>
                        <?php echo __('Category status', $this->plugin_name); ?>
                        <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Choose whether the popup category is active or not. If you choose Unpublished option, the popup category won’t be shown anywhere on your website',$this->plugin_name)?>">
                            <i class="ays_fa ays_fa-info-circle"></i>
                        </a>
                    </label>
                </div>

                <div class="col-sm-3">
                    <div class="form-check form-check-inline">
                        <input type="radio" id="ays-publish" name="ays_publish" value="1" <?php echo ( $popup_category["published"] == '' ) ? "checked" : ""; ?> <?php echo ( $popup_category['published'] == '1') ? 'checked' : ''; ?> />
                        <label class="form-check-label" for="ays-publish"> <?php echo __('Published', $this->plugin_name); ?> </label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input type="radio" id="ays-unpublish" name="ays_publish" value="0" <?php echo ( $popup_category['published']  == '0' ) ? 'checked' : ''; ?> />
                        <label class="form-check-label" for="ays-unpublish"> <?php echo __('Unpublished', $this->plugin_name); ?> </label>
                    </div>
                </div>
            </div>

            <hr/>
            <?php
            wp_nonce_field('popup_category_action', 'popup_category_action');
            $other_attributes = array( 'id' => 'ays-cat-button-apply' );
            $other_attributes_save = array( 'id' => 'ays-cat-button-apply' );
            submit_button( __( 'Save and close', $this->plugin_name ), 'primary', 'ays_submit', false, $other_attributes );
            submit_button( __( 'Save', $this->plugin_name), '', 'ays_apply', false, $other_attributes_save);
            echo $loader_iamge;
            ?>
        </form>
    </div>
</div>