<?php
global $remote_db;
$remote_db = new wpdb( 'ipastagi_areli', 'r)JZisIK$gl,', 'ipastagi_191010', 'staging.instituteofphysicalart.com' );

function get_courses( $limit = null, $category = null ) {
	global $remote_db;

	$sql = "SELECT d.*,
               r.request_path
        FROM ipa_course_details d
                 JOIN cataloginventory_stock_status s
                      ON d.id = s.product_id
                 LEFT JOIN core_url_rewrite r
                           ON d.id = r.product_id
        WHERE d.date >= DATE(NOW())
          AND r.store_id = 1
          AND r.is_system = 1
          AND r.category_id IS NOT NULL
          AND s.stock_status = 1";

	if ( ! empty( $category ) ) :
		$sql .= " AND d.course_type_name = '{$category}'";
	endif;

	if ( ! empty( $limit ) ) :
		$sql .= " LIMIT {$limit}";
	endif;

	$courses = $remote_db->get_results( $sql, ARRAY_A );

	return $courses;
}

function get_sorted_courses( $limit = null, $category = null ) {
	$courses = get_courses( $limit, $category );

	$sorted = array();
	foreach ( $courses as $element ) {
		$sorted[ $element['course_type_name'] ][] = $element;
	}

	return $sorted;
}
