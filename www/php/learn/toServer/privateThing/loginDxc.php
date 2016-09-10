
<?php
/*
usage:
<?php include_once 'loginDxc.php' ?>
 */
?>
<?php include_once 'HttpClient.php' ?>
<?php
$clientDxcLogin=new HttpClient("www.dxc020.com");
$userpairDxcs=array();
$userpairDxcs["ericlin"]="111qqqaa";
//print_r($userpairDxcs);
foreach ($userpairDxcs as $user=>$pass){
	//	echo $user."=>".$pass."<br/>";
	echo "try logining user:".$user." in Dxc<br/>";
	if($clientDxcLogin->post("/Login.asp?action=save",array(
		'name'=>$user,
		'password'=>$pass
	))){
		echo "Have posted in www.dxc020.com <b>username:".$user."</b><br/>";
	}else{
		echo "fail to post <i>username:".$user."</i><br/>";
	}
	//$pageContent=$clientDxcLogin->getContent();
	echo "<br/>";
}
?>

