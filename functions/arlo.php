<?php
/**
 * @param $state
 * @return false|string
 */
function get_region_by_state($state)
{
	$regions = array(
		'midatlantic' => 'PA MD DC VA WV',
		'midwest' => 'ND SD NE IA MN WI MI IL IN OH KS MO AR',
		'northeast' => 'CT ME MA NH NJ NY PA RI VT DE',
		'northwest' => 'MT WY ID OR WA AK',
		'southwest' => 'TX AR LA OK NM',
		'southeast' => 'KY TN NC SC MS GA AL FL',
		'-west' => 'AZ CA NV HI CO UT',
	);

	foreach ($regions as $key => $value) {
		if (strpos($value, $state) !== false) {
			return $key;
		}
	}

	return false;
}

/**
 * @param $timestamp
 * @param $timezone
 * @param string $format
 * @return string
 * @throws Exception
 */
function get_date_time($timestamp, $timezone, string $format = 'd.m.Y, H:i:s'): string
{
	$dt = new DateTime("now", new DateTimeZone($timezone));
	$dt->setTimestamp(strtotime($timestamp));
	return $dt->format($format);
}

/**
 * @param $where
 * @return array|string|string[]
 */
function presenter_events_where($where)
{
	return str_replace("meta_key = 'presenters_$", "meta_key LIKE 'presenters_%", $where);
}

add_filter('posts_where', 'presenter_events_where');