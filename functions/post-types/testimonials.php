<?php
// Register Custom Post Type
function testimonials_post_type() {

	$labels = array(
		'name'                  => _x( 'Testimonials', 'Post Type General Name', 'ipa' ),
		'singular_name'         => _x( 'Testimonial', 'Post Type Singular Name', 'ipa' ),
		'menu_name'             => __( 'Testimonials', 'ipa' ),
		'name_admin_bar'        => __( 'Testimonial', 'ipa' ),
		'archives'              => __( 'Testimonial Archives', 'ipa' ),
		'attributes'            => __( 'Testimonial Attributes', 'ipa' ),
		'parent_item_colon'     => __( 'Parent Testimonial:', 'ipa' ),
		'all_items'             => __( 'All Testimonials', 'ipa' ),
		'add_new_item'          => __( 'Add New Testimonial', 'ipa' ),
		'add_new'               => __( 'Add New', 'ipa' ),
		'new_item'              => __( 'New Testimonial', 'ipa' ),
		'edit_item'             => __( 'Edit Testimonial', 'ipa' ),
		'update_item'           => __( 'Update Testimonial', 'ipa' ),
		'view_item'             => __( 'View Testimonial', 'ipa' ),
		'view_items'            => __( 'View Testimonials', 'ipa' ),
		'search_items'          => __( 'Search Testimonial', 'ipa' ),
		'not_found'             => __( 'Not found', 'ipa' ),
		'not_found_in_trash'    => __( 'Not found in Trash', 'ipa' ),
		'featured_image'        => __( 'Featured Image', 'ipa' ),
		'set_featured_image'    => __( 'Set featured image', 'ipa' ),
		'remove_featured_image' => __( 'Remove featured image', 'ipa' ),
		'use_featured_image'    => __( 'Use as featured image', 'ipa' ),
		'insert_into_item'      => __( 'Insert into Testimonial', 'ipa' ),
		'uploaded_to_this_item' => __( 'Uploaded to this Testimonial', 'ipa' ),
		'items_list'            => __( 'Testimonials list', 'ipa' ),
		'items_list_navigation' => __( 'Testimonials list navigation', 'ipa' ),
		'filter_items_list'     => __( 'Filter Testimonials list', 'ipa' ),
	);
	$args = array(
		'label'                 => __( 'Testimonial', 'ipa' ),
		'description'           => __( 'Customer testimonials', 'ipa' ),
		'labels'                => $labels,
		'supports'              => array( 'title', 'editor', 'thumbnail' ),
		'hierarchical'          => false,
		'public'                => true,
		'show_ui'               => true,
		'show_in_menu'          => true,
		'menu_position'         => 20,
		'menu_icon'             => 'dashicons-format-quote',
		'show_in_admin_bar'     => true,
		'show_in_nav_menus'     => true,
		'can_export'            => true,
		'has_archive'           => false,
		'exclude_from_search'   => false,
		'publicly_queryable'    => true,
		'capability_type'       => 'page',
	);
	register_post_type( 'Testimonials', $args );

}
add_action( 'init', 'testimonials_post_type', 0 );