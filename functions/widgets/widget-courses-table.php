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
		'course_cat' => ''
	), $atts);

	ob_start();

	$category = $atts['course_cat'];

	$args = array(
		'post_type' => 'ipa_arlo_events',
		'post_status' => 'publish',
		'posts_per_page' => -1,
		'meta_key' => 'startdatetime',
		'orderby' => 'meta_value',
		'order' => 'ASC'
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

	        <?php
	        get_template_part(
		        'parts/course-table', 'filters',
		        array(
			        "courses" => $courses
		        )
	        );
            ?>

            <div class="grid-x ipa-courses-table-widget">

				<?php get_template_part('parts/course-table', 'sort'); ?>

                <div class="small-12 medium-12 cell ipa-courses-table-widget-cell">
                    <ul class="accordion ipa-accordion-widget" data-accordion data-allow-all-closed="true">
						<?php foreach ($courses as $title => $ids) : ?>
                            <li class="accordion-item ipa-accordion-item" data-accordion-item id="course-<?= strtolower($title); ?>">
                                <a href="#<?= strtolower($title); ?>" class="accordion-title ipa-accordion-title text-color-black">
                                    <b><?= $title; ?></b>
                                </a>
                                <div class="accordion-content ipa-accordion-content" data-tab-content id="<?= strtolower($title); ?>">
                                    <ul class="accordion ipa-accordion-widget course-wrapper"
                                        data-accordion data-allow-all-closed="true">
		                                <?php
		                                foreach ($ids as $id) :
			                                $eventId = get_field('eventid', $id);
			                                $eventCode = get_field('code', $id);
			                                $templateCode = get_field('code', $id);

			                                $parentClasses = [
				                                $eventId,
				                                $eventCode,
				                                $templateCode,
				                                "accordion-item",
				                                "ipa-accordion-item",
				                                "mix-parent"
			                                ];

			                                if (have_rows("presenters", $id)) :
				                                while (have_rows("presenters", $id)) : the_row();
					                                $parentClasses[] = acf_slugify(get_sub_field('name'));
				                                endwhile;
			                                endif;
			                                ?>
                                            <li class="<?= implode(" ", $parentClasses); ?>" data-accordion-item
                                                id="<?= $eventId; ?>">
                                                <a href="#"
                                                   class="accordion-title ipa-accordion-title text-color-black">
                                                    <div class="grid-x align-middle" style="padding-right: 50px;">
                                                        <div class="auto cell">
                                                            <span class="course-table-name" style="display: block; margin-bottom: 8px;">
                                                                <b><?= (empty($category)) ? get_the_title($id) : $category; ?></b>
                                                            </span>
                                                            <span class="course-table-date text-color-dark-gray">
                                                                <i class="fal fa-clock"></i>
                                                                <?php
                                                                get_template_part(
	                                                                'parts/arlo/events/loop-event',
	                                                                'datetime',
	                                                                array(
		                                                                'post' => $id
	                                                                )
                                                                );
                                                                ?>
                                                            </span>
                                                        </div>
                                                        <div class="shrink cell show-for-medium">
							                                <?php
                                                            if (have_rows('presenters', $id)) :
                                                                get_template_part(
									                                'parts/arlo/events/loop',
									                                'presenters',
									                                array(
										                                'post' => $id,
										                                "disable_link" => true
									                                )
								                                );
                                                            endif; ?>
                                                        </div>
                                                    </div>
                                                </a>
                                                <div class="accordion-content ipa-accordion-content" data-tab-content
                                                     id="<?= $eventId; ?>">
	                                                <?php
	                                                if (have_rows('sessions', $id)) :
		                                                get_template_part(
			                                                'parts/arlo/events/event',
			                                                'table',
			                                                array(
				                                                'post' => $id
			                                                )
		                                                );
	                                                endif;
                                                    ?>

                                                    <div class="grid-x grid-padding-x align-middle">
                                                        <div class="auto cell">
	                                                        <?php
	                                                        if (have_rows('presenters', $id)) :
		                                                        get_template_part(
			                                                        'parts/arlo/events/loop',
			                                                        'presenters',
			                                                        array(
				                                                        'post' => $id
			                                                        )
		                                                        );
	                                                        endif; ?>
                                                        </div>
                                                        <div class="auto cell">
	                                                        <?php
	                                                        get_template_part(
		                                                        'parts/arlo/events/event',
		                                                        'register',
		                                                        array(
			                                                        'post' => $id
		                                                        )
	                                                        );
	                                                        ?>
                                                        </div>
                                                    </div>
                                                </div>
                                            </li>
		                                <?php endforeach; ?>
                                    </ul>
                                </div>
                            </li>
						<?php endforeach; ?>
                    </ul>
                </div>
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
			)
		));
	} catch (Exception $e) {
		echo 'Caught exception: ', $e->getMessage(), "\n";
	}
}

add_action('vc_before_init', 'ipa_courses_table_integrateWithVC');
