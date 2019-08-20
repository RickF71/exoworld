<?php
// It all starts here!

include('base/preheader.php');

// non-authorized pages are special ... they don't use the templates and are confined to the 'user' area
if ($auth_level == 0) {
	// specific page request, only access user area since not authenticated
	if ($_REQUEST['p'] != '') {
		// if the file doesn't exist, go to default page
		if (file_exists('core/user/'.$_REQUEST['p'].'.php')) {
			include('core/user/'.$_REQUEST['p'].'.php');
		} else {
			header('Location: /');
		}
		exit();
	}
	// not logged in
	include('core/user/start.php');
	exit();
}

// do the requested action file, could be AJAX files, or could be redirects
if ($_REQUEST['a'] != '') { 
	// if file exists do action, else do nothing
	if (file_exists('core/'.$_REQUEST['a'].'.a.php')) {
		include('core/'.$_REQUEST['a'].'.a.php');
		exit();
	} else {
		$_SESSION['warning'] = "Action <strong>".$_REQUEST['p']."</strong> requested, not found.";
		header('Location: /');
	}
	exit();
}

// Dipsplay specific file
if ($_REQUEST['p'] != '') {
	$ptemp = $_REQUEST['p'];
	// if file doesn't exist, send back to home
	// check if no template (notem) is specified ... display header if not
	if (!file_exists('core/'.$ptemp.'.php')) {
		$warning = "Page <strong>".$ptemp."</strong> requested, not found.";
		$ptemp = 'template/notfound';
	}
	// check for action file ... to perform code before we display the header
	if (file_exists('core/'.$ptemp.'.a.php')) {
		include('core/'.$ptemp.'.a.php');	
	}
	if (!isset($_REQUEST['notem'])) { 
		include('core/template/header.php');
	}
	include('core/'.$ptemp.'.php');
	if (!isset($_REQUEST['notem'])) { 
		include('core/template/footer.php');
	} 
	exit();
}



// default action, no action specified

// check for action file ... to perform code before we display the header
// get_default_page is located in base/functions.inc.php
$ptemp = get_default_page();
if (file_exists('core/'.$ptemp.'.a.php')) {
	include('core/'.$ptemp.'.a.php');	
}

include('core/template/header.php');

// check current status of this user, and user preferences for a "home" page
// located at base/functions.inc.php
include('core/'.$ptemp.'.php');

include('core/template/footer.php');


?>

