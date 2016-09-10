<?php
include 'HttpClient.php'
?>
<?php
function getUrl($turl){
	$ch = curl_init(); 
	curl_setopt($ch, CURLOPT_URL,$turl); 
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true); 
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, true); 
	$result = curl_exec($ch); 
	echo 'result:';
	print_r($result); 
	return $result;

}
?>
<?php
$access_token='2.0097Ri4CetxWME694b01f6famu7bfE';
echo 'access_token='.$access_token;
$client=new HttpClient('api.weibo.com');
$client->setDebug(true);
$client->get('/2/statuses/user_timeline.json?appkey=3849704102&uid=2182674812&page=1&count=200&access_token='.$access_token);
$url='https://api.weibo.com/2/statuses/user_timeline.json?appkey=3849704102&uid=2182674812&page=1&count=200&access_token='.$access_token;
echo $client->getContent();

getUrl($url);
?>
