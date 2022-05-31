<?php

Redux::setSection( learts_Redux::$opt_name, array(
	'title' => esc_html__( 'Navigation', 'learts' ),
	'id'    => 'panel_nav',
	'icon'  => 'fa fa-bars',
) );

require_once LEARTS_OPTIONS_DIR . DS . 'menu' . DS . 'site_menu.php';
require_once LEARTS_OPTIONS_DIR . DS . 'menu' . DS . 'mobile_menu.php';
