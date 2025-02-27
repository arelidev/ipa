<?php
global $remote_db, $stage_url;

switch ( wp_get_environment_type() ) {
	case 'local':
	case 'development':
	case 'staging':
		$db_username = "ipatest_areli";
		$db_password = "s;cC@^zp.HF7";
		$db_database = "ipatest_201121";
		$db_host     = "test.instituteofphysicalart.com";
		$stage_url   = "https://test.instituteofphysicalart.com";
		break;
	default:
		$db_username = "myipa_wordpress";
		$db_password = "s;cC@^zp.HF7";
		$db_database = "myipa_191221";
		$db_host     = "my.instituteofphysicalart.com";
		$stage_url   = "https://my.instituteofphysicalart.com";
		break;
}

// Connect to remove database
$remote_db = new wpdb( $db_username, $db_password, $db_database, $db_host );

// Set IPA staging URL
function stage_url( $path = '' ): string {
	global $stage_url;

	return "{$stage_url}/{$path}";
}

/**
 * Get Raw Courses
 *
 * @param null $limit
 * @param null $category
 * @param false $popular
 * @param int $delivery_method
 *
 * @return array|object|null
 */
function get_courses( $limit = null, $category = null, $popular = false, $delivery_method = 1 ): ?array {
	global $remote_db;

	$sql = "
select `e`.*,
       `r`.`request_path`                                                    AS `request_path`,
       `at_status`.`value`                                                   AS `status`,
       `at_visibility`.`value`                                               AS `visibility`,
       `at_end_date`.`value`                                                 AS `end_date`,
       `at_instr1`.`value`                                                   AS `instr1`,
       `at_image1`.`value`                                                   AS `image1`,
       `at_instr2`.`value`                                                   AS `instr2`,
       `at_image2`.`value`                                                   AS `image2`,
       `at_instr3`.`value`                                                   AS `instr3`,
       `at_image3`.`value`                                                   AS `image3`,
       `at_instr4`.`value`                                                   AS `instr4`,
       `at_image4`.`value`                                                   AS `image4`,
       `at_popular_course`.`value`                                           AS `popular_course`,
       `ipa_course_type`.`delivery_method`                                   as `delivery_method`,
       CONCAT(TRIM(at_instr1_fname.value), ' ', TRIM(at_instr1_lname.value)) AS `instructor1`,
       CONCAT(TRIM(at_instr2_fname.value), ' ', TRIM(at_instr2_lname.value)) AS `instructor2`,
       CONCAT(TRIM(at_instr3_fname.value), ' ', TRIM(at_instr3_lname.value)) AS `instructor3`,
       CONCAT(TRIM(at_instr4_fname.value), ' ', TRIM(at_instr4_lname.value)) AS `instructor4`
from `ipa_course_details` as `e`
         LEFT JOIN core_url_rewrite as `r`
                   ON `e`.`id` = `r`.`product_id`
         left join `catalog_product_entity_int` as `at_status`
                   on (`at_status`.`entity_id` = `e`.`id`) AND (`at_status`.`attribute_id` = 84)
         left join `catalog_product_entity_int` as `at_visibility`
                   on (`at_visibility`.`entity_id` = `e`.`id`) AND (`at_visibility`.`attribute_id` = 91)
         left join `catalog_product_entity_datetime` as `at_end_date`
                   on (`at_end_date`.`entity_id` = `e`.`id`) AND (`at_end_date`.`attribute_id` = 156)
         left join `catalog_product_entity_int` as `at_instr1`
                   on (`at_instr1`.`entity_id` = `e`.`id`) AND (`at_instr1`.`attribute_id` = 137)
         left join `customer_entity_varchar` as `at_instr1_lname`
                   on (`at_instr1`.`value` = `at_instr1_lname`.`entity_id` and `at_instr1_lname`.`attribute_id` = 7)
         left join `customer_entity_varchar` as `at_instr1_fname`
                   on (`at_instr1`.`value` = `at_instr1_fname`.`entity_id` and `at_instr1_fname`.`attribute_id` = 5)
         LEFT JOIN `customer_entity_varchar` AS `at_image1`
                   ON (`at_instr1`.`value` = `at_image1`.`entity_id`) AND (`at_image1`.`attribute_id` = '119')
         left join `catalog_product_entity_int` as `at_instr2`
                   on (`at_instr2`.`entity_id` = `e`.`id`) AND (`at_instr2`.`attribute_id` = 138)
         left join `catalog_product_entity_int` as `at_popular_course`
                   on (`at_popular_course`.`entity_id` = `e`.`id`) AND (`at_popular_course`.`attribute_id` = 244)
         left join `customer_entity_varchar` as `at_instr2_lname`
                   on (`at_instr2`.`value` = `at_instr2_lname`.`entity_id` and `at_instr2_lname`.`attribute_id` = 7)
         left join `customer_entity_varchar` as `at_instr2_fname`
                   on (`at_instr2`.`value` = `at_instr2_fname`.`entity_id` and `at_instr2_fname`.`attribute_id` = 5)
         LEFT JOIN `customer_entity_varchar` AS `at_image2`
                   ON (`at_instr2`.`value` = `at_image2`.`entity_id`) AND (`at_image2`.`attribute_id` = '119')
         left join `catalog_product_entity_int` as `at_instr3`
                   on (`at_instr3`.`entity_id` = `e`.`id`) AND (`at_instr3`.`attribute_id` = 139)
         left join `customer_entity_varchar` as `at_instr3_lname`
                   on (`at_instr3`.`value` = `at_instr3_lname`.`entity_id` and `at_instr3_lname`.`attribute_id` = 7)
         left join `customer_entity_varchar` as `at_instr3_fname`
                   on (`at_instr3`.`value` = `at_instr3_fname`.`entity_id` and `at_instr3_fname`.`attribute_id` = 5)
         LEFT JOIN `customer_entity_varchar` AS `at_image3`
                   ON (`at_instr3`.`value` = `at_image3`.`entity_id`) AND (`at_image3`.`attribute_id` = '119')
         left join `catalog_product_entity_int` as `at_instr4`
                   on (`at_instr4`.`entity_id` = `e`.`id`) AND (`at_instr4`.`attribute_id` = 140)
         left join `customer_entity_varchar` as `at_instr4_lname`
                   on (`at_instr4`.`value` = `at_instr4_lname`.`entity_id` and `at_instr4_lname`.`attribute_id` = 7)
         left join `customer_entity_varchar` as `at_instr4_fname`
                   on (`at_instr4`.`value` = `at_instr4_fname`.`entity_id` and `at_instr4_fname`.`attribute_id` = 5)
         LEFT JOIN `customer_entity_varchar` AS `at_image4`
                   ON (`at_instr4`.`value` = `at_image4`.`entity_id`) AND (`at_image4`.`attribute_id` = '119')
         left join `ipa_course_type`
                   on (`ipa_course_type`.`id` = `course_type`)
where `at_end_date`.`value` >= DATE(NOW())
  and `at_status`.`value` = 1
  and `at_visibility`.`value` IN (2, 4)
  AND `r`.`store_id` = 1
  AND `r`.`is_system` = 1
  AND `r`.`category_id` IS NOT NULL
";

	if ( ! empty( $category ) ) :
		$sql .= " AND e.course_type_name = '{$category}'";
	endif;

	if ( $popular ) :
		$sql .= " AND `at_popular_course`.`value` = 1";
	endif;

	$sql .= " AND delivery_method IN ({$delivery_method})";

	$sql .= " ORDER BY `course_type_name`, `end_date`";

	if ( ! empty( $limit ) ) :
		$sql .= " LIMIT {$limit}";
	endif;

	$sql .= ";";

	return $remote_db->get_results( $sql, ARRAY_A );
}

/**
 * Get Courses From Category
 *
 * @param null $limit
 * @param null $category
 * @param int $delivery_method
 *
 * @return array
 */
function get_sorted_courses( $limit = null, $category = null, $delivery_method = 1 ): array {
	$courses = get_courses( $limit, $category, false, $delivery_method );

	$sorted  = array();
	$sort_by = ( $delivery_method == 1 ) ? 'course_type_name' : 'end_date';
	foreach ( $courses as $element ) {
		$sorted[ $element[ $sort_by ] ][] = $element;
	}

	ksort( $sorted );

	return $sorted;
}

/**
 * Get Raw Faculty
 *
 * @param null $id
 *
 * @return array|object|null
 */
function get_faculty( $id = null ): ?array {
	global $remote_db;

	// Values are as follows:  0=Inactive/Not an instructor, 1=Primary, 2=Associate

	$sql = "SELECT `e`.*,
       `at_prefix`.`value`            AS `prefix`,
       `at_firstname`.`value`         AS `firstname`,
       `at_middlename`.`value`        AS `middlename`,
       `at_lastname`.`value`          AS `lastname`,
       `at_suffix`.`value`            AS `suffix`,
       `at_image`.`value`             AS `image`,
       `at_credentials`.`value`       AS `credentials`,
       `at_bio`.`value`               AS `bio`,
       CONCAT(IF(at_prefix.value IS NOT NULL AND at_prefix.value != '', CONCAT(LTRIM(RTRIM(at_prefix.value)), ' '), ''),
              LTRIM(RTRIM(at_firstname.value)), ' ', IF(at_middlename.value IS NOT NULL AND at_middlename.value != '',
                                                        CONCAT(LTRIM(RTRIM(at_middlename.value)), ' '), ''),
              LTRIM(RTRIM(at_lastname.value)),
              IF(at_suffix.value IS NOT NULL AND at_suffix.value != '', CONCAT(' ', LTRIM(RTRIM(at_suffix.value))),
                 ''))                 AS `name`,
       `at_work_street`.`value`       AS `work_street`,
       `at_work_city`.`value`         AS `work_city`,
       `at_work_state`.`value`        AS `work_state`,
       `at_work_zip`.`value`          AS `work_zip`,
       `at_work_phone`.`value`        AS `work_telephone`,
       `at_work_email`.`value`        AS `work_email`,
       `at_work_country`.`value`      AS `work_country`,
       `at_customer_website`.`value`  AS `customer_website`,
       `at_instructor_status`.`value` AS `instructor_status`,
       `at_cfmt_honors`.`value`   		AS `cfmt_honors`,
       `at_cfmt_distinction`.`value`   	AS `cfmt_distinction`,
       `at_FAAOMPT`.`value`   			AS `FAAOMPT`
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
         LEFT JOIN `customer_entity_text` AS `at_work_street`
                   ON (`at_work_street`.`entity_id` = `e`.`entity_id`) AND (`at_work_street`.`attribute_id` = '178')
         LEFT JOIN `customer_entity_varchar` AS `at_work_city`
                   ON (`at_work_city`.`entity_id` = `e`.`entity_id`) AND (`at_work_city`.`attribute_id` = '179')
         LEFT JOIN `customer_entity_varchar` AS `at_work_state`
                   ON (`at_work_state`.`entity_id` = `e`.`entity_id`) AND (`at_work_state`.`attribute_id` = '180')
         LEFT JOIN `customer_entity_varchar` AS `at_work_zip`
                   ON (`at_work_zip`.`entity_id` = `e`.`entity_id`) AND (`at_work_zip`.`attribute_id` = '181')
         LEFT JOIN `customer_entity_varchar` AS `at_work_phone`
                   ON (`at_work_phone`.`entity_id` = `e`.`entity_id`) AND (`at_work_phone`.`attribute_id` = '182')
         LEFT JOIN `customer_entity_varchar` AS `at_work_email`
                   ON (`at_work_email`.`entity_id` = `e`.`entity_id`) AND (`at_work_email`.`attribute_id` = '183')
         LEFT JOIN `customer_entity_text` AS `at_customer_website`
                   ON (`at_customer_website`.`entity_id` = `e`.`entity_id`) AND
                      (`at_customer_website`.`attribute_id` = '184')
         LEFT JOIN `customer_entity_varchar` AS `at_work_country`
                   ON (`at_work_country`.`entity_id` = `e`.`entity_id`) AND (`at_work_country`.`attribute_id` = '181')
         LEFT JOIN `customer_entity_int` AS `at_instructor_status`
                   ON (`at_instructor_status`.`entity_id` = `e`.`entity_id`) AND
                      (`at_instructor_status`.`attribute_id` = '243')
        LEFT JOIN `customer_entity_int` AS `at_cfmt_honors`
                   ON (`at_cfmt_honors`.`entity_id` = `e`.`entity_id`) AND (`at_cfmt_honors`.`attribute_id` = '189')
         LEFT JOIN `customer_entity_int` AS `at_cfmt_distinction`
                   ON (`at_cfmt_distinction`.`entity_id` = `e`.`entity_id`) AND (`at_cfmt_distinction`.`attribute_id` = '190')
         LEFT JOIN `customer_entity_int` AS `at_FAAOMPT`
                   ON (`at_FAAOMPT`.`entity_id` = `e`.`entity_id`) AND (`at_FAAOMPT`.`attribute_id` = '191')
WHERE (`e`.`entity_type_id` = '1')";

	if ( empty( $id ) ) :
		$sql .= " AND (`e`.`group_id` = '5')";
		$sql .= " AND (`at_instructor_status`.`value` IN (1, 2))";
	endif;

	if ( ! empty( $id ) ) :
		$sql .= " AND (`e`.`entity_id` IN ({$id}) )";
	endif;

	$sql .= "ORDER BY `lastname`;";

	return $remote_db->get_results( $sql, ARRAY_A );
}

/**
 * Get Courses for Instructors
 * todo: unify for raw courses query
 *
 * @param $id
 *
 * @return array|object|null
 */
function get_instructor_courses( $id ): ?array {
	global $remote_db;

	$sql = "select `e`.*,
       `r`.`request_path`       AS `request_path`,
       `r`.`category_id`        as `category_id`,
       `r`.`store_id`           as `store_id`,
       `at_start_date`.`value`  AS `start_date`,
       `at_end_date`.`value`    AS `end_date`,
       `at_instructor1`.`value` AS `instructor1`,
       `at_instructor2`.`value` AS `instructor2`,
       `at_instructor3`.`value` AS `instructor3`,
       `at_instructor4`.`value` AS `instructor4`,
       `at_status`.`value`      AS `status`,
       `at_visibility`.`value`  AS `visibility`,
        `at_instr1`.`value`                                                   AS `instr1`,
       `at_image1`.`value`                                                   AS `image1`,
       `at_instr2`.`value`                                                   AS `instr2`,
       `at_image2`.`value`                                                   AS `image2`,
       `at_instr3`.`value`                                                   AS `instr3`,
       `at_image3`.`value`                                                   AS `image3`,
       `at_instr4`.`value`                                                   AS `instr4`,
       `at_image4`.`value`                                                   AS `image4`,
        CONCAT(TRIM(at_instr1_fname.value), ' ', TRIM(at_instr1_lname.value)) AS `instructor1`,
       CONCAT(TRIM(at_instr2_fname.value), ' ', TRIM(at_instr2_lname.value)) AS `instructor2`,
       CONCAT(TRIM(at_instr3_fname.value), ' ', TRIM(at_instr3_lname.value)) AS `instructor3`,
       CONCAT(TRIM(at_instr4_fname.value), ' ', TRIM(at_instr4_lname.value)) AS `instructor4`
from `ipa_course_details` as `e`
         left join `core_url_rewrite` as `r`
                   on `e`.`id` = `r`.`product_id`
         left join `catalog_product_entity_int` as `at_status`
                   on (`at_status`.`entity_id` = `e`.`id`) AND (`at_status`.`attribute_id` = 84)
         left join `catalog_product_entity_int` as `at_visibility`
                   on (`at_visibility`.`entity_id` = `e`.`id`) AND (`at_visibility`.`attribute_id` = 91)
         left join `catalog_product_entity_int` as `at_instructor1`
                   on (`at_instructor1`.`entity_id` = `e`.`id`) AND (`at_instructor1`.`attribute_id` = 137)
         left join `catalog_product_entity_int` as `at_instructor2`
                   on (`at_instructor2`.`entity_id` = `e`.`id`) AND (`at_instructor2`.`attribute_id` = 138)
         left join `catalog_product_entity_int` as `at_instructor3`
                   on (`at_instructor3`.`entity_id` = `e`.`id`) AND (`at_instructor3`.`attribute_id` = 139)
         left join `catalog_product_entity_int` as `at_instructor4`
                   on (`at_instructor4`.`entity_id` = `e`.`id`) AND (`at_instructor4`.`attribute_id` = 140)
         left join `catalog_product_entity_datetime` as `at_start_date`
                   on (`at_start_date`.`entity_id` = `e`.`id`) AND (`at_start_date`.`attribute_id` = 161)
         left join `catalog_product_entity_datetime` as `at_end_date`
                   on (`at_end_date`.`entity_id` = `e`.`id`) AND (`at_end_date`.`attribute_id` = 156)
           left join `catalog_product_entity_int` as `at_instr1`
                   on (`at_instr1`.`entity_id` = `e`.`id`) AND (`at_instr1`.`attribute_id` = 137)
         left join `customer_entity_varchar` as `at_instr1_lname`
                   on (`at_instr1`.`value` = `at_instr1_lname`.`entity_id` and `at_instr1_lname`.`attribute_id` = 7)
         left join `customer_entity_varchar` as `at_instr1_fname`
                   on (`at_instr1`.`value` = `at_instr1_fname`.`entity_id` and `at_instr1_fname`.`attribute_id` = 5)
         LEFT JOIN `customer_entity_varchar` AS `at_image1`
                   ON (`at_instr1`.`value` = `at_image1`.`entity_id`) AND (`at_image1`.`attribute_id` = '119')
         left join `catalog_product_entity_int` as `at_instr2`
                   on (`at_instr2`.`entity_id` = `e`.`id`) AND (`at_instr2`.`attribute_id` = 138)
         left join `catalog_product_entity_int` as `at_popular_course`
                   on (`at_popular_course`.`entity_id` = `e`.`id`) AND (`at_popular_course`.`attribute_id` = 244)
         left join `customer_entity_varchar` as `at_instr2_lname`
                   on (`at_instr2`.`value` = `at_instr2_lname`.`entity_id` and `at_instr2_lname`.`attribute_id` = 7)
         left join `customer_entity_varchar` as `at_instr2_fname`
                   on (`at_instr2`.`value` = `at_instr2_fname`.`entity_id` and `at_instr2_fname`.`attribute_id` = 5)
         LEFT JOIN `customer_entity_varchar` AS `at_image2`
                   ON (`at_instr2`.`value` = `at_image2`.`entity_id`) AND (`at_image2`.`attribute_id` = '119')
         left join `catalog_product_entity_int` as `at_instr3`
                   on (`at_instr3`.`entity_id` = `e`.`id`) AND (`at_instr3`.`attribute_id` = 139)
         left join `customer_entity_varchar` as `at_instr3_lname`
                   on (`at_instr3`.`value` = `at_instr3_lname`.`entity_id` and `at_instr3_lname`.`attribute_id` = 7)
         left join `customer_entity_varchar` as `at_instr3_fname`
                   on (`at_instr3`.`value` = `at_instr3_fname`.`entity_id` and `at_instr3_fname`.`attribute_id` = 5)
         LEFT JOIN `customer_entity_varchar` AS `at_image3`
                   ON (`at_instr3`.`value` = `at_image3`.`entity_id`) AND (`at_image3`.`attribute_id` = '119')
         left join `catalog_product_entity_int` as `at_instr4`
                   on (`at_instr4`.`entity_id` = `e`.`id`) AND (`at_instr4`.`attribute_id` = 140)
         left join `customer_entity_varchar` as `at_instr4_lname`
                   on (`at_instr4`.`value` = `at_instr4_lname`.`entity_id` and `at_instr4_lname`.`attribute_id` = 7)
         left join `customer_entity_varchar` as `at_instr4_fname`
                   on (`at_instr4`.`value` = `at_instr4_fname`.`entity_id` and `at_instr4_fname`.`attribute_id` = 5)
         LEFT JOIN `customer_entity_varchar` AS `at_image4`
                   ON (`at_instr4`.`value` = `at_image4`.`entity_id`) AND (`at_image4`.`attribute_id` = '119')
where `at_end_date`.`value` >= DATE(NOW())
  AND `r`.`store_id` = 1
  AND `r`.`is_system` = 1
  AND `r`.`category_id` IS NOT NULL
  and `at_status`.`value` = 1
  and {$id} in (`at_instructor1`.`value`, `at_instructor2`.`value`, `at_instructor3`.`value`, `at_instructor4`.`value`)
ORDER BY `end_date`;";

	return $remote_db->get_results( $sql, ARRAY_A );
}

/**
 * Create Instructor Course Table
 *
 * @param $id
 */
function get_instructor_course_table( $id ) {
	$courses = get_instructor_courses( $id );
	?>
	<?php if ( ! empty( $courses ) ) : ?>
        <div class="courses-table-widget">
            <div class="course-wrapper">
                <table class="course-table hover stack">
                    <thead>
                    <tr>
                        <th><?= __( 'Course', 'ipa' ); ?></th>
                        <th><?= __( 'Location', 'ipa' ); ?></th>
                        <th><?= __( 'Date', 'ipa' ); ?></th>
                        <th><?= __( 'Scheduled Instructor(s)', 'ipa' ); ?></th>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody>
					<?php foreach ( $courses as $course ) : ?>
                        <tr>
                            <td class="course-table-location no-sort">
                                <span class="hide-for-medium"><b><?= __( 'Course', 'ipa' ); ?>:</b></span> <?= $course['course_type_name']; ?>
                            </td>
                            <td class="course-table-location no-sort">
                                <span class="hide-for-medium"><b><?= __( 'Location', 'ipa' ); ?>:</b></span> <?= $course['city']; ?>
                                , <?= $course['state']; ?>
                            </td>
                            <td class="course-table-date"
                                data-order="<?= date( 'u', strtotime( $course['date'] ) ); ?>">
                                <span class="hide-for-medium"><b><?= __( 'Date', 'ipa' ); ?>:</b></span>
								<?= date( get_option( 'date_format' ), strtotime( $course['date'] ) ); ?>
                                -
								<?= date( get_option( 'date_format' ), strtotime( $course['end_date'] ) ); ?>
                            </td>
                            <td class="course-table-instructor">
                                <span class="hide-for-medium"><b><?= __( 'Scheduled Instructor(s)', 'ipa' ); ?>:</b></span>
								<?php if ( ! empty( $instructor_1 = $course['instructor1'] ) ) : ?>
                                    <a href="<?= home_url(); ?>/profile/<?= clean( $instructor_1 ); ?>/<?= $course['instr1']; ?>">
                                        <img src="<?= get_instructor_image( $course['image1'] ); ?>"
                                             class="course-card-trainer"
                                             alt="<?= $instructor_1; ?>"
                                             data-tooltip tabindex="1"
                                             title="<?= $instructor_1; ?>">
                                    </a>
								<?php endif; ?>
								<?php if ( ! empty( $instructor_2 = $course['instructor2'] ) ) : ?>
                                    <a href="<?= home_url(); ?>/profile/<?= clean( $instructor_2 ); ?>/<?= $course['instr2']; ?>">
                                        <img src="<?= get_instructor_image( $course['image2'] ); ?>"
                                             class="course-card-trainer"
                                             alt="<?= $instructor_2; ?>"
                                             data-tooltip tabindex="2"
                                             title="<?= $instructor_2; ?>">
                                    </a>
								<?php endif; ?>
								<?php if ( ! empty( $instructor_3 = $course['instructor3'] ) ) : ?>
                                    <a href="<?= home_url(); ?>/profile/<?= clean( $instructor_3 ); ?>/<?= $course['instr3']; ?>">
                                        <img src="<?= get_instructor_image( $course['image3'] ); ?>"
                                             class="course-card-trainer"
                                             alt="<?= $instructor_3; ?>"
                                             data-tooltip tabindex="3"
                                             title="<?= $instructor_3; ?>">
                                    </a>
								<?php endif; ?>
								<?php if ( ! empty( $instructor_4 = $course['instructor4'] ) ) : ?>
                                    <a href="<?= home_url(); ?>/profile/<?= clean( $instructor_4 ); ?>/<?= $course['instr4']; ?>">
                                        <img src="<?= get_instructor_image( $course['image4'] ); ?>"
                                             class="course-card-trainer"
                                             alt="<?= $instructor_4; ?>"
                                             data-tooltip tabindex="4"
                                             title="<?= $instructor_4; ?>">
                                    </a>
								<?php endif; ?>
                            </td>
                            <td class="course-table-apply">
								<?php get_course_link( $course['request_path'], $course['visibility'] ); ?>
                            </td>
                        </tr>
					<?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
	<?php else : ?>
        <div class="callout primary">
			<?= __( 'Currently no courses scheduled - Check back later', 'ipa' ); ?>
        </div>
	<?php endif; ?>
	<?php
}

/**
 * Get Clinics
 *
 * @return array|object|null
 */
function get_clinics(): ?array {
	global $remote_db;

	$sql = "SELECT `e`.*,
       `at_prefix`.`value`             AS `prefix`,
       `at_firstname`.`value`          AS `firstname`,
       `at_middlename`.`value`         AS `middlename`,
       `at_lastname`.`value`           AS `lastname`,
       `at_suffix`.`value`             AS `suffix`,
       `at_image`.`value`        	   AS `image`,
	   `at_credentials`.`value`        AS `credentials`,
       `at_bio`.`value`                AS `bio`,
       CONCAT(IF(at_prefix.value IS NOT NULL AND at_prefix.value != '', CONCAT(LTRIM(RTRIM(at_prefix.value)), ' '), ''),
              LTRIM(RTRIM(at_firstname.value)), ' ', IF(at_middlename.value IS NOT NULL AND at_middlename.value != '',
                                                        CONCAT(LTRIM(RTRIM(at_middlename.value)), ' '), ''),
              LTRIM(RTRIM(at_lastname.value)),
              IF(at_suffix.value IS NOT NULL AND at_suffix.value != '', CONCAT(' ', LTRIM(RTRIM(at_suffix.value))),
                 ''))                  AS `name`,

       `at_work_street`.`value`     	AS `work_street`,
       `at_work_street2`.`value`     	AS `work_street2`,	   
       `at_work_city`.`value`       	AS `work_city`,
       `at_work_state`.`value`       	AS `work_state`,
       `at_work_zip`.`value`   			AS `work_zip`,	   
       `at_work_phone`.`value`  		AS `work_telephone`,
       `at_work_email`.`value`     		AS `work_email`,
       `at_business_name`.`value`     	AS `business_name`,	   
       `at_work_country`.`value` 		AS `work_country`,
       `at_work_latitude`.`value` 		AS `work_latitude`,
       `at_work_longitude`.`value` 		AS `work_longitude`,	   
       `at_customer_website`.`value`    AS `customer_website`,
	   `at_in_referral`.`value`   		AS `in_referral`,
	   `at_referral_approved`.`value`   AS `referral_approved`,
	   `at_cfmt_honors`.`value`   		AS `cfmt_honors`,
	   `at_cfmt_distinction`.`value`   	AS `cfmt_distinction`,
	   `at_FAAOMPT`.`value`   			AS `FAAOMPT`,
	   `at_current_fellow`.`value`   	AS `current_fellow`,
       `at_instructor_status`.`value` AS `instructor_status`,
	   `at_cfmt`.`value`   				AS `cfmt`	   
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
         LEFT JOIN `customer_entity_varchar` AS `at_image`
                   ON (`at_image`.`entity_id` = `e`.`entity_id`) AND (`at_image`.`attribute_id` = '119')				   
         LEFT JOIN `customer_entity_varchar` AS `at_credentials`
                   ON (`at_credentials`.`entity_id` = `e`.`entity_id`) AND (`at_credentials`.`attribute_id` = '164')
         LEFT JOIN `customer_entity_text` AS `at_bio`
                   ON (`at_bio`.`entity_id` = `e`.`entity_id`) AND (`at_bio`.`attribute_id` = '121')
		LEFT JOIN `customer_entity_text` AS `at_work_street`
                   ON (`at_work_street`.`entity_id` = `e`.`entity_id`) AND (`at_work_street`.`attribute_id` = '178')
		LEFT JOIN `customer_entity_text` AS `at_work_street2`
                   ON (`at_work_street2`.`entity_id` = `e`.`entity_id`) AND (`at_work_street2`.`attribute_id` = '219')				   
		LEFT JOIN `customer_entity_varchar` AS `at_work_city`
                   ON (`at_work_city`.`entity_id` = `e`.`entity_id`) AND (`at_work_city`.`attribute_id` = '179')
		LEFT JOIN `customer_entity_varchar` AS `at_work_state`
                   ON (`at_work_state`.`entity_id` = `e`.`entity_id`) AND (`at_work_state`.`attribute_id` = '180')
		LEFT JOIN `customer_entity_varchar` AS `at_work_zip`				   
                   ON (`at_work_zip`.`entity_id` = `e`.`entity_id`) AND (`at_work_zip`.`attribute_id` = '181')				   
		LEFT JOIN `customer_entity_varchar` AS `at_work_phone`
                   ON (`at_work_phone`.`entity_id` = `e`.`entity_id`) AND (`at_work_phone`.`attribute_id` = '182')				   
		LEFT JOIN `customer_entity_varchar` AS `at_business_name`
                   ON (`at_business_name`.`entity_id` = `e`.`entity_id`) AND (`at_business_name`.`attribute_id` = '208')
		LEFT JOIN `customer_entity_varchar` AS `at_work_email`
                   ON (`at_work_email`.`entity_id` = `e`.`entity_id`) AND (`at_work_email`.`attribute_id` = '183')
		LEFT JOIN `customer_entity_varchar` AS `at_work_country`
                   ON (`at_work_country`.`entity_id` = `e`.`entity_id`) AND (`at_work_country`.`attribute_id` = '217')
		LEFT JOIN `customer_entity_varchar` AS `at_work_latitude`
                   ON (`at_work_latitude`.`entity_id` = `e`.`entity_id`) AND (`at_work_latitude`.`attribute_id` = '215')
		LEFT JOIN `customer_entity_varchar` AS `at_work_longitude`
                   ON (`at_work_longitude`.`entity_id` = `e`.`entity_id`) AND (`at_work_longitude`.`attribute_id` = '216')
		LEFT JOIN `customer_entity_text` AS `at_customer_website`
                   ON (`at_customer_website`.`entity_id` = `e`.`entity_id`) AND (`at_customer_website`.`attribute_id` = '184')					   
		LEFT JOIN `customer_entity_int` AS `at_in_referral`
                   ON (`at_in_referral`.`entity_id` = `e`.`entity_id`) AND (`at_in_referral`.`attribute_id` = '125')	
		LEFT JOIN `customer_entity_int` AS `at_referral_approved`
                   ON (`at_referral_approved`.`entity_id` = `e`.`entity_id`) AND (`at_referral_approved`.`attribute_id` = '188')
		LEFT JOIN `customer_entity_int` AS `at_cfmt_honors`
                   ON (`at_cfmt_honors`.`entity_id` = `e`.`entity_id`) AND (`at_cfmt_honors`.`attribute_id` = '189')
		LEFT JOIN `customer_entity_int` AS `at_cfmt_distinction`
                   ON (`at_cfmt_distinction`.`entity_id` = `e`.`entity_id`) AND (`at_cfmt_distinction`.`attribute_id` = '190')
		LEFT JOIN `customer_entity_int` AS `at_FAAOMPT`
                   ON (`at_FAAOMPT`.`entity_id` = `e`.`entity_id`) AND (`at_FAAOMPT`.`attribute_id` = '191')
		LEFT JOIN `customer_entity_int` AS `at_current_fellow`
                   ON (`at_current_fellow`.`entity_id` = `e`.`entity_id`) AND (`at_current_fellow`.`attribute_id` = '192')
		LEFT JOIN `customer_entity_int` AS `at_cfmt`
                   ON (`at_cfmt`.`entity_id` = `e`.`entity_id`) AND (`at_cfmt`.`attribute_id` = '211')				  
        LEFT JOIN `customer_entity_int` AS `at_instructor_status`
                   ON (`at_instructor_status`.`entity_id` = `e`.`entity_id`) AND
                      (`at_instructor_status`.`attribute_id` = '243') 
				   
WHERE (`at_in_referral`.`value` = 1)
  AND (`at_referral_approved`.`value` = 1)
ORDER BY `work_country` DESC, `work_state`, `lastname` ;
";

	return $remote_db->get_results( $sql, ARRAY_A );
}

/**
 * Build address
 *
 * @param $data
 *
 * @return string[]
 */
function build_address( $data ): array {
	$street = $data['work_street'];
	$suite  = $data['work_street2'];
	$city   = $data['work_city'];
	$state  = $data['work_state'];
	$zip    = $data['work_zip'];

	$address_single = ( ! empty( $street ) ) ? "{$street}, " : "";
	// $address_single .= ( ! empty( $suite ) ) ? "{$suite}, " : "";
	$address_single .= ( ! empty( $city ) ) ? "{$city}" : "";
	$address_single .= ( ! empty( $state ) ) ? ", {$state}" : "";
	$address_single .= ( ! empty( $zip ) ) ? " $zip" : "";

	$address_build = ( ! empty( $street ) ) ? "{$street} <br>" : "";
	$address_build .= ( ! empty( $suite ) ) ? "{$suite} <br>" : "";
	$address_build .= ( ! empty( $city ) ) ? "{$city}, " : "";
	$address_build .= ( ! empty( $state ) ) ? "{$state} " : "";
	$address_build .= ( ! empty( $zip ) ) ? $zip : "";

	return array(
		'address'   => $address_single,
		'formatted' => $address_build
	);
}

/**
 * Get Instructor Certifications
 *
 * @param $clinics
 *
 * @return array
 */
function get_clinic_certifications( $clinics ): array {
	$credentials_array = [];

	foreach ( $clinics as $clinic ) {
		foreach ( explode( ',', $clinic['credentials'] ) as $creds ) {
			$creds = preg_replace( '/^ /', '', $creds, 1 );
			if ( $creds ) {
				array_push( $credentials_array, $creds );
			}
		}
	}

	return $credentials_array = array_unique( $credentials_array );
}

/**
 * Get Instructor Names
 *
 * @param $clinics
 *
 * @return array
 */
function get_clinic_names( $clinics ): array {
	$names_array = [];

	foreach ( $clinics as $clinic ) {
		array_push( $names_array, $clinic['name'] );
	}

	sort( $names_array );

	return array_unique( $names_array );
}

/**
 * Get Faculty Names
 *
 * @param $faculty
 *
 * @return array
 */
function get_primary_faculty_names( $faculty ): array {
	$names_array = [];

	foreach ( $faculty as $fac ) {

		if ( $fac['instructor_status'] == 1 ) {
			$names_array[ $fac['firstname'] . ' ' . $fac['lastname'] ] = $fac['name'];
		}

	}

	ksort( $names_array );

	return array_unique( $names_array );
}

/**
 * Get Instructor Image
 *
 * @param string|null $image
 *
 * @return string
 */
function get_instructor_image( string|null $image = "" ): string {
	global $stage_url;

	$stored  = get_field( 'default_instructor_image', 'options' );
	$default = ( ! empty( $stored ) ) ? $stored : get_template_directory_uri() . "/assets/images/ipa-placeholder.jpg";

	if ( ! empty( $image ) ) :
		// TODO: Add validation to check if the image exists
		$image_url = "$stage_url/media/ipa/profile/general/$image";
	else :
		$image_url = $default;
	endif;

	return $image_url;
}

/**
 * Create the course link to Magento
 *
 * @param $url
 * @param $visibility
 * @param null $classes
 */
function get_course_link( $url, $visibility, $classes = null ) {
	if ( $visibility !== "1" ) :
		?>
        <a href="<?= stage_url( $url ); ?>" class="enroll-button <?= $classes; ?>">
			<?= __( 'Enroll / More Info', 'ipa' ); ?>
        </a>
	<?php
	endif;
}
