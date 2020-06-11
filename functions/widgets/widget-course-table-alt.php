<?php
/**
 * Course Table Alt Widget
 *
 * @param $atts
 * @param null $content
 *
 * @return false|string
 */
function ipa_courses_table_alt_widget( $atts, $content = null ) {
	$atts = shortcode_atts( array(
		'limit'           => null,
		'course_cat'      => '',
		'disable_sidebar' => false
	), $atts );
	ob_start();

	$category = $atts['course_cat'];

	$courses = get_sorted_courses( $atts['limit'], $category );
	?>
	<div class="courses-table-widget">
		<div class="course-wrapper">
			<?php foreach ( $courses as $title => $course_details ) : $slug = acf_slugify( $title ); ?>
				<h4 class="course-table-title"><b><?= __( 'Locations & Dates', 'ipa' ); ?></b></h4>
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
                        <tr>
                            <td class="course-table-location no-sort">
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
                                    <img src="<?= get_instructor_image(); ?>" class="course-card-trainer" alt="<?= $instructor_1; ?>" data-tooltip tabindex="2" title="<?= $instructor_1; ?>">
								<?php endif; ?>
								<?php if ( ! empty( $instructor_2 = $course_detail['instructor2'] ) ) : ?>
                                    <img src="<?= get_instructor_image(); ?>" class="course-card-trainer" alt="<?= $instructor_2; ?>" data-tooltip tabindex="2" title="<?= $instructor_2; ?>">
								<?php endif; ?>
								<?php if ( ! empty( $instructor_3 = $course_detail['instructor3'] ) ) : ?>
                                    <img src="<?= get_instructor_image(); ?>" class="course-card-trainer" alt="<?= $instructor_3; ?>" data-tooltip tabindex="2" title="<?= $instructor_3; ?>">
								<?php endif; ?>
								<?php if ( ! empty( $instructor_4 = $course_detail['instructor4'] ) ) : ?>
                                    <img src="<?= get_instructor_image(); ?>" class="course-card-trainer" alt="<?= $instructor_4; ?>" data-tooltip tabindex="2" title="<?= $instructor_4; ?>">
								<?php endif; ?>
                            </td>
                            <td class="course-table-apply">
                                <a href="<?= stage_url( $course_detail['request_path'] ); ?>"><?= __( 'Enroll / More Info', 'ipa' ); ?></a>
                            </td>
                        </tr>
					<?php endforeach; ?>
                    </tbody>
                </table>
			<?php endforeach; ?>
		</div>
	</div>
	<?php
	return ob_get_clean();
}

add_shortcode( 'ipa_courses_table_alt', 'ipa_courses_table_alt_widget' );

// Integrate with Visual Composer
function ipa_courses_table_alt_integrateWithVC() {
	try {
		vc_map( array(
			"name"     => __( "Courses Table Alt", "ipa" ),
			"base"     => "ipa_courses_table_alt",
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

add_action( 'vc_before_init', 'ipa_courses_table_alt_integrateWithVC' );
