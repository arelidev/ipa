<?php
/**
 * @param $state
 *
 * @return false|string
 */
function get_region_by_state( $state ) {
	$regions = array(
		'midatlantic' => 'PA MD DC VA WV',
		'midwest'     => 'ND SD NE IA MN WI MI IL IN OH KS MO AR',
		'northeast'   => 'CT ME MA NH NJ NY PA RI VT DE',
		'northwest'   => 'MT WY ID OR WA AK',
		'southwest'   => 'TX AR LA OK NM',
		'southeast'   => 'KY TN NC SC MS GA AL FL',
		'west'        => 'AZ CA NV HI CO UT',
	);

	foreach ( $regions as $key => $value ) {
		if ( strpos( $value, $state ) !== false ) {
			return $key;
		}
	}

	return false;
}

/**
 * @param $timestamp
 * @param string $timezone
 * @param string $format
 *
 * @return string
 */
function get_date_time( $timestamp, string $timezone, string $format = 'd.m.Y, H:i:s' ): string {
	try {
		$dt = new DateTimeImmutable( $timestamp );
		$dt->setTimezone( new DateTimeZone( $timezone ) );

		return $dt->format( $format ) . " $timezone";
	} catch ( Exception $e ) {
		return $e->getMessage();
	}
}

/**
 * @param $where
 *
 * @return string
 */
function presenter_events_where( $where ): string {
	return str_replace( "meta_key = 'presenters_$", "meta_key LIKE 'presenters_%", $where );
}

add_filter( 'posts_where', 'presenter_events_where' );

/**
 * @param $where
 *
 * @return string
 */
function categories_events_where( $where ): string {
	return str_replace( "meta_key = 'categories_$", "meta_key LIKE 'categories_%", $where );
}

add_filter( 'posts_where', 'categories_events_where' );

/**
 * @param $categories
 *
 * @return bool
 */
function is_virtual( $categories ): bool {
	$is_virtual = false;

	foreach ( $categories as $category ) :
		if ( $category['name'] == "Virtual" ) :
			$is_virtual = true;
			break;
		endif;
	endforeach;

	return $is_virtual;
}