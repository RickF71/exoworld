<?php
// various global functions


// This function determins what page to display by default for this user
function get_default_page() {
	global $user;
	
	// check to see if the user has any players yet
	if ($user['default_player_id'] != '') {
		$page = 'world/location';
	} else {
		$check_players_sql = "SELECT * FROM players WHERE user_id=".$user['id']."";
		$check_players = mysql_query($check_players_sql);
		if (mysql_num_rows($check_players) < 1) {
			$page = 'player/new';
		} else {
			$page = 'player/list';
		}
	}
	return $page;
}


function reset_battle($cbid) {	
	if ($cbid != '') { 
		$battle_reset_sql = "DELETE FROM battles WHERE id=".$_SESSION['current_battle'];
		mysql_query($battle_reset_sql);
		$battler_reset_sql = "DELETE FROM battlers WHERE battle_id=".$_SESSION['current_battle'];
		mysql_query($battler_reset_sql);
		unset($_SESSION['current_battle']);
	}
}

function exw_rand($seed, $seq_nbr, $min=0, $max=0) {
	$rand = $seed;
	$a = 1259;
	$c = 90121;
	$m = 3447151;
	//echo $seed.'/'.$seq_nbr.'<br />';
	for ($i=0; $i<$seq_nbr; $i++) {
		$rand = ($a * $rand + $c) % $m;
	}
	// normalize between $min and $max
	if ($max != 0) {
		$divisor = $m/($max-$min+1);
		$rand = intval($rand/$divisor) + $min;
	}
	
	return $rand;
}


?>