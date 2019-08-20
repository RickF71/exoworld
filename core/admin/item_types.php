<?php 
$sql = "SELECT * FROM item_types ORDER BY name";
$dat = mysql_query($sql);

?>
<h1>Item Types</h1>
<?php while ($d = mysql_fetch_array($dat)) { ?>
	<div><?=$d['name'];?></div>
<?php } ?>