<?php
// Register Custom Post Type
function slider_post_type() {

	$labels = array(
		'name'                  => _x( 'Sliders', 'Post Type General Name', 'ipa' ),
		'singular_name'         => _x( 'Slider', 'Post Type Singular Name', 'ipa' ),
		'menu_name'             => __( 'Sliders', 'ipa' ),
		'name_admin_bar'        => __( 'Slider', 'ipa' ),
		'archives'              => __( 'Slider Archives', 'ipa' ),
		'attributes'            => __( 'Slider Attributes', 'ipa' ),
		'parent_item_colon'     => __( 'Parent Slider:', 'ipa' ),
		'all_items'             => __( 'All Sliders', 'ipa' ),
		'add_new_item'          => __( 'Add New Slider', 'ipa' ),
		'add_new'               => __( 'Add New', 'ipa' ),
		'new_item'              => __( 'New Slider', 'ipa' ),
		'edit_item'             => __( 'Edit Slider', 'ipa' ),
		'update_item'           => __( 'Update Slider', 'ipa' ),
		'view_item'             => __( 'View Slider', 'ipa' ),
		'view_items'            => __( 'View Sliders', 'ipa' ),
		'search_items'          => __( 'Search Slider', 'ipa' ),
		'not_found'             => __( 'Not found', 'ipa' ),
		'not_found_in_trash'    => __( 'Not found in Trash', 'ipa' ),
		'featured_image'        => __( 'Featured Image', 'ipa' ),
		'set_featured_image'    => __( 'Set featured image', 'ipa' ),
		'remove_featured_image' => __( 'Remove featured image', 'ipa' ),
		'use_featured_image'    => __( 'Use as featured image', 'ipa' ),
		'insert_into_item'      => __( 'Insert into Slider', 'ipa' ),
		'uploaded_to_this_item' => __( 'Uploaded to this Slider', 'ipa' ),
		'items_list'            => __( 'Sliders list', 'ipa' ),
		'items_list_navigation' => __( 'Sliders list navigation', 'ipa' ),
		'filter_items_list'     => __( 'Filter Sliders list', 'ipa' ),
	);
	$args = array(
		'label'                 => __( 'Slider', 'ipa' ),
		'description'           => __( 'Slider post type', 'ipa' ),
		'labels'                => $labels,
		'supports'              => array( 'title' ),
		'hierarchical'          => false,
		'public'                => true,
		'show_ui'               => true,
		'show_in_menu'          => true,
		'menu_position'         => 20,
		'menu_icon'             => 'dashicons-slides',
		'show_in_admin_bar'     => true,
		'show_in_nav_menus'     => true,
		'can_export'            => true,
		'has_archive'           => false,
		'exclude_from_search'   => false,
		'publicly_queryable'    => true,
		'rewrite'               => false,
		'capability_type'       => 'page',
	);
	register_post_type( 'slider', $args );

}
add_action( 'init', 'slider_post_type', 0 );
