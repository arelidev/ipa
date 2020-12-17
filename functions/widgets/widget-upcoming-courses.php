<?php
function ipa_upcoming_courses_widget( $atts, $content = null ) {
	$atts = shortcode_atts( array(), $atts );
	ob_start();

	$courses = get_courses( 3, null, true );

	function date_compare( $element1, $element2 ) {
		$datetime1 = strtotime( $element1['date'] );
		$datetime2 = strtotime( $element2['date'] );

		return $datetime1 - $datetime2;
	}

	usort( $courses, 'date_compare' );

	$default_course_images = get_field( 'default_course_category_images', 'options' );
	?>
    <div class="upcoming-courses-widget grid-x grid-margin-x grid-margin-y" data-equalizer="upcoming-courses-title"
         data-equalize-by-row="true">
		<?php foreach ( $courses as $title => $course_details ) : ?>
			<?php
			$course_type_name_id = searchForId( $course_details['course_type_name'], $default_course_images );
			?>
            <div class="course-card cell small-12 medium-6 large-4">
				<?php // if ( ! empty( $course_type_name_id ) ) :
					$image_id = $default_course_images[ $course_type_name_id ]['image']; ?>
                    <div class="course-card-image-wrapper">
						<?= wp_get_attachment_image( $image_id, 'full', false, array( 'class' => 'course-card-image' ) ); ?>
                    </div>
				<?php // endif; ?>
                <div class="course-card-inner">
                    <h4 class="course-card-title" data-equalizer-watch="upcoming-courses-title">
						<?= $course_details['name']; ?>
                    </h4>

                    <div class="grid-x">
                        <div class="small-12 medium-12 large-shrink cell">
                            <div class="course-card-trainer-wrapper">
								<?php if ( ! empty( $instructor_1 = $course_details['instructor1'] ) ) : ?>
                                    <a href="<?= home_url(); ?>/faculty/<?= clean( $instructor_1 ); ?>/<?= $course_details['instr1']; ?>">
                                        <img src="<?= get_instructor_image( $course_details['image1'] ); ?>"
                                             class="course-card-trainer"
                                             alt="<?= $instructor_1; ?>"
                                             data-tooltip tabindex="1"
                                             title="<?= $instructor_1; ?>">
                                    </a>
								<?php endif; ?>
								<?php if ( ! empty( $instructor_2 = $course_details['instructor2'] ) ) : ?>
                                    <a href="<?= home_url(); ?>/faculty/<?= clean( $instructor_2 ); ?>/<?= $course_details['instr2']; ?>">
                                        <img src="<?= get_instructor_image( $course_details['image2'] ); ?>"
                                             class="course-card-trainer"
                                             alt="<?= $instructor_2; ?>"
                                             data-tooltip tabindex="2"
                                             title="<?= $instructor_2; ?>">
                                    </a>
								<?php endif; ?>
								<?php if ( ! empty( $instructor_3 = $course_details['instructor3'] ) ) : ?>
                                    <a href="<?= home_url(); ?>/faculty/<?= clean( $instructor_3 ); ?>/<?= $course_details['instr3']; ?>">
                                        <img src="<?= get_instructor_image( $course_details['image3'] ); ?>"
                                             class="course-card-trainer"
                                             alt="<?= $instructor_3; ?>"
                                             data-tooltip tabindex="3"
                                             title="<?= $instructor_3; ?>">
                                    </a>
								<?php endif; ?>
								<?php if ( ! empty( $instructor_4 = $course_details['instructor4'] ) ) : ?>
                                    <a href="<?= home_url(); ?>/faculty/<?= clean( $instructor_4 ); ?>/<?= $course_details['instr4']; ?>">
                                        <img src="<?= get_instructor_image( $course_details['image4'] ); ?>"
                                             class="course-card-trainer"
                                             alt="<?= $instructor_4; ?>"
                                             data-tooltip tabindex="4"
                                             title="<?= $instructor_4; ?>">
                                    </a>
								<?php endif; ?>
                            </div>
                        </div>
                    </div>

                    <hr>

                    <p class="course-card-date">
                        <i class="fal fa-clock"></i>
						<?= date( get_option( 'date_format' ), strtotime( $course_details['date'] ) ); ?>
                        -
						<?= date( get_option( 'date_format' ), strtotime( $course_details['end_date'] ) ); ?>
                    </p>

                    <p class="course-card-location">
                        <i class="fal fa-map-marker-alt"></i>
						<?= $course_details['facility_name']; ?>, <?= $course_details['city']; ?>
                        , <?= $course_details['state']; ?>
                    </p>

                    <hr>

                    <p class="text-center course-card-learn-more">
						<?php get_course_link( $course_details['request_path'], $course_details['visibility'], 'button' ); ?>
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
