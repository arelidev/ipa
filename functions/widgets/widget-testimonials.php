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
	$atts = shortcode_atts( array(
		'category'       => '',
		'posts_per_page' => -1,
		'slides_to_show' => 3,
		'el_class'       => ''
	), $atts );
	ob_start();

	$args = array(
		'post_type'      => 'testimonials',
		'posts_per_page' => $atts['posts_per_page']
	);

	if ( ! empty( $term_id = $atts['category'] ) ) :
		$args['tax_query'] = array(
			array(
				'taxonomy' => 'testimonials_taxonomy',
				'field'    => 'term_id',
				'terms'    => $term_id,
			)
		);
	endif;

	$loop = new WP_Query( $args );
	?>
    <div class="testimonials-widget grid-x grid-padding-y <?= $atts['el_class']; ?>">
        <div class="small-12 medium-12 large-12 cell">
            <div class="testimonials-slider" data-equalizer="testimonials-content" data-equalize-on="medium">
				<?php while ( $loop->have_posts() ) : $loop->the_post(); ?>
					<?php get_template_part( 'parts/content', 'testimonial' ); ?>
				<?php endwhile; ?>
            </div>
        </div>
        <div class="cell small-12 text-center">
            <button class="slick-prev-custom-testimonials-widget slick-custom-button" aria-label="Previous" type="button">
                <i class="fa-solid fa-chevron-left fa-lg"></i>
            </button>
            <button class="slick-next-custom-testimonials-widget slick-custom-button" aria-label="Next" type="button">
                <i class="fa-solid fa-chevron-right fa-lg"></i>
            </button>
        </div>
    </div>

    <script type="text/javascript">
        jQuery(document).ready(function ($) {
            let testimonialsSlider = $('.testimonials-slider');

            resizeTestimonial();

            // todo: replace this with update plugin or function
            // @url https://github.com/viralpatel/jquery.shorten
            $(".testimonial-card-text-inner").shorten({
                moreText: "read more",
                lessText: "read less",
                onMore: function() {
                    resizeTestimonial();
                },
                onLess: function() {
                    resizeTestimonial();
                },
            });

            testimonialsSlider.slick({
                infinite: true,
                slidesToShow: <?= $atts['slides_to_show']; ?>,
                slidesToScroll: <?= $atts['slides_to_show']; ?>,
                nextArrow: '.slick-next-custom-testimonials-widget',
                prevArrow: '.slick-prev-custom-testimonials-widget',
                dots: false,
                adaptiveHeight: true,
                responsive: [
                    {
                        breakpoint: 1024,
                        settings: {
                            slidesToShow: <?= $atts['slides_to_show']; ?>,
                            slidesToScroll: <?= $atts['slides_to_show']; ?>,
                        }
                    },
                    {
                        breakpoint: 800,
                        settings: {
                            slidesToShow: 1,
                            slidesToScroll: 1,
                        }
                    },
                    {
                        breakpoint: 400,
                        settings: {
                            slidesToShow: 1,
                            slidesToScroll: 1,
                        }
                    }
                ],
            });

            function resizeTestimonial() {
                // todo: is there a better way to resize without reinitializing?
                let testimonialEqualizer = new Foundation.Equalizer(testimonialsSlider);
            }

        });

    </script>
	<?php
	wp_reset_postdata();

	return ob_get_clean();
}

add_shortcode( 'ipa_testimonials', 'ipa_testimonials_widget' );

// Integrate with Visual Composer
function ipa_testimonials_integrateWithVC() {
	try {
		$categories_array = array();
		$categories       = get_terms( 'testimonials_taxonomy', array( 'hide_empty' => false ) );
		foreach ( $categories as $category ) {
			$categories_array[ $category->name ] = $category->term_id;
		}

		vc_map( array(
			"name"     => __( "Testimonials Slider", "ipa" ),
			"base"     => "ipa_testimonials",
			"class"    => "",
			"category" => __( "Custom", "ipa" ),
			"params"   => array(
				array(
					'param_name'  => 'category',
					'type'        => 'dropdown',
					'value'       => $categories_array,
					'heading'     => __( 'Select Category:', 'ipa' ),
					'description' => '',
					'class'       => ''
				),
				array(
					"type"        => "textfield",
					"heading"     => __( "Number of posts", "ipa" ),
					"param_name"  => "posts_per_page",
					"description" => __( "Defaults to 3", "ipa" )
				),
				array(
					"type"        => "textfield",
					"heading"     => __( "Slides to show", "ipa" ),
					"param_name"  => "slides_to_show",
					"description" => __( "Defaults to 3", "ipa" )
				),
				array(
					"type"        => "textfield",
					"heading"     => __( "Extra class name", "ipa" ),
					"param_name"  => "el_class",
					"description" => __( "", "ipa" )
				)
			),
		) );
	} catch ( Exception $e ) {
		echo 'Caught exception: ', $e->getMessage(), "\n";
	}
}

add_action( 'vc_before_init', 'ipa_testimonials_integrateWithVC' );
