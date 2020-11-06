<?php
/**
 * Full Slider Shortcode Widget
 *
 * @param $atts
 * @param null $content
 *
 * @return false|string
 */
function full_slider_widget( $atts, $content = null ) {
	$atts = shortcode_atts( array(
		'slider'   => '',
		'el_class' => ''
	), $atts );

	ob_start();

	$args = array(
		'p'              => $atts['slider'],
		'post_type'      => 'slider',
		'posts_per_page' => - 1
	);
	$loop = new WP_Query( $args );

	?>
	<?php while ( $loop->have_posts() ) : $loop->the_post(); ?>
        <div class="full-slider-widget">
            <div class="full-slider-inner">
                <div class="full-slider">
					<?php if ( have_rows( 'slides' ) ): while ( have_rows( 'slides' ) ) : the_row(); ?>
                        <div class="single-full-slide hero" id="<?= acf_slugify( get_sub_field( 'slide_title' ) ); ?>"
                             style="background-image: url(<?php the_sub_field( 'background_image' ); ?>">
                            <div class="grid-container">
                                <div class="grid-x grid-padding-x align-center">
                                    <div class="small-10 medium-10 large-8 cell">
										<?= apply_filters( 'the_content', get_sub_field( 'content' ) ); ?>
                                    </div>
                                </div>
                            </div>
                        </div>
					<?php endwhile; endif; ?>
                </div>

                <button class="slick-prev-custom-full-slider slick-custom-button" aria-label="Previous" type="button">
                    <i class="far fa-chevron-left fa-lg"></i>
                </button>
                <button class="slick-next-custom-full-slider slick-custom-button" aria-label="Next" type="button">
                    <i class="far fa-chevron-right fa-lg"></i>
                </button>

				<?php if ( have_rows( 'slides' ) ): $i = 0; ?>
                    <div id="slide-navigation" class="grid-container grid-x grid-margin-x show-for-medium">
						<?php while ( have_rows( 'slides' ) ) : the_row(); ?>
                            <div class="cell auto slide-navigation-button" data-slick-index="<?= $i; ?>">
                                <span class=""><?php the_sub_field( 'title' ); ?></span>
                            </div>
						<?php $i++; endwhile; ?>
                    </div>
				<?php endif; ?>
            </div>
        </div>
	<?php endwhile; ?>
	<?php
	return ob_get_clean();
}

add_shortcode( 'full_slider', 'full_slider_widget' );

// Integrate with Visual Composer
function full_slider_integrateWithVC() {
	try {
		$args = array( 'post_type' => 'slider', 'posts_per_page' => - 1 );
		$loop = new WP_Query( $args );

		$sliders_array = array();
		while ( $loop->have_posts() ) : $loop->the_post();
			$sliders_array[ get_the_title() ] = get_the_ID();
		endwhile;

		vc_map( array(
			"name"     => __( "Full Slider", "ipa" ),
			"base"     => "full_slider",
			"class"    => "",
			"category" => __( "Custom", "ipa" ),
			"params"   => array(
				array(
					'param_name'  => 'slider',
					'type'        => 'dropdown',
					'value'       => $sliders_array,
					'heading'     => __( 'Select slider:', 'ipa' ),
					'description' => '',
					'class'       => ''
				),
				array(
					"type"       => "textfield",
					"holder"     => "el_class",
					"class"      => "",
					"heading"    => __( "Extra Class", "ipa" ),
					"param_name" => "el_class",
					// "value" => __( "<p>I am test text block. Click edit button to change this text.</p>", "ipa" ),
					// "description" => __( "Description for foo param.", "ipa" )
				),
			),
		) );
	} catch ( Exception $e ) {
		echo 'Caught exception: ', $e->getMessage(), "\n";
	}
}

add_action( 'vc_before_init', 'full_slider_integrateWithVC' );
