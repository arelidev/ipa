<?php
/**
 * Template part for displaying page content in page.php
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class( '' ); ?> role="article" itemscope
         itemtype="http://schema.org/WebPage">

    <header class="article-header hero hero-standard">
        <div class="hero-inner grid-container">
            <div class="grid-x grid-padding-x grid-margin-x align-middle">
                <div class="small-12 medium-12 large-auto cell">
                    <h1 class="page-title"><b><?php the_title(); ?></b></h1>
					<?php
					if ( function_exists( 'yoast_breadcrumb' ) ) {
						yoast_breadcrumb( '<p id="breadcrumbs">', '</p>' );
					}
					?>
                </div>
                <div class="small-12 medium-12 large-6 cell hide">
					<?php if ( has_excerpt() ) : the_excerpt(); endif; ?>
                </div>
            </div>
        </div>
    </header> <!-- end article header -->

    <div data-sticky-container>
        <div class="sticky magellan-bar styled-container" data-sticky data-margin-top="0" data-anchor="entry-content">
            <div class="grid-container">
                <div class="grid-x grid-padding-x grid-padding-y">
                    <div class="cell">
                        <ul class="menu" data-magellan>
							<?php if ( have_rows( 'course_magellan_navigation' ) ): ?>
								<?php while ( have_rows( 'course_magellan_navigation' ) ) : the_row(); ?>
                                    <li>
                                        <a href="#<?php the_sub_field( 'course_magellan_navigation_section_id' ); ?>">
											<?php the_sub_field( 'course_magellan_navigation_title' ); ?>
                                        </a>
                                    </li>
								<?php endwhile; ?>
							<?php endif; ?>
                            <li><a href="#courses"><?php _e( 'Courses', 'ipa' ); ?></a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <section class="entry-content grid-container" itemprop="text" id="entry-content">
        <div class="grid-x grid-margin-x">
            <div class="small-12 medium-12 large-8 small-order-2 large-order-1 cell" id="course-content">
				<?php the_content(); ?>
            </div>
            <div class="small-12 medium-12 large-4 small-order-1 large-order-2 cell">
				<?php if ( have_rows( 'course_highlights' ) ): ?>
                    <div class="course-sidebar" style="margin-bottom: 1rem;">
                        <div class="course-sidebar-meta styled-container">
							<?php while ( have_rows( 'course_highlights' ) ) : the_row(); ?>
                                <div class="grid-x grid-padding-x grid-padding-y">
                                    <div class="cell shrink">
                                        <?php $image_id = get_sub_field( 'course_highlight_icon'); ?>
										<?= wp_get_attachment_image( $image_id['id'], 'full'  ) ?>
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
            </div>
        </div>
        <div id="courses" data-magellan-target="courses">
			<?php if ( ! empty( $course_category = get_field( 'course_remote_cat' ) ) ) : ?>
                <div class="grid-x grid-margin-x grid-padding-x grid-padding-y" id="courses"
                     data-magellan-target="courses">
                    <div class="cell">
						<?= do_shortcode( "[ipa_courses_table course_cat='{$course_category}']" ); ?>
                    </div>
                </div>
			<?php endif; ?>
			<?php wp_link_pages(); ?>
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
