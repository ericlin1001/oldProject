<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="Content-Language" content="zh-CN" />

<?php include_once 'HttpClient.php' ;
?>

<?php
function unicodeDecode($str) { 
return preg_replace("#\\\u([0-9a-f]{4})#ie", "iconv('UCS-2BE', 'UTF-8', pack('H4', '\\1'))", $str);
} 


$f=fopen("data.txt","w");
$client=new HttpClient("weibo.com");
$client->get('/');
$pageContent=$client->getContent();
fputs($f,$pageContent);

//$usernames=array('spider4k','yaojinbo');
$usernames=array('spider4k','u/1780718673','u/1201254125');
foreach($usernames as $v){
	echo "fetching ".$v."<br/>";
	$client->get("/".$v);
	$pageContent=$client->getContent();
	$f=fopen($v.".txt","w");
	 $pageContent=unicodeDecode($pageContent);
	// $pageContent=htmlspecialchar_decoded($pageContent);
	fputs($f,$pageContent);
}
?>

