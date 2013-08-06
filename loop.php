<section class="posts-section">
	<div class="container">
		<ul class="posts-list">
			<?php global $wp_query; ?>
			<?php if(have_posts() && !$wp_query->get('city_not_found')) : ?>
				<?php while(have_posts()) : the_post(); ?>
					<?php get_template_part('item', 'small'); ?>
				<?php endwhile; ?>
			<?php endif; ?>
			<?php
			// extra posts
			if($wp_query->found_posts < 30 && $wp_query->get('fdt_event_time') == 'future') {
				$amount = 30 - $wp_query->found_posts;
				$extra_args = array('posts_per_page' => $amount, 'fdt_event_time' => 'past');
				$extra_query = new WP_Query(array_merge($wp_query->query, $extra_args));
				if($extra_query->have_posts() && !$extra_query->get('city_not_found')) {
					while($extra_query->have_posts()) {
						$extra_query->the_post();
						get_template_part('item', 'small');
					}
				}
			}
			?>
		</ul>
	</div>
</section>