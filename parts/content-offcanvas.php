<?php
/**
 * The template part for displaying offcanvas content
 *
 * For more info: http://jointswp.com/docs/off-canvas-menu/
 */
?>

<div class="off-canvas position-right" id="off-canvas" data-off-canvas>

    <div class="grid-x grid-padding-x grid-padding-y align-middle">

        <div class="shrink cell">
            <a href="<?= get_field( 'login_link', 'options' ); ?>" class="button hollow small" style="margin-bottom: 0;">
		        <?= __( 'My Accounts', 'ipa' ); ?>
            </a>
        </div>
        <div class="shrink cell">
	        <a href="<?= wc_get_cart_url(); ?>" data-tooltip class="center bottom" tabindex="2"
	           title="Your cart for IPA products">
		        <span class="show-for-sr"><?= __('Shopping cart', 'ipa'); ?></span>
		        <i class="far fa-shopping-cart" aria-hidden="true"></i>
	        </a>
        </div>
	    <div class="shrink cell">
		    <a href="https://instituteofphysicalart.arlo.co/checkout" data-tooltip class="center bottom"
		       tabindex="2" title="Your cart for course registration">
			    <span class="show-for-sr"><?= __('Shopping cart', 'ipa'); ?></span>
			    <i class="far fa-cart-flatbed" aria-hidden="true"></i>
		    </a>
	    </div>
        <div class="auto cell text-right">
            <button type="button" data-toggle="off-canvas">CLOSE <i class="fas fa-times"></i></button>
        </div>
    </div>

    <div class="grid-x">
        <div class="cell">
			<?php joints_off_canvas_nav(); ?>

			<?php if ( is_active_sidebar( 'offcanvas' ) ) : ?>
				<?php dynamic_sidebar( 'offcanvas' ); ?>
			<?php endif; ?>
        </div>
    </div>
</div>
