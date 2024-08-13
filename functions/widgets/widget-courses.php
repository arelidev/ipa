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
		"presenter" => false,
        "filters"   => true,
        "display"   => false,
        "condensed" => false,
		"el_class"  => ""
	], $atts );

	ob_start();

	if ( $atts["display"] ):
		$display = $atts["display"];
	else :
		$display = get_query_var( "display" );
	endif;

	$args = [
		"post_type"        => "ipa_arlo_events",
		"post_status"      => "publish",
		"posts_per_page"   => - 1,
		"suppress_filters" => false,
		"meta_key"         => "startdatetime",
		"orderby"          => "meta_value",
		"order"            => "ASC",
	];

	if ( $atts['presenter'] ) :
		$args['meta_query'] = [
			[
				'key'     => 'presenters_$_linked_presenter',
				'value'   => '"' . $atts['presenter'] . '"',
				'compare' => 'LIKE'
			]
		];
	endif;

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
			if ( $atts['filters'] !== "0" ) :
				get_template_part(
					'parts/content-filters', 'courses',
					array(
						"courses"    => $courses,
						"expandable" => false,
						"display"    => $display
					)
				);
			endif;
			?>
            <div class="ipa-courses-widget-wrapper">
	            <?php if ( ! $atts["condensed"] ) : ?>
		            <?php get_template_part( 'parts/loop-course', 'table', [
			            "courses" => $courses,
			            "display" => $display
		            ] ); ?>
	            <?php else : ?>
		            <?php get_template_part( 'parts/loop-course', 'table-condensed', [
			            "courses"   => $courses,
			            "display"   => $display
		            ] ); ?>
	            <?php endif; ?>
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
