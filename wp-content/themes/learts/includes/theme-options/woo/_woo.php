<?php

Redux::setSection( learts_Redux::$opt_name, array(
	'title' => esc_html__( 'Shop', 'learts' ),
	'id'    => 'panel_woo',
	'icon'  => 'fa fa-shopping-basket'
) );

require_once LEARTS_OPTIONS_DIR . DS . 'woo' . DS . 'woo_general.php';
require_once LEARTS_OPTIONS_DIR . DS . 'woo' . DS . 'woo_shop.php';
require_once LEARTS_OPTIONS_DIR . DS . 'woo' . DS . 'woo_shop_toolbar.php';
require_once LEARTS_OPTIONS_DIR . DS . 'woo' . DS . 'woo_product.php';
