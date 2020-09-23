<?php
/**
 * IPA Vertical Tabs Shortcode Widget
 *
 * @param $atts
 * @param null $content
 *
 * @return false|string
 */
function ipa_vertical_tabs_widget( $atts, $content = null ) {
	$atts = shortcode_atts( array(
		'tabs'     => '',
		'el_class' => ''
	), $atts );

	ob_start();

	$tabs = vc_param_group_parse_atts( $atts['tabs'] );
	?>
    <div class="grid-x ipa-vertical-tabs <?= $atts['el_class']; ?>">
        <div class="cell small-12 medium-6 large-4">
            <ul class="vertical tabs" id="vertical-tabs-widget" data-responsive-accordion-tabs="accordion large-tabs">
				<?php foreach ( $tabs as $index => $value ) : $title = $value['tab_title']; ?>
                    <li class="tabs-title <?= ( $index == 0 ) ? "is-active" : ""; ?>">
                        <a href="#<?= vc_slugify( $title ); ?>" aria-selected="true"><?= $title; ?></a>
                    </li>
				<?php endforeach; ?>
            </ul>
        </div>
        <div class="cell small-12 medium-6 large-auto">
            <div class="tabs-content" data-tabs-content="vertical-tabs-widget">
				<?php foreach ( $tabs as $index => $value ) : $title = $value['tab_title']; ?>
                    <div class="tabs-panel <?= ( $index == 0 ) ? "is-active" : ""; ?>"
                         id="<?= vc_slugify( $title ); ?>">
                        <div class="grid-x grid-padding-x">
                            <div class="cell auto tabs-content-inner">
								<?= apply_filters( 'the_content', $value['tab_content'] ); ?>
                            </div>
							<?php if ( ! empty( $image = $value['image'] ) ) : ?>
                                <div class="cell small-12 medium-12 large-6">
									<?= wp_get_attachment_image( $image, 'full' ); ?>
                                </div>
							<?php endif; ?>
                        </div>
                    </div>
				<?php endforeach; ?>
            </div>
        </div>
    </div>
	<?php
	return ob_get_clean();
}

add_shortcode( 'ipa_vertical_tabs', 'ipa_vertical_tabs_widget' );

// Integrate with Visual Composer
function ipa_vertical_tabs_integrateWithVC() {
	try {
		vc_map( array(
			"name"     => __( "Vertical Tabs", "ipa" ),
			"base"     => "ipa_vertical_tabs",
			"class"    => "",
			"category" => __( "Custom", "ipa" ),
			"params"   => array(
				array(
					'type'       => 'param_group',
					'value'      => '',
					'param_name' => 'tabs',
					'params'     => array(
						array(
							"type"       => "textfield",
							"class"      => "",
							"heading"    => __( "Title", "ipa" ),
							"param_name" => "tab_title",
							"value"      => __( "", "ipa" ),
						),
						array(
							"type"       => "textarea",
							"class"      => "",
							"heading"    => __( "Content", "ipa" ),
							"param_name" => "tab_content",
							"value"      => __( "", "ipa" ),
						),
						array(
							"type"       => "attach_image",
							"class"      => "",
							"heading"    => __( "Image", "ipa" ),
							"param_name" => "image",
							"value"      => '',
						),
					)
				)
			),
		) );
	} catch ( Exception $e ) {
		echo 'Caught exception: ', $e->getMessage(), "\n";
	}
}

add_action( 'vc_before_init', 'ipa_vertical_tabs_integrateWithVC' );
