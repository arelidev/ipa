<?php
/**
 * For more info: https://developer.wordpress.org/themes/basics/theme-functions/
 *
 */

define( 'GOOGLE_MAPS_API_KEY', 'AIzaSyBk2PyekdgVweld0_xj61_mogipGPiHGEc' );

// Connect to the remote database
require_once( get_template_directory() . '/functions/remote-db.php' );

// TGM plugin activation
require_once( get_template_directory() . '/functions/tgm/tgm-init.php' );

// Theme support options
require_once( get_template_directory() . '/functions/theme-support.php' );

// WP Head and other cleanup functions
require_once( get_template_directory() . '/functions/cleanup.php' );

// Register scripts and stylesheets
require_once( get_template_directory() . '/functions/enqueue-scripts.php' );

// Register custom menus and menu walkers
require_once( get_template_directory() . '/functions/menu.php' );

// Register sidebars/widget areas
require_once( get_template_directory() . '/functions/sidebar.php' );

// Makes WordPress comments suck less
require_once( get_template_directory() . '/functions/comments.php' );

// Replace 'older/newer' post links with numbered navigation
require_once( get_template_directory() . '/functions/page-navi.php' );

// Adds support for multiple languages
require_once( get_template_directory() . '/functions/translation/translation.php' );

// Adds site styles to the WordPress editor
require_once( get_template_directory() . '/functions/editor-styles.php' );

// Remove Emoji Support
require_once( get_template_directory() . '/functions/disable-emoji.php' );

// Related post function - no need to rely on plugins
// require_once(get_template_directory().'/functions/related-posts.php');

// Use this as a template for custom post types
require_once( get_template_directory() . '/functions/custom-post-type.php' );

// Customize the WordPress login menu
require_once( get_template_directory() . '/functions/login.php' );

// Customize the WordPress admin
require_once( get_template_directory() . '/functions/admin.php' );

// Register custom API endpoints
require_once( get_template_directory() . '/functions/custom-api.php' );

// Include all widget files dynamically
foreach ( scandir( get_template_directory() . '/functions/widgets/' ) as $filename ) {
	$path = get_template_directory() . '/functions/widgets/' . $filename;
	if ( is_file( $path ) ) {
		require_once $path;
	}
}

// Custom Rewrites
require_once( get_template_directory() . '/functions/rewrite-faculty.php' );

// Enabled ACF Pro theme options
// https://www.advancedcustomfields.com/resources/options-page/
if ( function_exists( 'acf_add_options_page' ) ) {
	acf_add_options_page();
}

// Add global Google API key for ACF Pro
// https://www.advancedcustomfields.com/blog/google-maps-api-settings/
function my_acf_init() {
	acf_update_setting( 'google_api_key', GOOGLE_MAPS_API_KEY );
}

add_action( 'acf/init', 'my_acf_init' );

/**
 * Slugify string
 * todo: remove this function, it's redundant with acf_slugify()
 *
 * @param $string
 *
 * @return string|string[]|null
 */
function clean( $string ) {
	$string = str_replace( ' ', '-', $string ); // Replaces all spaces with hyphens.

	return preg_replace( '/[^A-Za-z0-9\-]/', '', $string ); // Removes special chars.
}

/**
 * @param $id
 * @param $array
 *
 * @return int|string|null
 */
function searchForId( $id, $array ) {
	foreach ( $array as $key => $val ) {
		if ( $val['category'] === $id ) {
			return $key;
		}
	}

	return null;
}
