<?php
/**
 * @return void
 */
function ipa_add_woocommerce_support()
{
	add_theme_support('woocommerce');
}

add_action('after_setup_theme', 'ipa_add_woocommerce_support');

add_filter('woocommerce_show_page_title', '__return_null');


// Add custom ACF profile form
add_filter('woocommerce_account_menu_items', 'misha_log_history_link', 40);
function misha_log_history_link($menu_links)
{

	$menu_links = array_slice($menu_links, 0, 5, true)
		+ array('edit-profile' => 'Faculty Profile')
		+ array_slice($menu_links, 6, NULL, true);

	return $menu_links;

}

add_action('init', 'misha_add_endpoint');
function misha_add_endpoint()
{

	// WP_Rewrite is my Achilles' heel, so please do not ask me for detailed explanation
	add_rewrite_endpoint('edit-profile', EP_PAGES);

}

add_action('woocommerce_account_edit-profile_endpoint', 'misha_my_account_endpoint_content');
function misha_my_account_endpoint_content()
{
	acf_form_head();

	$user = 'user_' . get_current_user_id();

	$settings = [
		"post_id" => $user,
		'submit_value' => __("Update profile", 'acf'),
		'updated_message' => __("Profile updated", 'acf'),
		'html_updated_message' => '<div id="message" class="updated callout success"><p>%s</p></div>',
		'fields' => [
			'image',
			'bio',
			'credentials',
			'work_information',
			'social_profiles',
			'offices'
		],
	];

	acf_form( $settings );
}