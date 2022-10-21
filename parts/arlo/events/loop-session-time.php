<?php
$startTime = get_sub_field('startdatetime');
$startTimezone = get_sub_field('starttimezoneabbr');
$endTime = get_sub_field('enddatetime');
$endTimezone = get_sub_field('endtimezoneabbr');
?>
<span data-tooltip data-allow-html="true" tabindex="1"
      title="
      <?= date(get_option('date_format'), strtotime($startTime)); ?> <?= $startTimezone; ?>
      -
      <?= date(get_option('date_format'), strtotime($endTime)); ?> <?= $endTimezone; ?>
">
	<?= __('Starts at', 'ipa'); ?> <?= date(get_option('time_format'), strtotime($startTime)); ?> <?= $startTimezone; ?>
    <br/>
    <?= __('Ends at', 'ipa'); ?> <?= date(get_option('time_format'), strtotime($endTime)); ?> <?= $endTimezone; ?>
</span>