<?php get_header(); ?>

<?php if(have_posts()) : the_post(); ?>

	<?php jeo_map(); ?>

	<article id="content" class="single-post">
		<header class="single-post-header" class="clearfix">
			<div class="container">
				<div class="eight columns">
					<?php the_category(); ?>
					<h1><?php the_title(); ?></h1>
					<?php edit_post_link(); ?>
				</div>
				<div class="three columns offset-by-one">
					<div class="post-meta">
						<p class="author"><span class="lsf">&#xE137;</span> <?php _e('by', 'feiradetrocas'); ?> <?php the_author(); ?></p>
						<p class="date"><span class="lsf">&#xE12b;</span> <?php the_date(); ?></p>
					</div>
				</div>
				</div>
			</div>
		</header>
		<section class="content">
			<div class="container">
				<div class="eight columns">
					<p class="fdt-event-date">
						<span class="week-day"><?php echo fdt_get_event_date($post->ID, 'l'); ?></span>
						<span class="day"><?php echo fdt_get_event_date($post->ID, 'd'); ?></span>
						<span class="month"><?php echo fdt_get_event_date($post->ID, 'M'); ?></span>
						<span class="year"><?php echo fdt_get_event_date($post->ID, 'Y'); ?></span>
						<span class="time"><?php echo fdt_get_event_date($post->ID, 'H:i'); ?></span>
					</p>
					<p class="address">
						<?php echo get_post_meta($post->ID, 'geocode_address', true); ?>
					</p>
					<?php fdt_add_photo_button(); ?>
					<?php the_content(); ?>
					<div class="sponsors row">
						<h3><?php _e('Sponsors', 'feiradetrocas'); ?></h3>
						<?php $sponsors = fdt_get_event_sponsors(); ?>
						<div class="four columns alpha">
							<div class="sponsor-data">
								<h4><?php echo $sponsors[0]['name']; ?></h4>
								<?php if($sponsors[0]['email']) : ?>
									<p class="email"><?php echo $sponsors[0]['email']; ?></p>
								<?php endif; ?>
								<?php if($sponsors[0]['phone']) : ?>
									<p class="phone"><?php echo $sponsors[0]['phone']; ?></p>
								<?php endif; ?>
							</div>
						</div>
						<?php if($sponsors[1]['name']) : ?>
							<div class="four columns omega">
								<div class="sponsor-data">
									<h4><?php echo $sponsors[1]['name']; ?></h4>
									<?php if($sponsors[1]['email']) : ?>
										<p class="email"><?php echo $sponsors[1]['email']; ?></p>
									<?php endif; ?>
									<?php if($sponsors[1]['phone']) : ?>
										<p class="phone"><?php echo $sponsors[1]['phone']; ?></p>
									<?php endif; ?>
								</div>
							</div>
						<?php endif; ?>
					</div>
					<?php fdt_photo_gallery(); ?>
				</div>
				<div class="three columns offset-by-one">
					<aside id="sidebar">
						<ul class="widgets">
							<?php dynamic_sidebar('post'); ?>
						</ul>
					</aside>
				</div>
			</div>
		</section>
	</article>

<?php endif; ?>

<?php get_footer(); ?>