<?php
/**
 * The template for displaying all pages
 *
 * This is the template that displays all pages by default.
 */

get_header();
?>

<div class="content ">

	<div class="inner-content">

		<main class="main" role="main">

            <header class="article-header hero hero-standard">

                <div class="hero-inner grid-container">
                    <?php
                    if ( is_singular( 'product' ) ) :
                        global $product;
                        ?>
                        <h1 class="page-title hide"><b><?//= $product->get_title(); ?></b></h1>
                    <?php else : ?>
                        <h1 class="page-title"><b><?php woocommerce_page_title(); ?></b></h1>
                    <?php endif; ?>
                </div>

            </header> <!-- end article header -->

            <section class="entry-content grid-container" itemprop="text">

                <div class="grid-x grid-padding-x grid-padding-y">
                    <div class="cell">
	                    <?php woocommerce_content(); ?>
                    </div>
                </div>

            </section> <!-- end article section -->

		</main> <!-- end #main -->

	</div> <!-- end #inner-content -->

</div> <!-- end #content -->

<?php get_footer(); ?>
