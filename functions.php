<?php

/*
 * Theme setup
 */

function fdt_setup() {
	load_child_theme_textdomain('feiradetrocas', get_stylesheet_directory() . '/languages');
}
add_action('after_setup_theme', 'fdt_setup');

function fdt_scripts() {
	wp_enqueue_style('fdt-main', get_stylesheet_directory_uri() . '/css/main.css');
	wp_enqueue_script('fdt-main', get_stylesheet_directory_uri() . '/js/feiradetrocas.js');
	wp_enqueue_script('shadowbox-js', get_stylesheet_directory_uri() . '/inc/photos/shadowbox/shadowbox.js', array('jquery'));
	wp_enqueue_style('shadowbox-js', get_stylesheet_directory_uri() . '/inc/photos/shadowbox/shadowbox.css');
}
add_action('wp_enqueue_scripts', 'fdt_scripts');

function fdt_use_marker_extent() {
	return true;
}
add_filter('mappress_use_marker_extent', 'fdt_use_marker_extent');

include_once(STYLESHEETPATH . '/inc/featured.php');
function fdt_map() {
	if(is_front_page())
		fdt_featured_posts();
}
add_action('mappress_map', 'fdt_map');

require_once(STYLESHEETPATH . '/inc/acf-config.php'); // advanced custom fields setup
require_once(STYLESHEETPATH . '/inc/events.php'); // events feature
require_once(STYLESHEETPATH . '/inc/photos/photos.php'); // photos feature
require_once(STYLESHEETPATH . '/inc/geolocator.php'); // geolocation feature (content connected to user city)

?>