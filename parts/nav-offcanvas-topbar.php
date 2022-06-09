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
        if ( function_exists( 'the_custom_logo' ) ) {
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
                <a href="<?= wc_get_cart_url(); ?>">
                    <i class="far fa-shopping-cart"></i>
                </a>
            </li>
            <li>
                <a href="<?= !(is_user_logged_in()) ? get_field('login_link', 'option') : wc_get_page_permalink('myaccount'); ?>">
                    <i class="far fa-user"></i>
                </a>
            </li>
        </ul>
    </div>
    <div class="shrink cell show-for-medium">
        <ul class="menu">
            <li>
                <a href="<?= get_field( 'magento_login_url', 'options' ); ?>" class="button hollow white">
                    <?= __( 'My Account', 'ipa' ); ?>
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
