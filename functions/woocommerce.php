<?php
/**
 * @return void
 */
function ipa_add_woocommerce_support() {
	add_theme_support( 'woocommerce' );
}

add_action( 'after_setup_theme', 'ipa_add_woocommerce_support' );

add_filter( 'woocommerce_show_page_title', '__return_null' );
