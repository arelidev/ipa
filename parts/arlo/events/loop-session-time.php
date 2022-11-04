<?php
$stateDateTime = get_sub_field('startdatetime');
$stateDateTimezone = get_sub_field('starttimezoneabbr');
$endDateTime = get_sub_field('enddatetime');
$endDateTimezone = get_sub_field('endtimezoneabbr');

$start_date = get_date_time($stateDateTime, $stateDateTimezone, get_option('date_format'));
$state_time = get_date_time($stateDateTime, $stateDateTimezone, get_option('time_format'));
$end_date = get_date_time($endDateTime, $endDateTimezone, get_option('date_format'));
$end_time = get_date_time($endDateTime, $endDateTimezone, get_option('time_format'));
?>
<span data-tooltip data-allow-html="true" tabindex="1" title=" <?= $start_date; ?> - <?= $end_date; ?>">
	<?= __('Starts at', 'ipa'); ?> <?= $state_time; ?>
    <br/>
    <?= __('Ends at', 'ipa'); ?> <?= $end_time; ?>
</span>