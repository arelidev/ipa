<?php
global $remote_db;

define( 'FACULTY_MEMBER_IMAGE_URL', 'http://staging.instituteofphysicalart.com/media/ipa/profile/general/' );

// Connect to remove database
$remote_db = new wpdb(
	'ipatest_areli',
	's;cC@^zp.HF7',
	'ipatest_191221',
	'test.instituteofphysicalart.com'
);

/**
 * Get Raw Courses
 *
 * @param null $limit
 * @param null $category
 *
 * @return array|object|null
 */
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

	return $remote_db->get_results( $sql, ARRAY_A );
}

/**
 * Get Courses From Category
 *
 * @param null $limit
 * @param null $category
 *
 * @return array
 */
function get_sorted_courses( $limit = null, $category = null ) {
	$courses = get_courses( $limit, $category );

	$sorted = array();
	foreach ( $courses as $element ) {
		$sorted[ $element['course_type_name'] ][] = $element;
	}

	return $sorted;
}

/**
 * Get Raw Faculty
 *
 * @param null $id
 *
 * @return array|object|null
 */
function get_faculty( $id = null ) {
	global $remote_db;

	$sql = "SELECT `e`.*,
       `at_prefix`.`value`             AS `prefix`,
       `at_firstname`.`value`          AS `firstname`,
       `at_middlename`.`value`         AS `middlename`,
       `at_lastname`.`value`           AS `lastname`,
       `at_suffix`.`value`             AS `suffix`,
       `at_credentials`.`value`        AS `credentials`,
       `at_image`.`value`        AS `image`,
       `at_bio`.`value`                AS `bio`,
       CONCAT(IF(at_prefix.value IS NOT NULL AND at_prefix.value != '', CONCAT(LTRIM(RTRIM(at_prefix.value)), ' '), ''),
              LTRIM(RTRIM(at_firstname.value)), ' ', IF(at_middlename.value IS NOT NULL AND at_middlename.value != '',
                                                        CONCAT(LTRIM(RTRIM(at_middlename.value)), ' '), ''),
              LTRIM(RTRIM(at_lastname.value)),
              IF(at_suffix.value IS NOT NULL AND at_suffix.value != '', CONCAT(' ', LTRIM(RTRIM(at_suffix.value))),
                 ''))                  AS `name`,
       `at_default_billing`.`value`    AS `default_billing`,
       `at_billing_postcode`.`value`   AS `billing_postcode`,
       `at_billing_street`.`value`     AS `billing_street`,
       `at_billing_city`.`value`       AS `billing_city`,
       `at_billing_telephone`.`value`  AS `billing_telephone`,
       `at_billing_region`.`value`     AS `billing_region`,
       `at_billing_country_id`.`value` AS `billing_country_id`
FROM `customer_entity` AS `e`
         LEFT JOIN `customer_entity_varchar` AS `at_prefix`
                   ON (`at_prefix`.`entity_id` = `e`.`entity_id`) AND (`at_prefix`.`attribute_id` = '4')
         LEFT JOIN `customer_entity_varchar` AS `at_firstname`
                   ON (`at_firstname`.`entity_id` = `e`.`entity_id`) AND (`at_firstname`.`attribute_id` = '5')
         LEFT JOIN `customer_entity_varchar` AS `at_middlename`
                   ON (`at_middlename`.`entity_id` = `e`.`entity_id`) AND (`at_middlename`.`attribute_id` = '6')
         LEFT JOIN `customer_entity_varchar` AS `at_lastname`
                   ON (`at_lastname`.`entity_id` = `e`.`entity_id`) AND (`at_lastname`.`attribute_id` = '7')
         LEFT JOIN `customer_entity_varchar` AS `at_suffix`
                   ON (`at_suffix`.`entity_id` = `e`.`entity_id`) AND (`at_suffix`.`attribute_id` = '8')
         LEFT JOIN `customer_entity_varchar` AS `at_credentials`
                   ON (`at_credentials`.`entity_id` = `e`.`entity_id`) AND (`at_credentials`.`attribute_id` = '164')
         LEFT JOIN `customer_entity_varchar` AS `at_image`
                   ON (`at_image`.`entity_id` = `e`.`entity_id`) AND (`at_image`.`attribute_id` = '119')
         LEFT JOIN `customer_entity_text` AS `at_bio`
                   ON (`at_bio`.`entity_id` = `e`.`entity_id`) AND (`at_bio`.`attribute_id` = '121')
         LEFT JOIN `customer_entity_int` AS `at_default_billing`
                   ON (`at_default_billing`.`entity_id` = `e`.`entity_id`) AND
                      (`at_default_billing`.`attribute_id` = '13')
         LEFT JOIN `customer_address_entity_varchar` AS `at_billing_postcode`
                   ON (`at_billing_postcode`.`entity_id` = `at_default_billing`.`value`) AND
                      (`at_billing_postcode`.`attribute_id` = '29')
         LEFT JOIN `customer_address_entity_text` AS `at_billing_street`
                   ON (`at_billing_street`.`entity_id` = `at_default_billing`.`value`) AND
                      (`at_billing_street`.`attribute_id` = '24')
         LEFT JOIN `customer_address_entity_varchar` AS `at_billing_city`
                   ON (`at_billing_city`.`entity_id` = `at_default_billing`.`value`) AND
                      (`at_billing_city`.`attribute_id` = '25')
         LEFT JOIN `customer_address_entity_varchar` AS `at_billing_telephone`
                   ON (`at_billing_telephone`.`entity_id` = `at_default_billing`.`value`) AND
                      (`at_billing_telephone`.`attribute_id` = '30')
         LEFT JOIN `customer_address_entity_varchar` AS `at_billing_region`
                   ON (`at_billing_region`.`entity_id` = `at_default_billing`.`value`) AND
                      (`at_billing_region`.`attribute_id` = '27')
         LEFT JOIN `customer_address_entity_varchar` AS `at_billing_country_id`
                   ON (`at_billing_country_id`.`entity_id` = `at_default_billing`.`value`) AND
                      (`at_billing_country_id`.`attribute_id` = '26')
WHERE (`e`.`entity_type_id` = '1')
  AND (`e`.`group_id` = '5')";

	if ( ! empty( $id ) ) :
		$sql .= " AND (`e`.`entity_id` = '{$id}')";
	endif;

	return $remote_db->get_results( $sql, ARRAY_A );
}
