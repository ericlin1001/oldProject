<?php
//Get user ip
$ip = $_SERVER['REMOTE_ADDR'];
$time = gmdate("Y-M-d H:i:s",time()-6*3600);
$filename = $_SERVER['PHP_SELF'];
$file = "ip.txt";
$fp=fopen("ip.txt","a");

$txt = "$ip"." ---- "."$time"." ---- "."$filename"."\n";
fputs($fp,$txt);
fclose($fp);
 ?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional //EN">
<html lang="zh">
<head>
<meta http-equiv="Content-Type" content="text/html;charset=gb2312">
<title>Girl's festival</title>
<style type="text/css">
<!--
#pban{ color:#ff0000;
	font-size: 220% ;
	font-weight:200}
-->
</style>
</head>
<body text="#ff99cc" bgcolor="#ff3fcc" background="1.jpg">
<h1>女生节快乐</h1>
<p>可以试试下面的东西~<br>输入你的名字,然后点测试.</p>
<form id="form1" name="form1" method="GET" action="">

<label>名字：
<input type="text" name="name" />
<input type="submit" name="" value="测试"/>
</label>
</form>
<?php
if(isset($_GET["name"]) and $_GET[name]!=""){
	if($_GET[name]=="林俊浩"){
		echo '<p id="pban">You can not input this name!!!!</p>';
	}else{
$fp=fopen("ip.txt","a");

$txt = "$ip"." ---- "."$time"." ---- "."$filename"." name:".$_GET[name]."\n";
fputs($fp,$txt);
fclose($fp);

	$fh=fopen("2/setting.txt","w");
	$name=$_GET[name];
	echo "your name:".$name."<br>";
	$str1="first=";
	$str2="&music=1.mp3&name=&thing=Just for you\n成熟中有丝青涩，艳丽中有丝高贵，优雅中有丝自恋，纯洁中有丝邪念，浪漫中有丝童真，微笑中有丝狡黠，这就是你，可爱的小女生！祝37女生节快乐哟。";
	$sth=$str1.$name.$str2;
	$sth=iconv("gb2312","UTF-8",$sth);
	fwrite($fh,$sth);
	fclose($fh);
$url = "2/for.html";  
echo "<script language='javascript' type='text/javascript'>";  
echo "window.location.href='$url'";  
echo "</script>";  
	}	
}
?>
</body>
</html>

