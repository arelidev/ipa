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

	ob_start();

	$category = $atts['course_cat'];
	$delivery_method = $atts['delivery_method'];
	$courses = get_sorted_courses($atts['limit'], $category, $delivery_method);

	$instructors = [];
	foreach ($courses as $option_course) {
		foreach ($option_course as $option) {
			array_push($instructors, $option['instructor1']);
		}
	}

	$instructors = array_unique($instructors);

	uasort($instructors, 'lastNameSort');


	$args = array(
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
	?>
    <div class="courses-filter-parent">
        <div class="courses-filter-container">

			<?php if ($atts['disable_filters'] == false) : ?>
                <div> <!-- data-sticky-container -->
                    <div class="search-bar styled-container courses-table-widget-filters">
                        <!-- data-sticky data-margin-top="0" data-anchor="ipa-courses-widget" -->
                        <div class="grid-container">
                            <div class="grid-x grid-padding-x grid-padding-y align-middle">
                                <div class="cell auto show-for-medium">
                                    <b>Filter by:</b>
                                </div>
                                <div class="cell small-12 medium-auto">
                                    <label>
                                        <span class="hide-for-medium"><?= __('Course Type', 'ipa'); ?></span>
                                        <select class="course-filter-type">
                                            <option value="all">Course Type</option>
											<?php foreach ($courses as $title => $course_details) : ?>
                                                <option value="<?= $title; ?>"><?= $title; ?></option>
											<?php endforeach; ?>
                                        </select>
                                    </label>
                                </div>
                                <div class="cell small-12 medium-auto">
                                    <label>
                                        <span class="hide-for-medium"><?= __('Select Faculty', 'ipa'); ?></span>
                                        <select class="course-filter-select">
                                            <option value="all">State</option>
                                            <option value="all">All</option>
                                            <option value=".AL">Alabama</option>
                                            <option value=".AK">Alaska</option>
                                            <option value=".AZ">Arizona</option>
                                            <option value=".AR">Arkansas</option>
                                            <option value=".CA">California</option>
                                            <option value=".CO">Colorado</option>
                                            <option value=".CT">Connecticut</option>
                                            <option value=".DE">Delaware</option>
                                            <option value=".DC">District Of Columbia</option>
                                            <option value=".FL">Florida</option>
                                            <option value=".GA">Georgia</option>
                                            <option value=".HI">Hawaii</option>
                                            <option value=".ID">Idaho</option>
                                            <option value=".IL">Illinois</option>
                                            <option value=".IN">Indiana</option>
                                            <option value=".IA">Iowa</option>
                                            <option value=".KS">Kansas</option>
                                            <option value=".KY">Kentucky</option>
                                            <option value=".LA">Louisiana</option>
                                            <option value=".ME">Maine</option>
                                            <option value=".MD">Maryland</option>
                                            <option value=".MA">Massachusetts</option>
                                            <option value=".MI">Michigan</option>
                                            <option value=".MN">Minnesota</option>
                                            <option value=".MS">Mississippi</option>
                                            <option value=".MO">Missouri</option>
                                            <option value=".MT">Montana</option>
                                            <option value=".NE">Nebraska</option>
                                            <option value=".NV">Nevada</option>
                                            <option value=".NH">New Hampshire</option>
                                            <option value=".NJ">New Jersey</option>
                                            <option value=".NM">New Mexico</option>
                                            <option value=".NY">New York</option>
                                            <option value=".NC">North Carolina</option>
                                            <option value=".ND">North Dakota</option>
                                            <option value=".OH">Ohio</option>
                                            <option value=".OK">Oklahoma</option>
                                            <option value=".OR">Oregon</option>
                                            <option value=".PA">Pennsylvania</option>
                                            <option value=".RI">Rhode Island</option>
                                            <option value=".SC">South Carolina</option>
                                            <option value=".SD">South Dakota</option>
                                            <option value=".TN">Tennessee</option>
                                            <option value=".TX">Texas</option>
                                            <option value=".UT">Utah</option>
                                            <option value=".VT">Vermont</option>
                                            <option value=".VA">Virginia</option>
                                            <option value=".WA">Washington</option>
                                            <option value=".WV">West Virginia</option>
                                            <option value=".WI">Wisconsin</option>
                                            <option value=".WY">Wyoming</option>
                                        </select>
                                    </label>
                                </div>
                                <div class="cell small-12 medium-auto">
                                    <label>
                                        <span class="hide-for-medium"><?= __('Select Region', 'ipa'); ?></span>
                                        <select class="course-filter-region">
                                            <option value="all">Region</option>
                                            <option value="all">All</option>
                                            <option value="midatlantic">Mid-Atlantic</option>
                                            <option value="midwest">Mid-West</option>
                                            <option value="northeast">Northeast</option>
                                            <option value="northwest">Northwest</option>
                                            <option value="southwest">Southwest</option>
                                            <option value="southeast">Southeast</option>
                                            <option value="-west">West</option>
                                        </select>
                                    </label>
                                </div>
                                <div class="cell small-12 medium-auto">
                                    <label>
                                        <span class="hide-for-medium"><?= __('Search by instructor', 'ipa'); ?></span>
                                        <select class="clinics-filter-certification" id="course-filter-instructor">
                                            <option value="all">Primary Instructor</option>
											<?php foreach ($instructors as $option): ?>
                                                <option value="<?= $option ?>"><?= $option ?></option>
											<?php endforeach; ?>
                                        </select>
                                        <!-- <input type="text" placeholder="Search by instructor" id="course-filter-instructor"> -->
                                    </label>
                                </div>
                                <div class="cell small-12 medium-auto">
                                    <label>
                                        <span class="hide-for-medium"><?= __('Start Date', 'ipa'); ?></span>
                                        <input type="text" placeholder="Start Date" id="course-filter-date">
                                    </label>
                                </div>
                                <div class="cell small-12 hide-for-medium">
                                    <label>
                                        <span class="hide-for-medium"><?= __('Instructor Status', 'ipa'); ?></span>
                                        <select class="filter-select">
                                            <option value="all">All</option>
                                            <option value=".instructor-status-1"><?= __('Primary Instructor', 'ipa'); ?></option>
                                            <option value=".instructor-status-2"><?= __('Associate Instructor', 'ipa'); ?></option>
                                        </select>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
			<?php endif; ?>

            <div class="grid-x align-center" id="ipa-courses-widget">
                <div class="small-12 medium-12 cell">
                    <p class="text-left medium-text-right">
                        <a type="button" class="expand"><?= __('Show All', 'ipa'); ?></a>
                        /
                        <a type="button" class="collapse"><?= __('Hide All', 'ipa'); ?></a>
                    </p>
                </div>
                <div class="small-12 medium-12 cell" id="courses-table-widget-cell">
                    <div class="courses-table-widget">
                        <ul class="accordion ipa-accordion-widget course-wrapper"
                            data-accordion data-allow-all-closed="true" data-deep-link="true"
                            data-deep-link-smudge="true">
							<?php
							while ($loop->have_posts()) : $loop->the_post();
								$eventId = get_field('eventid');
								$eventCode = get_field('code'); ?>
                                <li class="accordion-item ipa-accordion-item mix-parent" data-accordion-item
                                    id="<?= $eventId; ?>">
                                    <a href="#" class="accordion-title ipa-accordion-title text-color-black">
										<?= (empty($category)) ? get_the_title() : $category; ?>
                                    </a>

                                    <div class="accordion-content ipa-accordion-content" data-tab-content
                                         id="<?= $eventId; ?>">
										<?php if (have_rows('sessions')) : ?>
                                            <?php get_template_part('parts/arlo/events/event', 'table'); ?>
										<?php endif; ?>
                                    </div>
                                </li>
							<?php endwhile; ?>
                        </ul>
                    </div>
                </div>
            </div>

            <script src="https://cdn.jsdelivr.net/npm/litepicker/dist/js/main.js"></script>
            <script src="https://cdn.jsdelivr.net/npm/litepicker-module-ranges/dist/index.js"></script>
        </div>
    </div>
	<?php
	wp_reset_postdata();
	return ob_get_clean();
}

add_shortcode('ipa_courses_table', 'ipa_courses_table_widget');

// Integrate with Visual Composer
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

function lastNameSort($a, $b)
{
	$array = explode(' ', $a);
	$aLast = end($array);
	$array1 = explode(' ', $b);
	$bLast = end($array1);

	return strcasecmp($aLast, $bLast);
}

function get_region_by_state($state)
{
	$regions = array(
		'midatlantic' => 'PA MD DC VA WV',
		'midwest' => 'ND SD NE IA MN WI MI IL IN OH KS MO AR',
		'northeast' => 'CT ME MA NH NJ NY PA RI VT DE',
		'northwest' => 'MT WY ID OR WA AK',
		'southwest' => 'TX AR LA OK NM',
		'southeast' => 'KY TN NC SC MS GA AL FL',
		'-west' => 'AZ CA NV HI CO UT',
	);
	foreach ($regions as $key => $value) {
		if (strpos($value, $state) !== false) {
			return $key;
		}
	}

	return false;
}
