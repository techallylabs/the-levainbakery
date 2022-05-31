<?php
namespace cmb2_tabs;

if ( is_admin() ) {
	// Run autoloader
	include __DIR__ . '/autoloader.php';

	// Connection css and js
	new inc\Assets();

	// Run global class
	new inc\CMB2_Tabs();
}
