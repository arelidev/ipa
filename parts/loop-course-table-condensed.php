<?php
$courses = $args["courses"];
$display = $args["display"];
?>

<div class="ipa-courses-widget-cell">
    <div class="styled-container">
        <table class="course-table hover stack">
			<?php get_template_part( 'parts/course-table/table', 'header' ); ?>
			<?php foreach ( $courses as $title => $ids ) : ?>
				<?php get_template_part( 'parts/course-table/table', 'body', [
					'ids'     => $ids,
					'display' => $display
				] ); ?>
			<?php endforeach; ?>
        </table>
    </div>
</div>