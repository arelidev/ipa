<?php
function site_scripts() {
	global $wp_styles; // Call global $wp_styles variable to add conditional wrapper around ie stylesheet the WordPress way

	// Adding scripts file in the footer
	wp_enqueue_script( 'easepick', 'https://cdn.jsdelivr.net/npm/@easepick/bundle@1.2.0/dist/index.umd.min.js', array(), false, true );

	wp_enqueue_script( 'googleapis', 'https://maps.googleapis.com/maps/api/js?key=' . GOOGLE_MAPS_API_KEY, array( 'jquery' ), false, true );
	wp_enqueue_script( 'site-js', get_template_directory_uri() . '/assets/scripts/scripts.js', array( 'jquery' ), filemtime( get_template_directory() . '/assets/scripts/js' ), true );

	// Register main stylesheet
	wp_enqueue_style( 'site-css', get_template_directory_uri() . '/assets/styles/style.css', array(), filemtime( get_template_directory() . '/assets/styles/scss' ), 'all' );

	// Comment reply script for threaded comments
	if ( is_singular() and comments_open() and ( get_option( 'thread_comments' ) == 1 ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}

add_action( 'wp_enqueue_scripts', 'site_scripts', 999 );
