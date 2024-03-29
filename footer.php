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

	<?php if ( get_field( 'enable_custom_cta' ) && ! is_page_template( 'template-course.php' ) ) : ?>
		<?php get_template_part( 'parts/content', 'get-started' ); ?>
	<?php endif; ?>

    <div class="inner-footer">

        <div class="grid-container">

            <div class="grid-x grid-padding-x grid-margin-x">

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

                <div class="small-12 medium-12 large-auto cell">
                    <nav role="navigation">
						<?php // joints_footer_links(); ?>
                    </nav>
                </div>

                <div class="small-12 medium-12 large-12 cell">
                    <div class="source-org copyright text-color-medium-gray">
			            <?php the_field( 'footer_copyright', 'options' ); ?>
                    </div>
                </div>

            </div>

        </div>

    </div> <!-- end #inner-footer -->

</footer> <!-- end .footer -->

<a href="#top" data-smooth-scroll id="scrollToTop"  title="Go to top">
    <span class="show-for-sr">Scroll to top</span> <i class="fa-solid fa-chevron-up fa-lg" aria-hidden="true"></i>
</a>

</div>  <!-- end .off-canvas-content -->

</div> <!-- end .off-canvas-wrapper -->

<?php wp_footer(); ?>

</body>

</html> <!-- end page -->
