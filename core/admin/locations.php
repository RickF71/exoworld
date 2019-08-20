<?php 
$sql = "SELECT * FROM locations ORDER BY name";
$dat = mysql_query($sql);

?>
<h1>Locations</h1>
<?php while ($d = mysql_fetch_array($dat)) { ?>
	<div><?=$d['name'];?></div>
<?php } ?>