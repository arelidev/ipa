<?php
/**
 * Course Widget
 *
 * @param $atts
 *
 * @return false|string
 */
function ipa_courses_widget( $atts ) {
	$atts = shortcode_atts( [
		"el_class" => ""
	], $atts );

	ob_start();

    $display = get_query_var( "display" );

	$args = [
		"post_type"        => "ipa_arlo_events",
		"post_status"      => "publish",
		"posts_per_page"   => - 1,
		"suppress_filters" => false,
		"meta_key"         => "startdatetime",
		"orderby"          => "meta_value",
		"order"            => "ASC",
	];

	$loop = new WP_Query( $args );

	$courses = [];
	if ( $loop->have_posts() ) :
		while ( $loop->have_posts() ) : $loop->the_post();
			if ( ! empty( $display ) && $display === "calendar" ) :
				$stateDateTime = get_field( "startdatetime" );
				$course        = date( "F, Y", strtotime( $stateDateTime ) );
			else :
				$course = get_field( "name" );
			endif;

			$courses[ $course ][] = get_the_ID();
		endwhile;
	endif;

	if ( ! empty( $display ) && $display === "calendar" ) :
		$monthOrder = array(
			"January",
			"February",
			"March",
			"April",
			"May",
			"June",
			"July",
			"August",
			"September",
			"October",
			"November",
			"December"
		);

		uksort( $courses, function ( $a, $b ) use ( $monthOrder ) {
			return array_search( $a, $monthOrder ) - array_search( $b, $monthOrder );
		} );
	else :
		ksort( $courses );
	endif;
	?>
    <div class="ipa-courses-widget <?= $atts['el_class']; ?>">
		<?php if ( $loop->have_posts() ) : ?>
			<?php
			get_template_part(
				'parts/content-filters', 'courses',
				array(
					"courses"    => $courses,
					"expandable" => false,
                    "display"    => $display
				)
			);
			?>
            <div class="ipa-courses-widget-wrapper">
				<?php foreach ( $courses as $title => $ids ) : ?>
                    <div class="ipa-courses-widget-cell" id="course-<?= acf_slugify( $title ); ?>">
                        <a href="#<?= acf_slugify( $title ); ?>" class="ipa-courses-widget-course-title text-color-black">
                            <h5><b><?= $title; ?></b></h5>
                        </a>
                        <div class="styled-container" id="<?= acf_slugify( $title ); ?>">
                            <table class="course-table hover stack">
                                <thead>
                                <tr>
                                    <th><?= __( 'Course', 'ipa' ); ?></th>
                                    <th><?= __( 'Location', 'ipa' ); ?></th>
                                    <th><?= __( 'Date', 'ipa' ); ?></th>
                                    <th><?= __( 'Instructor(s)', 'ipa' ); ?></th>
                                    <th></th>
                                </tr>
                                </thead>
                                <tbody>
								<?php
								foreach ( $ids as $id ) :
									$eventId = get_field( 'eventid', $id );
									$eventCode = get_field( 'code', $id );
									$templateCode = get_field( 'templatecode', $id );

									$parentClasses = [
										$eventId,
										$eventCode,
										$templateCode,
                                        "mix"
									];

									if ( have_rows( "presenters", $id ) ) :
										while ( have_rows( "presenters", $id ) ) : the_row();
											$parentClasses[] = acf_slugify( get_sub_field( 'name' ) );
										endwhile;
									endif;

									$eventTitle = get_the_title( $id );
									$sessions   = get_field( "sessions", $id );
									$startDate  = get_field( 'startdatetime', $id );
									$categories = get_field( 'categories', $id );

									if ( is_virtual( $categories ) ) :
										$parentClasses[] = "virtual";
                                    elseif ( is_on_demand( $categories ) ) :
										$parentClasses[] = "on-demand";
									else :
										$parentClasses[] = "in-person";
									endif;

									$venue = $city = $state = "";

									if ( $sessions ) :
										$first    = $sessions[0]["location"];
										$location = $first[0];

										$venue = $location['venuename'] ?? false;
										$city  = $location['city'] ?? false;
										$state = $location['state'] ?? false;

										if ( $state ) :
											$slugify_state   = acf_slugify( $state );
											$parentClasses[] = $slugify_state;

											$states = get_region_by_state( $slugify_state );
											foreach ( $states as $state ) :
												$parentClasses[] = $state;
											endforeach;
										endif;
									endif;

									$registrationInfo = get_field( 'registrationinfo', $id );
									$registerUri      = $registrationInfo['registeruri'];
									$registermessage  = $registrationInfo['registermessage'];
									?>
                                    <tr class="<?= implode( " ", $parentClasses ); ?>" id="<?= $eventId; ?>" data-start-date="<?= date( 'm/d/Y', strtotime( $startDate ) ); ?>">
                                        <td class="course-table--title">
											<a href="<?= $registerUri; ?>" <?= ( empty( $registerUri ) ) ? "disabled" : ""; ?>>
                                                <b><?= $eventTitle; ?></b>
                                            </a>
                                        </td>
                                        <td class="course-table--location">
											<?= $venue; ?>
											<?= ! empty( $city ) ? "<br>$city" : ""; ?> <?= ! empty( $state ) ? ", $state" : ""; ?>
                                        </td>
                                        <td class="course-table--date">
                                            <div class="text-color-dark-gray">
												<?php
												get_template_part(
													'parts/arlo/events/loop-event',
													'datetime',
													array(
														'post' => $id
													)
												);
												?>
                                            </div>
                                        </td>
                                        <td class="course-table--instructor">
											<?php
											if ( have_rows( 'presenters', $id ) ) :
												get_template_part(
													'parts/arlo/events/loop',
													'presenters',
													array(
														'post'         => $id,
														"disable_link" => true
													)
												);
											endif;
											?>
                                        </td>
                                        <td class="course-table--register text-center">
                                            <a href="<?= $registerUri; ?>" <?= ( empty( $registerUri ) ) ? "disabled" : ""; ?>>
                                                <b><?= $registermessage; ?></b>
                                            </a>
                                        </td>
                                    </tr>
								<?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
				<?php endforeach; ?>
            </div>
		<?php else : ?>
            <div class="callout primary">
				<?= __( 'There are no courses currently scheduled - Check back later', 'ipa' ); ?>
            </div>
		<?php endif; ?>
    </div>
	<?php
	wp_reset_postdata();

	return ob_get_clean();
}

add_shortcode( 'ipa_courses', 'ipa_courses_widget' );

/**
 * Integrate with Visual Composer
 *
 * @return void
 */
function ipa_courses_integrateWithVC() {
	try {
		vc_map( array(
			"name"     => __( "IPA Courses", "ipa" ),
			"base"     => "ipa_courses",
			"class"    => "",
			"category" => __( "Custom", "ipa" ),
			"params"   => array(
				array(
					"type"        => "textfield",
					"heading"     => __( "Presenter ID", "ipa" ),
					"param_name"  => "presenter",
					"description" => __( "The user ID linked to the event.", "ipa" )
				),
				array(
					"type"        => "textfield",
					"heading"     => __( "Template Code", "ipa" ),
					"param_name"  => "template",
					"description" => __( "The template code associated with Arlo Events.", "ipa" )
				),
				array(
					"type"       => "textfield",
					"heading"    => __( "Hide Filters", "ipa" ),
					"param_name" => "hide_filters",
				),
				array(
					"type"        => "textfield",
					"heading"     => __( "Extra class name", "tailpress" ),
					"param_name"  => "el_class",
					"description" => __( "", "tailpress" )
				)
			)
		) );
	} catch ( Exception $e ) {
		echo 'Caught exception: ', $e->getMessage(), "\n";
	}
}

add_action( 'vc_before_init', 'ipa_courses_integrateWithVC' );
