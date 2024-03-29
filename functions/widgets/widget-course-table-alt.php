<?php
/**
 * Course Table Alt Widget
 *
 * @param $atts
 *
 * @return false|string
 */
function ipa_courses_table_alt_widget( $atts ) {
	$atts = shortcode_atts( array(
		'limit'           => null,
		'course_cat'      => null,
		'delivery_method' => 1 // 1: In-person, 2: Virtual
	), $atts );

	ob_start();

	$category        = $atts['course_cat'];
	$delivery_method = $atts['delivery_method'];
	$courses         = false;
	?>
	<?php if ( ! empty( $courses ) ) : ?>
        <h4 class="course-table-title">
            <b>
		        <?php
		        if ( $delivery_method == 2 ) :
			        _e( 'Virtual Course Dates', 'ipa' );
		        else :
			        _e( 'Locations & Dates', 'ipa' );
		        endif;
		        ?>
            </b>
        </h4>
        <div class="courses-table-widget">
            <div class="course-wrapper">
                <table class="course-table hover stack">
                    <thead>
                    <tr>
						<?php if ( $delivery_method == 1 ) : ?>
                            <th><?= __( 'Location', 'ipa' ); ?></th>
						<?php elseif ( $delivery_method == 2 ) : ?>
                            <th><?= __( 'Course', 'ipa' ); ?></th>
						<?php endif; ?>
                        <th><?= __( 'Date', 'ipa' ); ?></th>
                        <th><?= __( 'Scheduled Instructor(s)', 'ipa' ); ?></th>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody>
					<?php foreach ( $courses as $title => $course_details ) : ?>
						<?php foreach ( $course_details as $course_detail ) : ?>
                            <tr>
								<?php if ( $delivery_method == 1 ) : ?>
                                    <td class="course-table-location">
                                        <span class="hide-for-medium"><b><?= __( 'Location', 'ipa' ); ?>:</b></span> <?= $course_detail['city']; ?>, <?= $course_detail['state']; ?>
                                    </td>
								<?php elseif ( $delivery_method == 2 ) : ?>
                                    <th><?= $course_detail['course_type_name']; ?></th>
								<?php endif; ?>
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
                                        <a href="<?= home_url(); ?>/profile/<?= clean( $instructor_1 ); ?>/<?= $course_detail['instr1']; ?>">
                                            <img src="<?= get_instructor_image( $course_detail['image1'] ); ?>"
                                                 class="course-card-trainer"
                                                 alt="<?= $instructor_1; ?>"
                                                 data-tooltip tabindex="1"
                                                 title="<?= $instructor_1; ?>">
                                        </a>
									<?php endif; ?>
									<?php if ( ! empty( $instructor_2 = $course_detail['instructor2'] ) ) : ?>
                                        <a href="<?= home_url(); ?>/profile/<?= clean( $instructor_2 ); ?>/<?= $course_detail['instr2']; ?>">
                                            <img src="<?= get_instructor_image( $course_detail['image2'] ); ?>"
                                                 class="course-card-trainer"
                                                 alt="<?= $instructor_2; ?>"
                                                 data-tooltip tabindex="2"
                                                 title="<?= $instructor_2; ?>">
                                        </a>
									<?php endif; ?>
									<?php if ( ! empty( $instructor_3 = $course_detail['instructor3'] ) ) : ?>
                                        <a href="<?= home_url(); ?>/profile/<?= clean( $instructor_3 ); ?>/<?= $course_detail['instr3']; ?>">
                                            <img src="<?= get_instructor_image( $course_detail['image3'] ); ?>"
                                                 class="course-card-trainer"
                                                 alt="<?= $instructor_3; ?>"
                                                 data-tooltip tabindex="3"
                                                 title="<?= $instructor_3; ?>">
                                        </a>
									<?php endif; ?>
									<?php if ( ! empty( $instructor_4 = $course_detail['instructor4'] ) ) : ?>
                                        <a href="<?= home_url(); ?>/profile/<?= clean( $instructor_4 ); ?>/<?= $course_detail['instr4']; ?>">
                                            <img src="<?= get_instructor_image( $course_detail['image4'] ); ?>"
                                                 class="course-card-trainer"
                                                 alt="<?= $instructor_4; ?>"
                                                 data-tooltip tabindex="4"
                                                 title="<?= $instructor_4; ?>">
                                        </a>
									<?php endif; ?>
                                </td>
                                <td class="course-table-apply">
									<?php get_course_link( $course_detail['request_path'], $course_detail['visibility'], 'button' ); ?>
                                </td>
                            </tr>
						<?php endforeach; ?>
					<?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
	<?php else : ?>
        <div class="callout primary">
			<?= __( 'Currently no courses scheduled - Check back later', 'ipa' ); ?>
        </div>
	<?php endif; ?>
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
