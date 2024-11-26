<?php
/**
 * @return void
 */
function ipa_add_woocommerce_support() {
	add_theme_support( 'woocommerce' );
}

add_action( 'after_setup_theme', 'ipa_add_woocommerce_support' );

// Remove page title
add_filter( 'woocommerce_show_page_title', '__return_null' );

/**
 * Change number of products that are displayed per page (shop page)
 *
 * @param $cols
 *
 * @return int
 */
function new_loop_shop_per_page( $cols ): int {
	return 50;
}

add_filter( 'loop_shop_per_page', 'new_loop_shop_per_page', 20 );

/**
 * @param $menu_links
 *
 * @return string[]
 */
function log_history_link( $menu_links ): array {
	return array_slice( $menu_links, 0, 5, true )
	       + array( 'edit-profile' => 'Profile Member' )
	       + array_slice( $menu_links, 6, null, true );
}

add_filter( 'woocommerce_account_menu_items', 'log_history_link', 40 );

/**
 * @return void
 */
function add_endpoint() {
	add_rewrite_endpoint( 'edit-profile', EP_PAGES );
}

add_action( 'init', 'add_endpoint' );

/**
 * @return void]
 */
function my_account_endpoint_content() {
	acf_form_head();

	$user_id   = get_current_user_id();
	$user_meta = get_user_meta( $user_id );

	$name = acf_slugify( $user_meta['first_name'][0] . " " . $user_meta['last_name'][0] );
	?>
    <div class="grid-x grid-padding-x align-center-middle">
        <div class="small-12 medium-auto">
            <h4>Edit your profile</h4>
        </div>
        <div class="small-12 medium-shrink">
            <a href='<?= home_url( '/profile-member/' . $name ); ?>' class='button small-only-expanded'
               target="_blank">
                View Profile
            </a>
        </div>
    </div>

    <hr/>
	<?php
	$settings = [
		"post_id"              => 'user_' . $user_id,
		'submit_value'         => __( "Update profile", 'acf' ),
		'updated_message'      => __( "Profile updated", 'acf' ),
		'html_updated_message' => '<div id="message" class="updated callout success"><p>%s</p></div>',
		'fields'               => [
			'profile_image',
			'bio',
			'credentials',
			'work_information',
			'social_profiles',
			'offices'
		],
	];

	acf_form( $settings );
}

add_action( 'woocommerce_account_edit-profile_endpoint', 'my_account_endpoint_content' );

add_action( 'woocommerce_check_cart_items', function () {
	echo '<div class="callout primary">Place your order now!  IPA Product orders process each Thursday.</div>';
} );

add_action( 'woocommerce_before_checkout_billing_form', function () {
	echo '<div class="callout primary">New Customer <b>(First product order since August 16th, 2022)</b>. Please create an account by filling out details below.</div>';
} );

/**
 * @return string
 */
function rename_returning_customer(): string {
	return 'Returning Customer (Has placed product order since August 16th, 2022)';
}

add_filter( 'woocommerce_checkout_login_message', 'rename_returning_customer' );

/**
 * @return void
 */
function rdf_custom_surcharge() {
	global $woocommerce;

	if ( is_admin() && ! defined( 'DOING_AJAX' ) ) {
		return;
	}

	$state = array( 'CO' );

	$surcharge = .28;

	if ( in_array( WC()->customer->get_shipping_state(), $state ) ) {
		$woocommerce->cart->add_fee( 'Colorado Retail Delivery Fee (RDF)', $surcharge );
	}
}

// add_action('woocommerce_cart_calculate_fees', 'rdf_custom_surcharge');

/**
 * @param $text
 * @param $order
 *
 * @return array|string|string[]
 */
function replace_placeholders( $text, $order ) {
	// Get the current user info
	$current_user = wp_get_current_user();
	$user_name    = $current_user->user_firstname ? $current_user->user_firstname : $current_user->user_email;

	// Get the order number
	$order_number = $order->get_order_number();

	// Replace the placeholders
	$text = str_replace( '{{user}}', $user_name, $text );
	$text = str_replace( '{{order_number}}', $order_number, $text );

	return $text;
}

function ipa_gc_form_field_recipient_html( $product ) {

	// Re-fill form.
	$to = isset( $_REQUEST['wc_gc_giftcard_to'] ) ? sanitize_text_field( $_REQUEST['wc_gc_giftcard_to'] ) : '';
	$to = empty( $to ) && isset( $_REQUEST['wc_gc_giftcard_to_multiple'] ) ? sanitize_text_field( $_REQUEST['wc_gc_giftcard_to_multiple'] ) : $to;

	if ( $product->is_sold_individually() ) { ?>
        <div class="wc_gc_field wc_gc_giftcard_to form-row">
            <label for="wc_gc_giftcard_to"><?php esc_html_e( 'Recipient’s full name', 'woocommerce-gift-cards' ); ?>
                <abbr class="required"
                      title="Required field"><?php echo esc_html_x( '*', 'character, indicating a required field', 'woocommerce-gift-cards' ); ?></abbr>
            </label>
            <input type="text" class="input-text" name="wc_gc_giftcard_to"
                   placeholder="<?php esc_attr_e( 'Enter gift card recipient email', 'woocommerce-gift-cards' ); ?>"
                   value="<?php echo esc_attr( $to ); ?>"/>
        </div>
	<?php } else { ?>
        <div class="wc_gc_field wc_gc_giftcard_to_multiple form-row">
            <label for="wc_gc_giftcard_to_multiple"><?php esc_html_e( 'Recipient’s full name', 'woocommerce-gift-cards' ); ?>
                <abbr class="required"
                      title="Required field"><?php echo esc_html_x( '*', 'character, indicating a required field', 'woocommerce-gift-cards' ); ?></abbr>
            </label>
			<?php
			/* translators: delimiter */
			$placeholder = sprintf( esc_attr__( 'Enter gift card recipient emails, separated by comma (%s)', 'woocommerce-gift-cards' ), wc_gc_get_emails_delimiter() );
			?>
            <input type="text" class="input-text" name="wc_gc_giftcard_to_multiple"
                   placeholder="<?php echo esc_attr( $placeholder ); ?>" value="<?php echo esc_attr( $to ); ?>"/>
        </div>
		<?php
	}
}

remove_action( 'woocommerce_gc_form_fields_html', 'wc_gc_form_field_recipient_html' );
add_action( 'woocommerce_gc_form_fields_html', 'ipa_gc_form_field_recipient_html' );

function ipa_gc_form_field_sender_html( $product ) {

	// Re-fill form.
	$from = isset( $_REQUEST['wc_gc_giftcard_from'] ) ? sanitize_text_field( wp_unslash( urldecode( $_REQUEST['wc_gc_giftcard_from'] ) ) ) : ''; // @phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized

	if ( empty( $from ) && get_current_user_id() ) {
		$customer_id = apply_filters( 'woocommerce_checkout_customer_id', get_current_user_id() );
		$customer    = new WC_Customer( $customer_id );
		if ( is_a( $customer, 'WC_Customer' ) ) {

			if ( is_email( $customer->get_display_name() ) || $customer->get_display_name() === $customer->get_username() ) {
				$customer->set_display_name( $customer->get_first_name() . ' ' . $customer->get_last_name() );
			}

			$from = ! empty( trim( $customer->get_display_name() ) ) ? $customer->get_display_name() : '';
		}
	}

	?>
    <div class="wc_gc_field wc_gc_giftcard_from form-row">
        <label for="wc_gc_giftcard_from"><?php esc_html_e( 'Recipient’s IPA Course account email address', 'woocommerce-gift-cards' ); ?>
            <abbr class="required"
                  title="Required field"><?php echo esc_html_x( '*', 'character, indicating a required field', 'woocommerce-gift-cards' ); ?></abbr>
        </label>
        <input type="text" class="input-text" name="wc_gc_giftcard_from"
               placeholder="<?php esc_attr_e( 'Enter your name', 'woocommerce-gift-cards' ); ?>"
               value="<?php echo esc_attr( $from ); ?>"/>
    </div>
	<?php
}

remove_action( 'woocommerce_gc_form_fields_html', 'wc_gc_form_field_sender_html', 20 );
add_action( 'woocommerce_gc_form_fields_html', 'ipa_gc_form_field_sender_html', 20 );

function ipa_gc_form_field_message_html( $product ) {

	// Re-fill form.
	$message = isset( $_REQUEST['wc_gc_giftcard_message'] ) ? sanitize_textarea_field( str_replace( '<br />', "\n", wp_unslash( urldecode( $_REQUEST['wc_gc_giftcard_message'] ) ) ) ) : ''; // @phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized

	?>
    <div class="wc_gc_field wc_gc_giftcard_message form-row">
        <label for="wc_gc_giftcard_message"><?php esc_html_e( 'Message for Recipient', 'woocommerce-gift-cards' ); ?></label>
        <textarea rows="3" class="input-text" name="wc_gc_giftcard_message"
                  placeholder="<?php esc_attr_e( 'Add your message (optional)', 'woocommerce-gift-cards' ); ?>"><?php echo esc_html( $message ); ?></textarea>
    </div>
	<?php
}

remove_action( 'woocommerce_gc_form_fields_html', 'wc_gc_form_field_message_html', 30 );
add_action( 'woocommerce_gc_form_fields_html', 'ipa_gc_form_field_message_html', 30 );

add_action( 'woocommerce_single_product_summary', 'conditionally_replace_add_to_cart', 30 );

function conditionally_replace_add_to_cart() {
	global $product;

	// Target product ID
	$target_product_id = 19555;

	// Check if we are on the correct product page
	if ( $product->get_id() == $target_product_id ) {
		// Set the target date and time (YYYY-MM-DD HH:MM:SS format)
		$target_date = '2024-11-27 23:59:59';

		// Get current date and time
		$current_datetime = current_time( 'Y-m-d H:i:s' );

		// Check if the current date/time is before the target date/time
		if ( $current_datetime < $target_date ) {
			if ( $product->is_type( 'variable' ) ) {
				// Remove the Add to Cart button for variable products
				echo '<style>.variations_form { display: none; }</style>';
			} else {
				// Remove the Add to Cart button for simple products
				remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_add_to_cart', 30 );
			}

			// Enqueue jQuery and add inline countdown timer script
			wp_enqueue_script( 'jquery' );

			echo '<div class="callout success" id="countdown-timer"></div>';
			echo '
            <script>
                jQuery(document).ready(function($) {
                    // Set the target date for the countdown
                    var targetDate = new Date("' . $target_date . '").getTime();

                    // Update the countdown every second
                    var countdown = setInterval(function() {
                        var now = new Date().getTime();
                        var distance = targetDate - now;

                        // Calculate time remaining
                        var days = Math.floor(distance / (1000 * 60 * 60 * 24));
                        var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                        var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                        var seconds = Math.floor((distance % (1000 * 60)) / 1000);

                        // Display the result
                        $("#countdown-timer").html("<h3>Available in:<b> " + days + "d " + hours + "h " + minutes + "m " + seconds + "s</b></h3>");

                        // If the countdown is finished, reload the page
                        if (distance < 0) {
                            clearInterval(countdown);
                            location.reload();
                        }
                    }, 1000);
                });
            </script>';
		}
	}
}