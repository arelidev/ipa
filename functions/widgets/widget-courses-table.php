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
		'limit'      => null,
		'course_cat' => ''
	), $atts );
	ob_start();

	$category = $atts['course_cat'];

	$courses = get_sorted_courses( $atts['limit'], $category );
	?>
    <div class="courses-table-widget">
		<?php foreach ( $courses as $title => $course_details ) : ?>
            <div class="course-wrapper">
                <h3><strong>
                        <?= ( empty( $category ) ) ? __( $title, 'ipa' ) : __( "{$category} Courses", 'ipa' ); ?>
                    </strong></h3>
                <table class="course-table hover"> <!-- .datatable -->
                    <thead>
                    <tr>
                        <th>Course</th>
                        <th>Location</th>
                        <th>Date</th>
                        <th>Instructor</th>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody>
					<?php foreach ( $course_details as $course_detail ) : ?>
                        <tr>
                            <td class="course-table-title no-sort">
                                <a href="<?= stage_url( $course_detail['request_path'] ); ?>"><?= $course_detail['name']; ?></a>
                            </td>
                            <td class="course-table-location">
								<?= $course_detail['city']; ?>, <?= $course_detail['state']; ?>
                            </td>
                            <td class="course-table-date"
                                data-order="<?= date( 'u', strtotime( $course_detail['date'] ) ); ?>">
								<?= date( get_option( 'date_format' ), strtotime( $course_detail['date'] ) ); ?>
                            </td>
                            <td class="course-table-instructor">
                                <img src="<?= get_template_directory_uri(); ?>/assets/images/trainer-placeholder.jpg"
                                     data-tooltip tabindex="1" title="Name" data-position="bottom"
                                     data-alignment="center">
                            </td>
                            <td class="course-table-apply">
                                <a href="<?= stage_url( $course_detail['request_path'] ); ?>">Apply Now</a>
                            </td>
                        </tr>
					<?php endforeach; ?>
                    </tbody>
                </table>
            </div>
		<?php endforeach; ?>
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
					"heading"     => __( "Course Category name", "my-text-domain" ),
					"param_name"  => "course_cat",
					"description" => __( "This name has to be exact to Magento's database fields.", "my-text-domain" )
				),
				array(
					"type"        => "textfield",
					"heading"     => __( "Limit", "my-text-domain" ),
					"param_name"  => "limit",
					"description" => __( "", "my-text-domain" )
				),
			)
		) );
	} catch ( Exception $e ) {
		echo 'Caught exception: ', $e->getMessage(), "\n";
	}
}

add_action( 'vc_before_init', 'ipa_courses_table_integrateWithVC' );
