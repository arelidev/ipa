<?php
/*
Template Name: Course Category
*/

get_header(); ?>

<div class="content">

	<div class="inner-content">

		<main class="main" role="main">

			<?php if (have_posts()) : while (have_posts()) : the_post(); ?>

				<?php get_template_part( 'parts/loop', 'course' ); ?>

			<?php endwhile; endif; ?>

		</main> <!-- end #main -->

	</div> <!-- end #inner-content -->

</div> <!-- end #content -->

<?php get_footer(); ?>
