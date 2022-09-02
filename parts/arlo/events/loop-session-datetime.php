<?php
// Usage:
// while (have_rows('sessions')) : the_row();

$startTime = get_sub_field('startdatetime');
$endTime = get_sub_field('enddatetime');
?>
<span data-tooltip data-allow-html="true" tabindex="1"
      title="
        Starts on <?= date(get_option('date_format'), strtotime($startTime)); ?> at <?= date(get_option('time_format'), strtotime($startTime)); ?>
        <br/>
        End on <?= date(get_option('date_format'), strtotime($endTime)); ?> at <?= date(get_option('time_format'), strtotime($endTime)); ?>
">
<?= date(get_option('date_format'), strtotime($startTime)); ?> - <?= date(get_option('date_format'), strtotime($endTime)); ?>
</span>