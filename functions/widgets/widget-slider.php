<?php
/**
 * Client Logo Slider Shortcode Widget
 *
 * @param $atts
 * @param null $content
 *
 * @return false|string
 */
function slider_widget( $atts, $content = null ) {
	$atts = shortcode_atts( array(
		'images'   => '',
		'el_class' => ''
	), $atts );

	ob_start();

	$image_ids = explode( ',', $atts['images'] );
	?>
    <div class="slider-widget grid-x align-center-middle">
        <div class="cell auto">
            <div class="slider-widget-slider">
				<?php foreach ( $image_ids as $image_id ) : ?>
                    <div class="slider-widget-image">
						<?php
						$logo    = wp_get_attachment_image( $image_id, 'large-thumbnail', false, array( 'class' => 'box-shadow' ) );
						$caption = wp_get_attachment_caption( $image_id );
						?>
						<?php if ( ! empty( $caption ) ) : ?>
                            <a href="<?= $caption; ?>" rel="external" target="_blank">
								<?= $logo; ?>
                            </a>
						<?php else : ?>
							<?= $logo; ?>
						<?php endif; ?>
                    </div>
				<?php endforeach; ?>
            </div>
        </div>
        <div class="cell small-12 large-shrink large-offset-2">
            <button class="slick-prev-custom-slider-widget slick-custom-button" aria-label="Previous" type="button">
                <i class="far fa-chevron-left fa-lg"></i>
            </button>
            <button class="slick-next-custom-slider-widget slick-custom-button" aria-label="Next" type="button">
                <i class="far fa-chevron-right fa-lg"></i>
            </button>
        </div>
    </div>
	<?php
	return ob_get_clean();
}

add_shortcode( 'slider', 'slider_widget' );


// Integrate with Visual Composer
function slider_integrateWithVC() {
	try {
		vc_map( array(
			"name"     => __( "Slider", "ipa" ),
			"base"     => "slider",
			"class"    => "",
			"category" => __( "Custom", "ipa" ),
			"params"   => array(
				array(
					"type"       => "attach_images",
					"heading"    => esc_html__( "Add Images", "ipa" ),
					"param_name" => "images",
					"value"      => "",
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
			)
		) );
	} catch ( Exception $e ) {
		echo 'Caught exception: ', $e->getMessage(), "\n";
	}
}

add_action( 'vc_before_init', 'slider_integrateWithVC' );
