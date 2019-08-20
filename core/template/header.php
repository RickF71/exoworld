<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <title><?php
    	if ($page_title == '') {
			echo 'Exo World';
		} else {
			echo $page_title;
		}
	?></title>
    <script type="text/javascript" src="inc/js/jquery-1.3.js"></script>
    <link rel="stylesheet" type="text/css" href="inc/css/main.css">
    <link rel="stylesheet" type="text/css" href="inc/css/structural.css">
    <link rel="stylesheet" type="text/css" href="inc/css/forms.css">
    <link rel="stylesheet" type="text/css" href="inc/css/text.css">
    <link rel="stylesheet" type="text/css" href="inc/css/items.css">
    <link rel="stylesheet" type="text/css" href="inc/css/buttons.css">
    <?php if ($header_includes != '') { echo $header_includes; } ?>
</head>

<body>
<div id="wrapper" style="  ">
	<div id="header" style="">
        Welcome to Exo World.
        <?php if ($user['id']==1000) { ?>
        <a class="abutton1" href="/?p=admin/main">Admin</a>
        <?php } ?>
        <a class="abutton1" href="/?p=user/logout&notem">Logout</a>
        <a class="abutton1" href="/">Home</a>
        <a class="abutton1" href="/?p=player/list">Players</a>
        <br />
        <?php if (isset($player)) { ?>
        	Active Player: <strong><a href="/?p=player/profile&player_id=<?=$player->id;?>"><?=$player->name;?></a></strong>
        <?php } ?>
	</div>
    <div id="content" style=" ">
    <?php if (isset($warning)) { ?><div id="warning"><?=$warning?></div><?php } ?>	
    <?php if (isset($message)) { ?><div id="message"><?=$message?></div><?php } ?>	
	<!-- CONTENT BEGIN -->	