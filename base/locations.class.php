<?php
// list of all used sequence numbers here
// 10 - Number of exits at location
// 11 to 19 next location id's
// 20 - Location's element
// 25 - number guards
// 30 to 39 - guard types

class Location {
	public $id;
	public $parent_id;
	public $name;
	public $nameable=false;
	public $num_exits;
	public $elem_nbr;
	public $elem_intensity;
	public $element;
	public $num_guards=1;
	public $exits=array();
	public $elemvals=array();
	private $db=array();
	
    function location($location_id, $set_foot = false)
    {
		// grab the database record
		$loc_sql = "SELECT * FROM locations WHERE id=$location_id";
		$loc = mysql_query($loc_sql);
		$loc = mysql_fetch_array($loc);
		
		$this->id = $location_id;
		$this->parent_id = $loc['parent_id'];
		
		$this->db = $loc;
		
		$this->name = get_loc_name($loc);
		if ($loc['name'] == '') {
			$this->nameable = true;
		}
		$this->num_exits = get_num_exits($location_id);
		// This location's element is defined by sequence number 20
		$this->elem_nbr = exw_rand($location_id, 20, 1, 4);
		$this->elem_intensity = get_elem_intensity($location_id);
		$this->element = get_element_name($this->elem_nbr);
		$this->num_guards = $loc['guards_active'];
		$this->elemvals['earth']=$loc['earth'];
		$this->elemvals['air']=$loc['air'];
		$this->elemvals['fire']=$loc['fire'];
		$this->elemvals['water']=$loc['water'];

		for ($a=1; $a <= $this->num_exits; $a++) {
			// use the pseudo-random seeds 11 (up) to 17 for locations
			$this->exits[$a]=exw_rand($location_id, 10+$a, 65536, 1048575);
			// create blank entries in database for exit locations
			$find_sql = "SELECT * FROM locations WHERE id=".$this->exits[$a];
			$find = mysql_query($find_sql);
			if (mysql_num_rows($find)==0) {
				// not found, create a new record
				$insert_sql = "INSERT INTO locations (id, parent_id) VALUES (".$this->exits[$a].", $location_id)";
				mysql_query($insert_sql);
			}
		}
		
		// if a person has actually set foot into this location, 
		// we need to collect/generate additional information
		if ($set_foot == true) {
			// check to see if element values have been set
			if ($loc['earth'] == 0 || $loc['air'] == 0 || $loc['fire'] == 0 || $loc['water'] == 0) {
				// elements have not been set.  Do it!
				$parent_loc = new Location($this->parent_id);
				$newe = $parent_loc->elemvals['earth'];
				$newa = $parent_loc->elemvals['air'];
				$newf = $parent_loc->elemvals['fire'];
				$neww = $parent_loc->elemvals['water'];
				switch($this->elem_nbr) {
					case 1:
						$newa += $this->elem_intensity;
						break;
					case 2:
						$newe += $this->elem_intensity;
						break;
					case 3:
						$newf += $this->elem_intensity;
						break;
					case 4:
						$neww += $this->elem_intensity;
						break;
				}
				// got the new values, do the sql update
				$elem_upd_sql = "UPDATE locations SET air=$newa, earth=$newe, fire=$newf, water=$neww WHERE id=".$this->id." limit 1";
				mysql_query($elem_upd_sql);
				$this->elemvals['earth']=$newe;
				$this->elemvals['air']=$newa;
				$this->elemvals['fire']=$newf;
				$this->elemvals['water']=$neww;
				
			} else {
				// we already have set up the elemental info
			}
			
			// check to see if guard information has been set up already
			if (($loc['guards_active'] + $loc['guards_engaged'] + $loc['guards_defeated']) == 0) {
				// guards not set up, generate active guards
				$num_guards = exw_rand($loc['id'], 25, 1, 10);
				$guards_upd_sql = "UPDATE locations SET guards_active=$num_guards WHERE id=".$this->id." limit 1";
				mysql_query($guards_upd_sql);
				$this->num_guards = $num_guards;
			}
			
		}
		
    }
}

function get_loc_name($loc) {
	$name = '';
	// will generate a slightly more human readable name than just a big number
	if ($loc['name'] == '') {
		// no name assigned ... give it one!
		$name = nbr_to_name($loc['id']);
	} else {
		$name = $loc['name'];		
	}	
	return $name;
}

function get_element_name ($elem_nbr) {
	switch($elem_nbr) {
		case 1:
			$name = 'Air';
			break;
		case 2:
			$name= 'Earth';
			break;
		case 3:
			$name = 'Fire';
			break;
		case 4:
			$name = 'Water';
			break;
	}
	return $name;
}

function get_elem_intensity($loc_id) {
	$rand = exw_rand($loc_id, 21, 1, 100);
	if ($rand <= 35) {
		$intense = 1;
	} elseif ($rand >= 36 && $rand <=55) {
		$intense = 2;
	} elseif ($rand >= 56 && $rand <=70) {
		$intense = 3;
	} elseif ($rand >= 71 && $rand <=75) {
		$intense = 4;
	} elseif ($rand >= 76 && $rand <=80) {
		$intense = 5;
	} elseif ($rand >= 81 && $rand <=85) {
		$intense = 6;
	} elseif ($rand >= 86 && $rand <=90) {
		$intense = 7;
	} elseif ($rand >= 91 && $rand <=95) {
		$intense = 8;
	} elseif ($rand >= 96 && $rand <=98) {
		$intense = 9;
	} elseif ($rand >= 99 && $rand <=100) {
		$intense = 10;
	}
	return $intense;
}

function nbr_to_name($nbr) {
	$newstr = '';
	// converts a number into a string
	$loc_hex = dechex($nbr);
	$size = strlen($loc_hex);
	for ($a=0; $a <$size; $a++) {
		switch(substr($loc_hex, $a, 1)) {
			case '0':
				$code = 'an';
				break;
			case '1':
				$code = 'ne';
				break;
			case '2':
				$code = 'ax';
				break;
			case '3':
				$code = 'on';
				break;
			case '4':
				$code = 'uv';
				break;
			case '5':
				$code = 'am';
				break;
			case '6':
				$code = 'es';
				break;
			case '7':
				$code = 'os';
				break;
			case '8':
				$code = 'is';
				break;
			case '9':
				$code = 'us';
				break;
			case 'a':
				$code = 'ad';
				break;
			case 'b':
				$code = 'ek';
				break;
			case 'c':
				$code = 'id';
				break;
			case 'd':
				$code = 'ok';
				break;
			case 'e':
				$code = 'ud';
				break;
			case 'f':
				$code = 'oz';
				break;
		}
		$newstr = $code.$newstr;
	}
	return ucfirst($newstr);
}

function name_to_nbr($name) {
	$newstr = '';
	$name = strtolower($name);

	$size = strlen($name);
	for ($a=0; $a <$size; $a=$a+2) {
		switch(substr($name, $a, 2)) {
			case 'an':
				$code = '0';
				break;
			case 'ne':
				$code = '1';
				break;
			case 'ax':
				$code = '2';
				break;
			case 'on':
				$code = '3';
				break;
			case 'uv':
				$code = '4';
				break;
			case 'am':
				$code = '5';
				break;
			case 'es':
				$code = '6';
				break;
			case 'os':
				$code = '7';
				break;
			case 'is':
				$code = '8';
				break;
			case 'us':
				$code = '9';
				break;
			case 'ad':
				$code = 'a';
				break;
			case 'ek':
				$code = 'b';
				break;
			case 'id':
				$code = 'c';
				break;
			case 'ok':
				$code = 'd';
				break;
			case 'ud':
				$code = 'e';
				break;
			case 'oz':
				$code = 'f';
				break;
		}
		$newstr = $code.$newstr;
	}
	//echo '*'.$newstr;
	$newnbr = hexdec($newstr);
	return ucfirst($newnbr);
}

function get_num_exits($room_nbr) {
	$num_exits_arr = array(0,0,
							1,1,1,1,1,1,1,
							2,2,2,2,2,2,
							3,3,4,4,5,6);
	$n = exw_rand($room_nbr, 10, 1, 20);
	
	return $num_exits_arr[$n];
	
}
?>