﻿<?php
include_once 'HttpClient.php' ;
include_once 'GetAns.php'
?>
<?php
?>
<form method='get' action='doCN.php'>
user:<input type="input" name='user'><br/>
pass:<input type='input' name='pass'><br/>
<hr/>
test2:170-220<br/>
test3:200-350<br/>
test4:300-400<br/>
from:<input type='input' value='170' name='from'><br/>
to:<input type='input' name='to' value='500'><br/>
<input type="submit">
</form>
<?php
if(isset($_GET['user']) && $_GET['user']!="" && isset($_GET['pass']) && $_GET['pass']!="" && isset($_GET['to']) && $_GET['to']!="" && isset($_GET['from']) && $_GET['from']!=""){

$user=$_GET['user'];
$pass=$_GET['pass'];
$g=new GetAns($user,$pass);
$min=$_GET['from'];
$max=$_GET['to'];
$id='';
//echo ' answer is '.$g->getAn($id);
//echo '<hr/>';
for ($i=$min;$i<$max;$i++){
	echo 'checking id='.$i;
	$id='';
	$id=$id.$i;
	echo ' answer is '.$g->getAn($id);
	echo '<br/>';
			echo '<hr/>';
}
/*
$client=new HttpClient('222.200.191.58',8080);
//$client->setDebug(true);
echo $client->post('/onlineTest/resources/j_spring_security_check',array('j_username'=>'11348076','j_password'=>'11348076'));
echo $client->getContent();
//$client->get('/onlineTest/questions/exam?type=1&page=1&size=3');
$questions=$client->getContent();
echo "question:".$questions;

$answer='EF,A,B,';
$questionId='201,202,203,';
$psize=3;
echo 'getScore='.$client->post('/onlineTest/questions/getScore.action',array('answer'=>$answer,'questionId'=>$questionId,'psize'=>$psize));
$client->get('/onlineTest/questions/showWrong?size=20&page=2');
echo $client->getContent();
*/
}
?>
