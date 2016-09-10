<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="Content-Language" content="zh-CN" />
<title>a</title>
</head>
<body>
<?php
/*
usage:
<?php include_once 'login114.php' ?>
 */
?>
<?php include_once 'HttpClient.php' ;
include 'validate.php' ;
?>
<?php
$client114Login=new HttpClient("gz.jiajiao114.com");
$userpair114s=array();
$userpair114s["zyz"]="2chen,18";
//$userpair114s["ericlin"]="111qqqaa";


foreach ($userpair114s as $user=>$pass){
	echo "try logining user:".$user." in 114<br/>";
$client114Login->get('/signin.php');
$img='1.png';
$out=fopen($img,"w");
$client114Login->get('/member/check_number.php');
fwrite($out,$client114Login->getContent());
fclose($out);
$vcode=getImgCode($img);
echo "<img src='".$img."'/> is recognized as ".$vcode."<br/>\n";
	if($client114Login->post("/signin.php",array(
		'username'=>$user,
		'password'=>$pass,
		'vcode'=>$vcode
	))){
		echo "Have posted in gz.jiajiao114.com <b>username:".$user."</b><br/>";
	}else{
		echo "fail to post <i>username:".$user."</i><br/>";
	}
	echo "<br/>";
}
echo "<hr>";
$page=$client114Login->getContent();
preg_match('/ss="sh_newrecTitle">[^`]*?<\/div>[^<]*?(<table[^`]*?<\/table>)/',$page,$m);
print_r($m[1]);
?>
</body>
</html>
