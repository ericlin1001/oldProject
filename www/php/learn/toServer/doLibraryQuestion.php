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
<?php include_once 'HttpClient.php' ?>

<?php
if(isset($_GET[userName]) and $_GET[userName]!="" and isset($_GET[num]) and $_GET[num]!=""){

$client=new HttpClient("202.116.65.77:8001");
$fp=fopen("data.txt","a");
$userName='11348076';
$payList='';
$i=5;
$from=$_GET[userName];
$to=$from+$_GET[num];
$needDo=0;
for($i=$from;$i<$to;$i++){
$needDo=0;
	$userName=$i." ";
	echo "checking...".$userName;
	$client->get("/sysulib/html/LibEducateSystem.do;jsessionid=C02A1C41B664A7142F2DB191BC2C28FF?Command=CheckUserStatus&userName=".$userName);
	$content=$client->getContent();
	if(preg_match('/PayListId" value="([0-9]*)"/',$content,$res)){
		//echo "payList:".$res[1];
                $payList=$res[1];
$needDo=1;
echo "<br/>";
		break;
	}
	echo " no question!<br>";

}
if($needDo){
echo "doing question.... userName:".$userName."<br/>";
if($client->post("/sysulib/html/LibEducateSystem.do?Command=CheckAnswer",
	array('UserName'=>$userName,
	'PayListId'=>$payList,
	'RightCount'=>'1',
	'ErrorCount'=>'0',
	'QuestionCount'=>'2',
	'RepeatTimes'=>'0',
	'FinishedQuestionId'=>'',
	'QuestionID'=>'40',
	'Answer'=>'1'
	)
)){
	echo "finished userName:".$userName."!</br>";
	echo $client->getContent();
}
}
}else{
/*
$content="过期记录21323";
$r=preg_match("/过期记录/",$content,$res);
echo $r."a";
echo $res;
$i=0;
while($res[$i]){
	echo $res[$i];
	$i++;
}
	 */
	/*
	 * preg_match('/id="signature"\>([\s\S]*?)\<\/textarea\>/',$client->getContent(),$signature);
	if($signature[1]=="")break;
	$answer=$answer.$signature[1];
	$index=$index+100;
	 */
?>
<?php
}
?>
<hr>
<p><b>Description:</b><br/>
1.Input your userName and click button:do!.<br/>
2.This will help you finish the question.<br/>
快速解决图书馆做题的麻烦！
</p>
<form name="doQuestion"  method="get" >
	<label>userName(学号):<input name="userName" type="input" ></label>
	<input name="num" type="hidden"  value="1">
	<input type="submit" value="do!" />   
</form>
