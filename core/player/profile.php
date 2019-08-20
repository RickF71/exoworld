<h1><?=$profile->name;?></h1>

<?php while ($item = mysql_fetch_array($items)) { ?>
	<div class="item_normal"><?=$item['name'];?></div>
<?php } ?>