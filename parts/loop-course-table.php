<?php
$courses = $args["courses"];
$display = $args["display"];
?>

<?php foreach ( $courses as $title => $ids ) : ?>
    <div class="ipa-courses-widget-cell" id="course-<?= acf_slugify( $title ); ?>">
        <a href="#<?= acf_slugify( $title ); ?>" class="ipa-courses-widget-course-title text-color-black">
            <h5><b><?= $title; ?></b></h5>
        </a>
        <div class="styled-container" id="<?= acf_slugify( $title ); ?>">
            <table class="course-table hover stack">
				<?php get_template_part( 'parts/course-table/table', 'header' ); ?>
				<?php get_template_part( 'parts/course-table/table', 'body', [
					'ids'     => $ids,
					'display' => $display
				] ); ?>
            </table>
        </div>
    </div>
<?php endforeach; ?>