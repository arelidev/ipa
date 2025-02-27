<?php
/**
 * Template part for displaying page content in page.php
 */

$hero_type       = get_field( 'hero_type' );
$course_category = get_field( 'course_remote_cat' );
$delivery_method = ! ( empty( get_field( 'course_delivery_method' ) ) ) ? get_field( 'course_delivery_method' ) : 1;
$linked_courses  = get_field( 'course_remote_cat' );
?>
<article id="post-<?php the_ID(); ?>" <?php post_class( '' ); ?> role="article" itemscope itemtype="http://schema.org/WebPage">

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
        <style>
            /* todo: I don't like this here */
            .header {
                background: #3C5895;
                box-shadow: 0 1px 10px 4px rgba(0, 0, 0, 0.22);
            }
        </style>
        <header class="article-header hero hero-image">
            <div class="hero-inner grid-x align-middle" data-equalizer>
                <div class="small-12 large-4 cell small-order-2 large-order-1" data-equalizer-watch>
                    <div class="hero-content-wrapper">
                        <h1 class="page-title h2"><b><?php the_title(); ?></b></h1>
						<?php if ( has_excerpt() ) : ?>
                            <p><small><?php the_excerpt(); ?></small></p>
						<?php endif; ?>
                    </div>
                </div>
                <div class="small-12 large-8 cell small-order-1 large-order-2 hero-image-wrapper">
                    <img src="<?= get_template_directory_uri(); ?>/assets/images/curved-border.svg" alt="" aria-hidden="true" class="hero-separator">
					<?php the_post_thumbnail( 'full' ); ?>
                </div>
            </div>
        </header> <!-- end article header -->
	<?php endif; ?>

    <div data-sticky-container>
        <div class="sticky magellan-bar styled-container" data-sticky data-margin-top="0" data-anchor="entry-content" data-offset="70">
            <div class="grid-container">
                <div class="grid-x grid-padding-x grid-padding-y">
                    <div class="cell">
                        <ul class="menu show-for-medium" id="course-magellan">
							<?php if ( have_rows( 'course_magellan_navigation' ) ): ?>
								<?php while ( have_rows( 'course_magellan_navigation' ) ) : the_row(); ?>
                                    <li>
                                        <a href="#<?php the_sub_field( 'course_magellan_navigation_section_id' ); ?>">
											<?php the_sub_field( 'course_magellan_navigation_title' ); ?>
                                        </a>
                                    </li>
								<?php endwhile; ?>
							<?php endif; ?>
                            <li>
                                <a href="#courses">
									<?php
	                                if ( get_field( "arlo_event" ) === "On-demand" ) :
		                                _e( "On-Demand Courses", "ipa" );
	                                else :
		                                _e( "Dates & Locations", "ipa" );
	                                endif;
	                                ?>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <section class="entry-content grid-container" itemprop="text" id="entry-content">
        <div class="grid-x grid-margin-x grid-margin-y">
            <div class="small-12 medium-12 large-8 small-order-2 large-order-1 cell" id="course-content">
				<?php the_content(); ?>
            </div>
            <div class="small-12 medium-12 large-4 small-order-1 large-order-2 cell sidebar" data-sticky-container>
                <div class="sticky" data-sticky data-anchor="course-content" data-margin-top="5" data-sticky-on="large">
					<?php if ( have_rows( 'course_highlights' ) ): ?>
                        <div class="course-sidebar" style="margin-bottom: 1rem;">
                            <div class="course-sidebar-meta styled-container">
								<?php while ( have_rows( 'course_highlights' ) ) : the_row(); ?>
                                    <div class="grid-x grid-padding-x grid-padding-y">
                                        <div class="cell shrink">
											<?php $image_id = get_sub_field( 'course_highlight_icon' ); ?>
											<?= wp_get_attachment_image( $image_id['id'], 'full' ) ?>
                                        </div>
                                        <div class="cell auto">
                                            <p><b><?php the_sub_field( 'course_highlight_title' ); ?></b></p>
											<?php the_sub_field( 'course_highlight_copy' ); ?>
                                        </div>
                                    </div>
								<?php endwhile; ?>
                            </div>
                        </div>
					<?php endif; ?>

					<?php if ( ! empty( $gallery = get_field( 'course_gallery' ) ) ) : ?>
                        <div class="course-gallery-wrapper position-relative">
                            <div class="course-gallery">
								<?php foreach ( $gallery as $item => $value ) : ?>
                                    <div class="gallery-slide">
                                        <a href="<?= wp_get_attachment_image_url( $value, 'full' ); ?>" data-fancybox="gallery">
											<?= wp_get_attachment_image( $value, 'large' ); ?>
                                        </a>
                                    </div>
								<?php endforeach; ?>
                            </div>
                            <button class="slick-prev-custom-single-course slick-custom-button slick-button-absolute-prev" aria-label="Previous" type="button">
                                <i class="fa-solid fa-chevron-left fa-lg"></i>
                            </button>
                            <button class="slick-next-custom-single-course slick-custom-button slick-button-absolute-next" aria-label="Next" type="button">
                                <i class="fa-solid fa-chevron-right fa-lg"></i>
                            </button>
                        </div>
					<?php endif; ?>
                </div>
            </div>
            <div class="small-12 medium-12 large-12 small-order-1 large-order-2 cell">
                <div id="courses" data-magellan-target="courses">
                    <div class="grid-x grid-margin-x grid-padding-x grid-padding-y" id="courses" data-magellan-target="courses">
                        <div class="cell">
                            <h3>
                                <b>
	                                <?php
	                                if ( get_field( "arlo_event" ) === "On-demand" ) :
		                                _e( "On-Demand Courses", "ipa" );
	                                else :
		                                _e( "Dates & Locations", "ipa" );
	                                endif;
	                                ?>
                                </b>
                            </h3>
                        </div>
	                    <?php if ( ! empty( get_field( "above_table_description" ) ) ) : ?>
                            <div class="cell">
                                <?= get_field( "above_table_description" ); ?>
                            </div>
	                    <?php endif; ?>
                        <div class="cell">
	                        <?php
	                        if ( get_field( "arlo_event" ) === "On-demand" ) :
		                        echo do_shortcode( "[ipa_courses template='$linked_courses' filters='0' condensed='1']" );
	                        else :
		                        echo do_shortcode( "[ipa_courses_table template='$linked_courses' single='1' filters='0' el_class='no-mix']" );
	                        endif;
	                        ?>
                        </div>
                    </div>

					<?php wp_link_pages(); ?>
                </div>
            </div>
        </div>
    </section> <!-- end article section -->

    <footer class="article-footer grid-container">
        <div class="grid-x grid-padding-x">
            <div class="cell">
				<?php wp_link_pages(); ?>
            </div>
        </div>
    </footer> <!-- end article footer -->

</article> <!-- end article -->

<script type="text/javascript">
    jQuery(document).ready(function ($) {
        let magellanTarget = '.vc_section';

        $(magellanTarget).each(function (index) {
            let $id = $(this).attr('id');
            $(this).attr('data-magellan-target', $id);
        });

        let elem = new Foundation.Magellan($('.magellan-bar'));
    });
</script>
