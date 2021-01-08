<?php
function ipa_directors_widget( $atts ) {
	$atts = shortcode_atts( array(
		'id' => ''
	), $atts );

	ob_start();

	// "10887, 33883"
	$directors = get_faculty( $atts['id'] );
	?>

	<?php foreach ( $directors as $director ) : ?>
		<div class="grid-container single-faculty-page">
			<div class="grid-x grid-padding-x">
				<div class="small-12 medium-4 large-4 cell">
					<div class="ipa-faculty-member styled-container">
						<img src="<?= get_instructor_image( $director['image'] ); ?>" class="ipa-faculty-member-image" alt="<?= $director['name']; ?>">
						<h3 class="ipa-faculty-member-name text-center"><b><?= $director['name']; ?></b></h3>
						<p class="ipa-faculty-member-credentials text-center text-color-medium-gray">
							<?= $director['credentials']; ?>
						</p>
					</div>
				</div>
				<div class="small-12 medium-8 large-7 large-offset-1 cell">
					<h5><b>About <?= $director['firstname']; ?></b></h5>
					<?= apply_filters( 'the_content', $director['bio'] ); ?>
				</div>
			</div>
		</div>
	<?php endforeach; ?>

	<?php
	return ob_get_clean();
}

add_shortcode( 'ipa_directors', 'ipa_directors_widget' );

// Integrate with Visual Composer
function ipa_directors_integrateWithVC() {
	try {
		vc_map( array(
			"name"                    => __( "Founding Directors", "ipa" ),
			"base"                    => "ipa_directors",
			"class"                   => "",
			"category"                => __( "Custom", "ipa" ),
			"params"                  => array(
				array(
					"type"        => "textfield",
					"heading"     => __( "Person ID", "ipa" ),
					"param_name"  => "id",
					"description" => __( "", "ipa" )
				)
			),
		) );
	} catch ( Exception $e ) {
		echo 'Caught exception: ', $e->getMessage(), "\n";
	}
}

add_action( 'vc_before_init', 'ipa_directors_integrateWithVC' );
