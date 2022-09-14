<?php
// Usage:
// while (have_rows('sessions')) : the_row();

$startTime = get_field('startdatetime');
$startTimezone = get_field('starttimezoneabbr');
$endTime = get_field('enddatetime');
$endTimezone = get_field('endtimezoneabbr');
?>
<span data-tooltip data-allow-html="true" tabindex="1"
      title="
        Starts on <?= date(get_option('date_format'), strtotime($startTime)); ?> at <?= date(get_option('time_format'), strtotime($startTime)); ?> <?= $startTimezone ; ?>
        <br/>
        End on <?= date(get_option('date_format'), strtotime($endTime)); ?> at <?= date(get_option('time_format'), strtotime($endTime)); ?> <?= $endTimezone ; ?>
">
<?= date(get_option('date_format'), strtotime($startTime)); ?> <?= $startTimezone ; ?> - <?= date(get_option('date_format'), strtotime($endTime)); ?> <?= $endTimezone ; ?>
</span>