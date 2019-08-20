<?php 
$loc_name = $_REQUEST['location'];
$loc_nbr = name_to_nbr($loc_name);



?><h1>Rename A Location</h1>
<p>You have chosen to rename the location <strong><?=$loc_name?></strong>.   This name was assigned by the ExoWorld Central Database.</p>

<p>What name would you like to give to this place?</p>

<form action="/?a=world/rename" method="post">
<input type="hidden" name="loc_code" value="<?=$loc_name;?>">
New Name: <input name="new_name"><input type="submit" value="Rename">
</form>