<?php

/*
 * Feira de Trocas
 * Collaborative events calendar
 */

class FdT_Events {

	function __construct() {

		add_action('jeo_init', array($this, 'setup'));

	}

	function setup() {
		$this->register_post_type();
		$this->acf_fields();
		$this->setup_ajax();
		add_action('jeo_featured_post_types', array($this, 'featured_post_types'));
		add_filter('query_vars', array($this, 'query_vars'));
		add_filter('pre_get_posts', array($this, 'pre_get_posts'), 1);
		add_filter('get_edit_post_link', array($this, 'edit_event_link'));
		add_shortcode('fdt_new_event', array($this, 'new_event_shortcode'));
		add_action('template_redirect', array($this, 'edit_event_template'));
		add_action('jeo_geocode_scripts', array($this, 'geocode_scripts'));
		add_action('jeo_marker_base_query', array($this, 'marker_base_query'));
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
			'menu_position' => 5,
			'taxonomies' => array('category', 'post_tag')
		);

		register_post_type('fdt_event', $args);

	}

	function acf_fields() {

		if(function_exists("register_field_group")) {
			/* 
			 * Date and time
			 */
			register_field_group(array (
				'id' => 'acf_event-date-time',
				'title' => __('Event date and time', 'feiradetrocas'),
				'fields' => array (
					array (
						'default_value' => '',
						'label' => __('Date and time', 'feiradetrocas'),
						'time_format' => 'HH:mm',
						'show_date' => 'true',
						'date_format' => 'dd-mm-yy',
						'show_week_number' => 'false',
						'picker' => 'slider',
						'save_as_timestamp' => 'true',
						'key' => 'field_event_date_time',
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
					/*
					array (
						'toolbar' => 'basic',
						'media_upload' => 'no',
						'default_value' => '',
						'key' => 'field_51d4a27e19712',
						'label' => __('Additional information', 'feiradetrocas'),
						'name' => '_fdt_additional_information',
						'type' => 'wysiwyg',
					),
					*/
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

	function featured_post_types($post_types) {
		$post_types[] = 'fdt_event';
		return $post_types;
	}

	function edit_event_template() {
		global $post;
		if(is_singular('fdt_event') && isset($_GET['edit']) && current_user_can('edit_post', $post->ID)) {
			?>
			<?php get_header(); ?>

			<?php if(have_posts()) : the_post(); ?>
				<section id="content" class="single-post">
					<header class="single-post-header">
						<div class="container">
							<div class="twelve columns">
								<h1><?php _e('Editing', 'feiradetrocas'); ?>: <?php the_title(); ?></h1>
							</div>
						</div>
					</header>
					<div class="container">
						<div class="twelve columns">
							<?php $this->event_form($post); ?>
						</div>
					</div>
				</section>
			<?php endif; ?>

			<?php get_footer(); ?>
			<?php
			exit();
		}
	}

	function geocode_scripts() {
		$geocode_service = jeo_get_geocode_service();
		$gmaps_key = jeo_get_gmaps_api_key();
		if($geocode_service == 'gmaps' && $gmaps_key)
			wp_enqueue_script('google-maps-api');
		wp_enqueue_script('jeo.geocode.box');
	}

	function event_form($post = false) {
		wp_enqueue_style('jquery-ui-smoothness', 'http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css');
		wp_enqueue_script('jquery-ui', 'http://code.jquery.com/ui/1.10.3/jquery-ui.js', array('jquery'));
		wp_enqueue_script('jquery-ui-timepicker-addon', get_stylesheet_directory_uri() . '/js/jquery-ui-timepicker-addon.js', array('jquery-ui'));
		wp_enqueue_style('jquery-ui-timepicker-addon', get_stylesheet_directory_uri() . '/js/jquery-ui-timepicker-addon.css');
		wp_enqueue_script('fdt-events', get_stylesheet_directory_uri() . '/inc/events.js', array('jquery'), '0.0.1');
		wp_localize_script('fdt-events', 'fdt_events', array(
			'ajaxurl' => admin_url('admin-ajax.php'),
			'loading_msg' => __('Sending information...', 'feiradetrocas')
		));
		if(is_user_logged_in() && current_user_can('edit_posts')) {
			?>
			<form id="event-form" class="event-form" method="POST">
				<p class="form-description"><?php _e('Required fields are marked with <span class="required-mark">*</span>', 'feiradetrocas'); ?></p>
				<?php if($post) : ?>
					<input type="hidden" name="event_data[post_id]" value="<?php echo $post->ID; ?>" />
				<?php endif; ?>
				<input type="hidden" name="_wpnonce" value="<?php echo wp_create_nonce('save_event'); ?>" />
				<div class="row">
					<div class="six columns alpha">
						<div class="post-data">
							<p class="event-title required">
								<input type="text" name="event_data[post_title]" placeholder="<?php _e('Event title', 'feiradetrocas'); ?>" <?php if($post) echo 'value="' . $post->post_title . '"' ?> />
							</p>
							<p class="event-description required">
								<textarea name="event_data[post_content]" placeholder="<?php _e('Description', 'feiradetrocas'); ?>"><?php if($post) echo $post->post_content; ?></textarea>
							</p>
							<?php if(!$post && !isset($_REQUEST['cat_id'])) : ?>
								<p class="event-category required">
									<?php
									$cats = get_categories(array('hide_empty' => 0));
									if($cats) :
										?>
										<select name="event_data[category]">
											<option><?php _e('Select an event category', 'feiradetrocas'); ?></option>
											<?php foreach($cats as $cat) : ?>
												<option value="<?php echo $cat->term_id; ?>"><?php echo $cat->name; ?></option>
											<?php endforeach; ?>
										</select>
									<?php endif; ?>
								</p>
							<?php else : ?>
								<input type="hidden" name="event_data[category]" value="<?php echo $_REQUEST['cat_id']; ?>" />
							<?php endif; ?>
						</div>
					</div>
					<div class="six columns omega">
						<div class="info">
							<h3><?php _e('Sponsors', 'feiradetrocas'); ?></h3>
							<p><?php _e('The event can have two sponsors.', 'feiradetrocas'); ?></p>
							<div class="three columns alpha">
								<div class="sponsor_01">
									<p class="tip"><?php _e('Main sponsor information', 'feiradetrocas'); ?></p>
									<p class="sponsor_01_name required">
										<?php
										$sponsor_01_name = false;
										if($post) {
											$sponsor_01_name = get_field('_fdt_sponsor_01_name', $post->ID);
										}
										?>
										<input type="text" name="event_data[sponsor_01_name]" placeholder="<?php _e('Name', 'feiradetrocas'); ?>" <?php if($sponsor_01_name) echo 'value="' . $sponsor_01_name . '"'; ?> />
									</p>
									<p class="sponsor_01_email required">
										<?php
										$sponsor_01_email = false;
										if($post) {
											$sponsor_01_email = get_field('_fdt_sponsor_01_email', $post->ID);
										}
										?>
										<input type="text" name="event_data[sponsor_01_email]" placeholder="<?php _e('Email', 'feiradetrocas'); ?>" <?php if($sponsor_01_email) echo 'value="' . $sponsor_01_email . '"'; ?> />
									</p>
									<p class="sponsor_01_phone required">
										<?php
										$sponsor_01_phone = false;
										if($post) {
											$sponsor_01_phone = get_field('_fdt_sponsor_01_phone', $post->ID);
										}
										?>
										<input type="text" name="event_data[sponsor_01_phone]" placeholder="<?php _e('Phone number', 'feiradetrocas'); ?>" <?php if($sponsor_01_phone) echo 'value="' . $sponsor_01_phone . '"'; ?> />
									</p>
								</div>
							</div>
							<div class="three columns omega">
								<div class="sponsor_02">
									<p class="tip"><?php _e('Secondary sponsor information', 'feiradetrocas'); ?></p>
									<p class="sponsor_02_name">
										<?php
										$sponsor_02_name = false;
										if($post) {
											$sponsor_02_name = get_field('_fdt_sponsor_02_name', $post->ID);
										}
										?>
										<input type="text" name="event_data[sponsor_02_name]" placeholder="<?php _e('Name', 'feiradetrocas'); ?>" <?php if($sponsor_02_name) echo 'value="' . $sponsor_02_name . '"'; ?> />
									</p>
									<p class="sponsor_02_email">
										<?php
										$sponsor_02_email = false;
										if($post) {
											$sponsor_02_email = get_field('_fdt_sponsor_02_email', $post->ID);
										}
										?>
										<input type="text" name="event_data[sponsor_02_email]" placeholder="<?php _e('Email', 'feiradetrocas'); ?>" <?php if($sponsor_02_email) echo 'value="' . $sponsor_02_email . '"'; ?> />
									</p>
									<p class="sponsor_02_phone">
										<?php
										$sponsor_02_phone = false;
										if($post) {
											$sponsor_02_phone = get_field('_fdt_sponsor_02_phone', $post->ID);
										}
										?>
										<input type="text" name="event_data[sponsor_02_phone]" placeholder="<?php _e('Phone number', 'feiradetrocas'); ?>" <?php if($sponsor_02_phone) echo 'value="' . $sponsor_02_phone . '"'; ?> />
									</p>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="seven columns alpha">
						<div class="geocode">
							<h3 class="required"><?php _e('Point the location', 'feiradetrocas'); ?></h3>
							<p><?php _e('Enter the address and hit enter to find the event location', 'feiradetrocas'); ?></p>
							<?php jeo_geocode_box($post); ?>
							<script type="text/javascript">
								jQuery(document).ready(function() {
									geocodeBox();
								});
							</script>
						</div>
					</div>
					<div class="four columns offset-by-one omega">
						<div class="date-time">
							<h3 class="required"><?php _e('Date and time', 'feiradetrocas'); ?></h3>
							<?php
							$date_time = false;
							if($post) {
								$date_time = get_field('field_event_date_time', $post->ID);
							}
							?>
							<p><input type="hidden" name="event_data[event_date_time]" id="event_date_time_picker_field" <?php if($date_time) echo 'value="' . $date_time . '" data-datetime="' . $date_time . '"'; ?> /></p>
							<div id="event_date_time_picker"></div>
							<script type="text/javascript">
								jQuery(document).ready(function($) {
									var picker = $('#event_date_time_picker').datetimepicker({
										altField: '#event_date_time_picker_field',
										altFieldTimeOnly: false,
										dateFormat: 'dd-mm-yy',
										timeFormat: 'HH:mm',
										showButtonPanel: false
									});
									if($('#event_date_time_picker_field').data('datetime')) {
										var datetime = $('#event_date_time_picker_field').data('datetime');
										var parsedDateTime = $.datepicker.parseDateTime('dd-mm-yy', 'HH:mm', datetime);
										picker.datetimepicker('setDate', parsedDateTime);
									}
								});
							</script>
						</div>
					</div>
				</div>
				<?php if($post) : ?>
					<input type="submit" value="<?php _e('Update event', 'feiradetrocas'); ?>" />
				<?php else : ?>
					<input type="submit" value="<?php _e('Submit event', 'feiradetrocas'); ?>" />
				<?php endif; ?>
			</form>
			<?php
		} elseif(!is_user_logged_in()) {
			?>
			<div class="row">
				<h3><?php _e('You must be logged in to create an event', 'feiradetrocas'); ?></h3>
			</div>
			<div class="four columns alpha">
				<?php wp_login_form(); ?>
			</div>
			<div class="four columns omega">
				<?php wp_register('<span class="button">', '</span>'); ?>
			</div>
			<?php
		} else {
			?><h3><?php _e('You are not allowed to create events', 'feiradetrocas'); ?></h3><?php
		}
	}

	function setup_ajax() {
		add_action('wp_ajax_nopriv_save_event', array($this, 'save_event'));
		add_action('wp_ajax_save_event', array($this, 'save_event'));
	}

	function save_event() {

		/*
		 * Check nonce
		 */
		if(!wp_verify_nonce($_REQUEST['_wpnonce'], 'save_event'))
			$this->ajax_response(array('status' => 'error', 'message' => __('Permission denied.', 'feiradetrocas')));

		$data = $_REQUEST['event_data'];

		/* 
		 * Check if user can edit post
		 */
		if(isset($data['post_id']) && !current_user_can('edit_post', $data['post_id']))
			$this->ajax_response(array('status' => 'error', 'message' => __('You are not allowed to edit this event.', 'feiradetrocas')));

		/*
		 * Validate fields
		 */
		if(
			!$data['post_title'] ||
			!$data['post_content'] ||
			!$data['category'] ||
			!$data['event_date_time'] ||
			!$data['sponsor_01_name'] ||
			(
				!$data['sponsor_01_email'] &&
				!$data['sponsor_01_phone']
			) ||
			!$_REQUEST['geocode_address']
		) {
			$this->ajax_response(array('status' => 'error', 'message' => __('Make sure you filled all the required fields.', 'feiradetrocas')));
		}

		/*
		 * Save data
		 */

		$parsed = array(
			'post' => array(
				'ID' => $data['post_id'],
				'post_title' => $data['post_title'],
				'post_content' => $data['post_content'],
				'post_status' => 'pending',
				'post_type' => 'fdt_event',
				'post_category' => array($data['category'])
			),
			'acf' => array(
				'_fdt_sponsor_01_name' => $data['sponsor_01_name'],
				'_fdt_sponsor_01_email' => $data['sponsor_01_email'],
				'_fdt_sponsor_01_phone' => $data['sponsor_01_phone'],
				'_fdt_sponsor_02_name' => $data['sponsor_02_name'],
				'_fdt_sponsor_02_email' => $data['sponsor_02_email'],
				'_fdt_sponsor_02_phone' => $data['sponsor_02_phone'],
				'field_event_date_time' => $data['event_date_time']
			)
		);

		$new = false;

		if($parsed['post']['ID']) {

			unset($parsed['post']['post_status']);
			unset($parsed['post']['post_type']);

			$post_id = wp_update_post($parsed['post']);

		} else {

			unset($parsed['post']['ID']);

			$new = true;

			$post_id = wp_insert_post($parsed['post']);

			// store pending metadata for email submit when approved
			update_post_meta($post_id, '_fdt_pending_approval', 1);
		}

		if(!$post_id)
			$this->ajax_response(array('status' => 'error', 'message' => __('Something went wrong while creating your event, please try again. If the error persists, please contact our team.', 'feiradetrocas')));

		// save acf
		foreach($parsed['acf'] as $field_key => $field_value) {
			update_field($field_key, $field_value, $post_id);
		}

		// save geo data
		jeo_geocode_save($post_id);

		if($new)
			$this->ajax_response(array('status' => 'success', 'message' => __('Thank you for sending your event! You will receive an email soon.', 'feiradetrocas')));
		else
			$this->ajax_response(array('status' => 'updated', 'message' => __('Your event has been updated', 'feiradetrocas')));

	}

	function ajax_response($data) {
		header('Content Type: application/json');
		echo json_encode($data);
		exit;
	}

	function new_event_shortcode($atts) {
		ob_start();
		$this->event_form();
		$form = ob_get_clean();
		return $form;
	}

	function edit_event_link($link) {
		global $post;

		if($post) {
			if(get_post_type($post->ID) == 'fdt_event' && !is_admin())
				$link = add_query_arg(array('edit' => 1), get_permalink());
		}

		return $link;
	}

	function query_vars($vars) {
		$vars[] = 'fdt_force_event_time';
		$vars[] = 'fdt_event_time';
		$vars[] = 'fdt_event_date_from';
		$vars[] = 'fdt_event_date_to';
		$vars[] = 'is_event_query';
		return $vars;
	}

	function get_event_time_query_array($value, $compare) {
		return array(
			'key' => '_fdt_event_date',
			'value' => $value,
			'compare' => $compare,
			'type' => 'CHAR'
		);
	}

	function get_available_times() {
		$times = array(
			'future' => array(
				'title' => __('Upcoming events', 'feiradetrocas'),
				'meta_query' => array(
					$this->get_event_time_query_array(time(), '>=')
				)
			),
			'week' => array(
				'title' => __('This week', 'feiradetrocas'),
				'meta_query' => array(
					'relation' => 'AND',
					$this->get_event_time_query_array(time(), '>='),
					$this->get_event_time_query_array(strtotime('+1 week'), '<')
				)
			),
			'month' => array(
				'title' => __('This month', 'feiradetrocas'),
				'meta_query' => array(
					'relation' => 'AND',
					$this->get_event_time_query_array(time(), '>='),
					$this->get_event_time_query_array(strtotime('+1 month'), '<')
				)
			),
			'past' => array(
				'title' => __('Past events', 'feiradetrocas'),
				'meta_query' => array(
					$this->get_event_time_query_array(time(), '<')
				)
			)
		);

		return $times;
	}

	function pre_get_posts($query) {

		/*
		 * Apply GET requests
		 */

		if(isset($_REQUEST['fdt_event_time'])) {
			$query->set('fdt_event_time', $_REQUEST['fdt_event_time']);
		}

		if(isset($_REQUEST['fdt_event_date_from'])) {
			$query->set('fdt_event_date_from', $_REQUEST['fdt_event_date_from']);
		}

		if(isset($_REQUEST['fdt_event_date_to'])) {
			$query->set('fdt_event_date_to', $_REQUEST['fdt_event_date_to']);
		}

		/*
		 * Set event post type
		 */

		if($query->is_main_query() && ($query->is_home() || $query->is_tag() || $query->is_category())) {
			$query->set('post_type', 'fdt_event');
		}

		/*
		 * Events meta query
		 */
		
		if($this->is_event_query($query)) {

			if(!$query->get('fdt_event_time') && !$query->get('fdt_event_date_from') && !$query->get('fdt_event_date_to') && !is_single())
				$query->set('fdt_event_time', 'future');

			$meta_query = false;

			/*
			 * Range filter
			 */

			if($query->get('fdt_event_date_from') || $query->get('fdt_event_date_to')) {

				$from = $query->get('fdt_event_date_from');
				$to = $query->get('fdt_event_date_to');

				if(!$from)
					$from = 0;
				if(!$to)
					$to = time() + time();

				$meta_query = array(
					'relation' => 'AND',
					$this->get_event_time_query_array(strtotime($from), '>='),
					$this->get_event_time_query_array(strtotime($to), '<')
				);

			/*
			 * Pre-defined filters
			 */

			} elseif($query->get('fdt_event_time')) {

				$event_time = $query->get('fdt_event_time');

				$query->set('post_type', 'fdt_event');

				$times = $this->get_available_times();

				// look inside available times query

				foreach($times as $time_key => $args) {

					if($time_key == $event_time) {
						$meta_query = $args['meta_query'];
					}

				}

			}

			/*
			 * Set meta query if any
			 */

			if($meta_query) {
				$query->set('meta_query', $meta_query);
			}

			/* 
			 * Events ordering
			 */

			$order = 'ASC';

			if($query->get('fdt_event_time') == 'past')
				$order = 'DESC';

			$query->set('orderby', 'meta_value');
			$query->set('meta_key', '_fdt_event_date');
			$query->set('order', $order);

		}

		if($query->get('is_marker_query') && $query->get('fdt_event_time') == 'future' && !$query->get('fdt_force_event_time')) {
			$query->set('meta_query', null);
			$query->set('meta_key', null);
		}

		return $query;
	}

	function marker_base_query($query) {
		if(isset($_REQUEST['fdt_event_time'])) {
			$query->set('fdt_force_event_time', 1);
		}
		return $query;
	}

	function is_event_query($query = false) {
		global $wp_query;
		$query = $query ? $query : $wp_query;

		return (!$query->get('not_event_query') && !is_admin() && ($query->get('post_type') == 'fdt_event' || $query->get('post_type') == array('fdt_event')));
	}

	function get_event_date($post_id = false, $format = false) {
		global $post;
		$post_id = $post_id ? $post_id : $post->ID;

		if(!$format)
			$format = _x('l, F d Y \a\t g:i a', 'Event full date format', 'feiradetrocas');

		$date = get_post_meta($post_id, '_fdt_event_date', true);

		return date_i18n($format, $date);
	}

	function get_event_day($post_id = false, $format = false) {
		$format = $format ? $format : _x('l, F d Y', 'Event day format', 'feiradetrocas');
		return $this->get_event_date($post_id, $format);
	}

	function get_event_time($post_id = false, $format = false) {
		$format = $format ? $format : _x('g:i a', 'Event time format', 'feiradetrocas');
		return $this->get_event_date($post_id, $format);
	}

	function has_event_passed($post_id = false) {
		global $post;
		$post_id = $post_id ? $post_id : $post->ID;

		$event_time = get_post_meta($post_id, '_fdt_event_date', true);

		if($event_time && $event_time < time())
			return true;

		return false;
	}

	function get_event_sponsors($post_id = false) {
		global $post;
		$post_id = $post_id ? $post_id : $post->ID;

		$sponsors = array(
			array(
				'name' => get_post_meta($post_id, '_fdt_sponsor_01_name', true),
				'email' => get_post_meta($post_id, '_fdt_sponsor_01_email', true),
				'phone' => get_post_meta($post_id, '_fdt_sponsor_01_phone', true)
			),
			array(
				'name' => get_post_meta($post_id, '_fdt_sponsor_02_name', true),
				'email' => get_post_meta($post_id, '_fdt_sponsor_02_email', true),
				'phone' => get_post_meta($post_id, '_fdt_sponsor_02_phone', true)
			)
		);

		return $sponsors;
	}

	function time_selector() {

		if(!$this->is_event_query())
			return false;

		global $wp_query, $wp;

		$current = $wp_query->get('fdt_event_time');
		$available = $this->get_available_times();

		$custom = false;
		if(is_array($current) || !isset($available[$current]))
			$custom = true;

		?>
		<div class="time-selector dropdown">
			<?php if(!$custom) : ?>
				<span class="title"><span class="lsf">&#xE03e;</span> <?php echo $available[$current]['title']; ?></span>
			<?php else : ?>
				<span class="title"><span class="lsf">&#xE03e;</span> <?php _e('Custom filter', 'feiradetrocas'); ?></span>
			<?php endif; ?>
			<ul class="list">
				<?php foreach($available as $time_key => $args) : ?>
					<?php if($time_key == $current) continue; ?>
					<li>
						<a href="<?php echo add_query_arg('fdt_event_time', $time_key, home_url($wp->request)); ?>"><?php echo $args['title']; ?></a>
					</li>
				<?php endforeach; ?>
			</ul>
		</div>
		<?php
	}

	function custom_selector() {

		if(!$this->is_event_query())
			return false;

		wp_enqueue_style('jquery-ui-smoothness', 'http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css');
		wp_enqueue_script('jquery-ui-datepicker');

		$from = isset($_REQUEST['fdt_event_date_from']) ? $_REQUEST['fdt_event_date_from'] : false;
		$to = isset($_REQUEST['fdt_event_date_to']) ? $_REQUEST['fdt_event_date_to'] : false;

		?>
		<form class="fdt-date-range" method="GET">
			<label class="title" for="fdt_event_range_from"><?php _e('Filter by date range:', 'feiradetrocas'); ?></label>
			<label class="from" for="fdt_event_range_from"><?php _e('from', 'feiradetrocas'); ?></label>
			<input id="fdt_event_range_from" name="fdt_event_date_from" type="text" size="12" value="<?php if($from) echo $from; ?>" />
			<label class="to" for="fdt_event_range_to"><?php _e('to', 'feiradetrocas'); ?></label>
			<input id="fdt_event_range_to" name="fdt_event_date_to" type="text" size="12" value="<?php if($to) echo $to; ?>" />
		
			<input type="submit" value="<?php _e('Filter', 'feiradetrocas'); ?>" />	
			<script type="text/javascript">
				jQuery(document).ready(function($) {

					var options = {
						dateFormat: 'dd-mm-yy'
					};

					$('#fdt_event_range_from').datepicker(options);
					$('#fdt_event_range_to').datepicker(options);

				});
			</script>
		</form>
		<?php
	}


}

$fdt_events = new FdT_Events();

function fdt_get_event_date($post_id = false, $format = false) {
	global $fdt_events;
	return $fdt_events->get_event_date($post_id, $format);
}

function fdt_get_event_day($post_id = false, $format = false) {
	global $fdt_events;
	return $fdt_events->get_event_day($post_id, $format);
}

function fdt_get_event_time($post_id = false, $format = false) {
	global $fdt_events;
	return $fdt_events->get_event_time($post_id, $format);
}

function fdt_get_event_sponsors($post_id = false) {
	global $fdt_events;
	return $fdt_events->get_event_sponsors($post_id);
}

function fdt_has_event_passed($post_id = false) {
	global $fdt_events;
	return $fdt_events->has_event_passed($post_id);
}

function fdt_time_selector() {
	global $fdt_events;
	return $fdt_events->time_selector();
}
function fdt_custom_selector() {
	global $fdt_events;
	return $fdt_events->custom_selector();
}
function fdt_is_event_query($query = false) {
	global $fdt_events;
	return $fdt_events->is_event_query($query);
}