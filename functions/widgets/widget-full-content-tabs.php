<?php
/**
 * Full Content Tabs Shortcode Widget
 *
 * @param $atts
 * @param $content
 *
 * @return false|string
 */
function widget_full_content_tabs( $atts, $content ) {
	$atts = shortcode_atts( array(), $atts );

	ob_start();
	?>
    <div class="widget-full-content-tabs-wrapper">

        <ul class="grid-container grid-x grid-margin-x grid-margin-y align-center tabs"
            data-responsive-accordion-tabs="accordion large-tabs" data-deep-link="true" data-allow-all-closed="true"
            id="widget-full-content-tabs"></ul>

        <div class="tabs-content" data-tabs-content="widget-full-content-tabs">

			<?= do_shortcode( $content ); ?>

        </div>

    </div>
    <script>
        // Append Tabs before plugin initialization
        jQuery('.widget-full-content-tab-container').each(function () {
            jQuery('#widget-full-content-tabs').append(jQuery(this).find(jQuery(".ipa-single-card-widget")));
        });
    </script>
	<?php

	return ob_get_clean();
}

add_shortcode( 'full_content_tabs', 'widget_full_content_tabs' );

/**
 * Full Content Tab Shortcode Widget
 *
 * @param $atts
 * @param $content
 */
function widget_full_content_tab( $atts, $content ) {
	$atts = shortcode_atts( array(
		'title'       => '',
		'icon'        => '',
		'description' => '',
		'is_active'   => false,
		'columns'     => 'three'
	), $atts );

	$title     = vc_slugify( $atts['title'] );
	$is_active = ( $atts['is_active'] ) ? "is-active" : "";

	$classes = [];

	switch ( $atts['columns'] ) {
		case 'four':
			$classes[] = "small-12 medium-3 large-3";
			break;
		default:
			$classes[] = "small-12 medium-4 large-4";
	}
	?>
    <div class="widget-full-content-tab-container tabs-panel grid-container <?= $is_active; ?>" id="<?= $title; ?>">

        <li class="tabs-title ipa-single-card-widget cell <?= implode( " ", $classes ); ?> <?= $is_active; ?>">
            <a href="#<?= $title; ?>" aria-selected="true">
                <span class="ipa-single-card-widget-inner">
					<?php if ( ! empty( $atts['icon'] ) ) : ?>
						<?= wp_get_attachment_image( $atts['icon'], 'full', true, array( 'class' => 'ipa-single-card-widget-icon' ) ); ?>
					<?php endif; ?>

                    <h4 class="ipa-single-card-widget-title"><?= $atts['title']; ?></h4>

					<?php if ( ! empty( $description = $atts['description'] ) ) : ?>
                        <h5 class="text-color-dark-gray"><?= $description; ?></h5>
					<?php endif; ?>
                </span>
            </a>
        </li>

		<?= do_shortcode( $content ); ?>

    </div>

	<?php
}

add_shortcode( 'full_content_tab', 'widget_full_content_tab' );


// Integrate with Visual Composer
function full_content_tabs_integrateWithVC() {
	try {
		vc_map( array(
			"name"                    => __( "Full Content Tabs", "ipa" ),
			"base"                    => "full_content_tabs",
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
			"name"                    => __( "Full Content Tab", "ipa" ),
			"base"                    => "full_content_tab",
			"content_element"         => true,
			"show_settings_on_create" => false,
			"is_container"            => true,
			"category"                => __( "Custom", "ipa" ),
			"params"                  => array(
				array(
					"type"       => "textfield",
					"class"      => "",
					"heading"    => __( "Title", "ipa" ),
					"param_name" => "title",
					"value"      => __( "", "ipa" ),
				),
				array(
					"type"       => "textarea",
					"class"      => "",
					"heading"    => __( "Description", "ipa" ),
					"param_name" => "description",
					"value"      => __( "", "ipa" ),
				),
				array(
					"type"       => "attach_image",
					"class"      => "",
					"heading"    => __( "Icon", "ipa" ),
					"param_name" => "icon",
					"value"      => '',
				),
				array(
					"type"        => "checkbox",
					"class"       => "",
					"heading"     => __( "Is Active", "ipa" ),
					"param_name"  => "is_active",
					"value"       => __( "", "ipa" ),
					"description" => __( "Check for only the first tab.", "ipa" )
				),
				array(
					'type'        => 'dropdown',
					'heading'     => __( 'Column Size', "ipa" ),
					'param_name'  => 'columns',
					'value'       => array(
						__( 'Three', "ipa" ) => 'three',
						__( 'Four', "ipa" )    => 'four',
					),
					"description" => __( "", "ipa" )
				),
				array(
					"type"       => "textfield",
					"heading"    => __( "Extra class name", "ipa" ),
					"param_name" => "el_class",
				)
			),
			"js_view"                 => 'VcColumnView'
		) );

		if ( class_exists( 'WPBakeryShortCodesContainer' ) ) {
			class WPBakeryShortCode_Full_Content_Tabs extends WPBakeryShortCodesContainer {
			}
		}

		if ( class_exists( 'WPBakeryShortCode' ) ) {
			class WPBakeryShortCode_Full_Content_Tab extends WPBakeryShortCodesContainer {
			}
		}
	} catch ( Exception $e ) {
		echo 'Caught exception: ', $e->getMessage(), "\n";
	}
}

add_action( 'vc_before_init', 'full_content_tabs_integrateWithVC' );
