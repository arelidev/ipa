<?php
function ipa_arlo_events()
{

	$labels = array(
		'name' => _x('Events', 'Post Type General Name', 'ipa'),
		'singular_name' => _x('Event', 'Post Type Singular Name', 'ipa'),
		'menu_name' => __('Arlo Events', 'ipa'),
		'name_admin_bar' => __('Events', 'ipa'),
		'archives' => __('Event Archives', 'ipa'),
		'attributes' => __('Event Attributes', 'ipa'),
		'parent_item_colon' => __('Parent Event:', 'ipa'),
		'all_items' => __('All Events', 'ipa'),
		'add_new_item' => __('Add New Event', 'ipa'),
		'add_new' => __('Add New', 'ipa'),
		'new_item' => __('New Event', 'ipa'),
		'edit_item' => __('Edit Event', 'ipa'),
		'update_item' => __('Update Event', 'ipa'),
		'view_item' => __('View Event', 'ipa'),
		'view_items' => __('View Events', 'ipa'),
		'search_items' => __('Search Event', 'ipa'),
		'not_found' => __('Not found', 'ipa'),
		'not_found_in_trash' => __('Not found in Trash', 'ipa'),
		'featured_image' => __('Featured Image', 'ipa'),
		'set_featured_image' => __('Set featured image', 'ipa'),
		'remove_featured_image' => __('Remove featured image', 'ipa'),
		'use_featured_image' => __('Use as featured image', 'ipa'),
		'insert_into_item' => __('Insert into Event', 'ipa'),
		'uploaded_to_this_item' => __('Uploaded to this Event', 'ipa'),
		'items_list' => __('Events list', 'ipa'),
		'items_list_navigation' => __('Events list navigation', 'ipa'),
		'filter_items_list' => __('Filter Events list', 'ipa'),
	);
	$args = array(
		'label' => __('Event', 'ipa'),
		'labels' => $labels,
		'supports' => array('title', 'editor', 'thumbnail'),
		'hierarchical' => false,
		'public' => true,
		'show_ui' => true,
		'show_in_menu' => true,
		'menu_position' => 20,
		'menu_icon' => 'dashicons-calendar',
		'show_in_admin_bar' => false,
		'show_in_nav_menus' => false,
		'can_export' => true,
		'has_archive' => true,
		'exclude_from_search' => false,
		'publicly_queryable' => true,
		'capability_type' => 'page',
	);
	register_post_type('ipa_arlo_events', $args);

}

add_action('init', 'ipa_arlo_events', 0);