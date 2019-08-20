<?php 

$player_name = $_REQUEST['player_name'];
$uid = $user['id'];

// submit action for new player
$insert_sql = "INSERT INTO players (name, user_id) VALUES ('$player_name', $uid)";
mysql_query($insert_sql);

header('Location: /?p=player/list');

?>