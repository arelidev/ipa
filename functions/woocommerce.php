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
if (is_user_logged_in()) :
	$user = wp_get_current_user();
	$roles = $user->roles;

	if (in_array('profile_member', $roles)) :

		add_filter('woocommerce_account_menu_items', 'log_history_link', 40);
		function log_history_link($menu_links)
		{
			return array_slice($menu_links, 0, 5, true)
				+ array('edit-profile' => 'Profile Member')
				+ array_slice($menu_links, 6, NULL, true);
		}

		add_action('init', 'add_endpoint');
		function add_endpoint()
		{
			add_rewrite_endpoint('edit-profile', EP_PAGES);
		}

		add_action('woocommerce_account_edit-profile_endpoint', 'my_account_endpoint_content');
		function my_account_endpoint_content()
		{
			acf_form_head();

			$user_id = get_current_user_id();
			$user_meta = get_user_meta($user_id);

			$name = acf_slugify($user_meta['first_name'][0] . " " . $user_meta['last_name'][0]);
			?>
            <div class="grid-x grid-padding-x align-center-middle">
                <div class="small-12 medium-auto">
                    <h4>Edit your profile</h4>
                </div>
                <div class="small-12 medium-shrink">
                    <a href='<?= home_url('/profile/' . $name . '/' . get_current_user_id()); ?>'
                       class='button small-only-expanded'>
                        View Profile
                    </a>
                </div>
            </div>

            <hr/>
			<?php
			$settings = [
				"post_id" => 'user_' . $user_id,
				'submit_value' => __("Update profile", 'acf'),
				'updated_message' => __("Profile updated", 'acf'),
				'html_updated_message' => '<div id="message" class="updated callout success"><p>%s</p></div>',
				'fields' => [
					'profile_image',
					'bio',
					'credentials',
					'work_information',
					'social_profiles',
					'offices'
				],
			];

			acf_form($settings);
		}
	endif;
endif;