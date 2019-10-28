<?php
/**
 * The off-canvas menu uses the Off-Canvas Component
 *
 * For more info: http://jointswp.com/docs/off-canvas-menu/
 */
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
    <div class="small-12 medium-shrink cell show-for-xlarge">
		<?php joints_top_nav(); ?>
    </div>
    <div class="shrink cell border-left">
        <ul class="menu">
            <li><a href="#"><i class="fas fa-search fa-lg"></i></a></li>
            <li><a href="#"><i class="far fa-shopping-cart fa-lg"></i></a></li>
        </ul>
    </div>
    <div class="shrink cell">
        <ul class="menu">
            <li><a href="#" class="button hollow white">Login</a></li>
        </ul>
    </div>
    <div class="shrink cell">
        <ul class="menu">
            <li>
                <button class="menu-icon" type="button" data-toggle="off-canvas"></button>
            </li>
        </ul>
    </div>
</div>