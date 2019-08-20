<?php

$player_id = $_REQUEST['player_id'];
$profile = new Player($player_id);

$items_sql = "SELECT items.* FROM player_items LEFT JOIN items ON player_items.item_id=items.id WHERE player_id=$player_id";
$items = mysql_query($items_sql);

?>