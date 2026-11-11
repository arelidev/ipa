<?php
/**
 * ACF Google Map field: run the address search on the Places API (New).
 *
 * ACF's field uses google.maps.places.Autocomplete from the legacy Places API, which
 * our Google project can't enable, so the search box fails with
 * LegacyApiNotActivatedMapError. See assets/scripts/acf-google-map.js.
 *
 * acf/input/admin_enqueue_scripts also fires for front-end acf_form() screens, so this
 * covers the member edit-profile page as well as wp-admin.
 */
function ipa_acf_google_map_scripts() {
	$theme = wp_get_theme();

	wp_enqueue_script( 'ipa-acf-google-map', get_template_directory_uri() . '/assets/scripts/acf-google-map.js', [ 'acf-input' ], $theme->get( "Version" ), true );
}

add_action( 'acf/input/admin_enqueue_scripts', 'ipa_acf_google_map_scripts' );
