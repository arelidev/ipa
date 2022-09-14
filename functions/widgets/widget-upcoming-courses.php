<?php
/**
 * @return false|string
 */
function ipa_upcoming_courses_widget()
{
	ob_start();

	$args = array(
		'post_type' => 'ipa_arlo_events',
		'post_status' => 'publish',
		'posts_per_page' => -1,
		'meta_query' => array(
			'relation' => 'AND',
			array(
				'key' => 'is_featured',
				'value' => '1',
			),
//			array(
//				'relation' => 'OR',
//				array(
//					'key' => 'isprivate',
//					'value' => '0',
//				),
//				array(
//					'key' => 'isprivate',
//					'compare' => 'EXISTS',
//				),
//			)
		)
	);

	$loop = new WP_Query($args);
	?>
    <div class="upcoming-courses-widget grid-x grid-margin-x grid-margin-y"
         data-equalizer="upcoming-courses-title" data-equalize-by-row="true">
		<?php
		while ($loop->have_posts()) : $loop->the_post();
			$eventId = get_field('eventid');
			$eventCode = get_field('code');
			?>
            <div class="course-card cell small-12 medium-6 large-4" id="<?= $eventId ?>">

                <div class="course-card-image-wrapper">
					<?php the_post_thumbnail('full', array('class' => 'course-card-image')); ?>
                </div><!-- end .course-card-image-wrapper -->

                <div class="course-card-inner">
                    <p class="course-card-date text-color-dark-gray">
                        <i class="fal fa-clock"></i>
                        <?php get_template_part('parts/arlo/events/loop-event', 'datetime'); ?>
                    </p>

                    <h4 class="course-card-title" data-equalizer-watch="upcoming-courses-title">
						<?php the_title(); ?>
                    </h4>

					<?= apply_filters('the_content', get_field('summary')); ?>

                    <!-- Presenters -->
					<?php if (have_rows('presenters')) : ?>
                        <div class="grid-x">
                            <div class="small-12 medium-12 large-shrink cell">
                                <div class="course-card-trainer-wrapper">
									<?php get_template_part('parts/arlo/events/loop', 'presenters'); ?>
                                </div>
                            </div>
                        </div>
					<?php endif; ?>

                    <!-- Sessions -->
					<?php if (have_rows('sessions')) : ?>
                        <ul class="accordion ipa-accordion-widget"
                            id="<?= wp_unique_id("accordion-" . $eventId . "-"); ?>"
                            data-accordion
                            data-allow-all-closed="true">
                            <li class="accordion-item ipa-accordion-item" data-accordion-item>
                                <a href="#<?= $eventCode; ?>"
                                   class="accordion-title ipa-accordion-title text-color-black">
                                    <b><?= __('View sessions', 'ipa'); ?></b>
                                </a>
                                <div class="accordion-content ipa-accordion-content" data-tab-content
                                     id="<?= $eventCode ?>">
									<?php get_template_part('parts/arlo/events/loop', 'sessions'); ?>
                                </div>
                            </li>
                        </ul>
					<?php endif; ?>

                    <!-- Registration -->
					<?php get_template_part('parts/arlo/events/event', 'register'); ?>

                </div> <!-- end .course-card-inner -->
            </div> <!-- end .course-card -->
		<?php endwhile; ?>
    </div> <!-- end .upcoming-courses-widget -->
	<?php
	wp_reset_postdata();
	return ob_get_clean();
}

add_shortcode('ipa_upcoming_courses', 'ipa_upcoming_courses_widget');

// Integrate with Visual Composer
function ipa_upcoming_courses_integrateWithVC()
{
	try {
		vc_map(array(
			"name" => __("Upcoming Courses", "ipa"),
			"base" => "ipa_upcoming_courses",
			"class" => "",
			"category" => __("Custom", "ipa"),
			"params" => array(),
			"show_settings_on_create" => false
		));
	} catch (Exception $e) {
		echo 'Caught exception: ', $e->getMessage(), "\n";
	}
}

add_action('vc_before_init', 'ipa_upcoming_courses_integrateWithVC');
