<?php 
$sql = "SELECT * FROM players ORDER BY name";
$dat = mysql_query($sql);

?>
<h1>Players</h1>
<?php while ($d = mysql_fetch_array($dat)) { ?>
	<div><?=$d['name'];?></div>
<?php } ?>