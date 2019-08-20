<?php


class Battle {
	public $id;
	public $server_time;
	public $player_id;
	public $location_id;
	public $battle_cycle_time;
	public $actions=array();
	public $battlers=array();
	public $messages=array();
	public $clientrequest=array();
	public $clinfo=array();

	// bid = Battle ID
	function Battle($pid, $location_id, $bid='') {
		global $battle_cycle_time;	
		$this->battle_cycle_time = $battle_cycle_time;
		$this->player_id = $pid;
		$this->location_id = $location_id;
		$this->server_time = time() + microtime();
		if ($bid =='') {
			// create new battle
			// multiply time by 1000 within MySQL cause it supports 64 bit with bigint, php does 32 bit calcs normally .... not good enough for unix timestamps
			$battle_db_sql = "INSERT INTO battles (location_id, uts_battle_start) VALUES ($location_id, $this->server_time * 1000) ";
			$battle_db = mysql_query($battle_db_sql);
			$bid = mysql_insert_id();
			$this->id = $bid;
			// add this player to the battle
			$this->addBattler($pid);
			// Add a generic "guard" to battle position 5 (5-8 are opponents)
			// Lame, this needs to be much more robust
			$this->addBattler(9,5);		
			$this->addBattler(9,6);		
			$this->addBattler(9,7);		
			$this->addBattler(9,8);		
			$this->actions = '';
			$this->message[] = "Battle Restarted";
		} else {
			// get existing battle record
			$battle_sql = "SELECT * FROM battles WHERE id=$bid";
			$battle = mysql_query($battle_sql);
			$battle = mysql_fetch_array($battle);
			$this->id = $battle['id'];
			$bq = unserialize($battle['queue']);
			$this->actions = $bq;
			$bm = unserialize($battle['messages']);
			$this->messages = $bm;
		}
		$this->clinfo['test']='test';
		$this->getBattlers();
		if (isset($this->battlers)) { 
			foreach ($this->battlers as $b) {
				// for each battler, add info to the client array
				$ba=array();
				$ba['name'] = $b->name;
				$ba['icon'] = $b->icon;
				$ba['position'] = $b->position;
				$ba['hp'] = $b->hp;
				$ba['hpnow'] = $b->hpnow;
				$ba['ep'] = $b->ep;
				$ba['epnow'] = $b->epnow;
				$this->clinfo['battlers'][$b->battler_id] = $ba;
			}
		}
	}
	
	//  Adds a battle action
	function addAction($action_id, $start_time, $action_desc) {
		// get action recorde
		$act_sql = "SELECT * FROM actions WHERE id=$action_id";
		$act = mysql_query($act_sql);
		$act = mysql_fetch_array($act);
		$aa = $act;
		$aa['action_id'] = $action_id;
		$aa['start_time'] = $start_time;
		$aa['end_time'] = $start_time+$act['duration'];
		$aa['action_desc'] = $action_desc;
		$aa['player_id'] = $this->player_id;
		$aa['completed'] = false;
		$this->actions[$start_time]=$aa;
		asort($this->actions);
		while (count($this->actions) > 10) {
			// hard coded 10 limit to length of this array
			array_shift($this->actions);
		}
		$q2 = serialize($this->actions);
		$battle_upd_sql = "UPDATE battles SET queue='$q2' WHERE id=$this->id LIMIT 1";
		$battle_upd = mysql_query($battle_upd_sql);
	}
	
	function setActionCompleted	($action_id) {
		$this->actions[$action_id]['completed'] = true;
		$q2 = serialize($this->actions);
		$battle_upd_sql = "UPDATE battles SET queue='$q2' WHERE id=$this->id LIMIT 1";
		$battle_upd = mysql_query($battle_upd_sql);		
	}
	
	function removeActionsByType($action_type_id, $filter='all') {
		if (is_array($this->actions)) {
			foreach($this->actions as $aid => $ad) {
				if ($ad['action_id'] == $action_type_id && $ad['player_id'] == $this->player_id) {
					unset($this->actions[$aid]);
					$this->addMessage('Attack cancelled');
				}
			}
			$q2 = serialize($this->actions);
			$battle_upd_sql = "UPDATE battles SET queue='$q2' WHERE id=$this->id LIMIT 1";
			$battle_upd = mysql_query($battle_upd_sql);			
		}
	}
	
	// messages are simply information to be displayed to the player
	function addMessage($message, $display_time=0, $type="generic") {
		if ($display_time == 0 ) {
			$display_time = intval($this->server_time)*1000;
		}
		$display_time = intval($display_time);
		$abm['message'] = $message;
		$abm['time'] = $display_time;
		$abm['type'] = $type;
		while (is_array($this->messages[$display_time])) {
			$display_time++;
		}
		$this->messages[$display_time]=$abm;
		ksort($this->messages);
		while (count($this->messages) > 50) {
			// hard coded 50 limit to length of this array
			array_shift($this->messages);
		}
		$q2 = serialize($this->messages);
		$battle_upd_sql = "UPDATE battles SET messages='$q2' WHERE id=$this->id LIMIT 1";
		$battle_upd = mysql_query($battle_upd_sql);		
		
	}
	
	function processActions() {
		if (is_array($this->actions)) {
			foreach($this->actions as $aid => $ad) {
				if ($ad['end_time'] < intval($this->server_time*1000) + $this->battle_cycle_time && $ad['completed'] == false) {
					if ($ad['action_id']==1) {
						$this->addMessage("Attack performed", $ad['end_time']);
						$this->addAction(1, $ad['end_time']+500, "Attacking...");				
					} else {
						$this->addMessage("Action performed", $ad['end_time']);
					}
					$this->setActionCompleted($aid);
				} elseif ($ad['end_time'] < intval($this->server_time*1000) - 15000) {
					// 15 seconds after the end of the action, remove it
					unset($this->actions[$aid]);
					$q2 = serialize($this->actions);
					$battle_upd_sql = "UPDATE battles SET queue='$q2' WHERE id=$this->id LIMIT 1";
					$battle_upd = mysql_query($battle_upd_sql);						
				}
			}
		}
	}

	// player actions are the actions taken by the client in their web browser
	// they are converted into "actions" here on the server
	function addPlayerActions($placts) {
		if (is_array($placts)) {
			foreach ($placts as $id => $plact) {

				switch($plact) {
					case "auto_attack_off":
						$this->addMessage("Turned off auto attack", $id);
						$this->removeActionsByType(1);				
						break;
					case "auto_attack_on":
						$this->addMessage("Turned on auto attack", $id);
						//$this->addMessage("Cycle time: ".$this->battle_cycle_time, $id);
						$this->addAction(1, $id, "Attacking...");
						break;
				}			
				$this->addClientRequest('remove_plact', $id, $plact);
			}
		}
	}
	
	function addClientRequest($name, $id, $plact) {
		$this->clientrequest[$id]['name']= $name;
		$this->clientrequest[$id]['info']= $plact;
	}

	function addBattler($pid, $loc=1) {
		// eventually this will have to check which of the 4 player slots to put the player into
		$bplayer = new Player($pid);
		$battler_db_sql = "INSERT INTO battlers (battle_id, player_id, player_type, position, t_last_action) VALUES ($this->id, $pid, '$bplayer->player_type', $loc, 0) ";
		$battler_db = mysql_query($battler_db_sql);
		//$this->player[1] = new Player($pid);
	}
	
	function getBattlers() {
		$battler_get_sql = "SELECT * FROM battlers WHERE battle_id=$this->id";
		$battlers = mysql_query($battler_get_sql);
		while ($b = mysql_fetch_array($battlers)) {
			$this->battlers[$b['position']] = new Battler($b['player_id'],$b['position']);
		}
	}
}

class Battler extends Player {
	public $battler_id;
	public $battle_id;
	private $opponent_position;
	public $position;
	
	function Battler($pid, $pos) {
		$btlr_sql = "SELECT * FROM battlers WHERE player_id=$pid and position=$pos";
		$btlr = mysql_query($btlr_sql);
		$btlr = mysql_fetch_array($btlr);
		$this->battler_id = $btlr['id'];
		$this->opponent_position = $btlr['opp_pos'];
		$this->battle_id = $btlr['battle_id'];
		$this->position = $btlr['position'];
		$player_sql = "SELECT * FROM players WHERE id=$pid";
		$player = mysql_query($player_sql);
		$player = mysql_fetch_array($player);
		$this->id = $player_id;
		$this->name = $player['name'];
		$this->access = $player['access'];
		$this->icon = $player['icon'];
		$this->hp = $player['hp'];
		$this->hpnow = $player['hpnow'];
		$this->ep = $player['ep'];
		$this->epnow = $player['epnow'];
		if ($player['access']=='admin' || $player['access']=='user') {
			$this->player_type = 'pc';
		} else {
			$this->player_type = 'npc';
		}		
	}
	
	function getOpponentPosition() {
		if ($this->opponent_position == NULL) {
			// find the first available battler
			return($this->setOpponentPosition(5));
		} else {
			return $battler_focus_id;
		}
	}

	function setOpponentPosition($pos_nbr) {
		$upd_btlr_sql = "UPDATE battlers SET opp_pos=$pos_nbr WHERE id=$this->battler_id";
		$upd_btlr = mysql_query($upd_btlr_sql);
		$this->opponent_position=$pos_nbr;
		return $pos_nbr;
	}
	
}



function get_battle_from_battler_id($brid) {
	$battler_find_sql = "SELECT * FROM battlers WHERE player_id=$brid";
	$battler_find = mysql_query($battler_find_sql);
	if (mysql_num_rows($battler_find) > 0) {
		$battle = mysql_fetch_array($battler_find);
		return $battle['battle_id'];	
	} else {
		return '';
	}
}


?>
