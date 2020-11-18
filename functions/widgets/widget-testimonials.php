<?php
/**
 * Testimonials Shortcode Widget
 *
 * @param $atts
 * @param null $content
 *
 * @return false|string
 */
function ipa_testimonials_widget( $atts, $content = null ) {
	$atts = shortcode_atts( array(), $atts );
	ob_start();

	$args = array(
		'post_type'      => 'testimonials',
		'posts_per_page' => 3
	);

	$loop = new WP_Query( $args );
	?>
    <div class="testimonials-widget grid-x">
        <div class="small-12 medium-12 large-12 cell">
            <div class="testimonials-slider" data-equalizer="testimonials-content" data-equalize-on="medium">
				<?php while ( $loop->have_posts() ) : $loop->the_post(); ?>
					<?php get_template_part( 'parts/content', 'testimonial' ); ?>
				<?php endwhile; ?>
            </div>
        </div>
        <div class="cell small-12 hide-for-large text-center">
            <button class="slick-prev-custom-testimonials-widget slick-custom-button" aria-label="Previous" type="button">
                <i class="far fa-chevron-left fa-lg"></i>
            </button>
            <button class="slick-next-custom-testimonials-widget slick-custom-button" aria-label="Next" type="button">
                <i class="far fa-chevron-right fa-lg"></i>
            </button>
        </div>
    </div>
	<?php
	wp_reset_postdata();

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
