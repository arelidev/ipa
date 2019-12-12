<?php
/**
 * IPA Faculty Shortcode Widget
 *
 * @param $atts
 * @param null $content
 *
 * @return false|string
 */
function ipa_faculty_widget( $atts, $content = null ) {
	$atts = shortcode_atts( array(), $atts );

	ob_start();

	$faculty = get_faculty();
	?>

    <div class="ipa-faculty-widget grid-x grid-padding-x grid-padding-y grid-margin-x grid-margin-y">
		<?php foreach ( $faculty as $item => $value ) : ?>
            <div class="ipa-faculty-member small-12 medium-4 large-3 cell text-center styled-container">
                <div class="ipa-faulty-member-info">
					<?php
					if ( ! empty( $image = $value['image'] ) ) :
						$image_url = FACULTY_MEMBER_IMAGE_URL . $image;
					else :
						$image_url = "https://via.placeholder.com/500x500";
					endif; ?>
                    <img src="<?= $image_url; ?>" class="ipa-faculty-member-image"
                         alt="Image for <?= $value['name']; ?>">
                    <h5 class="ipa-faulty-member-name"><b><?= $value['name']; ?></b></h5>
                    <p class="ipa-faulty-member-credentials text-color-medium-gray"><?= $value['credentials']; ?></p>
                    <p class="ipa-faulty-member-city"><?= $value['billing_city'] . ", " . $value['billing_region']; ?></p>
                </div>
                <div class="ipa-faculty-member-bio text-center flex-container flex-dir-column align-center-middle">
                    <h5 class="ipa-faulty-member-name text-color-white"><b><?= $value['name']; ?></b></h5>
                    <p class="ipa-faculty-member-bio-copy text-color-white">
                        <small><?= wp_trim_words( $value['bio'], 20 ); ?></small>
                    </p>
                    <a href="<?= home_url(); ?>/faculty/<?= clean($value['name']); ?>/<?= $value['entity_id']; ?>" class="button hollow white">View Profile</a>
                </div>
            </div>
		<?php endforeach; ?>
    </div>

	<?php
	return ob_get_clean();
}

add_shortcode( 'ipa_faculty', 'ipa_faculty_widget' );

// Integrate with Visual Composer
function ipa_faculty_integrateWithVC() {
	try {
		vc_map( array(
			"name"                    => __( "IPA Faculty Grid", "ipa" ),
			"base"                    => "ipa_faculty",
			"class"                   => "",
			"category"                => __( "Custom", "ipa" ),
			"params"                  => array(),
			"show_settings_on_create" => false
		) );
	} catch ( Exception $e ) {
		echo 'Caught exception: ', $e->getMessage(), "\n";
	}
}

add_action( 'vc_before_init', 'ipa_faculty_integrateWithVC' );
