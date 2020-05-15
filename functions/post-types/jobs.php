<?php
// Register Custom Post Type
function jobs_post_type() {

	$labels = array(
		'name'                  => _x( 'Job Postings', 'Post Type General Name', 'ipa' ),
		'singular_name'         => _x( 'Job Posting', 'Post Type Singular Name', 'ipa' ),
		'menu_name'             => __( 'Job Posting', 'ipa' ),
		'name_admin_bar'        => __( 'Job Posting', 'ipa' ),
		'archives'              => __( 'Job Archives', 'ipa' ),
		'attributes'            => __( 'Job Attributes', 'ipa' ),
		'parent_item_colon'     => __( 'Parent Job:', 'ipa' ),
		'all_items'             => __( 'All Jobs', 'ipa' ),
		'add_new_item'          => __( 'Add New Job', 'ipa' ),
		'add_new'               => __( 'Add New', 'ipa' ),
		'new_item'              => __( 'New Job', 'ipa' ),
		'edit_item'             => __( 'Edit Job', 'ipa' ),
		'update_item'           => __( 'Update Job', 'ipa' ),
		'view_item'             => __( 'View Job', 'ipa' ),
		'view_items'            => __( 'View Jobs', 'ipa' ),
		'search_items'          => __( 'Search Job', 'ipa' ),
		'not_found'             => __( 'Not found', 'ipa' ),
		'not_found_in_trash'    => __( 'Not found in Trash', 'ipa' ),
		'featured_image'        => __( 'Featured Image', 'ipa' ),
		'set_featured_image'    => __( 'Set featured image', 'ipa' ),
		'remove_featured_image' => __( 'Remove featured image', 'ipa' ),
		'use_featured_image'    => __( 'Use as featured image', 'ipa' ),
		'insert_into_item'      => __( 'Insert into Job', 'ipa' ),
		'uploaded_to_this_item' => __( 'Uploaded to this Job', 'ipa' ),
		'items_list'            => __( 'Jobs list', 'ipa' ),
		'items_list_navigation' => __( 'Jobs list navigation', 'ipa' ),
		'filter_items_list'     => __( 'Filter Jobs list', 'ipa' ),
	);
	$args = array(
		'label'                 => __( 'Job Posting', 'ipa' ),
		'description'           => __( 'IPA job listings', 'ipa' ),
		'labels'                => $labels,
		'supports'              => array( 'title' ),
		'hierarchical'          => false,
		'public'                => true,
		'show_ui'               => true,
		'show_in_menu'          => true,
		'menu_position'         => 20,
		'menu_icon'             => 'dashicons-feedback',
		'show_in_admin_bar'     => true,
		'show_in_nav_menus'     => true,
		'can_export'            => true,
		'has_archive'           => false,
		'exclude_from_search'   => false,
		'publicly_queryable'    => true,
		'rewrite'               => false,
		'capability_type'       => 'page',
	);
	register_post_type( 'ipa_jobs', $args );

}
add_action( 'init', 'jobs_post_type', 0 );
