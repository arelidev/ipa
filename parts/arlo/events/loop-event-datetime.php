<?php
$post = isset($args['post']) ? (int)$args['post'] : get_the_ID();

$startTime = get_field('startdatetime', $post);
$startTimezone = get_field('starttimezoneabbr', $post);
$endTime = get_field('enddatetime', $post);
$endTimezone = get_field('endtimezoneabbr', $post);
?>
<span data-tooltip data-allow-html="true" tabindex="1"
      title="
        Starts on <?= date(get_option('date_format'), strtotime($startTime)); ?> at <?= date(get_option('time_format'), strtotime($startTime)); ?> <?= $startTimezone; ?>
        <br/>
        End on <?= date(get_option('date_format'), strtotime($endTime)); ?> at <?= date(get_option('time_format'), strtotime($endTime)); ?> <?= $endTimezone; ?>
">
<?= date(get_option('date_format'), strtotime($startTime)); ?> <?= $startTimezone; ?> - <?= date(get_option('date_format'), strtotime($endTime)); ?> <?= $endTimezone; ?>
</span>