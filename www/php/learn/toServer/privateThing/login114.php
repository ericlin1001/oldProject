
<?php
/*
usage:
<?php include_once 'login114.php' ?>
 */
?>
<?php include_once 'HttpClient.php' ?>
<?php
$client114Login=new HttpClient("gz.jiajiao114.com");
$userpair114s=array();
$userpair114s["zyz"]="2chen,18";
$userpair114s["ericlin"]="111qqqaa";
//print_r($userpair114s);
foreach ($userpair114s as $user=>$pass){
	//	echo $user."=>".$pass."<br/>";
	echo "try logining user:".$user." in 114<br/>";
	if($client114Login->post("/signin.php",array(
		'username'=>$user,
		'password'=>$pass
	))){
		echo "Have posted in gz.jiajiao114.com <b>username:".$user."</b><br/>";
	}else{
		echo "fail to post <i>username:".$user."</i><br/>";
	}
	echo "<br/>";
}
?>

