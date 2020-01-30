<?php
/**
 * The template for displaying the footer.
 *
 * Comtains closing divs for header.php.
 *
 * For more info: https://developer.wordpress.org/themes/basics/template-files/#template-partials
 */
?>

<footer class="footer" role="contentinfo">

	<?php if ( ! get_field( 'disable_footer', get_the_ID() ) ) : ?>
		<?php get_template_part( 'parts/content', 'get-started' ); ?>
	<?php endif; ?>

    <div class="inner-footer">

        <div class="grid-container">

            <div class="grid-x grid-padding-x">

                <div class="small-12 medium-6 large-3 cell">
					<?php dynamic_sidebar( 'footer-1' ); ?>
                </div>

                <div class="small-12 medium-6 large-3 cell">
					<?php dynamic_sidebar( 'footer-2' ); ?>
                </div>

                <div class="small-12 medium-6 large-3 cell">
					<?php dynamic_sidebar( 'footer-3' ); ?>
                </div>

                <div class="small-12 medium-6 large-3 cell">
					<?php dynamic_sidebar( 'footer-4' ); ?>
                </div>

            </div>

            <div class="grid-x grid-margin-x grid-padding-x align-middle">

                <div class="small-12 medium-12 large-shrink cell">
                    <p class="source-org copyright text-center large-text-left">
                        &copy; <?php echo date( 'Y' ); ?> <?php bloginfo( 'name' ); ?>.
                    </p>
                </div>

                <div class="small-12 medium-12 large-auto cell">
                    <nav role="navigation">
						<?php joints_footer_links(); ?>
                    </nav>
                </div>

                <div class="small-12 medium-12 large-shrink cell">
					<?= do_shortcode( '[social_icons_group id="51"]' ); ?>
                </div>

            </div>

        </div>

    </div> <!-- end #inner-footer -->

</footer> <!-- end .footer -->

</div>  <!-- end .off-canvas-content -->

</div> <!-- end .off-canvas-wrapper -->

<?php wp_footer(); ?>

</body>

</html> <!-- end page -->
