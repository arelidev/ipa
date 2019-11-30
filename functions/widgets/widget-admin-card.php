<?php
function ipa_admin_card_widget( $atts, $content = null ) {
	$atts = shortcode_atts( array(
		'el_class' => ''
	), $atts );

	ob_start();
	?>
    <div class="ipa-admin-card-widget grid-x grid-margin-x grid-padding-x" data-equalizer="ipa-admin-card-widget"
         data-equalize-by-row="true">
		<?= do_shortcode( $content ); ?>
    </div>
	<?php
	return ob_get_clean();
}

add_shortcode( 'ipa_admin_card', 'ipa_admin_card_widget' );

function ipa_single_admin_card_widget( $atts, $content = null ) {
	$atts = shortcode_atts( array(
		'name'     => '',
		'title'    => '',
		'image'    => '',
		'email'    => '',
		'el_class' => ''
	), $atts );
	?>
    <div class="ipa-single-admin-card-widget styled-container small-12 medium-12 large-6 cell <?= $atts['size']; ?>"
         data-equalizer-watch="ipa-admin-card-widget">
        <div class="ipa-single-admin-card-widget-inner">
            <div class="grid-x grid-padding-x align-bottom">
				<?php if ( ! empty( $image = $atts['image'] ) ) : ?>
                    <div class="small-12 medium-shrink cell">
						<?= wp_get_attachment_image( $image, 'medium', false, array( 'class' => 'ipa-single-admin-card-widget-image' ) ); ?>
                    </div>
				<?php endif; ?>
                <div class="auto cell">
                    <h4 class="ipa-single-admin-card-widget-name"><b><?= $atts['name']; ?></b></h4>
                    <p class="ipa-single-admin-card-widget-title text-color-medium-gray"><?= $atts['title']; ?></p>
                </div>
                <div class="cell">
                    <hr>
                </div>
            </div>
            <div class="grid-x grid-padding-x">
				<?php if ( ! empty( $email = $atts['email'] ) ) : ?>
                    <div class="cell">
                        <p><i class="fal fa-envelope fa-lg"></i> <a href="mailto:<?= $email; ?>"><?= $email; ?></a></p>
                    </div>
				<?php endif; ?>
                <div class="cell text-color-dark-gray">
					<?= apply_filters( 'the_content', $content ); ?>
                </div>
            </div>
        </div>
    </div>
	<?php
}

add_shortcode( 'ipa_single_admin_card', 'ipa_single_admin_card_widget' );

// Integrate with Visual Composer
function ipa_admin_card_integrateWithVC() {
	try {
		vc_map( array(
			"name"                    => __( "Admin Cards", "my-text-domain" ),
			"base"                    => "ipa_admin_card",
			"as_parent"               => array( 'only' => 'ipa_single_admin_card' ),
			"content_element"         => true,
			"show_settings_on_create" => false,
			"is_container"            => true,
			"category"                => __( "Custom", "ipa" ),
			"params"                  => array(
				array(
					"type"        => "textfield",
					"heading"     => __( "Extra class name", "my-text-domain" ),
					"param_name"  => "el_class",
					"description" => __( "", "my-text-domain" )
				)
			),
			"js_view"                 => 'VcColumnView'
		) );

		vc_map( array(
			"name"            => __( "Single Card", "my-text-domain" ),
			"base"            => "ipa_single_admin_card",
			"content_element" => true,
			"as_child"        => array( 'only' => 'ipa_admin_card' ),
			"params"          => array(
				array(
					"type"        => "textfield",
					"heading"     => __( "Name", "my-text-domain" ),
					"param_name"  => "name",
					"holder"      => "h3",
					"description" => __( "", "my-text-domain" )
				),
				array(
					"type"        => "textfield",
					"heading"     => __( "Title", "my-text-domain" ),
					"param_name"  => "title",
					"description" => __( "", "my-text-domain" )
				),
				array(
					"type"        => "textfield",
					"heading"     => __( "Email", "my-text-domain" ),
					"param_name"  => "email",
					"description" => __( "", "my-text-domain" )
				),
				array(
					"type"        => "attach_image",
					"class"       => "",
					"heading"     => __( "Image", "my-text-domain" ),
					"param_name"  => "image",
					"value"       => '',
					"description" => __( "Enter description.", "my-text-domain" )
				),
				array(
					"type"        => "textarea_html",
					"class"       => "",
					"heading"     => __( "Content", "my-text-domain" ),
					"param_name"  => "content",
					"value"       => '',
					"description" => __( "Enter description.", "my-text-domain" )
				),
				array(
					"type"        => "textfield",
					"heading"     => __( "Extra class name", "my-text-domain" ),
					"param_name"  => "el_class",
					"description" => __( "", "my-text-domain" )
				)
			)
		) );

		if ( class_exists( 'WPBakeryShortCodesContainer' ) ) {
			class WPBakeryShortCode_Ipa_Admin_Card extends WPBakeryShortCodesContainer {
			}
		}

		if ( class_exists( 'WPBakeryShortCode' ) ) {
			class WPBakeryShortCode_Ipa_Single_Admin_Card extends WPBakeryShortCode {
			}
		}
	} catch ( Exception $e ) {
		echo 'Caught exception: ', $e->getMessage(), "\n";
	}
}

add_action( 'vc_before_init', 'ipa_admin_card_integrateWithVC' );
