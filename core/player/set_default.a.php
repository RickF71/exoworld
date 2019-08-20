<?php

// set a default player
$def_player_id = $_REQUEST['player_id'];

$update_sql = "UPDATE users SET default_player_id=$def_player_id WHERE id=".$user['id'];
mysql_query($update_sql);

$_SESSION['message'] = "New active player set: ".show_player($def_player_id);

if (isset($_REQUEST['enter_world'])) {
	header('Location: /?p=world/location');
} else {
	header('Location: /?p=player/list');
}
?>