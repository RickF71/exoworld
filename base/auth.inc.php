<?php

// Core authentication module
if ($_SESSION['exwusername']=='') {
	$auth_level = 0;
} else {
	$auth_level = 1;
	$auth_user_id = $_SESSION['exwid'];
	$auth_user = $_SESSION['exwusername'];
	$auth_pass = $_SESSION['exwpassword'];

	$get_user_sql = "SELECT id, name, password, default_player_id FROM users WHERE users.id=$auth_user_id and users.password='$auth_pass'";
	$get_user = mysql_query($get_user_sql);
	
	//echo $get_user_sql;
	
	if (mysql_num_rows($get_user) < 1) { 
		$auth_level = 0;
		unset($_SESSION['exwid']);
		unset($_SESSION['exwusername']);
		unset($_SESSION['exwpassword']);
	} else {
		$user = mysql_fetch_array($get_user);
	}
}

?>