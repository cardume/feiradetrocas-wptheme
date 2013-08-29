<?php get_header(); ?>

<?php
if(is_front_page()) {
	$options = jeo_get_options();
	if(!$options || (isset($options['front_page']) && $options['front_page']['front_page_map'] == 'latest'))
		jeo_featured();
	else
		get_template_part('content', 'featured');
} else {
	jeo_featured();
}
?>

<div class="section-title">
	<div class="container">
		<div class="twelve columns">
			<?php fdt_custom_selector(); ?>
			<?php fdt_time_selector(); ?>
			<?php fdt_city_selector(); ?>
			<h2><?php single_cat_title(); ?></h2>
		</div>
	</div>
</div>
<?php get_template_part('loop'); ?>

<?php get_footer(); ?>