<?php
$eventId = get_field('eventid');
$delivery_method = 1;
?>
<table class="course-table hover stack"> <!-- .datatable -->
	<thead>
	<tr>
        <th><?= __('Course', 'ipa'); ?></th>
		<th><?= __('Date', 'ipa'); ?></th>
		<th><?= __('Scheduled Instructor(s)', 'ipa'); ?></th>
	</tr>
	</thead>
	<tbody>
	<?php
	while (have_rows('sessions')) : the_row();
		$location = get_sub_field('location');
		$startTime = get_sub_field('startdatetime');

		$course_classes = array(
			'ipa-single-course',
			'mix',
			// $course_detail['course_type_name'],
			$location['state'],
		);

		$instructor_1 = "REPLACE ME";
		?>
		<tr class="<?= implode(" ", $course_classes) ?>"
		    data-state=".<?= $location['state'] ?>"
		    data-primary-instructor="<?= $instructor_1; ?>"
		    data-course-type="<?= $eventId; ?>"
		    data-region="<?= get_region_by_state($location['state']) ?>"
		    data-start-date="<?= date('m-d-y', strtotime($startTime)); ?>">

            <td class="course-table-location">
                <span class="hide-for-medium"><b><?= __('Course', 'ipa'); ?>:</b></span>
                <b><?php get_template_part('parts/arlo/events/loop-session', 'name'); ?></b><br>
				<?php get_template_part('parts/arlo/events/loop-session', 'location'); ?>
            </td>

			<td class="course-table-date"
			    data-order="<?= date('u', strtotime($startTime)); ?>">
				<span class="hide-for-medium"><b><?= __('Date', 'ipa'); ?>:</b></span>
				<?php get_template_part('parts/arlo/events/loop-session', 'datetime'); ?>
			</td>

			<td class="course-table-instructor">
				<span class="hide-for-medium"><b><?= __('Scheduled Instructor(s)', 'ipa'); ?>:</b></span>
				<?php if (have_rows('presenters')) : ?>
					<?php get_template_part('parts/arlo/events/loop', 'presenters'); ?>
				<?php endif; ?>
			</td>
		</tr>
	<?php endwhile; ?>
	</tbody>
</table>