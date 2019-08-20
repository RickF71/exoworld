<?php 
$sql = "SELECT * FROM positions ORDER BY name";
$dat = mysql_query($sql);

?>
<h1>Positions</h1>
<?php while ($d = mysql_fetch_array($dat)) { ?>
	<div><?=$d['name'];?></div>
<?php } ?>