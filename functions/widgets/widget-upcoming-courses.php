<?php
function ipa_upcoming_courses_widget( $atts, $content = null ) {
	$atts = shortcode_atts( array(), $atts );
	ob_start();

	$courses = get_courses( 3, null, true );
	?>
    <div class="upcoming-courses-widget grid-x grid-margin-x grid-margin-y" data-equalizer="upcoming-courses-title" data-equalize-by-row="true">
		<?php foreach ( $courses as $title => $course_details ) : ?>
            <div class="course-card cell small-12 medium-6 large-4">
                <div class="course-card-inner">
                    <h4 class="course-card-title" data-equalizer-watch="upcoming-courses-title">
						<?= $course_details['name']; ?>
                    </h4>

                    <div class="grid-x">
                        <div class="small-12 medium-12 large-auto cell">
                            <p class="course-card-category">
                                <small><?= $course_details['course_type_name']; ?></small>
                            </p>
                        </div>
                        <div class="small-12 medium-12 large-shrink cell">
                            <div class="course-card-trainer-wrapper">
		                        <?php if ( ! empty( $instructor_1 = $course_details['instructor1'] ) ) : ?>
                                    <img src="<?= get_instructor_image(); ?>" class="course-card-trainer" alt="<?= $instructor_1; ?>" data-tooltip tabindex="2" title="<?= $instructor_1; ?>">
		                        <?php endif; ?>
		                        <?php if ( ! empty( $instructor_2 = $course_details['instructor2'] ) ) : ?>
                                    <img src="<?= get_instructor_image(); ?>" class="course-card-trainer" alt="<?= $instructor_2; ?>" data-tooltip tabindex="2" title="<?= $instructor_2; ?>">
		                        <?php endif; ?>
		                        <?php if ( ! empty( $instructor_3 = $course_details['instructor3'] ) ) : ?>
                                    <img src="<?= get_instructor_image(); ?>" class="course-card-trainer" alt="<?= $instructor_3; ?>" data-tooltip tabindex="2" title="<?= $instructor_3; ?>">
		                        <?php endif; ?>
		                        <?php if ( ! empty( $instructor_4 = $course_details['instructor4'] ) ) : ?>
                                    <img src="<?= get_instructor_image(); ?>" class="course-card-trainer" alt="<?= $instructor_4; ?>" data-tooltip tabindex="2" title="<?= $instructor_4; ?>">
		                        <?php endif; ?>
                            </div>
                        </div>
                    </div>

                    <hr>

                    <p class="course-card-date">
                        <small>
                            <i class="fal fa-clock"></i>
                            <?= date( get_option( 'date_format' ), strtotime( $course_details['date'] ) ); ?>
                            -
                            <?= date( get_option( 'date_format' ), strtotime( $course_details['end_date'] ) ); ?>
                        </small>
                    </p>

                    <p class="course-card-location">
                        <small>
                            <i class="fal fa-map-marker-alt"></i>
	                        <?= $course_details['facility_name']; ?>, <?= $course_details['city']; ?>, <?= $course_details['state']; ?>
                        </small>
                    </p>

                    <hr>

                    <p class="text-center course-card-learn-more">
                        <a href="<?= stage_url( $course_details['request_path'] ); ?>" class="button">
		                    <?= __( 'Enroll / More Info', 'ipa' ); ?>
                        </a>
                    </p>
                </div>
            </div>
		<?php endforeach; ?>
    </div>
	<?php
	return ob_get_clean();
}

add_shortcode( 'ipa_upcoming_courses', 'ipa_upcoming_courses_widget' );

// Integrate with Visual Composer
function ipa_upcoming_courses_integrateWithVC() {
	try {
		vc_map( array(
			"name"                    => __( "Upcoming Courses", "ipa" ),
			"base"                    => "ipa_upcoming_courses",
			"class"                   => "",
			"category"                => __( "Custom", "ipa" ),
			"params"                  => array(),
			"show_settings_on_create" => false
		) );
	} catch ( Exception $e ) {
		echo 'Caught exception: ', $e->getMessage(), "\n";
	}
}

add_action( 'vc_before_init', 'ipa_upcoming_courses_integrateWithVC' );
