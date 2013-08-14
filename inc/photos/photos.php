<?php

/*
 * Feira de Trocas
 * Collaborative photo system for events
 */

class FdT_Events_Photos extends FdT_Events {

	function __construct() {
		$this->setup_ajax();
		add_action('template_redirect', array($this, 'add_photo_template'));
	}

	function add_photo_button() {
		global $wp;
		if($this->has_event_passed()) {
			?>
			<a class="button add-picture" href="<?php echo add_query_arg('add_photo', 1, home_url($wp->request)); ?>"><?php _e('Submit a picture', 'feiradetrocas'); ?></a>
			<?php
		}
	}

	function add_photo_form($post) {
		if(!$post)
			return false;

		if(is_user_logged_in() && current_user_can('edit_posts')) {
			?>
			<form id="photo-form" method="POST">
				<?php $this->box_images(); ?>
				<input type="hidden" name="post_id" value="<?php echo $post->ID; ?>" />
				<input type="hidden" name="_wpnonce" value="<?php echo wp_create_nonce('save_images'); ?>" />
				<input type="submit" value="<?php _e('Send images', 'feiradetrocas'); ?>" />
			</form>
			<?php
		} elseif(!is_user_logged_in()) {
			?>
			<div class="row">
				<h3><?php _e('You must be logged in to submit a photo', 'feiradetrocas'); ?></h3>
			</div>
			<div class="four columns alpha">
				<?php wp_login_form(); ?>
			</div>
			<div class="four columns omega">
				<?php wp_register('<span class="button">', '</span>'); ?>
			</div>
			<?php
		}

	}

	function box_images($post = false) {

		wp_enqueue_script('jquery-form', get_stylesheet_directory_uri() . '/inc/photos/jquery.form.min.js', array('jquery'));
		wp_enqueue_script('fdt-photos', get_stylesheet_directory_uri(). '/inc/photos/photos.js', array('jquery', 'jquery-form'), '0.0.4');
		wp_localize_script('fdt-photos', 'fdt_photos', array(
			'ajaxurl' => admin_url('admin-ajax.php'),
			'crunching_message' => __('Crunching images...', 'feiradetrocas')
		));

		?>
		<div id="images_box" class="loop-box row">
			<div class="box-inputs clearfix">
				<ul class="image-template" style="display:none;">
					<li class="template">
						<?php $this->image_input_template(); ?>
					</li>
				</ul>
				<ul class="image-list">
				</ul>
			</div>
			<a class="new-image new-button button secondary" href="#"><?php _e('Add another image', 'feiradetrocas'); ?></a>
		</div>
		<?php
	}

	function image_input_template($id = false, $title = false, $thumb_url = false) {
		?>
			<p class="input-container image main-input">
				<input type="text" class="image-title" size="30" <?php if($id) echo 'name="images[' . $id . '][title]"'; ?> <?php if($title) echo 'value="' . $title . '"'; ?> placeholder="<?php _e('Image title', 'feiradetrocas'); ?>" />
				<input type="file" class="image-file" size="40" <?php if($id) echo 'name="image_files[]"'; ?> placeholder="<?php _e('Image file', 'feiradetrocas'); ?>" />
			</p>
			<input type="hidden" class="image-id" <?php if($id) echo 'name="images[' . $id . '][id]" value="' . $id . '"'; ?> />
			<a class="remove-image button remove" href="#"><?php _e('Remove', 'feiradetrocas'); ?></a>
		<?php
	}

	function setup_ajax() {
		add_action('wp_ajax_nopriv_save_images', array($this, 'save_images'));
		add_action('wp_ajax_save_images', array($this, 'save_images'));
		add_action('wp_ajax_nopriv_delete_image', array($this, 'delete_image'));
		add_action('wp_ajax_delete_image', array($this, 'delete_image'));
	}

	function save_images() {

		$post_id = $_REQUEST['post_id'];

		if(!get_post($post_id))
			$this->ajax_response(array('status' => 'error', 'message' => __('The event doesn\'t exist', 'feiradetrocas')));

		if(!wp_verify_nonce($_REQUEST['_wpnonce'], 'save_images') || !current_user_can('edit_posts'))
			$this->ajax_response(array('status' => 'error', 'message' => __('Permission denied', 'feiradetrocas')));

		if(isset($_FILES['image_files'])) {
			$files = $_FILES['image_files'];
			$data = $_REQUEST['images'];

			$i = 0;
			foreach($files['name'] as $key => $value) {
				if($files['name'][$key]) {
					$file = array(
						'name'     => $files['name'][$key],
						'type'     => $files['type'][$key],
						'tmp_name' => $files['tmp_name'][$key],
						'error'    => $files['error'][$key],
						'size'     => $files['size'][$key]
					);

					$_FILES = array("images" => $file);
					foreach($_FILES as $file => $array) {
						if(getimagesize($array['tmp_name'])) {
							$attachment_id = media_handle_upload($file, $post_id, array('post_title' => $data['image-' . $i]['title']));
						}
					}
					$i++;
				} else {
					$this->ajax_response(array('status' => 'error', 'message' => __('Invalid file.', 'feiradetrocas')));
				}
			}
			$this->ajax_response(array('status' => 'success', 'message' => __('Your images have been added to the event!', 'feiradetrocas')));
		} else {
			$this->ajax_response(array('status' => 'error', 'message' => __('You must select an image!', 'feiradetrocas')));
		}


	}

	function add_photo_template() {
		global $post;
		if(is_singular('fdt_event') && isset($_GET['add_photo'])) {
			?>
			<?php get_header(); ?>

			<?php if(have_posts()) : the_post(); ?>
				<section id="content" class="single-post">
					<header class="single-post-header">
						<div class="container">
							<div class="twelve columns">
								<h1><?php _e('Submit photos to', 'feiradetrocas'); ?> <?php the_title(); ?></h1>
							</div>
						</div>
					</header>
					<div class="container">
						<div class="twelve columns">
							<?php $this->add_photo_form($post); ?>
						</div>
					</div>
				</section>
			<?php endif; ?>

			<?php get_footer(); ?>
			<?php
			exit();
		}
	}

	function delete_image() {

		$post_id = $_REQUEST['postid'];

		$image = get_post($post_id);

		if(get_current_user_id() == $image->post_author || current_user_can('edit_others_posts') || current_user_can('edit_post')) {

			wp_delete_post($post_id, true);
			$this->ajax_response(array('success' => 1));

		}
		$this->ajax_response(array('success' => false));
	}

	function photo_gallery($post_id = false) {
		global $post;
		$post_id = $post_id ? $post_id : $post->ID;

		$images = get_posts(array(
			'post_type' => 'attachment',
			'post_status' => null,
			'post_parent' => $post_id,
			'posts_per_page' => -1,
			'not_geo_query' => 1
		));

		if($images) {
			?>
			<div id="photo-gallery" class="photo-gallery row">
				<h3><?php _e('Photo gallery', 'feiradetrocas'); ?></h3>
				<div class="row">
					<ul class="photo-gallery-list">
						<?php $i = 0; foreach($images as $image) { global $post; $post = $image; setup_postdata($post); ?>
							<li id="image-<?php echo $image->ID; ?>" class="two columns <?php if($i % 4 == 0) echo 'alpha'; elseif(($i+1) % 4 == 0) echo 'omega'; ?>">
								<?
								$src = wp_get_attachment_image_src($image->ID, 'thumbnail', false);
								$thumb_url = $src[0];
								?>
								<div class="image-box">
									<a href="<?php echo wp_get_attachment_url($post->ID); ?>" title="<?php the_title(); ?>" rel="shadowbox[gallery-<?php echo $post_id; ?>]"><img src="<?php echo $thumb_url; ?>" /></a>
									<h4><?php the_title(); ?></h4>
									<p class="author"><?php _e('sent by', 'feiradetrocas'); ?> <?php the_author(); ?></p>
									<?php if(get_current_user_id() == $post->post_author || current_user_can('edit_others_posts')) : ?>
										<a href="#" class="delete-image" data-imageid="<?php echo $post->ID; ?>"><?php _e('Delete', 'feiradetrocas'); ?></a>
									<?php endif; ?>
								</div>
						<?php wp_reset_postdata(); $i++; } ?>
					</ul>
				</div>
				<?php $this->add_photo_button(); ?>
				<script type="text/javascript">
					Shadowbox.init();

					<?php if(get_current_user_id() == $post->post_author || current_user_can('edit_others_posts') || current_user_can('edit_post')) : ?>

						jQuery(document).ready(function($) {

							$('.delete-image').click(function() {

								var $el = $(this);

								var c = confirm('<?php _e("Are you sure?", "feiradetrocas"); ?>');

								if(c == true) {

									$.post('<?php echo admin_url('admin-ajax.php'); ?>',
										{
											action: 'delete_image',
											postid: $el.data('imageid')
										}, function (data) {

											if(data.success) {

												$el.parents('li').fadeOut('fast', function() {
													$(this).remove();
												});

											}

										}, 'json');

								}

								return false;

							});

						});

					<?php endif; ?>

				</script>
			</div>
			<?php
		}

	}

}

$fdt_photos = new FdT_Events_Photos();

function fdt_add_photo_button() {
	global $fdt_photos;
	return $fdt_photos->add_photo_button();
}

function fdt_photo_gallery($post_id = false) {
	global $fdt_photos;
	return $fdt_photos->photo_gallery($post_id);
}