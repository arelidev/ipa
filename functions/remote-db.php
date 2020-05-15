<?php
global $remote_db;

define( 'FACULTY_MEMBER_IMAGE_URL', 'http://test.instituteofphysicalart.com/media/ipa/profile/general/' );

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

	$sql = "select `e`.*,
       `r`.`request_path`                                                    AS `request_path`,
       `at_status`.`value`                                                   AS `status`,
       `at_visibility`.`value`                                               AS `visibility`,
       `at_end_date`.`value`                                                 AS `end_date`,
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
         left join `catalog_product_entity_int` as `at_instr2`
                   on (`at_instr2`.`entity_id` = `e`.`id`) AND (`at_instr2`.`attribute_id` = 138)
         left join `customer_entity_varchar` as `at_instr2_lname`
                   on (`at_instr2`.`value` = `at_instr2_lname`.`entity_id` and `at_instr2_lname`.`attribute_id` = 7)
         left join `customer_entity_varchar` as `at_instr2_fname`
                   on (`at_instr2`.`value` = `at_instr2_fname`.`entity_id` and `at_instr2_fname`.`attribute_id` = 5)
         left join `catalog_product_entity_int` as `at_instr3`
                   on (`at_instr3`.`entity_id` = `e`.`id`) AND (`at_instr3`.`attribute_id` = 139)
         left join `customer_entity_varchar` as `at_instr3_lname`
                   on (`at_instr3`.`value` = `at_instr3_lname`.`entity_id` and `at_instr3_lname`.`attribute_id` = 7)
         left join `customer_entity_varchar` as `at_instr3_fname`
                   on (`at_instr3`.`value` = `at_instr3_fname`.`entity_id` and `at_instr3_fname`.`attribute_id` = 5)
         left join `catalog_product_entity_int` as `at_instr4`
                   on (`at_instr4`.`entity_id` = `e`.`id`) AND (`at_instr4`.`attribute_id` = 140)
         left join `customer_entity_varchar` as `at_instr4_lname`
                   on (`at_instr4`.`value` = `at_instr4_lname`.`entity_id` and `at_instr4_lname`.`attribute_id` = 7)
         left join `customer_entity_varchar` as `at_instr4_fname`
                   on (`at_instr4`.`value` = `at_instr4_fname`.`entity_id` and `at_instr4_fname`.`attribute_id` = 5)
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

	if ( ! empty( $limit ) ) :
		$sql .= " LIMIT {$limit}";
	endif;

	$sql .= " ORDER BY `course_type_name`, `end_date`";

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
function get_faculty( $id = null ) {
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
       `at_instructor_status`.`value` AS `instructor_status`
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
WHERE (`e`.`entity_type_id` = '1')
  AND (`e`.`group_id` = '5')
  AND (`at_instructor_status`.`value` IN (1, 2))";

	if ( ! empty( $id ) ) :
		$sql .= " AND (`e`.`entity_id` = '{$id}')";
	endif;

	$sql .= "ORDER BY `instructor_status`, `lastname`;";

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
function get_instructor_courses( $id ) {
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
       `at_visibility`.`value`  AS `visibility`
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
 * @param $id
 */
function get_instructor_course_table( $id ) {
	$courses = get_instructor_courses( $id );
	?>
	<div class="courses-table-widget">
		<div class="course-wrapper">
			<table class="course-table hover">
				<thead>
				<tr>
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
							<?= $course['city']; ?>, <?= $course['state']; ?>
						</td>
						<td class="course-table-date" data-order="<?= date( 'u', strtotime( $course['date'] ) ); ?>">
							<?= date( get_option( 'date_format' ), strtotime( $course['date'] ) ); ?> - <?= date( get_option( 'date_format' ), strtotime( $course['end_date'] ) ); ?>
						</td>
						<td class="course-table-instructor">
							<?php if ( ! empty( $instructor_1 = $course['instructor1'] ) ) : ?>
								<img src="https://api.adorable.io/avatars/100/<?= $instructor_1; ?>.png" class="course-card-trainer" alt="<?= $instructor_1; ?>" data-tooltip tabindex="2" title="<?= $instructor_1; ?>">
							<?php endif; ?>
							<?php if ( ! empty( $instructor_2 = $course['instructor2'] ) ) : ?>
								<img src="https://api.adorable.io/avatars/100/<?= $instructor_2; ?>.png" class="course-card-trainer" alt="<?= $instructor_2; ?>" data-tooltip tabindex="2" title="<?= $instructor_2; ?>">
							<?php endif; ?>
							<?php if ( ! empty( $instructor_3 = $course['instructor3'] ) ) : ?>
								<img src="https://api.adorable.io/avatars/100/<?= $instructor_3; ?>.png" class="course-card-trainer" alt="<?= $instructor_3; ?>" data-tooltip tabindex="2" title="<?= $instructor_3; ?>">
							<?php endif; ?>
							<?php if ( ! empty( $instructor_4 = $course['instructor4'] ) ) : ?>
								<img src="https://api.adorable.io/avatars/100/<?= $instructor_4; ?>.png" class="course-card-trainer" alt="<?= $instructor_4; ?>" data-tooltip tabindex="2" title="<?= $instructor_4; ?>">
							<?php endif; ?>
						</td>
						<td class="course-table-apply">
							<a href="<?= stage_url( $course['request_path'] ); ?>"><?= __( 'Enroll / More Info', 'ipa' ); ?></a>
						</td>
					</tr>
				<?php endforeach; ?>
				</tbody>
			</table>
		</div>
	</div>
	<?php
}
