<?php 
if ($_POST['action'] == 'new_user') { 
	// create new user
	
}



?><!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <title>Exo World</title>
    <link rel="stylesheet" type="text/css" href="inc/css/main.css">
    <link rel="stylesheet" type="text/css" href="inc/css/structural.css">
    <link rel="stylesheet" type="text/css" href="inc/css/text.css">
</head>

<body>
<div id="wrapper" style="  ">
	<div id="header" style="">
        Welcome to Exo World.  
		<br />
	</div>
    <div id="content" style=" ">
<br />
		<div style="color:red; margin-bottom:20px; ">
		<?php 
		if (isset($_SESSION['short_username']) && $_SESSION['short_username']==true) {
			unset($_SESSION['short_username']);
			echo 'Name must be 4 characters or more.  ';
		} 
		if (isset($_SESSION['bad_pass']) && $_SESSION['bad_pass']==true) {
			unset($_SESSION['bad_pass']);
			echo 'Passwords did not match.  ';
		}
		if (isset($_SESSION['short_pass']) && $_SESSION['short_pass']==true) {
			unset($_SESSION['short_pass']);
			echo 'Password is too short.  ';
		}
		?>		
		</div>
		<form action="?p=create" method="post" name="new_account">
			<input type="hidden" name="action" value="new_user">
			Name: <input type="text" name="username" id="username"><br />
			Email: <input type="text" name="email" id="email"><br />
			Password: <input type="password" name="pass1" id="pass1"><br />
			Reenter Pass: <input type="password" name="pass2" id="pass2"><br />
			<input type="submit" name="submit" value="submit">
		
		</form>
    	<p>Exo World sign-up is not currently active.  </p>
		<br />
    </div>
	<div id="footer" style=" ">
		
	</div>
</div>
</body>
</html>
