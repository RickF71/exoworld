<?php 

if ($user['default_player_id'] != '') {
	$player = new Player($user['default_player_id']);
}

class Player {
	public $id;
	public $name;
	public $access;
	public $icon;
	public $hp;
	public $hpnow;
	public $ep;
	public $epnow;
	public $player_type;
	
	function Player($player_id) {
		$player_sql = "SELECT * FROM players WHERE id=$player_id";
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

}

function show_player($player_id) {
	$player_sql = "SELECT * FROM players WHERE id=$player_id";
	$player = mysql_query($player_sql);
	$player = mysql_fetch_array($player);
	
	return $player['name'];
}	


?>