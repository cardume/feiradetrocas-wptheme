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
		$this->acf_fields();
		add_filter('pre_get_posts', array($this, 'pre_get_posts'), 1);
		add_shortcode('fdt_new_event', array($this, 'new_event_shortcode'));
		add_action('mappress_geocode_scripts', array($this, 'geocode_scripts'));
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

	function acf_fields() {

		if(function_exists("register_field_group")) {
			/* 
			 * Date and time
			 */
			register_field_group(array (
				'id' => 'acf_event-date-and-time',
				'title' => __('Event date and time', 'feiradetrocas'),
				'fields' => array (
					array (
						'default_value' => '',
						'label' => __('Date and time', 'feiradetrocas'),
						'time_format' => 'HH:mm',
						'show_date' => 'true',
						'date_format' => 'yy-mm-dd',
						'show_week_number' => 'false',
						'picker' => 'slider',
						'save_as_timestamp' => 'true',
						'key' => 'field_51d488053e34b',
						'name' => '_fdt_event_date',
						'type' => 'date_time_picker',
						'required' => 1,
					),
				),
				'location' => array (
					array (
						array (
							'param' => 'post_type',
							'operator' => '==',
							'value' => 'fdt_event',
							'order_no' => 0,
							'group_no' => 0,
						),
					),
				),
				'options' => array (
					'position' => 'side',
					'layout' => 'default',
					'hide_on_screen' => array (
					),
				),
				'menu_order' => 0,
			));
			/*
			 * Extra information
			 */
			register_field_group(array (
				'id' => 'acf_event-information',
				'title' => __('Event information', 'feiradetrocas'),
				'fields' => array (
					array (
						'toolbar' => 'basic',
						'media_upload' => 'no',
						'default_value' => '',
						'key' => 'field_51d4a27e19712',
						'label' => __('Additional information', 'feiradetrocas'),
						'name' => '_fdt_additional_information',
						'type' => 'wysiwyg',
					),
					array (
						'key' => 'field_51d49fdb0ad54',
						'label' => __('Sponsor 1', 'feiradetrocas'),
						'name' => '',
						'type' => 'tab',
					),
					array (
						'default_value' => '',
						'formatting' => 'html',
						'key' => 'field_51d4a12b0ad55',
						'label' => __('Name', 'feiradetrocas'),
						'name' => '_fdt_sponsor_01_name',
						'type' => 'text',
					),
					array (
						'default_value' => '',
						'key' => 'field_51d4a13a0ad56',
						'label' => __('Email', 'feiradetrocas'),
						'name' => '_fdt_sponsor_01_email',
						'type' => 'email',
					),
					array (
						'default_value' => '',
						'formatting' => 'html',
						'key' => 'field_51d4a1550ad57',
						'label' => __('Phone number', 'feiradetrocas'),
						'name' => '_fdt_sponsor_01_phone',
						'type' => 'text',
					),
					array (
						'key' => 'field_51d4a20224b8f',
						'label' => __('Sponsor 2', 'feiradetrocas'),
						'name' => '',
						'type' => 'tab',
					),
					array (
						'default_value' => '',
						'formatting' => 'html',
						'key' => 'field_51d4a20624b90',
						'label' => __('Name', 'feiradetrocas'),
						'name' => '_fdt_sponsor_02_name',
						'type' => 'text',
					),
					array (
						'default_value' => '',
						'key' => 'field_51d4a20b24b91',
						'label' => __('Email', 'feiradetrocas'),
						'name' => '_fdt_sponsor_02_email',
						'type' => 'email',
					),
					array (
						'default_value' => '',
						'formatting' => 'html',
						'key' => 'field_51d4a20e24b92',
						'label' => __('Phone number', 'feiradetrocas'),
						'name' => '_fdt_sponsor_02_phone',
						'type' => 'text',
					),
				),
				'location' => array (
					array (
						array (
							'param' => 'post_type',
							'operator' => '==',
							'value' => 'fdt_event',
							'order_no' => 0,
							'group_no' => 0,
						),
					),
				),
				'options' => array (
					'position' => 'normal',
					'layout' => 'default',
					'hide_on_screen' => array (
					),
				),
				'menu_order' => 0,
			));
		}

	}

	function geocode_scripts() {
		$geocode_service = mappress_get_geocode_service();
		$gmaps_key = mappress_get_gmaps_api_key();
		if($geocode_service == 'gmaps' && $gmaps_key)
			wp_enqueue_script('google-maps-api');
		wp_enqueue_script('mappress.geocode.box');
	}

	function new_event_form() {
		wp_enqueue_style('jquery-ui-smoothness', 'http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css');
		wp_enqueue_script('jquery-ui', 'http://code.jquery.com/ui/1.10.3/jquery-ui.js', array('jquery'));
		wp_enqueue_script('jquery-ui-timepicker-addon', get_stylesheet_directory_uri() . '/js/jquery-ui-timepicker-addon.js', array('jquery-ui'));
		wp_enqueue_style('jquery-ui-timepicker-addon', get_stylesheet_directory_uri() . '/js/jquery-ui-timepicker-addon.css');
		if(is_user_logged_in() && current_user_can('edit_posts')) {
			?>
			<form id="new-event-form" class="event-form" method="POST">
				<div class="row">
					<div class="six columns alpha">
						<div class="post-data">
							<p class="event-title">
								<input type="text" name="event_data[post_title]" placeholder="<?php _e('Event title', 'feiradetrocas'); ?>" />
							</p>
							<p class="event-description">
								<textarea name="event_data[post_content]" placeholder="<?php _e('Description', 'feiradetrocas'); ?>"></textarea>
							</p>
						</div>
					</div>
					<div class="six columns omega">
						<div class="info">
							<h3><?php _e('Sponsors', 'feiradetrocas'); ?></h3>
							<p><?php _e('The event can have two sponsors.', 'feiradetrocas'); ?></p>
							<div class="three columns alpha">
								<div class="sponsor_01">
									<p class="tip"><?php _e('Main sponsor information', 'feiradetrocas'); ?></p>
									<p class="sponsor_01_name">
										<input type="text" name="event_data[sponsor_01_name]" placeholder="<?php _e('Name', 'feiradetrocas'); ?>" />
									</p>
									<p class="sponsor_01_email">
										<input type="text" name="event_data[sponsor_01_email]" placeholder="<?php _e('Email', 'feiradetrocas'); ?>" />
									</p>
									<p class="sponsor_01_phone">
										<input type="text" name="event_data[sponsor_01_phone]" placeholder="<?php _e('Phone number', 'feiradetrocas'); ?>" />
									</p>
								</div>
							</div>
							<div class="three columns omega">
								<div class="sponsor_02">
									<p class="tip"><?php _e('Second sponsor information', 'feiradetrocas'); ?></p>
									<p class="sponsor_02_name">
										<input type="text" name="event_data[sponsor_02_name]" placeholder="<?php _e('Name', 'feiradetrocas'); ?>" />
									</p>
									<p class="sponsor_02_email">
										<input type="text" name="event_data[sponsor_02_email]" placeholder="<?php _e('Email', 'feiradetrocas'); ?>" />
									</p>
									<p class="sponsor_02_phone">
										<input type="text" name="event_data[sponsor_02_phone]" placeholder="<?php _e('Phone number', 'feiradetrocas'); ?>" />
									</p>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="seven columns alpha">
						<div class="geocode">
							<h3><?php _e('Point the location', 'feiradetrocas'); ?></h3>
							<p><?php _e('Enter the address and hit enter to find the event location', 'feiradetrocas'); ?></p>
							<?php mappress_geocode_box(); ?>
							<script type="text/javascript">
								jQuery(document).ready(function() {
									geocodeBox();
								});
							</script>
						</div>
					</div>
					<div class="four columns offset-by-one omega">
						<div class="date-time">
							<h3><?php _e('Date and time', 'feiradetrocas'); ?></h3>
							<p class="event-date-time">
								<input type="hidden" name="event_data[event_date_time]" id="event_date_time_picker_field" />
								<div id="event_date_time_picker"></div>
								<script type="text/javascript">
									jQuery(document).ready(function($) {
										$('#event_date_time_picker').datetimepicker({
											altField: '#event_date_time_picker_field',
											altFieldTimeOnly: false,
											altFormat: 'dd-mm-yy',
											altTimeFormat: 'HH:mm'
										});
									});
								</script>
							</p>
						</div>
					</div>
				</div>
				<input type="submit" value="<?php _e('Submit event', 'feiradetrocas'); ?>" />
			</form>
			<?php
		} elseif(!is_user_logged_in()) {
			?><h3><?php _e('You must be logged in to create an event', 'feiradetrocas'); ?></h3><?php
			wp_login_form();
		} else {
			?><h3><?php _e('You are not allowed to create events', 'feiradetrocas'); ?></h3><?php
		}
	}

	function validate_event_form() {
		if(isset($_POST['event_data'])) {

			$data = $_POST['event_data'];

			if(
				!$data['post_title'] ||
				!$data['post_content'] ||
				!$data['sponsor_01_name'] ||
				!$data['sponsor_01_phone'] ||
				!(
					!$data['sponsor_01_email'] ||
					!$data['sponsor_01_phone']
				)
			) {
				return array('message' => __('Make sure you filled all the necessary information', 'feiradetrocas'), 'status' => 'error');
			}

		}

		return false;
	}

	function new_event_shortcode($atts) {
		return $this->new_event_form();
	}

	function query_vars() {
		global $wp;
		$wp->add_query_var('fdt_event_time');
	}

	function pre_get_posts($query) {
		if(is_front_page()) {
			$query->set('post_type', 'fdt_event');
			if(!$query->get('fdt_event_time'))
				$query->set('fdt_event_time', 'future');
		}

		/*
		 * Next events query
		 */

		if($query->get('fdt_event_time')) {

			$compare = '>=';
			$order = 'ASC';

			if($query->get('fdt_event_time') == 'past') {
				$compare = '<';
				$order = 'DESC';
			}

			$query->set('post_type', 'fdt_event');

			$next_events_arg = array(
				'key' => '_fdt_event_date',
				'value' => time(),
				'compare' => $compare,
				'type' => 'CHAR'
			);

			if($query->get('meta_query')) {
				$query->set('meta_query', array_merge_recursive($query->get('meta_query'), array($next_events_arg)));
			} else {
				$query->set('meta_query', array($next_events_arg));
			}

			$query->set('orderby', 'meta_value');
			$query->set('meta_key', '_fdt_event_date');
			$query->set('order', $order);

		}

		return $query;
	}

	function is_event_query($query = false) {
		global $wp_query;
		$query = $query ? $query : $wp_query;

		return ($query->get('post_type') == 'fdt_event' || $query->get('post_type') == array('fdt_event'));
	}

	function has_event_passed($post_id = false) {
		global $post;
		$post_id = $post_id ? $post_id : $post->ID;

		$event_time = get_post_meta($post_id, '_fdt_event_date', true);

		if(is_int($event_time) && $event_time < time())
			return true;

		return false;
	}

}

$fdt_events = new FdT_Events();

function fdt_has_event_passed($post_id = false) {
	global $fdt_events;
	return $fdt_events->has_event_passed($post_id);
}