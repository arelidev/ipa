<?php
add_filter( 'query_vars', 'faculty_rewrite_add_var' );
function faculty_rewrite_add_var( $vars ) {
	$vars[] = 'faculty_name';
	$vars[] = 'faculty_id';

	return $vars;
}

add_action( 'init', 'add_faculty_rewrite_rule' );
function add_faculty_rewrite_rule() {
	add_rewrite_tag( '%profile%', '([^&]+)' );
	add_rewrite_rule(
		'^profile/([^/]*)/([^/]*)/?',
		'index.php?faculty_name=$matches[1]&faculty_id=$matches[2]',
		'top'
	);
}

add_action( 'template_redirect', 'faculty_rewrite_catch' );
function faculty_rewrite_catch() {
	global $wp_query;

	if ( array_key_exists( 'faculty_name', $wp_query->query_vars ) ) {
		include( get_stylesheet_directory() . '/single-faculty.php' );
		exit;
	}
}
