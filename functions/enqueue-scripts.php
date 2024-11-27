<?php
function site_scripts() {
	global $wp_styles;

	$theme = wp_get_theme();

	wp_enqueue_script( 'easepick', 'https://cdn.jsdelivr.net/npm/@easepick/bundle@1.2.0/dist/index.umd.min.js', [], false, true );
	wp_enqueue_script( 'fontawesome', 'https://kit.fontawesome.com/e0253d37a7.js', [], false, true );
	wp_enqueue_script( 'googleapis', 'https://maps.googleapis.com/maps/api/js?key=' . GOOGLE_MAPS_API_KEY, [ 'jquery' ], false, true );
	wp_enqueue_script( 'site-js', get_template_directory_uri() . '/assets/scripts/scripts.js', [ 'jquery' ], $theme->get( "Version" ), true );

	wp_enqueue_style( 'site-css', get_template_directory_uri() . '/assets/styles/style.css', [], $theme->get( "Version" ) );

	if ( is_singular() and comments_open() and ( get_option( 'thread_comments' ) == 1 ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}

add_action( 'wp_enqueue_scripts', 'site_scripts', 999 );
