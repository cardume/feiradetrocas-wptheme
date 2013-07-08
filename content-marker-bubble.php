<?php
/*
 * Mousehover bubble content
 */
?>
<span class="arrow">&nbsp;</span>
<h4><?php the_title(); ?></h4>
<div class="event-date">
	<h5><?php _e('Date', 'feiradetrocas'); ?></h5>
	<p class="day"><?php echo fdt_get_event_day(); ?></p>
	<p class="time"><?php echo fdt_get_event_time(); ?></p>
</div>
<?php if(fdt_has_event_passed()) : ?>
	<p class="passed"><?php _e('This is event has passed', 'feiradetrocas'); ?></p>
<?php endif; ?>