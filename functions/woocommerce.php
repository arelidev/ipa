<?php
/**
 * @return void
 */
function ipa_add_woocommerce_support()
{
	add_theme_support('woocommerce');
}

add_action('after_setup_theme', 'ipa_add_woocommerce_support');

// Remove page title
add_filter('woocommerce_show_page_title', '__return_null');

/**
 * Change number of products that are displayed per page (shop page)
 *
 * @param $cols
 * @return int
 */
function new_loop_shop_per_page($cols): int
{
	return 50;
}

add_filter('loop_shop_per_page', 'new_loop_shop_per_page', 20);

/**
 * @param $menu_links
 * @return string[]
 */
function log_history_link($menu_links): array
{
	return array_slice($menu_links, 0, 5, true)
		+ array('edit-profile' => 'Profile Member')
		+ array_slice($menu_links, 6, NULL, true);
}

add_filter('woocommerce_account_menu_items', 'log_history_link', 40);

/**
 * @return void
 */
function add_endpoint()
{
	add_rewrite_endpoint('edit-profile', EP_PAGES);
}

add_action('init', 'add_endpoint');

/**
 * @return void]
 */
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
            <a href='<?= home_url('/profile-member/' . $name); ?>' class='button small-only-expanded'
               target="_blank">
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

add_action('woocommerce_account_edit-profile_endpoint', 'my_account_endpoint_content');

add_action('woocommerce_check_cart_items', function () {
	echo '<div class="callout primary">This is our new checkout ecommerce platform. Please read descriptions below to know whether you are a “Returning Customer” or “New Customer”.</div>';
});

add_action('woocommerce_before_checkout_billing_form', function () {
	echo '<div class="callout primary">New Customer <b>(First product order since August 16th, 2022)</b>. Please create an account by filling out details below.</div>';
});

/**
 * @return string
 */
function rename_returning_customer(): string
{
	return 'Returning Customer (Has placed product order since August 16th, 2022)';
}

add_filter('woocommerce_checkout_login_message', 'rename_returning_customer');

/**
 * @return void
 */
function rdf_custom_surcharge() {
	global $woocommerce;

	if ( is_admin() && ! defined( 'DOING_AJAX' ) ) {
		return;
	}

	$state = array( 'CO' );

	$surcharge = .27;

	if ( in_array( WC()->customer->get_shipping_state(), $state ) ) {
		$woocommerce->cart->add_fee( 'Colorado Retail Delivery Fee (RDF)', $surcharge );
	}
}

add_action('woocommerce_cart_calculate_fees', 'rdf_custom_surcharge');