<?php
function ipa_courses_table_widget( $atts, $content = null ) {
	$atts = shortcode_atts( array(), $atts );
	ob_start();
	?>
	<div class="upcoming-courses-widget">
		<?php get_template_part( 'parts/content', 'course-table' ); ?>
	</div>
	<?php
	return ob_get_clean();
}

add_shortcode( 'ipa_courses_table', 'ipa_courses_table_widget' );

// Integrate with Visual Composer
function ipa_courses_table_integrateWithVC() {
	try {
		vc_map( array(
			"name"                    => __( "Courses Table", "ipa" ),
			"base"                    => "ipa_courses_table",
			"class"                   => "",
			"category"                => __( "Custom", "ipa" ),
			"params"                  => array(),
			"show_settings_on_create" => false
		) );
	} catch ( Exception $e ) {
		echo 'Caught exception: ', $e->getMessage(), "\n";
	}
}

add_action( 'vc_before_init', 'ipa_courses_table_integrateWithVC' );