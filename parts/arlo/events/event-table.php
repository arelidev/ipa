<?php
$post    = isset( $args['post'] ) ? (int) $args['post'] : get_the_ID();
$eventId = get_field( 'eventid', $post );
?>
<table class="course-table hover stack">
    <thead>
    <tr>
        <th><?= __( 'Schedule', 'ipa' ); ?></th>
        <th><?= __( 'Location', 'ipa' ); ?></th>
        <th><?= __( 'Date & Time', 'ipa' ); ?></th>
    </tr>
    </thead>
    <tbody>
	<?php
	while ( have_rows( 'sessions', $post ) ) : the_row();
		$course_classes = array(
			'ipa-single-course',
		);
		?>
        <tr id="<?= $eventId; ?>" class="<?= implode( " ", $course_classes ) ?>">
            <td class="course-table-title">
                <span class="hide-for-medium"><b><?= __( 'Course', 'ipa' ); ?>:</b><br></span>
                <b><?php get_template_part( 'parts/arlo/events/loop-session', 'name' ); ?></b>
            </td>
            <td class="course-table-location">
                <span class="hide-for-medium"><b><?= __( 'Location', 'ipa' ); ?>:</b><br></span>
				<?php get_template_part( 'parts/arlo/events/loop-session', 'location', array( 'post' => $post ) ); ?>
            </td>
            <td class="course-table-date">
                <span class="hide-for-medium"><b><?= __( 'Date', 'ipa' ); ?>:</b><br></span>
				<?php get_template_part( 'parts/arlo/events/loop-session', 'time' ); ?>
            </td>
        </tr>
	<?php endwhile; ?>
    </tbody>
</table>