<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       http://ays-pro.com/
 * @since      1.0.0
 *
 * @package    Ays_Pb
 * @subpackage Ays_Pb/admin/partials
 */
$action = ( isset($_GET['action']) ) ? $_GET['action'] : '';
$id     = ( isset($_GET['popupbox']) ) ? $_GET['popupbox'] : null;

if($action == 'duplicate'){
$this->popupbox_obj->duplicate_popupbox($id);
}
if($action == 'unpublish' || $action == 'publish'){
$this->popupbox_obj->publish_unpublish_popupbox($id,$action);
}
?>
<!-- This file should primarily consist of HTML with a little bit of PHP. -->
<div class="wrap">
    <h1 class="wp-heading-inline">
        <?php
        echo esc_html(get_admin_page_title());
        echo sprintf( '<a href="?page=%s&action=%s" class="page-title-action">'. __( "Add New" ) .'</a>', esc_attr( $_REQUEST['page'] ), 'add');
        ?>
    </h1>

    <div id="poststuff">
        <div id="post-body" class="metabox-holder">
            <div id="post-body-content">
                <div class="meta-box-sortables ui-sortable">
                    <?php
                        $this->popupbox_obj->views();
                    ?>
                    <form method="post">
                        <?php
                        $this->popupbox_obj->prepare_items();
                        $search = __( "Search", $this->plugin_name );
                        $this->popupbox_obj->search_box($search, $this->plugin_name);
                        $this->popupbox_obj->display();
                        ?>
                    </form>
                </div>
            </div>
        </div>
        <br class="clear">
    </div>
</div>