<?php
//Get user ip
$ip = $_SERVER['REMOTE_ADDR'];
$time = gmdate("Y-M-d H:i:s",time()-6*3600);
$filename = $_SERVER['PHP_SELF'];
$file = "ip.txt";
$fp=fopen("ip.txt","a");

$txt = "$ip"." ---- "."$time"." ---- "."$filename"."\n";
fputs($fp,$txt);
 ?>
<p>unfinished.</p>
<h1>Just for study!</h1>
<p>
1.press the button of "login".<br>
2.enter the pid of which you want to get the sourcecode.<br>
3.press the button of "input".<br>
</p>
<form name="loginForm" action="http://soj.me/action.php?act=Login" method="post" name="loginform">
	<input name="username" type="hidden" id="username" size="10" maxlength="30" value="tempaccount"/>
	<input name="password" type="hidden" id="password" size="10" maxlength="16" value="tempaccount"/>
	<input id="lsession" name="lsession" type="hidden" value="1"/>
	<input type="submit" value="login" />   
</form>

<form id="forminput1" action="form.php" method="get">
<label for="#pid">pid:</label><input type="text" name="pid" id="pid">
<label for="#myusername"></label><input type="hidden" name="myusername" id="myusername" value="tempaccount">
<input type="hidden" name="index" value="-99">
<p>The answer will show in the signature!<p>
<input type="submit" name="input" value="input">
</form>



