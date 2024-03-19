<?php
$post = isset( $args['post'] ) ? (int) $args['post'] : get_the_ID();

$stateDateTime     = get_field( 'startdatetime', $post );
$stateDateTimezone = get_field( 'starttimezoneabbr', $post );
$endDateTime       = get_field( 'enddatetime', $post );
$endDateTimezone   = get_field( 'endtimezoneabbr', $post );

$start_date = date( "M j, Y", strtotime( $stateDateTime ) );
$state_time = get_date_time( $stateDateTime, $stateDateTimezone, get_option( 'time_format' ) );
$end_date   = date( "M j, Y", strtotime( $endDateTime ) );
$end_time   = get_date_time( $endDateTime, $endDateTimezone, get_option( 'time_format' ) );
?>
<span data-tooltip data-allow-html="true" tabindex="1"
      title="Starts on <?= $start_date; ?> at <?= $state_time; ?><br/>End on <?= $end_date; ?> at <?= $end_time; ?>">
      <?= $start_date; ?> - <?= $end_date; ?>
</span>