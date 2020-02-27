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
	?>
    <div class="search-bar styled-container">
        <div class="grid-container">
            <div class="grid-x grid-padding-x grid-padding-y align-middle">
                <div class="cell auto">
                    <b>Filter by:</b>
                </div>
                <div class="cell auto">
                    <label><span class="show-for-sr">Select Menu</span>
                        <select>
                            <option>Faculty</option>
                        </select>
                    </label>
                </div>
                <div class="cell auto">
                    <label><span class="show-for-sr">Input Label</span>
                        <input type="text" placeholder="Zip Code">
                    </label>
                </div>
                <div class="cell auto">
                    <label><span class="show-for-sr">Input Label</span>
                        <input type="text" placeholder="Search by instructor">
                    </label>
                </div>
            </div>
        </div>
    </div>

    <div class="grid-x grid-padding-x grid-container">
		<?php if ( ! $atts['disable_sidebar'] ) : ?>
            <div class="small-12 medium-2 large-2 cell" data-sticky-container data-options="stickyOn:small;">
                <div class="sticky" data-sticky data-anchor="courses-table-widget-cell">
                    <ul class="menu vertical course-table-nav show-for-medium" data-magellan>
						<?php foreach ( $courses as $title => $course_details ) : ?>
                            <li><a href="#<?= acf_slugify( $title ); ?>"><?= $title; ?></a></li>
						<?php endforeach; ?>
                    </ul>
                </div>
            </div>
		<?php endif; ?>
        <div class="auto cell" id="courses-table-widget-cell">
            <div class="courses-table-widget">
				<?php foreach ( $courses as $title => $course_details ) : $slug = acf_slugify( $title ); ?>
                    <div class="course-wrapper" id="<?= $slug; ?>" data-magellan-target="<?= $slug; ?>">
                        <h3>
                            <strong>
								<?= ( empty( $category ) ) ? $title : __( "{$category} Courses", 'ipa' ); ?>
                            </strong>
                        </h3>
                        <table class="course-table hover"> <!-- .datatable -->
                            <thead>
                            <tr>
                                <th>Location</th>
                                <th>Date</th>
                                <th>Instructor</th>
                                <th></th>
                            </tr>
                            </thead>
                            <tbody>
							<?php foreach ( $course_details as $course_detail ) : ?>
                                <tr>
                                    <td class="course-table-location no-sort">
										<?= $course_detail['city']; ?>, <?= $course_detail['state']; ?>
                                    </td>
                                    <td class="course-table-date"
                                        data-order="<?= date( 'u', strtotime( $course_detail['date'] ) ); ?>">
										<?= date( get_option( 'date_format' ), strtotime( $course_detail['date'] ) ); ?>
                                    </td>
                                    <td class="course-table-instructor">
                                        <img src="<?= get_template_directory_uri(); ?>/assets/images/trainer-placeholder.jpg"
                                             data-tooltip tabindex="1" title="Name" data-position="bottom"
                                             data-alignment="center" alt="Trainer name">
                                    </td>
                                    <td class="course-table-apply">
                                        <a href="<?= stage_url( $course_detail['request_path'] ); ?>"><?= __( 'Enroll Now', 'ipa' ); ?></a>
                                    </td>
                                </tr>
							<?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
				<?php endforeach; ?>
            </div>
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
