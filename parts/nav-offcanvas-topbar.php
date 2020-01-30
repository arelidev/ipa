<?php
/**
 * The off-canvas menu uses the Off-Canvas Component
 *
 * For more info: http://jointswp.com/docs/off-canvas-menu/
 */

// todo make external links dynamic
?>

<div class="grid-x grid-padding-x grid-padding-y align-middle align-right" id="top-bar-menu">
    <div class="small-12 medium-auto cell">
        <ul class="menu">
            <li>
				<?php
				if ( function_exists( 'the_custom_logo' ) ) {
					the_custom_logo();
				}
				?>
            </li>
        </ul>
    </div>
    <div class="small-12 medium-shrink cell show-for-large">
		<?php joints_top_nav(); ?>
    </div>
    <div class="shrink cell border-left">
        <ul class="menu">
            <li><a href="#"><i class="fas fa-search"></i></a></li>
            <li>
                <a href="https://my.instituteofphysicalart.com/index.php/checkout/cart">
                    <i class="far fa-shopping-cart"></i>
                </a>
            </li>
        </ul>
    </div>
    <div class="shrink cell">
        <ul class="menu">
            <li><a href="#" class="button white">Get Started</a></li>
        </ul>
    </div>
    <div class="shrink cell">
        <ul class="menu">
            <li>
                <a href="https://my.instituteofphysicalart.com/index.php/customer/account/login/" class="button hollow white">
                    My Account
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
