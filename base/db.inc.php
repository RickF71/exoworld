<?php
	session_start();
	// set up the database, yay!
	
	$link = mysql_connect('localhost', 'rickflei_exw', 'mM76GAFAM99Q') or die(mysql_error());
		
	mysql_select_db('rickflei_exw', $link) or die('Could not select database');	

?>