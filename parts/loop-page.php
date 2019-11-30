<?php
/**
 * Template part for displaying page content in page.php
 */

$hero_type = get_field( 'hero_type' );
?>

<article id="post-<?php the_ID(); ?>" <?php post_class( '' ); ?> role="article" itemscope
         itemtype="http://schema.org/WebPage">

	<?php if ( empty( $hero_type ) || $hero_type !== 'none' ) : ?>
        <header class="article-header hero hero-standard">
            <div class="hero-inner grid-container">
                <h1 class="page-title"><b><?php the_title(); ?></b></h1>
				<?php
				if ( function_exists( 'yoast_breadcrumb' ) ) {
					yoast_breadcrumb( '<p id="breadcrumbs">', '</p>' );
				}
				?>
            </div>
        </header> <!-- end article header -->
	<?php endif; ?>

    <section class="entry-content" itemprop="text">
		<?php the_content(); ?>
    </section> <!-- end article section -->

    <footer class="article-footer">
		<?php wp_link_pages(); ?>
    </footer> <!-- end article footer -->

</article> <!-- end article -->
