<?php
$ids     = $args["ids"];
$display = $args["display"];
?>

<tbody>
<?php
foreach ( $ids as $id ) :
	$eventId = get_field( 'eventid', $id );
	$eventCode = get_field( 'code', $id );
	$templateCode = get_field( 'templatecode', $id );

	$parentClasses = [
		$eventId,
		$eventCode,
		$templateCode,
		"mix"
	];

	if ( have_rows( "presenters", $id ) ) :
		while ( have_rows( "presenters", $id ) ) : the_row();
			$parentClasses[] = acf_slugify( get_sub_field( 'name' ) );
		endwhile;
	endif;

	$eventTitle = get_the_title( $id );
	$sessions   = get_field( "sessions", $id );
	$startDate  = get_field( 'startdatetime', $id );
	$endDate    = get_field( 'enddatetime', $id );
	$categories = get_field( 'categories', $id );

	$is_virtual   = is_virtual( $categories );
	$is_on_demand = is_on_demand( $categories );

	if ( $is_virtual ) :
		$parentClasses[] = "virtual";

		if ( $display === "calendar" ) :
			continue;
		endif;
    elseif ( $is_on_demand ) :
		$parentClasses[] = "on-demand";

		if ( $display === "calendar" ) :
			continue;
		endif;
	else :
		$parentClasses[] = "in-person";
	endif;

	$venue = $city = $state = "";

	if ( $sessions ) :
		$first    = $sessions[0]["location"];
		$location = $first[0];

		$venue = $location['venuename'] ?? false;
		$city  = $location['city'] ?? false;
		$state = $location['state'] ?? false;

		if ( $state ) :
			$slugify_state   = acf_slugify( $state );
			$parentClasses[] = $slugify_state;

			$regions = get_region_by_state( $slugify_state );
			foreach ( $regions as $region ) :
				$parentClasses[] = $region;
			endforeach;
		endif;
	endif;

	$registrationInfo = get_field( 'registrationinfo', $id );
	$registerUri      = $registrationInfo['registeruri'];
	$registermessage  = $registrationInfo['registermessage'];

	$permalink = getCoursePermalink( $templateCode, $is_virtual, $is_on_demand ) ?? $registerUri;
	?>
    <tr class="<?= implode( " ", $parentClasses ); ?>"
        id="<?= $eventId; ?>"
        data-start-date="<?= date( 'm/d/Y', strtotime( $startDate ) ); ?>"
        data-end-date="<?= date( 'm/d/Y', strtotime( $endDate ) ); ?>"
    >
        <td class="course-table--title">
            <a href="<?= $permalink; ?>">
                <b><?= $eventTitle; ?></b>
            </a>
        </td>
        <td class="course-table--location">
			<?= $venue; ?>
			<?= ! empty( $city ) ? "<br>$city" : ""; ?> <?= ! empty( $state ) ? ", $state" : ""; ?>
        </td>
        <td class="course-table--date">
            <div class="text-color-dark-gray">
				<?php
				get_template_part(
					'parts/arlo/events/loop-event',
					'datetime',
					array(
						'post' => $id
					)
				);
				?>
            </div>
        </td>
        <td class="course-table--instructor">
			<?php
			if ( have_rows( 'presenters', $id ) ) :
				get_template_part(
					'parts/arlo/events/loop',
					'presenters',
					array(
						'post' => $id
					)
				);
			endif;
			?>
        </td>
        <td class="course-table--register text-center">
            <a href="<?= $registerUri; ?>" <?= ( empty( $registerUri ) ) ? "disabled" : ""; ?>>
                <b><?= $registermessage; ?></b>
            </a>
            <a href="<?= $permalink; ?>" style="margin-left: 1rem;">
                <b><?= __( "More info", "ipa" ); ?></b>
            </a>
        </td>
    </tr>
<?php endforeach; ?>
</tbody>