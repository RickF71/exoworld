<?php 
// user submitted a rename on a location

$loc_code = $_POST['loc_code'];
$loc_id = name_to_nbr($loc_code);
$new_name = $_POST['new_name'];

$update_sql = "UPDATE locations SET name='$new_name', player_id=".$player->id." WHERE id=$loc_id";
mysql_query($update_sql);

$_SESSION['message'] = "$loc_code has been renamed to $new_name!";

header('Location: /?p=world/location&location='.$loc_code.'');

?>