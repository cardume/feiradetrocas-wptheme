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
	wp_enqueue_style('fdt-custom', get_stylesheet_directory_uri() . '/style.css');
	wp_enqueue_script('fdt-main', get_stylesheet_directory_uri() . '/js/feiradetrocas.js');
	wp_enqueue_script('shadowbox-js', get_stylesheet_directory_uri() . '/inc/photos/shadowbox/shadowbox.js', array('jquery'));
	wp_enqueue_style('shadowbox-js', get_stylesheet_directory_uri() . '/inc/photos/shadowbox/shadowbox.css');
}
add_action('wp_enqueue_scripts', 'fdt_scripts');

function fdt_use_marker_extent() {
	if(is_front_page())
		return false;
	
	return true;
}
add_filter('jeo_use_marker_extent', 'fdt_use_marker_extent');

function fdt_use_transient() {
	return false;
}
add_filter('jeo_markers_enable_transient', 'fdt_use_transient');

function fdt_use_browser_caching() {
	return false;
}
add_filter('jeo_markers_enable_browser_caching', 'fdt_use_browser_caching');

include_once(STYLESHEETPATH . '/inc/featured.php');
function fdt_map() {
	if(is_front_page())
		fdt_featured_posts();
}
add_action('jeo_map', 'fdt_map');

// geo queries
function fdt_geo_query($query) {
	global $wp_the_query;
	if(!is_admin() && $query === $wp_the_query && !$query->is_single())
		$query->set('geo_query', 1);

	return $query;
}
add_action('pre_get_posts', 'fdt_geo_query');

require_once(STYLESHEETPATH . '/inc/acf-config.php'); // advanced custom fields setup
require_once(STYLESHEETPATH . '/inc/events.php'); // events feature
require_once(STYLESHEETPATH . '/inc/photos/photos.php'); // photos feature
require_once(STYLESHEETPATH . '/inc/geolocator.php'); // geolocation feature (content connected to user city)

?>