<?php 
$sql = "SELECT * FROM users ORDER BY name";
$dat = mysql_query($sql);

?>
<h1>Users</h1>
<?php while ($d = mysql_fetch_array($dat)) { ?>
	<div><?=$d['name'];?></div>
<?php } ?>