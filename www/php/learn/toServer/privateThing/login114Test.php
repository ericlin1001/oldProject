<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="Content-Language" content="zh-CN" />

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
$userpair114s["lidjxy"]="ldj92520";
$userpair114s["zyz"]="2chen,18";
//$userpair114s["ericlin"]="111qqqaa";


foreach ($userpair114s as $user=>$pass){
	echo "try logining user:".$user." in 114<br/>";
$client114Login->setCookies(array());
$client114Login->get('/signin.php');
$img=$user.'.png';
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
		
$page=$client114Login->getContent();
if(preg_match('/signout.php/',$page,$m)){
echo "Login in <b>".$user."</b> successfully!<br/>";
}else{
echo "fail to login in <i>username:".$user."</i><br/>";
}
	}else{
		echo "fail to login in <i>username:".$user."</i><br/>";
	}
	echo "<br/>\n";
}
echo "<hr>";
$client114Login->get('/');
$page=$client114Login->getContent();
//echo $page;
preg_match('/ss="sh_newrecTitle">[^`]*?<\/div>[^<]*?(<table[^`]*?<\/table>)/',$page,$m);
print_r($m[1]);
?>
