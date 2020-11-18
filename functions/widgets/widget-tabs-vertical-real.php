<?php
/**
 * IPA Vertical Tabs Shortcode Widget
 *
 * @param $atts
 * @param null $content
 *
 * @return false|string
 */
function ipa_vertical_tabs_real_widget( $atts, $content = null ) {
	$atts = shortcode_atts( array(
		'tabs'     => '',
		'subtitle' => '',
		'el_class' => ''
	), $atts );

	ob_start();

	$tabs = vc_param_group_parse_atts( $atts['tabs'] );
	?>
    <div class="grid-x grid-margin-x ipa-vertical-tabs <?= $atts['el_class']; ?>">
        <div class="cell small-12 medium-12 large-5">
            <ul class="vertical tabs" data-responsive-accordion-tabs="accordion large-tabs" data-allow-all-closed="true" id="vertical-tabs-real">
				<?php foreach ( $tabs as $index => $value ) : ?>
                    <li class="tabs-title <?= ( $index == 0 ) ? "is-active" : ""; ?>">
                        <a href="#panel-<?= $index; ?>" aria-selected="true">
							<?= $value['title']; ?>
							<?php if ( ! empty( $value['subtitle'] ) ) : ?>
                                <span class="tabs-title-subtitle"><?= $value['subtitle']; ?></span>
							<?php endif; ?>
                        </a>
                    </li>
				<?php endforeach; ?>
            </ul>
        </div>
        <div class="cell small-12 medium-12 large-7">
            <div class="tabs-content" data-tabs-content="vertical-tabs-real">
				<?php foreach ( $tabs as $index => $value ) : ?>
                    <div class="tabs-panel <?= ( $index == 0 ) ? "is-active" : ""; ?>" id="panel-<?= $index; ?>">
						<?= apply_filters( 'the_content', $value['content'] ); ?>
                    </div>
				<?php endforeach; ?>
            </div>
        </div>
    </div>
	<?php
	return ob_get_clean();
}

add_shortcode( 'ipa_vertical_tabs_real', 'ipa_vertical_tabs_real_widget' );

// Integrate with Visual Composer
function ipa_vertical_tabs_real_integrateWithVC() {
	try {
		vc_map( array(
			"name"     => __( "Vertical Tabs", "ipa" ),
			"base"     => "ipa_vertical_tabs_real",
			"class"    => "",
			"category" => __( "Custom", "ipa" ),
			"params"   => array(
				array(
					'type'       => 'param_group',
					'value'      => '',
					'param_name' => 'tabs',
					'params'     => array(
						array(
							'type'       => 'textfield',
							'value'      => '',
							'heading'    => 'Title',
							'param_name' => 'title',
						),
						array(
							'type'       => 'textfield',
							'value'      => '',
							'heading'    => 'Subtitle',
							'param_name' => 'subtitle',
						),
						array(
							"type"        => "textarea",
							"class"       => "",
							"heading"     => __( "Content", "ipa" ),
							"param_name"  => "content",
							"value"       => '',
							"description" => __( "Enter description.", "ipa" )
						),
					)
				)
			),
		) );
	} catch ( Exception $e ) {
		echo 'Caught exception: ', $e->getMessage(), "\n";
	}
}

add_action( 'vc_before_init', 'ipa_vertical_tabs_real_integrateWithVC' );
