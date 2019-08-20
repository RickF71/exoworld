<?php

	$username = $_POST['username'];
	$password = md5($_POST['password']);
	
	$verify_sql = "SELECT * FROM users WHERE name='$username' and password='$password'";
	$verify = mysql_query($verify_sql);
	
	if (mysql_num_rows($verify) > 0) {
		// yay!  found it!
		$ver = mysql_fetch_array($verify);
		$_SESSION['exwid']=$ver['id'];
		$_SESSION['exwusername']=$ver['name'];
		$_SESSION['exwpassword']=$ver['password'];
		header('Location: /');
	} else {
		unset($_SESSION['exwid']);
		unset($_SESSION['exwusername']);
		unset($_SESSION['exwpassword']);
		header('Location: /?p=login');	
	}
?>