<?php

Redux::setSection( learts_Redux::$opt_name, array(
	'title'            => esc_html__( 'Style', 'learts' ),
	'id'               => 'panel_style',
	'customizer_width' => '400px',
	'icon'             => 'fa fa-paint-brush',
) );

require_once LEARTS_OPTIONS_DIR . DS . 'style' . DS . 'style_typo.php';
require_once LEARTS_OPTIONS_DIR . DS . 'style' . DS . 'style_colors.php';
