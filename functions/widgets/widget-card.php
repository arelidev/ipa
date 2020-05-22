<?php
function ipa_card_widget( $atts, $content = null ) {
	$atts = shortcode_atts( array(
		'el_class' => ''
	), $atts );
	ob_start();
	?>
    <div class="ipa-card-widget <?= $atts['el_class']; ?>" data-equalizer="ipa-card-widget-title" data-equalize-by-row="true">
        <div class="grid-x grid-margin-x grid-margin-y" data-equalizer="ipa-card-widget-content" data-equalize-by-row="true">
	        <?= do_shortcode( $content ); ?>
        </div>
    </div>
	<?php
	return ob_get_clean();
}

add_shortcode( 'ipa_card', 'ipa_card_widget' );

function ipa_single_card_widget( $atts, $content = null ) {
	$atts = shortcode_atts( array(
		'title'    => '',
		'icon'     => '',
		'size'     => '',
		'link'     => '',
		'el_class' => ''
	), $atts );

	$href = vc_build_link( $atts['link'] );
	?>
    <div class="ipa-single-card-widget cell <?= $atts['size']; ?>">
        <div class="ipa-single-card-widget-inner">
			<?php if ( ! empty( $atts['icon'] ) ) : ?>
				<?= wp_get_attachment_image( $atts['icon'], 'full', true, array( 'class' => 'ipa-single-card-widget-icon' ) ); ?>
			<?php endif; ?>

            <h3 class="ipa-single-card-widget-title" data-equalizer-watch="ipa-card-widget-title"><?= $atts['title']; ?></h3>

            <div class="ipa-single-card-widget-content">
                <div class="ipa-single-card-widget-content-text" data-equalizer-watch="ipa-card-widget-content">
					<p><small><?= $content; ?></small></p>
                </div>
            </div>

	        <?php if ( ! empty( $href['url'] ) ) : ?>
                <a href="<?= $href['url']; ?>" target="<?= $href['target']; ?>" rel="<?= $href['rel']; ?>" title="<?= $href['title']; ?>" class="ipa-single-card-widget-content-link">
                    <span><?= __( 'Read More', 'ipa' ); ?></span> <i class="fas fa-arrow-right fa-lg"></i>
                </a>
	        <?php endif; ?>
        </div>
    </div>
	<?php
}

add_shortcode( 'ipa_single_card', 'ipa_single_card_widget' );

// Integrate with Visual Composer
function ipa_card_integrateWithVC() {
	try {
		vc_map( array(
			"name"                    => __( "Card", "ipa" ),
			"base"                    => "ipa_card",
			"as_parent"               => array( 'only' => 'ipa_single_card' ),
			"content_element"         => true,
			"show_settings_on_create" => false,
			"is_container"            => true,
			"category"                => __( "Custom", "ipa" ),
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
			"name"            => __( "Single Card", "ipa" ),
			"base"            => "ipa_single_card",
			"content_element" => true,
			"as_child"        => array( 'only' => 'ipa_card' ),
			"params"          => array(
				array( // todo: convert to select field
					"type"        => "textfield",
					"heading"     => __( "Container Size", "ipa" ),
					"param_name"  => "size",
					"description" => __( "", "ipa" )
				),
				array(
					"type"        => "textfield",
					"heading"     => __( "Title", "ipa" ),
					"param_name"  => "title",
					"description" => __( "", "ipa" ),
					"holder"      => "p"
				),
				array(
					"type"        => "attach_image",
					"class"       => "",
					"heading"     => __( "Icon", "ipa" ),
					"param_name"  => "icon",
					"value"       => '',
					"description" => __( "Enter description.", "ipa" )
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
					"type"        => "vc_link",
					"class"       => "",
					"heading"     => __( "CTA Link", "ipa" ),
					"param_name"  => "link",
					"value"       => '',
					"description" => __( "", "ipa" )
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
			class WPBakeryShortCode_Ipa_Card extends WPBakeryShortCodesContainer {
			}
		}

		if ( class_exists( 'WPBakeryShortCode' ) ) {
			class WPBakeryShortCode_Ipa_Single_Card extends WPBakeryShortCode {
			}
		}
	} catch ( Exception $e ) {
		echo 'Caught exception: ', $e->getMessage(), "\n";
	}
}

add_action( 'vc_before_init', 'ipa_card_integrateWithVC' );
