<?php

/*
 * Feira de Trocas
 * Collaborative events calendar
 */

class FdT_Events {

	function __construct() {

		add_action('mappress_init', array($this, 'setup'));

	}

	function setup() {
		$this->register_post_type();
		add_filter('pre_get_posts', array($this, 'pre_get_posts'), 1);
	}

	function register_post_type() {

		$labels = array( 
			'name' => __('Events', 'feiradetrocas'),
			'singular_name' => __('Event', 'feiradetrocas'),
			'add_new' => __('Add event', 'feiradetrocas'),
			'add_new_item' => __('Add new event', 'feiradetrocas'),
			'edit_item' => __('Edit event', 'feiradetrocas'),
			'new_item' => __('New event', 'feiradetrocas'),
			'view_item' => __('View event', 'feiradetrocas'),
			'search_items' => __('Search events', 'feiradetrocas'),
			'not_found' => __('No event found', 'feiradetrocas'),
			'not_found_in_trash' => __('No event found in the trash', 'feiradetrocas'),
			'menu_name' => __('Events', 'feiradetrocas')
		);

		$args = array( 
			'labels' => $labels,
			'hierarchical' => false,
			'description' => __('Events', 'feiradetrocas'),
			'supports' => array('title', 'editor', 'author', 'excerpt', 'thumbnail'),
			'public' => true,
			'show_ui' => true,
			'show_in_menu' => true,
			'has_archive' => true,
			'rewrite' => array('slug' => 'events', 'with_front' => false),
			'menu_position' => 5
		);

		register_post_type('fdt_event', $args);

	}

	function pre_get_posts($query) {
		if(is_front_page())
			$query->set('post_type', 'fdt_event');

		return $query;
	}

}

$fdt_events = new FdT_Events();