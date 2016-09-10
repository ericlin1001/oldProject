<head>
<meta http-equiv="Content-Type" content="html;charset=UTF-8">
</head>
<a href="index.php">Home</a><br><hr>
<?php
//Get user ip
$ip = $_SERVER['REMOTE_ADDR'];
$time = gmdate("Y-M-d H:i:s",time()+8*3600);
$filename = $_SERVER['PHP_SELF'];
$file = "ip.txt";
$fp=fopen("ip.txt","a");

$txt = "$ip"." ---- "."$time"." ---- "."$filename"."\n";
fputs($fp,$txt);
?>


<a href="getSourceCodeAdance.php">西西里提源码简易</a><br>
<a href="doLibraryQuestion.php">图书馆刷题</a><br>
<a href="getSourceCode.php">西西里提源码</a><br>
<a href="../english/">practiseListening</a>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>

