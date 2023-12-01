<?php
/**
 * @return false|string
 */
function ipa_upcoming_courses_widget()
{
	ob_start();

	$args = array(
		'post_type'      => 'ipa_arlo_events',
		'post_status'    => 'publish',
		'posts_per_page' => - 1,
		'orderby'        => 'meta_value',
		'order'          => 'ASC',
		'meta_key'       => 'startdatetime',
		'meta_query'     => array(
			array(
				'key'   => 'is_featured',
				'value' => '1',
			)
		),
	);

	$loop = new WP_Query( $args );

    $default_course_images = get_field( 'default_course_category_images', 'options' );
	?>
    <div class="upcoming-courses-widget grid-x grid-margin-x grid-margin-y" data-equalizer="upcoming-courses-title" data-equalize-by-row="true">
		<?php
		while ($loop->have_posts()) : $loop->the_post();
			$eventId             = get_field( 'eventid' );
			$eventCode           = get_field( 'code' );
			$eventTemplateCode   = get_field( 'templatecode' );
			$course_type_name_id = searchForId( $eventTemplateCode, $default_course_images );
			?>
            <div class="course-card cell small-12 medium-6 large-4" id="<?= $eventId ?>">

	            <?php if (has_post_thumbnail()) : ?>
                    <div class="course-card-image-wrapper">
			            <?php the_post_thumbnail('full', array('class' => 'course-card-image')); ?>
                    </div><!-- end .course-card-image-wrapper -->
                <?php else : ?>
		            <?php $image_id = $default_course_images[ $course_type_name_id ]['image']; ?>
                    <div class="course-card-image-wrapper">
			            <?= wp_get_attachment_image( $image_id, 'full', false, array( 'class' => 'course-card-image' ) ); ?>
                    </div>
	            <?php endif; ?>

                <div class="course-card-inner">
                    <p class="course-card-title" data-equalizer-watch="upcoming-courses-title">
						<b><?php the_title(); ?></b>
                    </p>

                    <p class="course-card-date text-color-dark-gray">
                        <i class="fal fa-clock hide"></i>
                        <?php get_template_part('parts/arlo/events/loop-event', 'datetime'); ?>
                    </p>

	                <?php
	                if (have_rows('sessions')) : ?>
                        <p class="course-card-location text-color-dark-gray">
                            <i class="fal fa-map-marker-alt hide"></i>
			                <?php
			                while (have_rows('sessions')) : the_row();
				                get_template_part('parts/arlo/events/loop-session', 'location');
                                break;
			                endwhile;
			                ?>
                        </p>
	                <?php endif; ?>

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
                                    <b><?= __('View Details', 'ipa'); ?></b>
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
