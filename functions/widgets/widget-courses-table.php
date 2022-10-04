<?php
/**
 * Course Table Widget
 *
 * @param $atts
 *
 * @return false|string
 */
function ipa_courses_table_widget($atts)
{
	$atts = shortcode_atts(array(
		'limit' => null,
		'course_cat' => '',
		'disable_filters' => false,
		'delivery_method' => 1 // 1: In-person, 2: Virtual
	), $atts);

	global $post;

	ob_start();

	$category = $atts['course_cat'];

	$args = array(
		'post_type' => 'ipa_arlo_events',
		'post_status' => 'publish',
		'posts_per_page' => -1,
	);

	$loop = new WP_Query($args);

	$courses = [];
	if ($loop->have_posts()) :
		while ($loop->have_posts()) : $loop->the_post();
			$templateCode = get_field('templatecode');
			$courses[$templateCode][] = get_the_ID();
		endwhile;
	endif;

    ksort($courses);
	?>
    <div class="courses-filter-parent">
        <div class="courses-filter-container">

			<?php if (!$atts['disable_filters']) : ?>
				<?php get_template_part('parts/course-table', 'filters'); ?>
			<?php endif; ?>

            <div class="grid-x grid-padding-x grid-padding-y ipa-courses-table-widget">

				<?php get_template_part('parts/course-table', 'sort'); ?>

				<?php
				foreach ($courses as $title => $ids) :?>
                    <div class="small-12 medium-12 cell styled-container container-border-top ipa-courses-table-widget-cell"
                         id="<?= strtolower($title); ?>">
                        <p class="h4 course-table-widget-title">
                            <a href="#<?= strtolower($title); ?>">
                                <i class="fa-solid fa-hashtag"></i> <b><?= $title; ?></b>
                            </a>
                        </p>
                        <div class="courses-table-widget">
                            <ul class="accordion ipa-accordion-widget course-wrapper"
                                data-accordion data-allow-all-closed="true">
								<?php
								foreach ($ids as $id) :
									$post = get_post($id);
									setup_postdata($post);
									?>
									<?php
									$eventId = get_field('eventid');
									$eventCode = get_field('code');
									$templateCode = get_field('code');

									$parentClasses = [
										$eventId,
										$eventCode,
										$templateCode,
										"accordion-item",
										"ipa-accordion-item",
										"mix-parent"
									];

									if (have_rows("presenters")) :
										while (have_rows("presenters")) : the_row();
											$parentClasses[] = acf_slugify(get_sub_field('name'));
										endwhile;
									endif;
									?>
                                    <li class="<?= implode(" ", $parentClasses); ?>" data-accordion-item
                                        id="<?= $eventId; ?>">
                                        <a href="#" class="accordion-title ipa-accordion-title text-color-black">
                                            <div class="grid-x align-middle" style="padding-right: 50px;">
                                                <div class="auto cell">
                                                    <span class="course-table-name" style="display: block; margin-bottom: 8px;">
                                                        <b><?= (empty($category)) ? get_the_title() : $category; ?></b>
                                                    </span>
                                                    <span class="course-table-date text-color-dark-gray">
                                                        <i class="fal fa-clock"></i>
                                                        <?php get_template_part('parts/arlo/events/loop-event', 'datetime'); ?>
                                                    </span>
                                                </div>
                                                <div class="shrink cell show-for-medium">
	                                                <?php if (have_rows('presenters')) : ?>
		                                                <?php
		                                                get_template_part(
			                                                'parts/arlo/events/loop', 'presenters',
			                                                array("disable_link" => true)
		                                                );
		                                                ?>
	                                                <?php endif; ?>
                                                </div>
                                            </div>
                                        </a>

                                        <div class="accordion-content ipa-accordion-content" data-tab-content
                                             id="<?= $eventId; ?>">
											<?php if (have_rows('sessions')) : ?>
												<?php get_template_part('parts/arlo/events/event', 'table'); ?>
											<?php endif; ?>

											<?php get_template_part('parts/arlo/events/event', 'register'); ?>
                                        </div>
                                    </li>
								<?php endforeach; ?>
                            </ul>
                        </div>
                    </div>
				<?php endforeach; ?>
				<?php wp_reset_postdata(); ?>
            </div>
        </div>
    </div>
	<?php
	wp_reset_query();
	return ob_get_clean();
}

add_shortcode('ipa_courses_table', 'ipa_courses_table_widget');

/**
 * Integrate with Visual Composer
 *
 * @return void
 */
function ipa_courses_table_integrateWithVC()
{
	try {
		vc_map(array(
			"name" => __("Courses Table", "ipa"),
			"base" => "ipa_courses_table",
			"class" => "",
			"category" => __("Custom", "ipa"),
			"params" => array(
				array(
					"type" => "textfield",
					"heading" => __("Course Category name", "ipa"),
					"param_name" => "course_cat",
					"description" => __("This name has to be exact to Magento's database fields.", "ipa")
				),
				array(
					"type" => "textfield",
					"heading" => __("Limit", "ipa"),
					"param_name" => "limit",
					"description" => __("", "ipa")
				),
				array(
					"type" => "textfield",
					"heading" => __("Delivery Method", "ipa"),
					"param_name" => "delivery_method",
					"description" => __("Default to 1 (in-person). Enter '2' for virtual courses", "ipa")
				),
				array(
					"type" => "textfield",
					"heading" => __("Disable Filters", "ipa"),
					"param_name" => "disable_filters",
					"description" => __("Enter 'true' to disable filters.", "ipa")
				),
			)
		));
	} catch (Exception $e) {
		echo 'Caught exception: ', $e->getMessage(), "\n";
	}
}

add_action('vc_before_init', 'ipa_courses_table_integrateWithVC');
