<?php
function ipa_upcoming_courses_widget( $atts, $content = null ) {
	$atts = shortcode_atts( array(), $atts );
	ob_start();
	?>
	<div class="upcoming-courses-widget grid-x grid-margin-x grid-margin-y">
		<?php get_template_part( 'parts/content', 'course-card' ); ?>
		<?php get_template_part( 'parts/content', 'course-card' ); ?>
		<?php get_template_part( 'parts/content', 'course-card' ); ?>
		<?php get_template_part( 'parts/content', 'course-card' ); ?>
		<?php get_template_part( 'parts/content', 'course-card' ); ?>
		<?php get_template_part( 'parts/content', 'course-card' ); ?>
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