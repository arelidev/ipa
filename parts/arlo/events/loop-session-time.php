<?php
$stateDateTime = get_sub_field('startdatetime');
$stateDateTimezone = get_sub_field('starttimezoneabbr');
$endDateTime = get_sub_field('enddatetime');
$endDateTimezone = get_sub_field('endtimezoneabbr');
?>
<span data-tooltip data-allow-html="true" tabindex="1"
      title="
      <?= date(get_option('date_format'), strtotime($stateDateTime)); ?>
      -
      <?= date(get_option('date_format'), strtotime($endDateTime)); ?>
">
	<?= __('Starts at', 'ipa'); ?> <?= get_date_time($stateDateTime, $stateDateTimezone, get_option('time_format')); ?>
    <br/>
    <?= __('Ends at', 'ipa'); ?> <?= get_date_time($endDateTime, $endDateTimezone, get_option('time_format')); ?>
</span>