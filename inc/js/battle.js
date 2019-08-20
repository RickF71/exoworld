// JavaScript Document
var battleCycleTime = 5000;
var syncNbr = 0;
var battleActive = true;
var serverTimeZero = 1235670434000;
var serverTime;
var serverTimeDiff;
var calcServerTime;
var battleRoom;
var resetBattle = false;
var autoAttack = false;
var actions = [];
var messages = [];
var players = [];
var plact = [];

// once the page is loaded, start up the main battle function
$(document).ready(function(){
  // Your code here...
  battleStart();
});


function battleStart() {
	$('#battle_sync').html('Synchronizing with the battle server... <img src="/images/loader2.gif" />');	
	//setTimeout('$("#bp1").hide();$("#bp1").html(\'<img src="/images/player/default1.png">\');$("#bp1").fadeIn(1000);', 2000);
	setTimeout("battleCycle()",2000);
	timeSync();
}

// onscreen display only, this displays the "ExoWorld" time for the user
// and handles dipslay of player actions
function timeSync() {
	var output='';
	
	calcServerTime = (get_millitime() - serverTimeDiff) * 1000;
	if (calcServerTime == NaN) {
		calcServerTime = 'n/a';	
	}
	$("#exo_time").html(convertExoTime(calcServerTime));
	$('#battle_status').html("");	
	if (messages.length != 0) {
		for (var i=0; i<messages.length; i++) {
			if (messages[i][0] < calcServerTime) {
				output = '<div class="battle_status_message">'+convertExoTime(messages[i][0], 'battle')+' '+messages[i][1]+'</div>' + output;
			} 
		}
	}	
	if (actions.length != 0) {
		for (var i=0; i<actions.length; i++) {
			if (actions[i][0] < calcServerTime && actions[i][2] >=calcServerTime) {
				output = '<div class="battle_status_action">'+convertExoTime(actions[i][0], 'battle')+' '+actions[i][1]+'</div>' + output;
			} else {
				//output = '<div class="battle_status_action">'+convertExoTime(actions[i][0], 'battle')+' to ' +convertExoTime(actions[i][2], 'battle')+ actions[i][1]+'</div>' + output;
			}
		}
	}	
	output = '<div class="battle_status_message"><center>'+convertExoTime(calcServerTime)+'</center></div>'+output;
	$('#battle_status').html(output);	
	if (actions.length > 20) {
		actions.shift();	
	}
	setTimeout("timeSync()", 200);
}
function battleCycle() {
	var getData;
	var cur_action;
	syncNbr++;
	if (resetBattle) {
		cur_action='reset_battle';
		resetBattle = false;
	} else {
		cur_action = '';
	}
	if (plact =='') {
		plact[0]='No Changes';	
	}
	$.getJSON('/?a=world/battle_ajax', 
		{
			time: get_millitime(),
			location: battleRoom, 
			action: cur_action,
			auto_attack: autoAttack,
			plact: serialize(plact),
			battleCycleTime: battleCycleTime
		},
		function(data){
			//$("#battle_status").append("test");
			if (data.sync.server_time != undefined) {
				serverTime = data.sync.server_time;
				serverTimeDiff = data.sync.time_diff;
				calcServerTime = serverTime * 1000;
			}
			$.each(data.sync, function(i,item){
				i=i;
				//$("#battle_status").prepend(i+' '+item+'<br />');
				//if ( i == 4 ) return false;
			});
			
			if (data.debug2 != undefined) {
				$("#debug").html(data.debug2);
			}
			
			if (data.sync_message != undefined) {
				$("#battle_sync").html(data.sync_message);
			}
			
			if (data.player_action != undefined) {
				actions = [];
				messages = [];
				if (data.player_action.battlers != undefined) {
					$.each(data.player_action.battlers, function (i, item) {
						//alert(i + '-' + item);
						//if (item.action=='add_player') {
							// add player!
							addPlayer(item.position, item.icon, item.name);
							setPlayerHP(item.position, item.hpnow,item.hp);	
							setPlayerEP(item.position, item.epnow, item.ep);
						//}
						//if (item.action=='battler_select') {
							setSelectedPlayer(item.position);
							//alert(item.position);
						//}
						if (item.action=='message') {
							messages.push([[item.timestamp], [item.text]]);
							//alert('push:'+actions.length);
						}
						if (item.action=='action') {
							actions.push([[item.timestamp], [item.text], [item.endtime]]);
							//alert('push:'+actions.length);
						}
						if (item.action=='remove_plact') {
							delete plact[item.id];
						}
					});
				}
			}
		});
	
	if (battleActive) {
		setTimeout("battleCycle()",battleCycleTime);		
	}
}

function addPlayer(position, icon, name) {
	var newdisp = '<img src="'+icon+'" alt="'+name+'">';
	if (newdisp == $("#bp"+position).html()) {
		// same, do not update
	} else {
		$("#bp"+position).hide();
		$("#bp"+position).html(newdisp);
		$("#bp"+position).fadeIn(1000);
	}
}

function setSelectedPlayer(position) {
	$('#bp'+position).addClass("player_tiny_selected");	
}

function setPlayerHP(position, currenthp, maxhp) {
	var maxwidth = 50;
	var thiswidth;
	var red, green;
	if (currenthp/maxhp > .5) {
		red = parseInt(256 * (1-(currenthp)/maxhp) * 3);
		green = 255;
	} else {
		green = parseInt(256 * (currenthp) / maxhp * 3);		
		red = 255;
	}
	thiswidth = parseInt(maxwidth * currenthp / maxhp);
	var newdisp = '<div style="background-color:rgb('+red+','+green+',0);height:2px; width:' + thiswidth + 'px;margin-top:1px; "></div>';
	$("#hp"+position).html(newdisp);
}

function setPlayerEP(position, currentep, maxep) {
	var maxwidth = 50;
	var thiswidth;
	var red, green, blue;
	if (currentep > maxep) {
		// this is an error, handle it
		currentep = maxep;	
	}
	if (currentep/maxep > .5) {
		red = parseInt(256 * (1-(currentep)/maxep) * 2);
		green = 255;
	} else {
		green = parseInt(256 * (currentep) / maxep * 2);		
		red = 255;
	}
	red=0;
	green=0;
	blue=255;
	thiswidth = parseInt(maxwidth * currentep / maxep);
	var newdisp = '<div style="background-color:rgb('+red+','+green+','+blue+');height:2px; width:' + thiswidth + 'px; margin-top:1px; "></div>';
	$("#ep"+position).html(newdisp);
}

function resetBattle2() {
	$('#battle_sync').html('Resetting...');	
	$("#bp1").html('');
	$("#bp2").html('');
	$("#bp3").html('');
	$("#bp4").html('');
	$("#bp5").html('');
	$("#bp6").html('');
	$("#bp7").html('');
	$("#bp8").html('');
	resetBattle = true;
	delete actions;
	delete players;
	delete plact;
}

// ****************** Battle Functions **********************
function toggleAutoAttack() {
	if (autoAttack) {
		$('#auto_attack').removeClass("battle_on").addClass("battle_off").text("Auto Attack: OFF");
		plact[parseInt(calcServerTime)] = 'auto_attack_off';
		messages.push([[calcServerTime], ['Turned off Auto-Attack']]);
		autoAttack = false;
	} else {
		$('#auto_attack').removeClass("battle_off").addClass("battle_on").text("Auto Attack: ON");
		plact[parseInt(calcServerTime)] = 'auto_attack_on';
		messages.push([[calcServerTime], ['Turned ON Auto-Attack']]);
		autoAttack = true;
	}
	//alert (autoAttack);
	$('#auto_attack').blur();
}


function get_millitime() {
	var now = new Date();
	return now.getTime() / 1000;
//	return microtime();
	
}

function convertExoTime(calcServerTime, format) {
	est = 0;
	calcServerTime = parseInt((calcServerTime - serverTimeZero)) / 1000 * 24;
	// Convert to days / hours / mins / secs
	calcServerTimeSecs = zeroPad(parseInt(calcServerTime % 60), 2);
	calcServerTimeMins = zeroPad(parseInt(((calcServerTime - calcServerTimeSecs)/60) % 60), 2);
	calcServerTimeHours = zeroPad(parseInt(((calcServerTime - calcServerTimeMins*60 - calcServerTimeSecs)/3600) % 24), 2);
	calcServerTimeDays = parseInt(calcServerTime / (24*3600));
	if (isNaN(calcServerTimeDays)) {
		return "ExoWorld Time not yet synchronized.";
	} else {
		if (format == null) {
			//return calcServerTime;
			return "(Day " + calcServerTimeDays + ") "  + calcServerTimeHours + ":" + calcServerTimeMins;
		} else if (format=='battle') {
			return calcServerTimeHours + ":" + calcServerTimeMins + ":" + calcServerTimeSecs;
		} else {
			return calcServerTimeHours + ":" + calcServerTimeMins + "";
		}
	}
}


function zeroPad(num,count) {
	var numZeropad = num + '';
	while(numZeropad.length < count) {
	numZeropad = "0" + numZeropad;
	}
	return numZeropad;
}


// {{{ serialize
function serialize( mixed_value ) {
    // Generates a storable representation of a value
    // 
    // +    discuss at: http://kevin.vanzonneveld.net/techblog/article/javascript_equivalent_for_phps_serialize/
    // +       version: 812.3015
    // +   original by: Arpad Ray (mailto:arpad@php.net)
    // +   improved by: Dino
    // +   bugfixed by: Andrej Pavlovic
    // +   bugfixed by: Garagoth
    // %          note: We feel the main purpose of this function should be to ease the transport of data between php & js
    // %          note: Aiming for PHP-compatibility, we have to translate objects to arrays
    // *     example 1: serialize(['Kevin', 'van', 'Zonneveld']);
    // *     returns 1: 'a:3:{i:0;s:5:"Kevin";i:1;s:3:"van";i:2;s:9:"Zonneveld";}'
    // *     example 2: serialize({firstName: 'Kevin', midName: 'van', surName: 'Zonneveld'});
    // *     returns 2: 'a:3:{s:9:"firstName";s:5:"Kevin";s:7:"midName";s:3:"van";s:7:"surName";s:9:"Zonneveld";}'

    var _getType = function( inp ) {
        var type = typeof inp, match;
        var key;
        if (type == 'object' && !inp) {
            return 'null';
        }
        if (type == "object") {
            if (!inp.constructor) {
                return 'object';
            }
            var cons = inp.constructor.toString();
            if (match = cons.match(/(\w+)\(/)) {
                cons = match[1].toLowerCase();
            }
            var types = ["boolean", "number", "string", "array"];
            for (key in types) {
                if (cons == types[key]) {
                    type = types[key];
                    break;
                }
            }
        }
        return type;
    };
    var type = _getType(mixed_value);
    var val, ktype = '';
    
    switch (type) {
        case "function": 
            val = ""; 
            break;
        case "undefined":
            val = "N";
            break;
        case "boolean":
            val = "b:" + (mixed_value ? "1" : "0");
            break;
        case "number":
            val = (Math.round(mixed_value) == mixed_value ? "i" : "d") + ":" + mixed_value;
            break;
        case "string":
            val = "s:" + mixed_value.length + ":\"" + mixed_value + "\"";
            break;
        case "array":
        case "object":
            val = "a";
            /*
            if (type == "object") {
                var objname = mixed_value.constructor.toString().match(/(\w+)\(\)/);
                if (objname == undefined) {
                    return;
                }
                objname[1] = serialize(objname[1]);
                val = "O" + objname[1].substring(1, objname[1].length - 1);
            }
            */
            var count = 0;
            var vals = "";
            var okey;
            var key;
            for (key in mixed_value) {
                ktype = _getType(mixed_value[key]);
                if (ktype == "function") { 
                    continue; 
                }
                
                okey = (key.match(/^[0-9]+$/) ? parseInt(key) : key);
                vals += serialize(okey) +
                        serialize(mixed_value[key]);
                count++;
            }
            val += ":" + count + ":{" + vals + "}";
            break;
    }
    if (type != "object" && type != "array") val += ";";
    return val;
}// }}}

