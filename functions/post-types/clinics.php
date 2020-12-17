<?php
// Register Custom Post Type
function clinics_post_type() {

	$labels = array(
		'name'                  => _x( 'Clinics', 'Post Type General Name', 'ipa' ),
		'singular_name'         => _x( 'Clinic', 'Post Type Singular Name', 'ipa' ),
		'menu_name'             => __( 'Clinics', 'ipa' ),
		'name_admin_bar'        => __( 'Clinic', 'ipa' ),
		'archives'              => __( 'Clinic Archives', 'ipa' ),
		'attributes'            => __( 'Clinic Attributes', 'ipa' ),
		'parent_item_colon'     => __( 'Parent Clinic:', 'ipa' ),
		'all_items'             => __( 'All Clinics', 'ipa' ),
		'add_new_item'          => __( 'Add New Clinic', 'ipa' ),
		'add_new'               => __( 'Add New', 'ipa' ),
		'new_item'              => __( 'New Clinic', 'ipa' ),
		'edit_item'             => __( 'Edit Clinic', 'ipa' ),
		'update_item'           => __( 'Update Clinic', 'ipa' ),
		'view_item'             => __( 'View Clinic', 'ipa' ),
		'view_items'            => __( 'View Clinics', 'ipa' ),
		'search_items'          => __( 'Search Clinic', 'ipa' ),
		'not_found'             => __( 'Not found', 'ipa' ),
		'not_found_in_trash'    => __( 'Not found in Trash', 'ipa' ),
		'featured_image'        => __( 'Featured Image', 'ipa' ),
		'set_featured_image'    => __( 'Set featured image', 'ipa' ),
		'remove_featured_image' => __( 'Remove featured image', 'ipa' ),
		'use_featured_image'    => __( 'Use as featured image', 'ipa' ),
		'insert_into_item'      => __( 'Insert into Clinic', 'ipa' ),
		'uploaded_to_this_item' => __( 'Uploaded to this Clinic', 'ipa' ),
		'items_list'            => __( 'Clinics list', 'ipa' ),
		'items_list_navigation' => __( 'Clinics list navigation', 'ipa' ),
		'filter_items_list'     => __( 'Filter Clinics list', 'ipa' ),
	);
	$args = array(
		'label'                 => __( 'Clinic', 'ipa' ),
		'description'           => __( 'IPA Clinics', 'ipa' ),
		'labels'                => $labels,
		'supports'              => array( 'title', 'editor', 'thumbnail' ),
		'hierarchical'          => false,
		'public'                => true,
		'show_ui'               => true,
		'show_in_menu'          => true,
		'menu_position'         => 20,
		'menu_icon'             => 'dashicons-location',
		'show_in_admin_bar'     => true,
		'show_in_nav_menus'     => true,
		'can_export'            => true,
		'has_archive'           => false,
		'exclude_from_search'   => false,
		'publicly_queryable'    => true,
		'rewrite'               => false,
		'capability_type'       => 'page',
	);
	register_post_type( 'ipa_clinics', $args );

}
add_action( 'init', 'clinics_post_type', 0 );
