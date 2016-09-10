<meta http-equiv="Content-Type" content="text/html; charset=utf-8" /> 
<?php
include_once 'HttpClient.php'
?>

<?php
function testGet($host,$url,$cookiesArr){
	$client=new HttpClient($host);
	$client->setCookies($cookiesArr);
	$client->setDebug(true);
	$client->get($url);
	return $client->getContent();
}
function doget ($url, $referer, $cookie){
	$optionget = array('http' => array('method' => "GET", 'header' => "User-Agent:Mozilla/4.0 (compatible; MSIE //7.0; Windows NT 6.0; SLCC1; .NET CLR 2.0.50727; Media Center PC 5.0; .NET CLR 3.5.21022; .NET CLR 3.0.04506; CIBA)\r\nAccept:*/*\r\nReferer:" . $referer . "\r\nCookie:" . $cookie));        
	$header= "User-Agent:Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 6.0; SLCC1; .NET CLR 2.0.50727; Media Center PC 5.0; .NET CLR 3.5.21022; .NET CLR 3.0.04506; CIBA)\r\nAccept:*/*\r\nReferer:" . $referer . "\r\nCookie:" . $cookie;
	$file = file_get_contents($url, false , stream_context_create($optionget));        
	//echo '<br/>';
	//print_r($http_response_header);
	//echo '<br/>';
	preg_match_all( "/Set-Cookie:(.*?)\r\n/" , implode( "\r\n" ,  $http_response_header ),  $cookies );
	//print_r($cookies);
	return $file;

}
function loadCookie($fileName){
$file_handle = fopen($fileName,'r');
$content=fgets($file_handle);
fclose($file_handle);
return $content;
}
?>
<?php
$uid = '1197161814';
$cookies=loadCookie("mycookies.txt");
$cookies="";
$url = 'http://weibo.com/'.$uid.'/info';
$UID_INFO = doget($url,$refer,$cookies);
$str=$UID_INFO;
$userInfo=testGet('weibo.com','/'.$uid.'/info');
echo htmlspecialchars($userInfo);

/********result*******/
echo "<hr/>";
echo '<p style="{color:#ff0000; font:200%; }">';
if(!preg_match('/register/',$str,$m,$r)){
	echo "OK!";
}else{
	echo "fail!";
}
echo '</p>';
echo "<hr/>";
/********result*******/

$afterConvertStr=htmlspecialchars($str);
echo $afterConvertStr;
?>
