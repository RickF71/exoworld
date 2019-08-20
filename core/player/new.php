<?php

// check to see if this user already has any players configured
$players_sql = "SELECT * FROM players WHERE user_id=".$user['id'];
$players = mysql_query($players_sql);
$num_chars = mysql_num_rows($players);

?>


<?php if ($num_chars < 1) { ?>
<h1>Welcome To ExoWorld!!!</h1>
<p>In order to enter ExoWorld, you must first create a player to use.   All we need to get you started is your first player name.  Don't worry, in the future you can create another character with a different name if you like.</p>

<?php } else { ?>


<h1>Create New Player</h1>

<?php } ?>

<p class="request">Enter a name below and hit "Create Player"</p>
<form action="/?a=player/submit" method="post">
	<input type="text" name="player_name" id="player_name" />
    <input type="submit" name="submit" value="Create Player" />
</form>

