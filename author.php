<?php
/**
 * The template for displaying all pages
 *
 * This is the template that displays all pages by default.
 */

get_header(); ?>

<div class="content single-faculty-page">

	<div class="inner-content">

		<main class="main" role="main">

			<?php get_template_part( 'parts/loop', 'author' ); ?>

		</main> <!-- end #main -->

	</div> <!-- end #inner-content -->

</div> <!-- end #content -->

<?php get_footer(); ?>
