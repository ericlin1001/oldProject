<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional //EN">
<html lang="zh">
<head>
<title>Girl's festival</title>
<style type="text/css">
<!--
#pban{ color:#ff0000;
	font-size: 220% ;
	font-weight:200}
-->
</style>
</head>
<body text="#0099cc" bgcolor="#ff32cc" background="1.jpg">
<h1>Ů���ڿ���</h1>
<p>������������Ķ���~</p>
<form id="form1" name="form1" method="GET" action="">
<label>���֣�
<input type="text" name="name" />
<input type="submit" name="" value="����"/>
</label>
</form>
<?php
ini_set("default_charset","utf-8");
if(isset($_GET["name"]) and $_GET[name]!=""){
	if($_GET[name]=="�ֿ���"){
		echo '<p id="pban">You can not input this name!!!!</p>';
	}else{
	$fh=fopen("1/setting.txt","w");
	$name=$_GET[name];
	echo "your name:".$name."<br>";
	$str1="first=";
	$str2="&music=1.mp3&name=&thing=Just for you\n��������˿��ɬ����������˿�߹���������˿��������������˿а���������˿ͯ�棬΢Ц����˿���������㣬�ɰ���СŮ����ף37Ů���ڿ���Ӵ";
	$sth=$str1.$name.$str2;
	$sth=iconv("gb2312","UTF-8",$sth);
	fwrite($fh,$sth);
	fclose($fh);
$url = "1/for.html";  
echo "<script language='javascript' type='text/javascript'>";  
echo "window.location.href='$url'";  
echo "</script>";  
	}	
}
?>
</body>
</html>

