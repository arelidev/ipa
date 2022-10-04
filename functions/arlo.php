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