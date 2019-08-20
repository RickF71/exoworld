<?php 

if ($_REQUEST['location'] != '') {
	$room_nbr = name_to_nbr($_REQUEST['location']);
} else {
	// default home room
	$room_name = 'Amusuvamos';
	$room_nbr = name_to_nbr($room_name);
}
//echo $room_nbr;
$curloc = new Location($room_nbr,true);

$exits = get_num_exits($room_nbr);

$page_title = $curloc->name.' - Exo World';

?>