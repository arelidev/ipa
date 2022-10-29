<?php
$post = isset($args['post']) ? (int)$args['post'] : get_the_ID();
$eventId = get_field('eventid', $post);
$delivery_method = 1;
?>
<table class="course-table hover stack"> <!-- .datatable -->
    <thead>
    <tr>
        <th><?= __('Schedule', 'ipa'); ?></th>
        <th><?= __('Location', 'ipa'); ?></th>
        <th><?= __('Date & Time', 'ipa'); ?></th>
    </tr>
    </thead>
    <tbody>
	<?php
	while (have_rows('sessions', $post)) : the_row();
		$location = get_sub_field('location');
		$startTime = get_sub_field('startdatetime');

		$state = $location[0]['state'] ?? "unknown";

		$course_classes = array(
			'ipa-single-course',
			'mix',
			$state,
		);
		?>
        <tr class="<?= implode(" ", $course_classes) ?>"
            id="<?= $eventId; ?>"
            data-state=".<?= $state ?>"
            data-course-type="<?= $eventId; ?>"
            data-region="<?= get_region_by_state($state) ?>"
            data-start-date="<?= date('m-d-y', strtotime($startTime)); ?>">

            <td class="course-table-location">
                <span class="hide-for-medium"><b><?= __('Course', 'ipa'); ?>:</b><br></span>
                <b><?php get_template_part('parts/arlo/events/loop-session', 'name'); ?></b>
            </td>

            <td class="course-table-location">
                <span class="hide-for-medium"><b><?= __('Location', 'ipa'); ?>:</b><br></span>
		        <?php get_template_part('parts/arlo/events/loop-session', 'location', array('post' => $post)); ?>
            </td>

            <td class="course-table-date"
                data-order="<?= date('u', strtotime($startTime)); ?>">
                <span class="hide-for-medium"><b><?= __('Date', 'ipa'); ?>:</b><br></span>
				<?php get_template_part('parts/arlo/events/loop-session', 'time'); ?>
            </td>
        </tr>
	<?php endwhile; ?>
    </tbody>
</table>