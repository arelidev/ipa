<?php
/**
 * Course Table Widget
 *
 * @param $atts
 * @param null $content
 *
 * @return false|string
 */

function lastNameSort( $a, $b ) {
	$array  = explode( ' ', $a );
	$aLast  = end( $array );
	$array1 = explode( ' ', $b );
	$bLast  = end( $array1 );

	return strcasecmp( $aLast, $bLast );
}

function get_region_by_state( $state ) {
	$regions = array(
		'midatlantic' => 'PA MD DC VA WV',
		'midwest'     => 'ND SD NE IA MN WI MI IL IN OH KS MO AR',
		'northeast'   => 'CT ME MA NH NJ NY PA RI VT DE',
		'northwest'   => 'MT WY ID OR WA AK',
		'southwest'   => 'TX AR LA OK NM',
		'southeast'   => 'KY TN NC SC MS GA AL FL',
		'-west'       => 'AZ CA NV HI CO UT',
	);
	foreach ( $regions as $key => $value ) {
		if ( strpos( $value, $state ) !== false ) {
			return $key;
		}
	}

	return false;
}

function ipa_courses_table_widget( $atts, $content = null ) {
	$atts = shortcode_atts( array(
		'limit'           => null,
		'course_cat'      => '',
		'disable_sidebar' => false
	), $atts );
	ob_start();

	$category = $atts['course_cat'];

	$courses = get_sorted_courses( $atts['limit'], $category );

	$instructors = [];
	foreach ( $courses as $option_course ) {
		foreach ( $option_course as $option ) {
			array_push( $instructors, $option['instructor1'] );
		}
	}

	$instructors = array_unique( $instructors );

	uasort( $instructors, 'lastNameSort' );

	// Course Type
	// Primary Instructor
	// State
	// Date
	?>
    <div class="courses-filter-parent">
        <div class="courses-filter-container">


            <div data-sticky-container>
                <div class="search-bar styled-container" data-sticky data-margin-top="0" data-anchor="ipa-courses-widget">
                    <div class="grid-container">
                        <div class="grid-x grid-padding-x grid-padding-y align-middle">
                            <div class="cell auto show-for-medium">
                                <b>Filter by:</b>
                            </div>
                            <div class="cell small-12 medium-auto">
                                <label>
                                    <span class="hide-for-medium"><?= __( 'Course Type', 'ipa' ); ?></span>
                                    <select class="course-filter-type">
                                        <option value="all">Course Type</option>
                                        <?php foreach ( $courses as $title => $course_details ) : ?>
                                            <option value="<?= $title; ?>"><?= $title; ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </label>
                            </div>
                            <div class="cell small-12 medium-auto">
                                <label>
                                    <span class="hide-for-medium"><?= __( 'Select Faculty', 'ipa' ); ?></span>
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
                                    <span class="hide-for-medium"><?= __( 'Select Region', 'ipa' ); ?></span>
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
                                    <span class="hide-for-medium"><?= __( 'Search by instructor', 'ipa' ); ?></span>
                                    <select class="clinics-filter-certification" id="course-filter-instructor">
                                        <option value="all">Primary Instructor</option>
                                        <?php foreach ( $instructors as $option ): ?>
                                            <option value="<?= $option ?>"><?= $option ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                    <!-- <input type="text" placeholder="Search by instructor" id="course-filter-instructor"> -->
                                </label>
                            </div>
                            <div class="cell small-12 medium-auto">
                                <label>
                                    <span class="hide-for-medium"><?= __( 'Start Date', 'ipa' ); ?></span>
                                    <select id="course-filter-date">
                                        <option value="all">Start Date</option>
                                        <option value="all">Any Date</option>
                                        <option value="1">This Week</option>
                                        <option value="2">This Month</option>
                                        <option value="3">Next 60 Days</option>
                                        <option value="4">Next 90 Days</option>
                                    </select>
                                </label>
                            </div>
                            <div class="cell small-12 hide-for-medium">
                                <label>
                                    <span class="hide-for-medium"><?= __( 'Instructor Status', 'ipa' ); ?></span>
                                    <select class="filter-select">
                                        <option value="all">All</option>
                                        <option value=".instructor-status-1"><?= __( 'Primary Instructor', 'ipa' ); ?></option>
                                        <option value=".instructor-status-2"><?= __( 'Associate Instructor', 'ipa' ); ?></option>
                                    </select>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid-container grid-x grid-padding-x align-center" id="ipa-courses-widget">
                <div class="small-12 medium-10 cell">
                    <p class="text-left medium-text-right">
                        <a type="button" id="expand"><?= __( 'Show All', 'ipa' ); ?></a>
                        /
                        <a type="button" id="collapse"><?= __( 'Hide All', 'ipa' ); ?></a>
                    </p>
                </div>
                <div class="small-12 medium-10 cell" id="courses-table-widget-cell">
                    <div class="courses-table-widget">
                        <ul class="accordion ipa-accordion-widget course-wrapper"
                            data-accordion data-allow-all-closed="true" data-deep-link="true" data-deep-link-smudge="true">
                            <?php foreach ( $courses as $title => $course_details ) : $slug = acf_slugify( $title ); ?>
                                <li class="accordion-item ipa-accordion-item mix-parent" data-accordion-item id="<?= $title; ?>">
                                    <a href="#" class="accordion-title ipa-accordion-title text-color-black">
                                        <?= ( empty( $category ) ) ? $course_details[0]['name'] : $category; ?>
                                    </a>

                                    <div class="accordion-content ipa-accordion-content" data-tab-content id="<?= $slug; ?>">
                                        <table class="course-table hover stack"> <!-- .datatable -->
                                            <thead>
                                            <tr>
                                                <th><?= __( 'Location', 'ipa' ); ?></th>
                                                <th><?= __( 'Date', 'ipa' ); ?></th>
                                                <th><?= __( 'Scheduled Instructor(s)', 'ipa' ); ?></th>
                                                <th></th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <?php foreach ( $course_details as $course_detail ) : ?>
                                                <?php
                                                $course_classes = array(
                                                    'ipa-single-course',
                                                    'mix',
                                                    $course_detail['course_type_name'],
                                                    $course_detail['state'],
                                                );

                                                $instructor_1 = $course_detail['instructor1'];
                                                ?>
                                                <tr class="<?= implode( " ", $course_classes ) ?>"
                                                    data-state=".<?= $course_detail['state'] ?>"
                                                    data-primary-instructor="<?= $instructor_1; ?>"
                                                    data-course-type="<?= $slug; ?>"
                                                    data-region="<?= get_region_by_state( $course_detail['state'] ) ?>"
                                                    data-start-date="<?= date( 'm-d-y', strtotime( $course_detail['date'] ) ); ?>">
                                                    <td class="course-table-location">
                                                        <span class="hide-for-medium"><b><?= __( 'Location', 'ipa' ); ?>:</b></span> <?= $course_detail['city']; ?>
                                                        , <?= $course_detail['state']; ?>
                                                    </td>
                                                    <td class="course-table-date"
                                                        data-order="<?= date( 'u', strtotime( $course_detail['date'] ) ); ?>">
                                                        <span class="hide-for-medium"><b><?= __( 'Date', 'ipa' ); ?>:</b></span>
                                                        <?= date( get_option( 'date_format' ), strtotime( $course_detail['date'] ) ); ?>
                                                        -
                                                        <?= date( get_option( 'date_format' ), strtotime( $course_detail['end_date'] ) ); ?>
                                                    </td>
                                                    <td class="course-table-instructor">
                                                        <span class="hide-for-medium"><b><?= __( 'Scheduled Instructor(s)', 'ipa' ); ?>:</b></span>
                                                        <?php if ( ! empty( $instructor_1 = $course_detail['instructor1'] ) ) : ?>
                                                            <img src="<?= get_instructor_image( $course_detail['image'] ); ?>"
                                                                class="course-card-trainer" alt="<?= $instructor_1; ?>"
                                                                data-tooltip tabindex="2" title="<?= $instructor_1; ?>">
                                                        <?php endif; ?>
                                                        <?php if ( ! empty( $instructor_2 = $course_detail['instructor2'] ) ) : ?>
                                                            <img src="<?= get_instructor_image(); ?>"
                                                                class="course-card-trainer" alt="<?= $instructor_2; ?>"
                                                                data-tooltip tabindex="2" title="<?= $instructor_2; ?>">
                                                        <?php endif; ?>
                                                        <?php if ( ! empty( $instructor_3 = $course_detail['instructor3'] ) ) : ?>
                                                            <img src="<?= get_instructor_image(); ?>"
                                                                class="course-card-trainer" alt="<?= $instructor_3; ?>"
                                                                data-tooltip tabindex="2" title="<?= $instructor_3; ?>">
                                                        <?php endif; ?>
                                                        <?php if ( ! empty( $instructor_4 = $course_detail['instructor4'] ) ) : ?>
                                                            <img src="<?= get_instructor_image(); ?>"
                                                                class="course-card-trainer" alt="<?= $instructor_4; ?>"
                                                                data-tooltip tabindex="2" title="<?= $instructor_4; ?>">
                                                        <?php endif; ?>
                                                    </td>
                                                    <td class="course-table-apply">
                                                        <a href="<?= stage_url( $course_detail['request_path'] ); ?>"><?= __( 'Enroll / More Info', 'ipa' ); ?></a>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                </div>
            </div>

            <script src="https://cdn.jsdelivr.net/npm/litepicker/dist/js/main.js"></script>
            <script src="https://cdn.jsdelivr.net/npm/litepicker-module-ranges/dist/index.js"></script>

        </div>
    </div>
	<?php
	return ob_get_clean();
}

add_shortcode( 'ipa_courses_table', 'ipa_courses_table_widget' );

// Integrate with Visual Composer
function ipa_courses_table_integrateWithVC() {
	try {
		vc_map( array(
			"name"     => __( "Courses Table", "ipa" ),
			"base"     => "ipa_courses_table",
			"class"    => "",
			"category" => __( "Custom", "ipa" ),
			"params"   => array(
				array(
					"type"        => "textfield",
					"heading"     => __( "Course Category name", "ipa" ),
					"param_name"  => "course_cat",
					"description" => __( "This name has to be exact to Magento's database fields.", "ipa" )
				),
				array(
					"type"        => "textfield",
					"heading"     => __( "Limit", "ipa" ),
					"param_name"  => "limit",
					"description" => __( "", "ipa" )
				),
			)
		) );
	} catch ( Exception $e ) {
		echo 'Caught exception: ', $e->getMessage(), "\n";
	}
}

add_action( 'vc_before_init', 'ipa_courses_table_integrateWithVC' );
