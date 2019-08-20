<?php
/*
My rambling notes:

	Client side ultimately cannot be trusted for anything.   Any trust we give it will be betrayed
	by those wanting to usurp the system.  This is a truism.  
	
	All authority for anything comes from the server, and the server has to deal *harshly* with any 
	deviation.  The client merely reports what happened.  It has no say in the reality of things.  
	Server = God.  Client = Slave.   Sometimes though we have to give some leeway when actions take 
	less than the latency time.  (currently 5 seconds)
	
	Just keep in mind, we want the client to provide quick feedback.  That means, when someone 
	decides to help cure someone, or perhaps perform an enfeebling action, their feedback 
	should be instant, but not "official" until the server says it's been made so.
	
	We have to deal with latency here, and there is no way around it.  Currently I think we can deal
	with 5 second server sync period and 1 second client latency.   No actual actions should 
	take less than 1.5 seconds, so deal with that gap.  Just remember, SERVER changes things, 
	client REQUESTS things.
	
	I fully expect the 5 seconds to move to less over time.   Eventually it will be near 
	zero, but for now keep going with an assumption of a client sync time different than 
	what is happening on the server.
	
	That said, go forth and do battle against Exo World!
	
*/
include('base/battle.class.php');

// flag start of battle?   default is no
$battle_start = false;

// get location as reported by client, convert to location_id, store for later 
// we are trusting client reporting of its location for now, which is wrong.
// This can easily be hacked for various malfeasance, should be fixed
$location = $_REQUEST['location'];
$location_id = name_to_nbr($location);

// actual server's clock time (all timings go off this)
$server_time = time() + microtime();

// $client_js_time is the time on the client's computer.  Only use this to help keep the client synchronized
$client_js_time = $_REQUEST['time'];

// Also, the time diff between server and the client's javascript reporting
$diff = $client_js_time - $server_time;

// player id we are playing with, determined from the user table, mostly trustworthy
$player_id = $user['default_player_id'];

// Is the player's JS reporting that player is on auto_attack?   Player wouldn't get this far without authenticatation
$auto_attack = $_REQUEST['auto_attack'];

// this caps out at 10000
$battle_cycle_time = $_REQUEST['battleCycleTime'];
if ($battle_cycle_time > 10000) {
	$battle_cycle_time = 10000;
}

if ($_SESSION['current_battle'] != '') {
	$cb = $_SESSION['current_battle'];
	
	// check time on battle, over 30 minutes, delete it
	$btd_sql = "SELECT * FROM battles WHERE id=$cb";
	
	$btd = mysql_query($btd_sql);
	
	$bt = mysql_fetch_array($btd);
	$battle_time = time() - intval($bt['uts_battle_start']/1000);  
	
	
	
	//echo time().'|'.$bt['uts_battle_start'].'|'.$battle_time;
	
	// battle expired?
	if ($battle_time > 30 * 60) { // 30 minutes 
		$bex = true;
	} else {
		$bex = false;
	}
}
	
// did a reset_battle action come in?  if so reset it!
// this is a hardcore reaction and deletes all battle information we have so far
// eventually should be retired to less aggressive solutions
if ($bex) { 
	reset_battle($cb);
	$message = "Battle expired ... <br /> Please wait ...";
} elseif ($_REQUEST['action'] == 'reset_battle' && isset($_SESSION['current_battle'])) {
	// reset the battle
	reset_battle($cb);	
	$message = "Resetting the battle ... <br /> Please wait ...";

} else {
	// "real" normal battle computation    
	// We don't care about the relative time difference between client and server, what 
	// we care about is the *time difference delta changing over time* between client and server.
	// This time diff really shouldn't change much unless the client changes his timestamp
	// (aka computer clock) drasticaly, which normally would not happen except for hacking attempts
	// or if the user changes his clock during battle.
	if (abs($_SESSION['time_diff'] - $diff) > .5) {
		$message = 'Re-synchronizing with the battle server... <img src=/images/loader2.gif />';
		$message .= $_SESSION['time_diff'] - $diff;
	} else { 
		$message = 'Synchronized. ('.$_SESSION['current_battle'].')<br />Location: <strong>'.$location.'</strong><br />Ready to start battle!  [ <a href=\"\" onclick=\"resetBattle2();return false;\">reset</a> ]';
		// check if battle in progress, if not, check for battle and get it going
		if ($_SESSION['current_battle'] == '') {
			// check for reloaded battle page ...
			$battle_id = get_battle_from_battler_id($player->id);
			if ($battle_id =='') {
				// player not involved in any battles, create a new battle record
				$battle_start = true;
				$battle = new Battle($player->id, $location_id, NULL);
				$_SESSION['current_battle'] = $battle->id;
				$message = "New battle area set up.";
			} else {
				// this player already involved in a battle, get that battle record
				// I'm sure this is overly simplified, just make it work for now
				$battle_start = true;
				$battle = new Battle($player->id, $location_id, $battle_id);
				if ($battle->id =='') {
					$_SESSION['current_battle']=-1; 
					$message = 'Bad sync. [ <a href=\"\" onclick=\"resetBattle2();return false;\">reset</a> ]';
				} else {
					$_SESSION['current_battle'] = $battle_id;
					$message = "Reconnecting with existing battle area.";
				}
			}
		} else {
			// just load up existing battle
			$battle = new Battle($player->id, $location_id, $_SESSION['current_battle']);
		}
		// Send the player changes / requests / etc to the battle object
		$t1 = stripslashes($_REQUEST['plact']);
		$t2 = unserialize($t1);	
		unset($t2[0]);
		$battle->addPlayerActions($t2);
		$battle->processActions();
		$act_bef = count($battle->actions);
		// At this point, battle should be started and running
		/* 
		if ($auto_attack=='true') {
			$battle->addAction(1, $server_time, 'Server Ping: ATT ON');
		} else { 
			$battle->addAction(1, $server_time, 'Server Ping: ATT OFF');
		}
		*/
		$act_aft = count($battle->actions);

	} 
	
}

//print_r($battle);
//echo '<h1>'.$_SESSION['current_battle'].'</h1>';

$_SESSION['time_diff'] = $diff;

// end code, begin JSON output ... this will go through many changes for sure


$jd['sync']['server_time'] = $server_time;
$jd['sync']['js_time'] = $client_js_time;
$jd['sync']['time_diff'] = $diff;
$jd['sync']['battle_start'] = $battle->battle_start;

$jd['sync_message'] = $message;

$jd['player_action'] = $battle->clinfo;

$jd['debug2'] = serialize($battle);

$returnfun = json_encode($jd);
//echo '<pre>';
//print_r ($battle);
//echo '</pre>';

echo $returnfun;

exit();
?>
