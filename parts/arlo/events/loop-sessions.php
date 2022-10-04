<div class="course-card-sessions">
	<?php
	while (have_rows('sessions')) : the_row();
		$isFull = get_sub_field('isfull');
		?>
        <div class="course-card-session">
            <p class="course-card-name">
                <b><?php get_template_part('parts/arlo/events/loop-session', 'name'); ?></b>
            </p>

            <p class="course-card-date">
                <i class="fal fa-clock hide"></i>
				<?php get_template_part('parts/arlo/events/loop-session', 'datetime'); ?>
            </p>

            <!--
            <p class="course-card-location">
                <i class="fal fa-map-marker-alt hide"></i>
				<?php // get_template_part('parts/arlo/events/loop-session', 'location'); ?>
            </p>
            -->
        </div>
	<?php endwhile; ?>
</div>
