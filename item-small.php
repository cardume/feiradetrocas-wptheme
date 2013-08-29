<li id="post-<?php the_ID(); ?>" <?php post_class('three columns'); ?>>
	<article id="post-<?php the_ID(); ?>" class="<?php if(fdt_has_event_passed()) echo 'event-passed'; ?>">
		<header class="post-header clearfix">
			<?php if(get_post_type($post->ID) == 'fdt_event') { ?>
				<p class="fdt-event-date">
					<span class="day"><?php echo fdt_get_event_date($post->ID, 'd'); ?></span>
					<span class="month"><?php echo fdt_get_event_date($post->ID, 'M'); ?></span>
					<span class="year"><?php echo fdt_get_event_date($post->ID, 'Y'); ?></span>
					<span class="time"><?php echo fdt_get_event_date($post->ID, 'H:i'); ?></span>
				</p>
			<?php } ?>
			<h3><a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><?php the_title(); ?></a></h3>
			<p class="meta">
				<span class="author"><?php _e('by', 'feiradetrocas'); ?> <?php the_author(); ?></span>
			</p>
		</header>
		<section class="post-content">
			<div class="post-excerpt">
				<?php the_excerpt(); ?>
			</div>
		</section>
		<aside class="actions clearfix">
			<?php echo jeo_find_post_on_map_button(); ?>
			<a href="<?php the_permalink(); ?>"><?php _e('Read more', 'feiradetrocas'); ?></a>
		</aside>
	</article>
</li>