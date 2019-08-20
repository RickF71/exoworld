<?php 
// get user's players
$players_sql = "SELECT * FROM players WHERE user_id=".$user['id']."";
$players = mysql_query($players_sql);

?><h1>List of your Player Characters</h1>

<p><a class="abutton1" href="/?p=player/new">Create New Player</a></p>

<?php while($player = mysql_fetch_array($players)) { ?>
<div class="player_full">
	<h1><?=$player['name'];?></h1>
    <a class="abutton1" href="/?p=player/profile&player_id=<?=$player['id']?>" style="display:block; text-align:center;">Profile</a>
    <a class="abutton1" href="/?a=player/set_default&player_id=<?=$player['id']?>" style="display:block; text-align:center;">Set Active</a>
    <a class="abutton1" href="/?a=player/set_default&player_id=<?=$player['id']?>&enter_world=true" style="display:block; text-align:center;">Set Active / Enter</a>
</div>
<?php } ?>

