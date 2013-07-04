<?php

/*
 * Theme setup
 */

function fdt_scripts() {
	wp_enqueue_style('fdt-main', get_stylesheet_directory_uri() . '/css/main.css');
}
add_action('wp_enqueue_scripts', 'fdt_scripts');

function fdt_use_marker_extent() {
	return true;
}
add_filter('mappress_use_marker_extent', 'fdt_use_marker_extent');

require_once(STYLESHEETPATH . '/inc/acf-config.php'); // advanced custom fields setup
require_once(STYLESHEETPATH . '/inc/events.php'); // events feature
require_once(STYLESHEETPATH . '/inc/geolocator.php'); // geolocation feature (content connected to user city)

?>