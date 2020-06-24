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
            <div class="grid-x grid-padding-x grid-padding-y align-middle full-slider-inner">
                <div class="small-12 medium-12 large-12 cell">
                    <div class="full-slider">
						<?php if ( have_rows( 'slides' ) ): while ( have_rows( 'slides' ) ) : the_row(); ?>
                            <div class="single-full-slide" id="<?= acf_slugify( get_sub_field( 'slide_title' ) ); ?>">
                                <div class="grid-container grid-x grid-padding-x grid-padding-y <?= get_sub_field( 'align_row' ); ?>">
									<?php if ( have_rows( 'left_column' ) ): while ( have_rows( 'left_column' ) ) : the_row(); ?>
                                        <div class="cell small-order-2 medium-order-2 large-order-1 small-12 medium-12 <?= get_sub_field( 'size' ); ?> <?= get_sub_field( 'extra_class' ); ?>">
											<?= apply_filters( 'the_content', get_sub_field( 'content' ) ); ?>
                                        </div>
									<?php endwhile;endif; ?>
									<?php if ( have_rows( 'right_column' ) ): while ( have_rows( 'right_column' ) ) : the_row(); ?>
                                        <div class="cell small-order-1 medium-order-1 large-order-2 small-12 medium-12 <?= get_sub_field( 'size' ); ?> <?= get_sub_field( 'extra_class' ); ?>">
											<?= apply_filters( 'the_content', get_sub_field( 'content' ) ); ?>
                                        </div>
									<?php endwhile;endif; ?>
                                </div>
                            </div>
						<?php endwhile; endif; ?>
                    </div>
                </div>

                <div class="small-12 medium-12 large-12 cell" style="margin-bottom: -100px; transform: translateY(-25px);">
                    <div class="full-slider-nav">
	                    <?php if ( have_rows( 'slides' ) ): while ( have_rows( 'slides' ) ) : the_row(); ?>
                            <div class="single-full-slide testimonial-card">
	                            <div class="testimonial-card-inner">
		                            <p class="text-color-black text-center" style="margin-bottom: 0;">
                                        <b><?= get_sub_field( 'slide_title' ); ?></b>
                                    </p>
                                </div>
                            </div>
	                    <?php endwhile; endif; ?>
                    </div>
                </div>
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
