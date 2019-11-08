<?php
function ipa_upcoming_courses_widget( $atts, $content = null ) {
	$atts = shortcode_atts( array(), $atts );
	ob_start();

	$courses = get_courses( 6 );
	?>
    <div class="upcoming-courses-widget grid-x grid-margin-x grid-margin-y" data-equalizer="upcoming-courses-title"
         data-equalize-by-row="true">
		<?php foreach ( $courses as $title => $course_details ) : ?>
            <div class="course-card cell small-12 medium-6 large-4">
                <div class="course-card-image-wrapper">
                    <img src="<?= get_template_directory_uri(); ?>/assets/images/course-placeholder.jpg"
                         class="course-card-image">
                </div>
                <div class="course-card-inner">
                    <div class="course-card-trainer-wrapper">
                        <img src="<?= get_template_directory_uri(); ?>/assets/images/trainer-placeholder.jpg"
                             class="course-card-trainer">
                    </div>
                    <h3 class="course-card-price">
                        <span class="og-price">$772</span> <span class="sales-price">$750</span>
                    </h3>
                    <p class="course-card-title" data-equalizer-watch="upcoming-courses-title">
						<?= $course_details['name']; ?>
                    </p>
                    <p class="course-card-category">
                        <small><?= $course_details['course_type_name']; ?></small>
                    </p>
                    <hr>
                    <div class="grid-x grid-padding-x">
                        <div class="small-12 medium-12 large-auto cell">
                            <p class="course-card-date">
                                <small>
                                    <i class="fal fa-clock"></i>
									<?= date( get_option( 'date_format' ), strtotime( $course_details['date'] ) ); ?>
                                </small>
                            </p>
                        </div>
                        <div class="small-12 medium-12 large-shrink cell">
                            <p class="course-card-location">
                                <small>
                                    <i class="fal fa-map-marker-alt"></i>
									<?= $course_details['city']; ?>, <?= $course_details['state']; ?>
                                </small>
                            </p>
                        </div>
                    </div>
                    <hr>
                    <div class="grid-x grid-padding-x align-middle">
                        <div class="auto cell">
                            <a href="<?= stage_url( $course_details['request_path'] ); ?>"
                               class="button course-card-learn-more"><?= __( 'Learn More', 'ipa' ); ?></a>
                        </div>
                        <div class="shrink cell">
                            <small>
                                <a href="<?= stage_url( $course_details['request_path'] ); ?>"
                                   class="course-card-apply-now"><?= __( 'Apply Now', 'ipa' ); ?></a>
                            </small>
                        </div>
                    </div>
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