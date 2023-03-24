<?php
/**
 * Course Table Widget
 *
 * @param $atts
 *
 * @return false|string
 */
function ipa_courses_table_widget( $atts ) {
	$atts = shortcode_atts( array(
		'presenter' => false,
		'template'  => false,
		'filters'   => true,
		'el_class'  => ''
	), $atts );

	ob_start();

	$active = false;

	$args = array(
		'post_type'        => 'ipa_arlo_events',
		'post_status'      => 'publish',
		'posts_per_page'   => - 1,
		'suppress_filters' => false,
		'meta_key'         => 'startdatetime',
		'orderby'          => 'meta_value',
		'order'            => 'ASC',
	);

	if ( $atts['presenter'] ) :
		$args['meta_query'] = array(
			array(
				'key'     => 'presenters_$_linked_presenter',
				'value'   => '"' . $atts['presenter'] . '"',
				'compare' => 'LIKE'
			)
		);

		$active = true;
	endif;

	if ( $atts['template'] ) :
		if ( $atts['template'] === "Virtual" ) :
			$args['meta_query'] = array(
				array(
					'key'     => 'categories_$_name',
					'value'   => $atts['template'],
					'compare' => 'LIKE'
				)
			);
		else :
			$args['meta_query'] = array(
				array(
					'key'     => 'templatecode',
					'value'   => $atts['template'],
					'compare' => '='
				)
			);
		endif;

		$active = true;
	endif;

	$loop = new WP_Query( $args );

	$courses = [];
	if ( $loop->have_posts() ) :
		while ( $loop->have_posts() ) : $loop->the_post();
			$course               = get_field( 'name' );
			$courses[ $course ][] = get_the_ID();
		endwhile;
	endif;

	ksort( $courses );
	?>
    <div class="ipa-courses-table-widget <?= $atts['el_class']; ?>">
		<?php if ( $loop->have_posts() ) : ?>
			<?php
			if ( $atts['filters'] !== "0" ) :
				get_template_part(
					'parts/content-filters', 'courses',
					array(
						"courses" => $courses
					)
				);
			endif;
			?>
            <div class="ipa-courses-table-widget-cell">
                <ul class="accordion ipa-accordion-widget courses-parent" data-accordion data-allow-all-closed="true">
					<?php foreach ( $courses as $title => $ids ) : ?>
                        <li class="accordion-item ipa-accordion-item courses-parent-item <?= $active ? 'is-active' : ""; ?>"
                            data-accordion-item id="course-<?= acf_slugify( $title ); ?>">
                            <a href="#<?= acf_slugify( $title ); ?>"
                               class="accordion-title ipa-accordion-title text-color-black">
                                <b><?= $title; ?></b>
                            </a>
                            <div class="accordion-content ipa-accordion-content courses-parent-content" data-tab-content
                                 id="<?= acf_slugify( $title ); ?>">
                                <ul class="accordion ipa-accordion-widget courses-child" data-accordion
                                    data-allow-all-closed="true">
									<?php
									foreach ( $ids as $id ) :
										$eventId = get_field( 'eventid', $id );
										$eventCode = get_field( 'code', $id );
										$templateCode = get_field( 'templatecode', $id );

										$parentClasses = [
											$eventId,
											$eventCode,
											$templateCode,
											"accordion-item",
											"ipa-accordion-item",
											"courses-child-item"
										];

										if ( $atts['filters'] !== "0" ) :
											$parentClasses[] = "mix";
										endif;

										if ( have_rows( "presenters", $id ) ) :
											while ( have_rows( "presenters", $id ) ) : the_row();
												$parentClasses[] = acf_slugify( get_sub_field( 'name' ) );
											endwhile;
										endif;

										$eventTitle = get_the_title( $id );
										$sessions   = get_field( "sessions", $id );
										$startDate  = get_field( 'startdatetime', $id );
										$categories = get_field( 'categories', $id );

										$parentClasses[] = is_virtual( $categories ) ? "virtual" : "in-person";

										if ( $sessions ) :
											$first    = $sessions[0]["location"];
											$location = $first[0];

											$venue = $location['venuename'] ?? false;
											$city  = $location['city'] ?? false;
											$state = $location['state'] ?? false;

											$eventTitle = ( $venue ) ? $venue . "</br>" : "";
											$eventTitle .= ( $city ) ?: "";
											$eventTitle .= ( $state ) ? ", " . $state : "";

											if ( $state ) :
												$parentClasses[] = acf_slugify( $state );
												$parentClasses[] = get_region_by_state( $state );
											endif;
										endif;
										?>
                                        <li class="<?= implode( " ", $parentClasses ); ?>"
                                            id="<?= $eventId; ?>"
                                            data-accordion-item
                                            data-start-date="<?= date( 'm/d/Y', strtotime( $startDate ) ); ?>"
                                        >
                                            <a href="#" class="accordion-title ipa-accordion-title text-color-black">
                                                <div class="grid-x align-middle" style="padding-right: 50px;">
                                                    <div class="auto cell">
                                                        <p class="course-table-name"
                                                           style="display: block; margin-bottom: 8px;">
                                                            <b><?= $eventTitle; ?></b>
                                                        </p>
                                                        <span class="course-table-date text-color-dark-gray">
                                                            <i class="fal fa-clock"></i>
                                                            <?php
                                                            get_template_part(
	                                                            'parts/arlo/events/loop-event',
	                                                            'datetime',
	                                                            array(
		                                                            'post' => $id
	                                                            )
                                                            );
                                                            ?>
                                                        </span>
                                                    </div>
                                                    <div class="shrink cell show-for-medium">
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
														endif; ?>
                                                    </div>
                                                </div>
                                            </a>
                                            <div class="accordion-content ipa-accordion-content courses-child-content"
                                                 data-tab-content id="<?= $eventId; ?>">
												<?php
												if ( have_rows( 'sessions', $id ) ) :
													get_template_part(
														'parts/arlo/events/event',
														'table',
														array(
															'post' => $id
														)
													);
												endif;
												?>

                                                <div class="grid-x grid-padding-x align-middle">
                                                    <div class="auto cell">
														<?php
														if ( have_rows( 'presenters', $id ) ) :
															get_template_part(
																'parts/arlo/events/loop',
																'presenters',
																array(
																	'post' => $id
																)
															);
														endif; ?>
                                                    </div>
                                                    <div class="auto cell">
														<?php
														get_template_part(
															'parts/arlo/events/event',
															'register',
															array(
																'post' => $id
															)
														);
														?>
                                                    </div>
                                                </div>
                                            </div>
                                        </li>
									<?php endforeach; ?>
                                </ul>
                            </div>
                        </li>
					<?php endforeach; ?>
                </ul>
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

add_shortcode( 'ipa_courses_table', 'ipa_courses_table_widget' );

/**
 * Integrate with Visual Composer
 *
 * @return void
 */
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

add_action( 'vc_before_init', 'ipa_courses_table_integrateWithVC' );
