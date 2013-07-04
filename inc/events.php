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

	function query_vars() {
		global $wp;
		$wp->add_query_var('fdt_next_events');
		$wp->add_query_var('fdt_previous_events');
	}

	function pre_get_posts($query) {
		if(is_front_page()) {
			$query->set('post_type', 'fdt_event');
			$query->set('fdt_next_events', 1);
		}

		/*
		 * Next events query
		 */

		if($query->get('fdt_next_events')) {

			$query->set('post_type', 'fdt_event');

			$next_events_arg = array(
				'key' => '_fdt_event_date',
				'value' => time(),
				'compare' => '>=',
				'type' => 'CHAR'
			);

			if($query->get('meta_query')) {
				$query->set('meta_query', array_merge_recursive($query->get('meta_query'), array($next_events_arg)));
			} else {
				$query->set('meta_query', array($next_events_arg));
			}

			$query->set('orderby', 'meta_value');
			$query->set('meta_key', '_fdt_event_date');
			$query->set('order', 'ASC');

		}

		return $query;
	}

	function is_event_query($query = false) {
		global $wp_query;
		$query = $query ? $query : $wp_query;

		return ($query->get('post_type') == 'fdt_event' || $query->get('post_type') == array('fdt_event'));
	}

}

$fdt_events = new FdT_Events();