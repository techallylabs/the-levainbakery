<?php
    $pb_page_url = sprintf('?page=%s', 'ays-pb');
    $add_new_url = sprintf('?page=%s&action=%s', 'ays-pb', 'add');
?>

<div class="wrap">
     <div class="ays-pb-heart-beat-main-heading ays-pb-heart-beat-main-heading-container">
        <h1 class="ays-popup-box-wrapper ays_heart_beat">
            <?php echo __(esc_html(get_admin_page_title()),$this->plugin_name); ?> <i class="ays_fa ays_fa_heart animated"></i>
        </h1>
    </div>
    <div class="ays-pb-faq-main">
        <h2>
            <?php echo __("How to create a simple popup in 4 steps with the", $this->plugin_name ) .
            ' <strong>'. __("Popup Box", $this->plugin_name ) .'</strong> '.
            __("plugin.", $this->plugin_name ); ?>
            
        </h2>
        <fieldset>
            <div class="ays-pb-ol-container">
                <ol>
                    <li>
                        <?php echo __( "Go to the", $this->plugin_name ) . ' <a href="'. $pb_page_url .'" target="_blank">'. __( "Popups" , $this->plugin_name ) .'</a> ' .  __( "page and click on the ", $this->plugin_name ) . ' <a href="'. $add_new_url .'" target="_blank">'. __( "Add New" , $this->plugin_name ) .'</a> ' .  __( "button", $this->plugin_name ); ?>,
                    </li>
                    <li>
                        <?php echo __( "Select the popup type.", $this->plugin_name ); ?>
                        <ul>
                            <li><?php echo '<strong>' . __( "Shortcode", $this->plugin_name ) .'</strong> '; ?></li>
                            <li><?php echo '<strong>' . __( "Custom Content", $this->plugin_name ) .'</strong> '; ?></li>
                        </ul>
                    </li>
                    <li>
                        <?php echo __( "Choose when to show the popup with the", $this->plugin_name ) . ' <strong>'. __( "Popup trigger" , $this->plugin_name ) .'</strong> ' .  __( "option․", $this->plugin_name ); ?> 
                        <ul>
                            <li><?php echo '<strong>' . __( "Օn page load:", $this->plugin_name ) .'</strong> '.  __( "Choose to show the popup as soon as the page is loaded.", $this->plugin_name ) ; ?></li>
                            <li><?php echo '<strong>' . __( "On click:", $this->plugin_name ) .'</strong> '.  __( "Choose to show the popup as soon as the user clicks on the assigned CSS element(s). You can assign CSS elements with the", $this->plugin_name ) . ' <strong>'. __( "CSS selector(s) for trigger click" , $this->plugin_name ) .'</strong> ' .  __( "option․", $this->plugin_name ); ?></li>
                            <li><?php echo '<strong>' . __( "Shortcode:", $this->plugin_name ) .'</strong> '.  __( "Copy the popup shortcode", $this->plugin_name ) . ' <em>'. __( " ([ays_pb id=”Your_popup_ID” w=400 h=500])" , $this->plugin_name ) .'</em> ' .  __( "and paste it into any post or page.", $this->plugin_name ); ?></li>
                        </ul>
                    </li>
                    <li>
                         <?php echo __( "In the end, click on the", $this->plugin_name ) . ' <strong>'. __( "Save Changes" , $this->plugin_name ) .'</strong> ' .  __( "button.", $this->plugin_name ); ?> 
                    </li>
                </ol>
            </div>
            <div class="ays-pb-p-container">
                <p><?php echo __("That’s it! Your popup is ready to be displayed!" , $this->plugin_name); ?></p>
            </div>
        </fieldset>
    </div>  
    <br>

    <div class="ays-pb-community-wrap">
        <div class="ays-pb-community-title">
            <h4><?php echo __( "Community", $this->plugin_name ); ?></h4>
        </div>
        <div class="ays-pb-community-container">
            <div class="ays-pb-community-item">
                <a href="https://www.youtube.com/channel/UC-1vioc90xaKjE7stq30wmA" target="_blank" class="ays-pb-community-item-cover" style="color: #ff0000;background-color: rgba(253, 38, 38, 0.1);">
                    <img class="ays-pb-community-item-img" src="<?php echo AYS_PB_ADMIN_URL; ?>/images/youtube.png">
                </a>
                <h3 class="ays-pb-community-item-title"><?php echo __( "YouTube community", $this->plugin_name ); ?></h3>
                <p class="ays-pb-community-item-desc"></p>
                <div class="ays-pb-community-item-footer">
                    <a href="https://www.youtube.com/channel/UC-1vioc90xaKjE7stq30wmA" target="_blank" class="button"><?php echo __( "Subscribe", $this->plugin_name ); ?></a>
                </div>
            </div>
            <div class="ays-pb-community-item">
                <a href="https://wordpress.org/support/plugin/ays-popup-box/" target="_blank" class="ays-pb-community-item-cover" style="color: #0073aa;background-color: rgb(220, 220, 220);">
                    <img class="ays-pb-community-item-img" src="<?php echo AYS_PB_ADMIN_URL; ?>/images/wordpress.png">
                </a>
                <h3 class="ays-pb-community-item-title"><?php echo __( "Free support", $this->plugin_name ); ?></h3>
                <p class="ays-pb-community-item-desc"></p>
                <div class="ays-pb-community-item-footer">
                    <a href="https://wordpress.org/support/plugin/ays-popup-box/" target="_blank" class="button"><?php echo __( "Join", $this->plugin_name ); ?></a>
                </div>
            </div>
            <div class="ays-pb-community-item">
                <a href="https://ays-pro.com/contact" target="_blank" class="ays-pb-community-item-cover" style="color: #ff0000;background-color: rgba(0, 115, 170, 0.22);">
                    <img class="ays-pb-community-item-img" src="<?php echo AYS_PB_ADMIN_URL; ?>/images/logo_final.png">
                </a>
                <h3 class="ays-pb-community-item-title"><?php echo __( "Premium support", $this->plugin_name ); ?></h3>
                <p class="ays-pb-community-item-desc"></p>
                <div class="ays-pb-community-item-footer">
                    <a href="https://ays-pro.com/contact" target="_blank" class="button"><?php echo __( "Contact", $this->plugin_name ); ?></a>
                </div>
            </div>
        </div>
    </div>
</div>

