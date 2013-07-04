<?php

/* 
 * ACF
 */

function fdt_acf_dir() {
	return get_stylesheet_directory_uri() . '/inc/acf/';
}
add_filter('acf/helpers/get_dir', 'fdt_acf_dir');

function fdt_acf_register_fields_dir($dir, $addon = false) {

	if($addon == 'date_time_picker')
		$dir = get_stylesheet_directory_uri() . '/inc/add-ons/acf-field-date-time-picker/';

	return $dir;
}
add_filter('acf/helpers/get_dir', 'fdt_acf_register_fields_dir', 11, 2);

function fdt_acf_register_fields() {
	include_once(STYLESHEETPATH . '/inc/add-ons/acf-field-date-time-picker/date_time_picker-v4.php');
}
add_action('acf/register_fields', 'fdt_acf_register_fields');

define('ACF_LITE' , false);
include_once(STYLESHEETPATH . '/inc/acf/acf.php');

?>