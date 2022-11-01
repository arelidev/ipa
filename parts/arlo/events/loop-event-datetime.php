<?php
$post = isset($args['post']) ? (int)$args['post'] : get_the_ID();

$stateDateTime = get_field('startdatetime', $post);
$stateDateTimezone = get_field('starttimezoneabbr', $post);
$endDateTime = get_field('enddatetime', $post);
$endDateTimezone = get_field('endtimezoneabbr', $post);
?>
<span data-tooltip data-allow-html="true" tabindex="1"
      title="
        Starts on <?= date(get_option('date_format'), strtotime($stateDateTime)); ?> at <?= get_date_time($stateDateTime, $stateDateTimezone, get_option('time_format')); ?>
        <br/>
        End on <?= date(get_option('date_format'), strtotime($endDateTime)); ?> at <?= get_date_time($endDateTime, $endDateTimezone, get_option('time_format')); ?>
">
<?= date(get_option('date_format'), strtotime($stateDateTime)); ?> - <?= date(get_option('date_format'), strtotime($endDateTime)); ?>
</span>