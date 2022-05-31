<?php

Redux::setSection( learts_Redux::$opt_name, array(
	'title' => esc_html__( 'Blog', 'learts' ),
	'id'    => 'panel_blog',
	'icon'  => 'fa fa-pencil'
) );

require_once LEARTS_OPTIONS_DIR . DS . 'blog' . DS . 'blog_general.php';
require_once LEARTS_OPTIONS_DIR . DS . 'blog' . DS . 'blog_archive.php';
require_once LEARTS_OPTIONS_DIR . DS . 'blog' . DS . 'blog_single.php';
