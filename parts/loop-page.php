<?php
/**
 * Template part for displaying page content in page.php
 */

$hero_type = get_field( 'hero_type' );
?>

<article id="post-<?php the_ID(); ?>" <?php post_class( '' ); ?> role="article" itemscope
         itemtype="http://schema.org/WebPage">

	<?php if ( empty( $hero_type ) || $hero_type == 'default' ) : ?>
        <header class="article-header hero hero-standard">
            <div class="hero-inner grid-container">
                <h1 class="page-title"><b><?php the_title(); ?></b></h1>
				<?php if ( has_excerpt() ) : ?>
                    <p><?php the_excerpt(); ?></p>
				<?php endif; ?>
				<?php
				if ( function_exists( 'yoast_breadcrumb' ) ) {
					yoast_breadcrumb( '<p id="breadcrumbs">', '</p>' );
				}
				?>
            </div>
        </header> <!-- end article header -->
	<?php elseif ( $hero_type == 'image' ) : ?>
        <style type="text/css">
            /* todo: I don't like this here */
            .header {
                background: #3C5895;
                box-shadow: 0 1px 10px 4px rgba(0, 0, 0, 0.22);
            }
        </style>
        <header class="article-header hero hero-image">
            <div class="hero-inner grid-x align-middle">
                <div class="small-12 large-4 cell small-order-2 large-order-1">
                    <div class="hero-content-wrapper">
                        <h1 class="page-title"><b><?php the_title(); ?></b></h1>
						<?php if ( has_excerpt() ) : ?>
                            <p><small><?php the_excerpt(); ?></small></p>
						<?php endif; ?>
						<?php
						if ( function_exists( 'yoast_breadcrumb' ) ) {
							yoast_breadcrumb( '<p id="breadcrumbs">', '</p>' );
						}
						?>
                    </div>
                </div>
                <div class="small-12 large-8 cell small-order-1 large-order-2 hero-image-wrapper">
					<?php the_post_thumbnail( 'full' ); ?>
                </div>
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
