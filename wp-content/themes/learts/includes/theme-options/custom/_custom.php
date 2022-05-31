<?php

Redux::setSection( learts_Redux::$opt_name, array(
	'title' => esc_html__( 'Custom Code', 'learts' ),
	'id'    => 'panel_custom',
	'icon'  => 'fa fa-code'
) );

require_once LEARTS_OPTIONS_DIR . DS . 'custom' . DS . 'custom_css.php';
require_once LEARTS_OPTIONS_DIR . DS . 'custom' . DS . 'custom_js.php';

