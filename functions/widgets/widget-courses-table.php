<?php
/**
 * Course Table Widget
 *
 * @param $atts
 * @param null $content
 *
 * @return false|string
 */
function ipa_courses_table_widget( $atts, $content = null ) {
	$atts = shortcode_atts( array(
		'limit'           => null,
		'course_cat'      => '',
		'disable_sidebar' => false
	), $atts );
	ob_start();

	$category = $atts['course_cat'];

	$courses = get_sorted_courses( $atts['limit'], $category );
	// Course Type
	// Primary Instructor
	// State
	// Date
	?>
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
                                <select class="scroll-to">
                                    <option value="all">Course Type</option>
	                                <?php foreach ( $courses as $title => $course_details ) : ?>
                                        <option value="#<?= $title; ?>"><?= $title; ?></option>
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
                                <span class="hide-for-medium"><?= __( 'Search by instructor', 'ipa' ); ?></span>
                                <input type="text" placeholder="Search by instructor" id="course-filter-instructor">
                            </label>
                        </div>
                        <div class="cell small-12 medium-auto">
                            <label>
                                <span class="hide-for-medium"><?= __( 'Start Date', 'ipa' ); ?></span>
                                <input type="text" placeholder="Start Date" id="course-filter-date">
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
                            <li class="accordion-item ipa-accordion-item" data-accordion-item id="<?= $title; ?>">
                                <a href="#" class="accordion-title ipa-accordion-title text-color-black">
									<?= ( empty( $category ) ) ? $title : $category; ?>
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
                                                data-primary-instructor="<?= $instructor_1; ?>"
                                                data-start-date="<?= date( 'm-d-y', strtotime( $course_detail['date'] ) ) ;?>">
                                                <td class="course-table-location">
                                                    <span class="hide-for-medium"><b><?= __( 'Location', 'ipa' ); ?>:</b></span> <?= $course_detail['city']; ?>, <?= $course_detail['state']; ?>
                                                </td>
                                                <td class="course-table-date" data-order="<?= date( 'u', strtotime( $course_detail['date'] ) ); ?>">
                                                    <span class="hide-for-medium"><b><?= __( 'Date', 'ipa' ); ?>:</b></span>
													<?= date( get_option( 'date_format' ), strtotime( $course_detail['date'] ) ); ?>
                                                    -
													<?= date( get_option( 'date_format' ), strtotime( $course_details['end_date'] ) ); ?>
                                                </td>
                                                <td class="course-table-instructor">
                                                    <span class="hide-for-medium"><b><?= __( 'Scheduled Instructor(s)', 'ipa' ); ?>:</b></span>
													<?php if ( ! empty( $instructor_1 = $course_detail['instructor1'] ) ) : ?>
                                                        <img src="https://api.adorable.io/avatars/100/<?= $instructor_1; ?>.png" class="course-card-trainer" alt="<?= $instructor_1; ?>" data-tooltip tabindex="2" title="<?= $instructor_1; ?>">
													<?php endif; ?>
													<?php if ( ! empty( $instructor_2 = $course_detail['instructor2'] ) ) : ?>
                                                        <img src="https://api.adorable.io/avatars/100/<?= $instructor_2; ?>.png" class="course-card-trainer" alt="<?= $instructor_2; ?>" data-tooltip tabindex="2" title="<?= $instructor_2; ?>">
													<?php endif; ?>
													<?php if ( ! empty( $instructor_3 = $course_detail['instructor3'] ) ) : ?>
                                                        <img src="https://api.adorable.io/avatars/100/<?= $instructor_3; ?>.png" class="course-card-trainer" alt="<?= $instructor_3; ?>" data-tooltip tabindex="2" title="<?= $instructor_3; ?>">
													<?php endif; ?>
													<?php if ( ! empty( $instructor_4 = $course_detail['instructor4'] ) ) : ?>
                                                        <img src="https://api.adorable.io/avatars/100/<?= $instructor_4; ?>.png" class="course-card-trainer" alt="<?= $instructor_4; ?>" data-tooltip tabindex="2" title="<?= $instructor_4; ?>">
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
