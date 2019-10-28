<?php
function ipa_testimonials_widget( $atts, $content = null ) {
	$atts = shortcode_atts( array(), $atts );
	ob_start();
	?>
	<div class="testimonials-widget grid-x grid-margin-x">
		<?php get_template_part( 'parts/content', 'testimonial' ); ?>
		<?php get_template_part( 'parts/content', 'testimonial' ); ?>
		<?php get_template_part( 'parts/content', 'testimonial' ); ?>
	</div>
	<?php
	return ob_get_clean();
}

add_shortcode( 'ipa_testimonials', 'ipa_testimonials_widget' );

// Integrate with Visual Composer
function ipa_testimonials_integrateWithVC() {
	try {
		vc_map( array(
			"name"                    => __( "Testimonials Slider", "ipa" ),
			"base"                    => "ipa_testimonials",
			"class"                   => "",
			"category"                => __( "Custom", "ipa" ),
			"params"                  => array(),
			"show_settings_on_create" => false
		) );
	} catch ( Exception $e ) {
		echo 'Caught exception: ', $e->getMessage(), "\n";
	}
}

add_action( 'vc_before_init', 'ipa_testimonials_integrateWithVC' );