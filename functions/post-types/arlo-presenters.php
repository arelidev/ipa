<?php
function ipa_arlo_presenters()
{

	$labels = array(
		'name' => _x('Presenters', 'Post Type General Name', 'ipa'),
		'singular_name' => _x('Presenter', 'Post Type Singular Name', 'ipa'),
		'menu_name' => __('Arlo Presenters', 'ipa'),
		'name_admin_bar' => __('Presenters', 'ipa'),
		'archives' => __('Presenter Archives', 'ipa'),
		'attributes' => __('Presenter Attributes', 'ipa'),
		'parent_item_colon' => __('Parent Presenter:', 'ipa'),
		'all_items' => __('All Presenters', 'ipa'),
		'add_new_item' => __('Add New Presenter', 'ipa'),
		'add_new' => __('Add New', 'ipa'),
		'new_item' => __('New Presenter', 'ipa'),
		'edit_item' => __('Edit Presenter', 'ipa'),
		'update_item' => __('Update Presenter', 'ipa'),
		'view_item' => __('View Presenter', 'ipa'),
		'view_items' => __('View Presenter', 'ipa'),
		'search_items' => __('Search Presenter', 'ipa'),
		'not_found' => __('Not found', 'ipa'),
		'not_found_in_trash' => __('Not found in Trash', 'ipa'),
		'featured_image' => __('Featured Image', 'ipa'),
		'set_featured_image' => __('Set featured image', 'ipa'),
		'remove_featured_image' => __('Remove featured image', 'ipa'),
		'use_featured_image' => __('Use as featured image', 'ipa'),
		'insert_into_item' => __('Insert into Presenter', 'ipa'),
		'uploaded_to_this_item' => __('Uploaded to this Presenter', 'ipa'),
		'items_list' => __('Presenters list', 'ipa'),
		'items_list_navigation' => __('Presenters list navigation', 'ipa'),
		'filter_items_list' => __('Filter Presenters list', 'ipa'),
	);
	$args = array(
		'label' => __('Presenter', 'ipa'),
		'labels' => $labels,
		'supports' => array('title', 'editor', 'thumbnail'),
		'hierarchical' => false,
		'public' => true,
		'show_ui' => true,
		'show_in_menu' => true,
		'menu_position' => 20,
		'menu_icon' => 'dashicons-admin-users',
		'show_in_admin_bar' => false,
		'show_in_nav_menus' => false,
		'can_export' => true,
		'has_archive' => true,
		'exclude_from_search' => false,
		'publicly_queryable' => true,
		'capability_type' => 'page',
	);
	register_post_type('ipa_arlo_presenters', $args);

}

add_action('init', 'ipa_arlo_presenters', 0);