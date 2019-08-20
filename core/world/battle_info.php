<?php
include('base/battle.class.php');

$battles_sql = "SELECT * FROM battles";
$battles = mysql_query($battles_sql);




?>


<?php while ($b = mysql_fetch_array($battles)) { ?>
<div>
	<h2>Battle: <?=$b['id'];?></h2>
	<pre><?php print_r($b);?></pre>
	<pre><?php print_r(unserialize($b['queue']));?></pre>

</div>

<?php } ?>

<?php
$battle = new Battle(72);

echo '<h1>'.is_array($battle->actions).'</h1>';

echo '<pre>';
print_r($battle);
echo '</pre>';
foreach($battle->actions as $id => $data) { ?>

<pre><?=$data['action_desc'];?></pre>
<?php } ?>