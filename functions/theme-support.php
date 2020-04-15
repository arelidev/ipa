<?php

// Adding WP Functions & Theme Support
function joints_theme_support() {

	// Add WP Thumbnail Support
	add_theme_support( 'post-thumbnails' );

	// Default thumbnail size
	set_post_thumbnail_size( 125, 125, true );

	// Add RSS Support
	add_theme_support( 'automatic-feed-links' );

	// Add Support for WP Controlled Title Tag
	add_theme_support( 'title-tag' );

	// Add HTML5 Support
	add_theme_support( 'html5',
		array(
			'comment-list',
			'comment-form',
			'search-form',
		)
	);

	add_theme_support( 'custom-logo', array(
		'height'      => 100,
		'width'       => 400,
		'flex-height' => true,
		'flex-width'  => true,
		'header-text' => array( 'site-title', 'site-description' ),
	) );

	// Adding post format support
	add_theme_support( 'post-formats',
		array(
			'aside',             // title less blurb
			'gallery',           // gallery of images
			'link',              // quick link to other site
			'image',             // an image
			'quote',             // a quick quote
			'status',            // a Facebook like status update
			'video',             // video
			'audio',             // audio
			'chat'               // chat transcript
		)
	);

	// Add excerpt field for pages
	add_post_type_support( 'page', 'excerpt' );

	// Set the maximum allowed width for any content in the theme, like oEmbeds and images added to posts.
	$GLOBALS['content_width'] = apply_filters( 'joints_theme_support', 1200 );

} /* end theme support */

add_action( 'after_setup_theme', 'joints_theme_support' );

// Allow SVG through WordPress Media Uploader
function cc_mime_types( $mimes ) {
	$mimes['svg'] = 'image/svg+xml';

	return $mimes;
}

add_filter( 'upload_mimes', 'cc_mime_types' );

// Hero image settings
function hero_image_css() {
	global $post;

	$post = $post->ID;

	if ( get_field( 'hero_type', $post ) == 'image' ) :
		echo "<style type='text/css'>";
		echo ".hero-image .hero-inner .hero-image-wrapper img {";

		if ( ! empty( $position = get_field( 'hero_image_position', $post ) ) ) {
			echo "object-position: {$position}; ";
		}

		if ( ! empty( $size = get_field( 'hero_image_size', $post ) ) ) {
			echo "object-fit: {$size}; ";
		}

		echo "}";
		echo "</style>";
	endif;
}

add_action( 'wp_head', 'hero_image_css' );
