<?php
if(isset($_POST[name]) and $_POST[name]!="" and isset($_POST[suggestion]) and $_POST[suggestion]!=""){
	setcookie("hasSuggested",1,time()+1800);
}
?>
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
if(isset($_POST[name]) and $_POST[name]!="" and isset($_POST[suggestion]) and $_POST[suggestion]!=""){
	$hasSuggested=1;
	$name=$_POST[name];
	$suggestion=$_POST[suggestion];
	$ttime = gmdate("Y-M-d H:i:s",time()+8*3600);

	$fsuggest=fopen("suggestions.txt","a");
	$suggesttxt = "{\nip=".$ip.";\nname=".$name.";\nsuggestion=".$suggestion."---".$ttime.";\n}\n";
	fputs($fsuggest,$suggesttxt);
	fclose($fsuggest);
	echo "<p style='{color:#ff0000;}'>Submit success!Thank for your comment!</p>";
}
if($_COOKIE["hasSuggested"]!=1 && $hasSuggested!=1){
	echo "Write a comment first!<br>";
?>
<p>
<form name="suggess"  method="post" >
	<label>Name:<input name="name" type="input" id="pid"></label><br>
	<label>Comment:<br><textarea name="suggestion" rows="5" cols="30%"></textarea></label><br>
	<input type="submit" value="Sumbit" />   
</form>
</p>
<?php
}
?>

<h1>Just for study!</h1>
<p>To get source code!<br>
This can get answer about the Courses and Problems!<br>
Please paste the url of your problem in purl,and press Button:Get<br>
for example:<br>
1.http://www.soj.me/1000<br>
2.http://www.soj.me/submit.php?problem_id=1001<br>
3.http://www.soj.me/show_problem.php?pid=1003&amp;cid=844<br>
</p>
<form name="getPid"  method="get" >
	<!--<label>pid:<input name="pid" type="input" id="pid"></label>-->
	<label>purl:<input name="purl" type="input" size="50%" id="purl"/></label><br/>
	<label>other:<input name="ch" type="input" id="ch" value="0"></label>
	<input type="submit" value="Get" />   
</form>
<?php
if((isset($_GET[pid]) and $_GET[pid]!="" or isset($_GET[purl]) and $_GET[purl]!="") and isset($_GET[ch]) and $_GET[ch]!=""){
	if($_COOKIE["hasSuggested"]!=1){
		echo "<p style='{color:#ff0000;}'>You haven't write the comment!</p>";
		return ;
	}
	$purl=$_GET[purl];
	$pid=$_GET[pid];
	$ch=$_GET[ch];
	$pid=(int)$pid;
	$ch=(int)$ch;
	//print "<p>pid=".$pid."</p>";
	//print "<p>ch=".$ch."</p>";
	$client = new HttpClient('soj.me');
	$username="test741";
	$pass="741";
	$uid=0;
	if($client->post('/action.php?act=Login',array(
		'username'=>$username,
		'password'=>$pass,
		'lsession'=>'1'
	))){
		//echo '<p>Login in Success!</p>';
		$gethead=$client->getHeaders();
		$cookies=$gethead['set-cookie'];
		if (!is_array($cookies)) {
			$cookies = array($cookies);
		}

		foreach ($cookies as $cookie) {
			if (preg_match('/([^=]+)=([^;]+);/', $cookie, $m)) {
				$cooies[$m[1]] = $m[2];
			}
		}
		$client->setCookies($cooies);
		$uid=$cooies['uid'];
		$index=1;
		$postData=array(
			'uid'=>$uid,
			'nickname'=>'',
			'signature'=>'',
			'email'=>'1@1.1',
			'phone'=>'',
			'address'=>'',
			'student_id'=>'',
			'cn_name'=>'',
			'en_name'=>'',
			'gender'=>'',
			'major'=>'',
			'grade'=>'',
			'class'=>'');
		$cid=0;
		$cpid=0;
		$matchRes=array();
		if(!preg_match("/problem_id=([0-9]*)/",$purl,$matchRes)){
			if(!preg_match("/pid=([0-9]*)/",$purl,$matchRes)){
				preg_match("/soj.me\/([0-9]*)/",$purl,$matchRes);
			}
		}
		$pid=$cpid=$matchRes[1];
		if(preg_match("/cid=([0-9]*)/",$purl,$matchRes)){
			$cid=$matchRes[1];
			echo "cid=".$cid."<br/>";
			$str2="',signature=(select substr(group_concat(concat('pid:',pid,'endpid')),1,100) from contest_problems where cid=".$cid." and cpid=".$cpid.") where username='".$username."';##";
			$postData['signature']=$str2;
			if($client->post('/profile_edit.php',$postData)){
				$client->get('/profile_edit.php');
				preg_match('/pid:([0-9]*)endpid/',$client->getContent(),$matchRes);
				$pid=$matchRes[1];
			}
		}
		//
		echo "<br>real pid=".$pid."<br/>";
		echo "<p>".$str1."</p>";
		$answer="//URL-:".$purl."\n";
		$answer=$answer."//pid=".$pid."\n";
		while(1){
		$str1="',signature=(select substr(sourcecode,".$index.",100) from status where pid=".$pid." and status='Accepted' and language='C++' and length(sourcecode)>0 order by length(sourcecode) limit ".$ch.",1) where username='".$username."'#";
		$postData['signature']=$str1;
			if($client->post('/profile_edit.php',$postData)){
				$client->get('/profile_edit.php');
				preg_match('/id="signature"\>([\s\S]*?)\<\/textarea\>/',$client->getContent(),$signature);
				if($signature[1]==""){
					echo "****************OK*****************<br>";
					break;
				}
				//print_r($signature);
				$answer=$answer.$signature[1];
				$index=$index+100;
			}
		}
		//print "<p>answer=<br><pre><code>".htmlspecialchars($answer)."</code></pre><br></p>";
		print "<textarea rows='10%' cols='80%'>".htmlspecialchars($answer)."</textarea>";
	}
}else{
	//echo "<p>Pleaes input the pid! Other is to change other sourceCode!<p>";
}
?>


<?php
if($_COOKIE["hasSuggested"]==1 || $hasSuggested==1){
?>
<p>
<form name="suggess"  method="post" >
	<label>Name:<input name="name" type="input" id="pid"></label><br>
	<label>Comment:<br><textarea name="suggestion" rows="5" cols="30%"></textarea></label><br>
	<input type="submit" value="Sumbit" />   
</form>
</p>
<?php
}
?>
<br>
<div id="clustrmaps-widget"></div><script type="text/javascript">var _clustrmaps = {'url' : '172.18.157.121', 'user' : 1029422, 'server' : '4', 'id' : 'clustrmaps-widget', 'version' : 1, 'date' : '2012-07-14', 'lang' : 'zh', 'corners' : 'square' };(function (){ var s = document.createElement('script'); s.type = 'text/javascript'; s.async = true; s.src = 'http://www4.clustrmaps.com/counter/map.js'; var x = document.getElementsByTagName('script')[0]; x.parentNode.insertBefore(s, x);})();</script><noscript><a href="http://www4.clustrmaps.com/user/5c8fb52e"><img src="http://www4.clustrmaps.com/stats/maps-no_clusters/172.18.157.121-thumb.jpg" alt="Locations of visitors to this page" /></a></noscript>
