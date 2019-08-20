
<h1>You are at: <?=$curloc->name?></h1>
<?php if ($curloc->nameable) { ?>
Noone has given this location a name.  You can <a href="?p=world/name&location=<?=$curloc->name;?>">name this location</a> if you like.
<?php } ?>

<p>
	<?php
		$elemtot = $curloc->elemvals['fire']+$curloc->elemvals['earth']+$curloc->elemvals['air']+$curloc->elemvals['water'];
		$elemwid = 650;
		$er = $elemwid/$elemtot;
	?>
	<div class="" style="float:left; height:10px; width:<?=intval($er*$curloc->elemvals['fire']+  0.5);?>px;background-image:url(/images/bg_fire.png);  " ></div>
	<div class="" style="float:left; height:10px; width:<?=intval($er*$curloc->elemvals['earth']+ 0.5);?>px;background-image:url(/images/bg_earth.png); " ></div>
	<div class="" style="float:left; height:10px; width:<?=intval($er*$curloc->elemvals['air']+   0.5);?>px;background-image:url(/images/bg_air.png);   " ></div>
	<div class="" style="float:left; height:10px; width:<?=intval($er*$curloc->elemvals['water']+ 0.5);?>px;background-image:url(/images/bg_water.png); " ></div>
</p>
<p style="clear:both;">
    Fire: <?=$curloc->elemvals['fire'];?><br />
    Earth: <?=$curloc->elemvals['earth'];?><br />
    Air: <?=$curloc->elemvals['air'];?><br />
    Water: <?=$curloc->elemvals['water'];?><br />
</p>

<?php if ($curloc->parent_id != '') { 
	$parent_loc = new Location($curloc->parent_id);
	?>
	<div class="loc_exit elem_<?=strtolower($parent_loc->element);?>">
		<h1>Go Back<br />&lt;&lt; <a href="/?p=world/location&location=<?=nbr_to_name($parent_loc->id)?>"><?=$parent_loc->name?></a></h1>
		Fire: <?=$parent_loc->elemvals['fire'];?><br />
		Earth: <?=$parent_loc->elemvals['earth'];?><br />
		Air: <?=$parent_loc->elemvals['air'];?><br />
		Water: <?=$parent_loc->elemvals['water'];?><br />
	</div>
<?php } ?>

<?php if ($curloc->num_guards > 0) { ?>
	<div class="loc_exit">
    	There are <?=$curloc->num_guards?> groups of guards to be eliminated to get past this location.
        <a class="abutton1" style="display:block; text-align:center;" href="/?p=world/battle&location=<?=nbr_to_name($curloc->id)?>">Attack Guards</a>
    </div>
  
<?php } else {
	for ($a=1; $a <= $curloc->num_exits; $a++) {
		$tmploc = new Location($curloc->exits[$a]); 
	?>
	
		<div class="loc_exit elem_<?=strtolower($tmploc->element);?>">
			<h1>Go Deeper<br /><a href="/?p=world/location&location=<?=nbr_to_name($tmploc->id)?>"><?=$tmploc->name?></a> &gt;&gt;</h1>
			<em>No other information</em>
		</div>
	<?php } ?>
<?php } ?>

<?php if ($player->access=='admin') { ?>
<div style="clear:both">&nbsp;</div>
<div style="border:1px solid red;">
	<h1>ADMIN</h1>
    <em>Administrators only</em>
	<?php for ($a=1; $a <= $curloc->num_exits; $a++) {
		$tmploc = new Location($curloc->exits[$a]); 
	?>
	
		<div class="loc_exit elem_<?=strtolower($tmploc->element);?>">
			<h1>Go Deeper<br /><a href="/?p=world/location&location=<?=nbr_to_name($tmploc->id)?>"><?=$tmploc->name?></a> &gt;&gt;</h1>
            Fire: <?=$tmploc->elemvals['fire'];?><br />
            Earth: <?=$tmploc->elemvals['earth'];?><br />
            Air: <?=$tmploc->elemvals['air'];?><br />
            Water: <?=$tmploc->elemvals['water'];?><br />
		</div>
	<?php } ?>
	<div style="clear:both">&nbsp;</div>
</div>
<?php } ?>

<div style="clear:both">&nbsp;</div>
