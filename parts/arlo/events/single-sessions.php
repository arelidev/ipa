<?php
$event = get_field('arlo_event');
$args = array(
	'p' => $event,
	'post_type' => 'ipa_arlo_events',
	'post_status' => 'publish',
//		'meta_query' => array(
//			'relation' => 'OR',
//			array(
//				'key' => 'isprivate',
//				'value' => '0',
//			),
//			array(
//				'key' => 'isprivate',
//				'compare' => 'EXISTS',
//			),
//		)
);

$loop = new WP_Query($args);

if ($loop->have_posts()) :
	while ($loop->have_posts()) : $loop->the_post();
		?>
        <h4 class="course-table-title"><b><?= __('Locations & Dates', 'ipa'); ?></b></h4>

        <div class="courses-table-widget">
            <div class="course-wrapper">
	            <?php if (have_rows('sessions')) : ?>
		            <?php get_template_part('parts/arlo/events/event', 'table'); ?>
	            <?php endif; ?>
            </div>
        </div>
	<?php endwhile; ?>
<?php else: ?>
    <div class="callout primary">
		<?= __('Currently no courses scheduled - Check back later', 'ipa'); ?>
    </div>
<?php
endif;
wp_reset_postdata();