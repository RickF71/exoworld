<?php 
	// battle!!!
	unset($_SESSION['time_diff']);
	unset($_SESSION['current_battle']);
?>
<div id="exo_time">time</div>
<div id="battlefield" style="margin-left:20px;">
    <div id="battle_player_list">
        <div class="player_tiny">
        	<div id="bp1" class="player_icon"></div>
            <div id="hp1"></div>
            <div id="ep1"></div>
        </div>
        <div class="player_tiny">
        	<div id="bp2" class="player_icon"></div>
            <div id="hp2"></div>
            <div id="ep2"></div>
        </div>
        <div class="player_tiny">
        	<div id="bp3" class="player_icon"></div>
            <div id="hp3"></div>
            <div id="ep3"></div>
        </div>
        <div class="player_tiny">
        	<div id="bp4" class="player_icon"></div>
            <div id="hp4"></div>
            <div id="ep4"></div>
        </div>
    </div>
    
    <div id="battle_sync">
    </div>
    
    <div id="battle_opponent_list">
        <div class="player_tiny">
        	<div id="bp5" class="player_icon"></div>
            <div id="hp5"></div>
            <div id="ep5"></div>
        </div>
        <div class="player_tiny">
        	<div id="bp6" class="player_icon"></div>
            <div id="hp6"></div>
            <div id="ep6"></div>
        </div>
        <div class="player_tiny">
        	<div id="bp7" class="player_icon"></div>
            <div id="hp7"></div>
            <div id="ep7"></div>
        </div>
        <div class="player_tiny">
        	<div id="bp8" class="player_icon"></div>
            <div id="hp8"></div>
            <div id="ep8"></div>
        </div>
    </div>
    
    <div id="battle_player">
    	<a id="auto_attack" class="battle_off" href="#" onclick="toggleAutoAttack();return false;" style="display:block; text-align:center;">Auto Attack: OFF</a>
    </div>
    
    <div id="battle_status">
    </div>
    
    <div id="battle_opponent">
    </div>
    
    <div id="debug" style="clear:both; width:760px;">debug</div>
</div>