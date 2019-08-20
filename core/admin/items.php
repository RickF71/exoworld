<?php 
$sql = "SELECT items.*, item_types.name AS item_type_name FROM items LEFT JOIN item_types ON items.item_type_id=item_types.id ORDER BY name";
$dat = mysql_query($sql);

?>
<h1>Items</h1>
<?php while ($d = mysql_fetch_array($dat)) { ?>
	<div><?=$d['name'];?> (<?=$d['item_type_name'];?>)</div>
<?php } ?>