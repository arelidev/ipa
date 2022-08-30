<?php
function ipa_arlo_venues()
{

	$labels = array(
		'name' => _x('Venues', 'Post Type General Name', 'ipa'),
		'singular_name' => _x('Venue', 'Post Type Singular Name', 'ipa'),
		'menu_name' => __('Arlo Venues', 'ipa'),
		'name_admin_bar' => __('Venues', 'ipa'),
		'archives' => __('Venue Archives', 'ipa'),
		'attributes' => __('Venue Attributes', 'ipa'),
		'parent_item_colon' => __('Parent Venue:', 'ipa'),
		'all_items' => __('All Venues', 'ipa'),
		'add_new_item' => __('Add New Venue', 'ipa'),
		'add_new' => __('Add New', 'ipa'),
		'new_item' => __('New Venue', 'ipa'),
		'edit_item' => __('Edit Venue', 'ipa'),
		'update_item' => __('Update Venue', 'ipa'),
		'view_item' => __('View Venue', 'ipa'),
		'view_items' => __('View Venue', 'ipa'),
		'search_items' => __('Search Venue', 'ipa'),
		'not_found' => __('Not found', 'ipa'),
		'not_found_in_trash' => __('Not found in Trash', 'ipa'),
		'featured_image' => __('Featured Image', 'ipa'),
		'set_featured_image' => __('Set featured image', 'ipa'),
		'remove_featured_image' => __('Remove featured image', 'ipa'),
		'use_featured_image' => __('Use as featured image', 'ipa'),
		'insert_into_item' => __('Insert into Venue', 'ipa'),
		'uploaded_to_this_item' => __('Uploaded to this Venue', 'ipa'),
		'items_list' => __('Venues list', 'ipa'),
		'items_list_navigation' => __('Venue list navigation', 'ipa'),
		'filter_items_list' => __('Filter Venues list', 'ipa'),
	);
	$args = array(
		'label' => __('Venue', 'ipa'),
		'labels' => $labels,
		'supports' => array('title', 'editor', 'thumbnail'),
		'hierarchical' => false,
		'public' => true,
		'show_ui' => true,
		'show_in_menu' => true,
		'menu_position' => 20,
		'menu_icon' => 'dashicons-building',
		'show_in_admin_bar' => false,
		'show_in_nav_menus' => false,
		'can_export' => true,
		'has_archive' => true,
		'exclude_from_search' => false,
		'publicly_queryable' => true,
		'capability_type' => 'page',
	);
	register_post_type('ipa_arlo_venues', $args);

}

add_action('init', 'ipa_arlo_venues', 0);