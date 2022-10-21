<?php
$eventId = get_field('eventid');
$delivery_method = 1;
?>
<table class="course-table hover stack"> <!-- .datatable -->
    <thead>
    <tr>
        <th><?= __('Course', 'ipa'); ?></th>
        <th><?= __('Location', 'ipa'); ?></th>
        <th><?= __('Date', 'ipa'); ?></th>
        <th class="hide"><?= __('Scheduled Instructor(s)', 'ipa'); ?></th>
    </tr>
    </thead>
    <tbody>
	<?php
	while (have_rows('sessions')) : the_row();
		$location = get_sub_field('location');
		$startTime = get_sub_field('startdatetime');

		$state = $location[0]['state'] ?? "unknown";

		$course_classes = array(
			'ipa-single-course',
			'mix',
			$state,
		);

		$instructor_1 = "REPLACE ME";
		?>
        <tr class="<?= implode(" ", $course_classes) ?>"
            data-state=".<?= $state ?>"
            data-primary-instructor="<?= $instructor_1; ?>"
            data-course-type="<?= $eventId; ?>"
            data-region="<?= get_region_by_state($state) ?>"
            data-start-date="<?= date('m-d-y', strtotime($startTime)); ?>">

            <td class="course-table-location">
                <span class="hide-for-medium"><b><?= __('Course', 'ipa'); ?>:</b><br></span>
                <b><?php get_template_part('parts/arlo/events/loop-session', 'name'); ?></b>
            </td>

            <td class="course-table-location">
                <span class="hide-for-medium"><b><?= __('Location', 'ipa'); ?>:</b><br></span>
				<?php get_template_part('parts/arlo/events/loop-session', 'location'); ?>
            </td>

            <td class="course-table-date"
                data-order="<?= date('u', strtotime($startTime)); ?>">
                <span class="hide-for-medium"><b><?= __('Date', 'ipa'); ?>:</b><br></span>
				<?php get_template_part('parts/arlo/events/loop-session', 'time'); ?>
            </td>

            <td class="course-table-instructor hide">
                <span class="hide-for-medium"><b><?= __('Scheduled Instructor(s)', 'ipa'); ?>:</b><br></span>
				<?php if (have_rows('presenters')) : ?>
					<?php get_template_part('parts/arlo/events/loop', 'presenters'); ?>
				<?php endif; ?>
            </td>
        </tr>
	<?php endwhile; ?>
    </tbody>
</table>