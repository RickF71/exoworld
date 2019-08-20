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
    <link rel="stylesheet" type="text/css" href="/inc/css/main.css">
    <link rel="stylesheet" type="text/css" href="/inc/css/structural.css">
    <link rel="stylesheet" type="text/css" href="/inc/css/text.css">
</head>

<body>
<div id="wrapper" style="border:1px solid red; ">
	<div id="header">
        <a href="/" onfocus="this.blur();">
        <img src="images/exoworld_web_small.png" border="0">
        </a>
        Welcome to Exo World.  
	</div>
    <div id="content">
		<form action="" method="post" name="new_account">
			<input type="hidden" name="action" value="new_user">
			Name: <input type="text" name="username" id="username"><br />
			Email: <input type="text" name="email" id="email"><br />
			Password: <input type="password" name="pass1" id="pass1"><br />
			Reenter Pass: <input type="password" name="pass2" id="pass2"><br />
			<input type="submit" name="submit" value="submit">
		
		</form>
    	<p>Exo World sign-up is not currently active.  </p>
    </div>
</div>
</body>
</html>
