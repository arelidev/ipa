<?php
/**
 * IPA Accordion Shortcode Widget
 *
 * @param $atts
 * @param null $content
 *
 * @return false|string
 */
function ipa_accordion_widget( $atts, $content = null ) {
	$atts = shortcode_atts( array(
		'el_class' => ''
	), $atts );

	ob_start();
	?>
    <ul class="accordion ipa-accordion-widget <?= $atts['el_class']; ?>" id="<?= wp_unique_id( "accordion-" ); ?>"
        data-accordion
        data-allow-all-closed="true"
        data-deep-link="true"
        data-deep-link-smudge="true"
        data-multi-expand="true">
		<?= do_shortcode( $content ); ?>
    </ul>
	<?php
	return ob_get_clean();
}

add_shortcode( 'ipa_accordion', 'ipa_accordion_widget' );

/**
 * IPA Accordion Item
 *
 * @param $atts
 * @param $content
 */
function ipa_accordion_widget_item( $atts, $content ) {
	$atts = shortcode_atts( array(
		'el_class'  => '',
		'is_active' => false, // add class is-active
		'title'     => '',
	), $atts );

	$slug = clean( strtolower( $atts['title'] ) );
	?>
    <li class="accordion-item ipa-accordion-item <?= $atts['el_class']; ?>" data-accordion-item>
        <a href="#<?= $slug; ?>" class="accordion-title ipa-accordion-title text-color-black"><b><?= $atts['title']; ?></b></a>
        <div class="accordion-content ipa-accordion-content" data-tab-content id="<?= $slug; ?>">
			<?= do_shortcode( $content ); ?>
        </div>
    </li>
	<?php
}

add_shortcode( 'ipa_accordion_item', 'ipa_accordion_widget_item' );

// Integrate with Visual Composer
function ipa_accordion_integrateWithVC() {
	try {
		vc_map( array(
			"name"                    => __( "Accordion", "ipa" ),
			"base"                    => "ipa_accordion",
			"as_parent"               => array( 'only' => 'ipa_accordion_item' ),
			"content_element"         => true,
			"show_settings_on_create" => false,
			"is_container"            => true,
			"category"                => __( "Custom", "ipa-steak-house" ),
			"params"                  => array(
				array(
					"type"        => "textfield",
					"heading"     => __( "Extra class name", "ipa" ),
					"param_name"  => "el_class",
					"description" => __( "", "ipa" )
				)
			),
			"js_view"                 => 'VcColumnView'
		) );

		vc_map( array(
			"name"            => __( "Accordion Item", "ipa" ),
			"base"            => "ipa_accordion_item",
			"content_element" => true,
			"as_child"        => array( 'only' => 'ipa_accordion' ),
			"params"          => array(
				array(
					"type"        => "textfield",
					"heading"     => __( "Title", "ipa" ),
					"param_name"  => "title",
					"holder"      => "h3",
					"description" => __( "", "ipa" )
				),
				array(
					"type"        => "textarea_html",
					"class"       => "",
					"heading"     => __( "Content", "ipa" ),
					"param_name"  => "content",
					"value"       => '',
					"description" => __( "Enter description.", "ipa" )
				),
				array(
					"type"        => "textfield",
					"heading"     => __( "Extra class name", "ipa" ),
					"param_name"  => "el_class",
					"description" => __( "", "ipa" )
				)
			)
		) );

		if ( class_exists( 'WPBakeryShortCodesContainer' ) ) {
			class WPBakeryShortCode_Ipa_Accordion extends WPBakeryShortCodesContainer {
			}
		}

		if ( class_exists( 'WPBakeryShortCode' ) ) {
			class WPBakeryShortCode_Ipa_Accordion_Item extends WPBakeryShortCode {
			}
		}
	} catch ( Exception $e ) {
		echo 'Caught exception: ', $e->getMessage(), "\n";
	}
}

add_action( 'vc_before_init', 'ipa_accordion_integrateWithVC' );
