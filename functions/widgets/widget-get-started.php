<?php
function ipa_get_started_widget( $atts, $content = null ) {
	$atts = shortcode_atts( array(), $atts );
	ob_start();
	?>
	<div class="get-started-widget">
		<div class="grid-container get-started">
			<div class="get-started-inner">
				<div class="grid-x grid-padding-x align-center-middle">
					<div class="shrink cell">
						<h3 class="text-center large-text-left"><b>I am a...</b></h3>
					</div>
					<div class="shrink large-auto cell">
						<div class="select-box">
							<div class="select-box__current" tabindex="1">
								<div class="select-box__value">
									<input class="select-box__input" type="radio" id="0" value="1" name="Ben" checked="checked"/>
									<p class="select-box__input-text">Certified PT</p>
								</div>
								<div class="select-box__value">
									<input class="select-box__input" type="radio" id="1" value="2" name="Ben" checked="checked"/>
									<p class="select-box__input-text">Student</p>
								</div>
								<img class="select-box__icon" src="http://cdn.onlinewebfonts.com/svg/img_295694.svg" alt="Arrow Icon" aria-hidden="true"/>
							</div>
							<ul class="select-box__list">
								<li>
									<label class="select-box__option" for="0" aria-hidden="true">Certified PT</label>
								</li>
								<li>
									<label class="select-box__option" for="1" aria-hidden="true">Student</label>
								</li>
							</ul>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<?php
	return ob_get_clean();
}

add_shortcode( 'ipa_get_started', 'ipa_get_started_widget' );

// Integrate with Visual Composer
function ipa_get_started_integrateWithVC() {
	try {
		vc_map( array(
			"name"                    => __( "Get Started", "ipa" ),
			"base"                    => "ipa_get_started",
			"class"                   => "",
			"category"                => __( "Custom", "ipa" ),
			"params"                  => array(),
			"show_settings_on_create" => false
		) );
	} catch ( Exception $e ) {
		echo 'Caught exception: ', $e->getMessage(), "\n";
	}
}

add_action( 'vc_before_init', 'ipa_get_started_integrateWithVC' );