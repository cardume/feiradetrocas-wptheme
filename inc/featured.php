<?php

/*
 * Featured posts
 */

function fdt_featured_posts() {
	wp_enqueue_script('fdt-featured', get_stylesheet_directory_uri() . '/inc/featured.js');

	$featured_query = new WP_Query(array(
		'post_type' => 'post',
		'meta_query' => array(
			array(
				'key' => '_jeo_featured',
				'value' => 1
			)
		),
		'posts_per_page' => 4,
		'not_geo_query' => 1,
		'not_event_query' => 1,
		'wihout_map_query' => 1
	));

	if($featured_query->have_posts()) { ?>
		<section id="featured-content" class="posts-section featured">
			<div class="container">
				<div class="eleven columns">
					<h2><?php _e('Featured', 'feiradetrocas'); ?></h2>
				</div>
				<div class="four columns">
					<div class="featured-content">
						<ul class="featured-list">
							<?php
							$class = 'slider-item';
							$i = 0;
							while($featured_query->have_posts()) {
								$featured_query->the_post();
								$active = $i >= 1 ? '' : ' active';
								?>
								<li id="post-<?php the_ID(); ?>" <?php post_class($class . ' ' . $active); ?>>
									<article id="post-<?php the_ID(); ?>">
										<header class="post-header">
											<h3><a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><?php the_title(); ?></a></h3>
											<p class="meta">
												<span class="date"><?php echo get_the_date(); ?></span>
												<span class="author"><?php _e('by', 'feiradetrocas'); ?> <?php the_author(); ?></span>
											</p>
										</header>
										<section class="post-content">
											<div class="post-excerpt">
												<?php the_excerpt(); ?>
											</div>
										</section>
										<aside class="actions">
											<a href="<?php the_permalink(); ?>"><?php _e('Read more', 'feiradetrocas'); ?></a>
										</aside>
									</article>
								</li>
								<?php
								$i++;
							}
							?>
						</ul>
					</div>
					<div class="slider-controllers">
						<ul>
							<?php
							$i = 0;
							while($featured_query->have_posts()) {
								$featured_query->the_post();
								$i++;
								?>
								<li class="slider-item-controller" data-postid="post-<?php the_ID(); ?>" title="<?php _e('Go to', 'feiradetrocas'); ?> <?php the_title(); ?>"><?php _e('Go to', 'feiradetrocas'); ?> <?php the_title(); ?></li>
								<?php
							}
							?>
						</ul>
				</div>
			</div>
		</section>
		<script type="text/javascript">
			jQuery(document).ready(function() {
				featuredSlider('featured-content');
			});
		</script>
		<?php
	}
	wp_reset_query();
}
?>