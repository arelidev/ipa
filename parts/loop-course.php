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
					<?php yoast_breadcrumb(); ?>
                </div>
                <div class="small-12 medium-12 large-6 cell hide">
					<?php if ( has_excerpt() ) : the_excerpt(); endif; ?>
                </div>
            </div>
        </div>
    </header> <!-- end article header -->

    <section class="entry-content grid-container" itemprop="text">
        <div class="grid-x grid-margin-x">
            <div class="small-12 medium-12 large-8 small-order-2 large-order-1 cell" id="course-content">
				<?php the_content(); ?>
            </div>
            <div class="small-12 medium-12 large-4 small-order-1 large-order-2 cell" data-sticky-container>

                <div class="course-sidebar sticky" data-sticky data-anchor="course-content">

                    <ul class="menu vertical" data-magellan>
                        <li><a href="#first"><?= __( 'Objectives', 'ipa' ); ?></a></li>
                        <li><a href="#second"><?= __( 'Requirements', 'ipa' ); ?></a></li>
                        <li><a href="#courses"><?= __( 'Courses', 'ipa' ); ?></a></li>
                    </ul>

                    <div class="couse-sidebar-meta styled-container">
                        <div class="grid-x grid-padding-x grid-padding-y">
                            <div class="cell auto">
                                <b><i class="far fa-clock"></i> <?= __( 'Hours:', 'ipa' ); ?></b>
                            </div>
                            <div class="cell auto">
								<?= get_field( 'course_hours' ); ?>
                            </div>
                        </div>

                        <div class="grid-x grid-padding-x grid-padding-y">
                            <div class="cell">
                                <b><i class="far fa-file-certificate"></i> <?= __( 'Prerequisites:', 'ipa' ); ?></b>
                            </div>
                            <div class="cell">
								<?= ( ! empty( $prereq = get_field( 'course_prerequisites' ) ) ) ? $prereq : __( 'None', 'ipa' ); ?>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section> <!-- end article section -->

    <footer class="article-footer grid-container">

		<?php if ( ! empty( $course_category = get_field( 'course_remote_cat' ) ) ) : ?>
            <div class="grid-x grid-margin-x grid-padding-x grid-padding-y" id="courses" data-magellan-target="courses">
                <div class="cell">
					<?= do_shortcode( "[ipa_courses_table course_cat='{$course_category}']" ); ?>
                </div>
            </div>
		<?php endif; ?>

		<?php wp_link_pages(); ?>
    </footer> <!-- end article footer -->

</article> <!-- end article -->
