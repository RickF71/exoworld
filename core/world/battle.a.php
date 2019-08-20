<?php 

if ($_REQUEST['location'] != '') {
	$room_name = $_REQUEST['location'];
} else {
	// default home room
	$room_name = 'Amusuvamos';
}
$room_nbr = name_to_nbr($room_name);


//echo $room_nbr;
$curloc = new Location($room_nbr,true);


$page_title = 'BATTLE - '.$curloc->name.' - Exo World';

$header_includes='    
<script type="text/javascript">
var battleRoom = "'.$room_name.'";
</script>
<script type="text/javascript" src="inc/js/battle.js"></script>
<link rel="stylesheet" type="text/css" href="inc/css/battle.css">
';
?>