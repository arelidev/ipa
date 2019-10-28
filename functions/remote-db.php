<?php
global $remote_db;
$remote_db = new wpdb( 'ipastagi_areli', 'r)JZisIK$gl,', 'ipastagi_191010', 'staging.instituteofphysicalart.com' );

$users = $remote_db->get_results( "SELECT * FROM admin_user", ARRAY_A );
foreach ( $users as $user ) :
	echo $user['firstname'] . "<br>";
endforeach;