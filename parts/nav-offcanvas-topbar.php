<?php
/**
 * The off-canvas menu uses the Off-Canvas Component
 *
 * For more info: http://jointswp.com/docs/off-canvas-menu/
 */
?>

<div class="grid-x grid-padding-x align-middle" id="top-bar-menu">
    <div class="auto large-shrink cell">
		<?php
		if (function_exists('the_custom_logo')) {
			the_custom_logo();
		}
		?>
    </div>
    <div class="small-12 medium-12 large-auto cell show-for-large">
		<?php joints_top_nav(); ?>
    </div>
    <div class="small-12 medium-shrink cell show-for-medium border-left">
        <ul class="menu">
            <li class="hide"><a href="#"><i class="fas fa-search"></i></a></li>
            <li>
                <a href="<?= wc_get_cart_url(); ?>" data-tooltip class="center bottom" tabindex="2" title="Your cart for IPA products">
                    <span class="show-for-sr"><?= __('Shopping cart', 'ipa'); ?></span>
                    <i class="fa-solid fa-shopping-cart" aria-hidden="true"></i>
                </a>
            </li>
            <li>
                <a href="https://instituteofphysicalart.arlo.co/checkout" data-tooltip class="center bottom" tabindex="2" title="Your cart for course registration">
                    <span class="show-for-sr"><?= __('Shopping cart', 'ipa'); ?></span>
                    <i class="fa-solid fa-cart-flatbed" aria-hidden="true"></i>
                </a>
            </li>
        </ul>
    </div>
    <div class="shrink cell show-for-medium" style="padding-left: 0;">
        <ul class="menu">
            <li>
                <a href="<?= get_field('login_link', 'options'); ?>" class="button hollow white small">
					<?= __('My Accounts', 'ipa'); ?>
                </a>
            </li>
        </ul>
    </div>
    <div class="shrink cell hide-for-large">
        <ul class="menu">
            <li>
                <button class="menu-icon" type="button" data-toggle="off-canvas"></button>
            </li>
        </ul>
    </div>
</div>
