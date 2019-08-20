<?php
session_start();

// database connectivity
include('db.inc.php');

// various variables
include('config.inc.php');

// authentication routines
include('auth.inc.php');

// misc functions and classes
include('functions.inc.php');
include('locations.class.php');
include('player.class.php');

if (isset($_SESSION['warning'])) {
	$warning = $_SESSION['warning'];
	unset($_SESSION['warning']);
} 

if (isset($_SESSION['message'])) {
	$message = $_SESSION['message'];
	unset($_SESSION['message']);
} 
?>