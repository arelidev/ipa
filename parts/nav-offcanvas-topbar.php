<?php
/**
 * The off-canvas menu uses the Off-Canvas Component
 *
 * For more info: http://jointswp.com/docs/off-canvas-menu/
 */

// todo make external links dynamic
?>

<div class="grid-x grid-padding-x grid-padding-y align-middle align-right" id="top-bar-menu">
    <div class="small-12 medium-12 large-auto cell text-center">
        <?php
        if ( function_exists( 'the_custom_logo' ) ) {
            the_custom_logo();
        }
        ?>
    </div>
    <div class="small-12 medium-12 large-shrink cell show-for-medium">
		<?php joints_top_nav(); ?>
    </div>
    <div class="small-12 medium-shrink cell show-for-large border-left">
        <ul class="menu">
            <li><a href="#"><i class="fas fa-search"></i></a></li>
            <li>
                <a href="https://my.instituteofphysicalart.com/index.php/checkout/cart">
                    <i class="far fa-shopping-cart"></i>
                </a>
            </li>
        </ul>
    </div>
    <div class="shrink cell show-for-large">
        <ul class="menu">
            <li>
                <a href="https://my.instituteofphysicalart.com/index.php/customer/account/login/" class="button hollow white">
                    My Account
                </a>
            </li>
        </ul>
    </div>
    <div class="shrink cell hide-for-medium">
        <ul class="menu">
            <li>
                <button class="menu-icon" type="button" data-toggle="off-canvas"></button>
            </li>
        </ul>
    </div>
</div>
