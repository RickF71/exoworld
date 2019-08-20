<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<title>Exo World Login</title>
<script type="text/javascript" src="/inc/js/jquery-1.2.6.min.js"></script>
</head>

<body style="background-color:black; ">

<div style="margin:0px auto; width:800px; height:500px; background-image:url(/images/exoworld_login.png)">
    
<div style="position:relative; width:800px; height:500px; overflow:hidden; ">
		<form action="/?p=verify" method="post" name="login_form">
			<input type="text" name="username" style="position:absolute; left:366px; top:259px; border:1px solid black; height:24px; width:233px; background-color:black; color:green; font-size:18px; cursor:pointer; ">
			<input type="password" name="password" style="position:absolute; left:367px; top:326px; border:1px solid black; height:24px; width:233px; background-color:black; color:green; font-size:18px; cursor:pointer; ">	
			<!-- <input type="submit" name="submit" value="submit"> -->
		</form>



	<div id="new_user_on" style="position:absolute; left: 536px; top: 383px; height:57px; width:179px; display:none;" onMouseOut="$('#new_user_off').show();$('#new_user_on').hide();">
	    <a href="/?p=new"><img src="images/new_user.png" alt="New User" width="179" height="57" border="0"></a>
    </div>
    
    <div id="new_user_off" style="position:absolute; left: 536px; top: 383px; height:57px; width:179px;"  onMouseOver="$('#new_user_on').show();$('#new_user_off').hide();"></div>





	<div id="enter_on" style="position:absolute; left: 180px; top: 375px; height:65px; width:156px; display:none;" onMouseOut="$('#enter_off').show();$('#enter_on').hide();">
	    <a href="#" onClick="document.login_form.submit();"><img src="images/enter.png" alt="New User" width="156" height="65" border="0"></a>
    </div>
    
    <div id="enter_off" style="position:absolute; left: 180px; top: 375px; height:65px; width:156px;"  onMouseOver="$('#enter_on').show();$('#enter_off').hide();"></div>

    
</div>

</div>

</body>
</html>
