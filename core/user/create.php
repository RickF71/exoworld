<?php
	// attempt to create a new user...
	$username = $_REQUEST['username'];
	$pass1 = $_REQUEST['pass1'];
	$pass2 = $_REQUEST['pass2'];
	$email = $_REQUEST['email'];
	
	$create_user = true;

	if ($pass1 != $pass2) {
		// bad password
		$_SESSION['bad_pass'] = true;
		$create_user = false;
	}

	if (strlen($pass1) < 5) {
		$_SESSION['short_pass'] = true;
		$create_user = false;
	}

	if (strlen($username) < 4) {
		$_SESSION['short_username'] = true;
		$create_user = false;
	}

	// email verify (eventually)
	if ($email) { 
	
	}
	
	if ($create_user) { 
		$pw_md5 = md5($pass1);
		$create_user_sql = "INSERT INTO users (name, email, password) VALUES ('$username', '$email', '$pw_md5')";
		//echo $create_user_sql.'<br />';
		$t = mysql_query($create_user_sql);
		$uid = mysql_insert_id();
		$_SESSION['exwid']=$uid;
		$_SESSION['exwusername']=$username;
		$_SESSION['exwpassword']=$pw_md5;
		header('Location: /');
	} else { 
		header('Location: /?p=user/new');
	}
?>