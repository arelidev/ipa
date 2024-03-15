<?php
/**
 * For more info: https://developer.wordpress.org/themes/basics/theme-functions/
 *
 */

const GOOGLE_MAPS_API_KEY = 'AIzaSyBML3WPXpFj6VT-fE8QNWoO80WH-WhO-hU';

// Arlo helper functions
require_once( get_template_directory() . '/functions/arlo.php' );

// Connect to the remote database
// @depreciated since 2.0
// require_once( get_template_directory() . '/functions/remote-db.php' );

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

// Customer user lookups
require_once( get_template_directory() . '/functions/custom-user.php' );

// Customize the WordPress login menu
require_once( get_template_directory() . '/functions/login.php' );

// Customize the WordPress admin
require_once( get_template_directory() . '/functions/admin.php' );

// Register custom API endpoints
require_once( get_template_directory() . '/functions/custom-api.php' );

// Register WooCommerce support
require_once( get_template_directory() . '/functions/woocommerce.php' );

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
 *
 * TODO: remove this function, it's redundant with acf_slugify()
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

/**
 * Get Instructor Image
 *
 * @param int $image
 * @param string $classes
 * @param bool $thumbnail
 *
 * @return string
 */
function get_profile_image( int $image, string $classes = '', bool $thumbnail = false ): string {
	$stored  = get_field( 'default_instructor_image', 'options' );
	$default = ( ! empty( $stored ) ) ? $stored : get_template_directory_uri() . "/assets/images/ipa-placeholder.jpg";

	if ( ! empty( $image ) ) :
		$image_url = wp_get_attachment_image( $image, 'large', false, array( 'class' => $classes ) );
	else :
		$image_url = "<img src='$default' class='$classes' alt='' aria-hidden='true'>";
	endif;

	return $image_url;
}

/**
 * @return mixed|string
 */
function get_placeholder_image() {
	$stored = get_field( 'default_instructor_image', 'options' );

	return ( ! empty( $stored ) ) ? $stored : get_template_directory_uri() . "/assets/images/ipa-placeholder.jpg";
}

function add_query_vars_filter( $vars ) {
	$vars[] = "display";

	return $vars;
}

add_filter( 'query_vars', 'add_query_vars_filter' );

/**
 * Map course links for template codes
 *
 * TODO: Replace with ACF options page
 *
 * @param $code
 *
 * @return false|string
 */
function getCoursePermalink( $code ) {
	$linkMaps = [
		"CFS"  => "/scheduled-courses/cfs-corefirst-strategies/",
		"PNF"  => "/scheduled-courses/pnf-functional-neuromuscular-and-motor-control-training/",
		"FM 1" => "/scheduled-courses/fm-i-functional-mobilization-i/",
		"FMLE" => "/scheduled-courses/fmle-functional-mobilization-lower-extremities/",
		"FMLT" => "/scheduled-courses/fmlt-functional-mobilization-lower-trunk/",
		"FMUE" => "/scheduled-courses/fmue-functional-mobilization-upper-extremities/",
		"FMUT" => "/scheduled-courses/fmut-functional-mobilization-upper-trunk/",
		"CBI"  => "/scheduled-courses/cbi-complete-body-integration/",
		"GAIT" => "/scheduled-courses/gait-functional-gait/",
		"VFM"  => "/scheduled-courses/vfm-visceral-functional-mobilization/",
		"CRS"  => "/scheduled-courses/crs-continence-reproduction-and-sex/",
		"DFA"  => "/scheduled-courses/dfa-dynamic-foot-and-ankle/",
		"KJD"  => "/scheduled-courses/kjd-knee-junction-dilemma/",
		"KSC"  => "/scheduled-courses/ksc-kinetic-shoulder-complex/",
		"PGP"  => "/scheduled-courses/pgp-the-pelvic-girdle-puzzle/",
		"SOP"  => "/scheduled-courses/sop-strategies-for-optimizing-performance/",
		"REM"  => "/scheduled-courses/rem-resistance-enhanced-manipulation/"
	];

	return $linkMaps[ $code ] ?? false;
}