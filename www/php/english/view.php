<head>
<meta http-equiv="Content-Type" content="html;charset=UTF-8">
</head>
<?php
include_once 'HttpClient.php'
?>
<?php
function testPreg($str,$pattern){
	$r=preg_match($pattern,$str,$res);
	if(!$r){
		echo "preg_match fail";
	}else{
		echo "preg_match success:<br/>";
		print_r($res);
	}
}
function mp3player($url,$skin=""){
if($skin!="")$skin='&amp;skin='.$skin;
$str='<object type="application/x-shockwave-flash" data="http://flash-mp3-player.net/medias/player_mp3_maxi.swf" width="200" height="20"><param name="movie" value="http://flash-mp3-player.net/medias/player_mp3_maxi.swf" /><param name="bgcolor" value="#ffffff" /><param name="FlashVars" value="mp3='.$url.'&amp;autoload=1&amp;showstop=1&amp;showinfo=1&amp;showvolume=1&amp;showloading=always'.$skin.'" /></object>';
return $str;
}
?>
<form method="get" action="#">
url:<input type="text" name="url" size="30%">
<input type="submit">
</form>

<?php
if(isset($_GET['url']) && $_GET['url']!=""){
$url=$_GET['url'];
if(preg_match('/http:\/\//',$url,$res))$url=substr($url,7);
preg_match('/([^`]*?)(\/[^`]*)/',$url,$res);
$host=$res[1];
$getPara=$res[2];
$client=new HttpClient($host);
$client->get($getPara);
$page=$client->getContent();
//analysing....
preg_match('/<div id="menubar">[^`]*?(ht[^`]*?.mp3)/',$page,$res);
$mp3=$res[1];
preg_match('/(<div class="title">([^`]*?)<\/div>[^`]*?){2}/',$page,$res);
$title=$res[1];
preg_match('/(<div class="boxout">[^`]*?<\/div>[^`]*?){1,}(<div>[^`]*?)<\/div>[^`]*?<div id="Bottom_/',$page,$res);
//
preg_match('/<div class="boxout">[^`]*?<div class="boxout">([^`]*?<\/div>)([^`]*?)<\/div>[^`]*?<div id="Bottom_/',$page,$res);
$text=$res[2];
$search=array("</p>","<br/>","<br />","<p>","<div>","</div>");
$replace=array("\n","\n","\n");
$text=str_replace($search,$replace,$text);
echo '<h2>'.$title.'</h2>';
echo mp3player($mp3);
//echo mp3player($mp3,'http://t3.gstatic.com/images?q=tbn:ANd9GcTReIpYDQ3e7husmTnn9fjLJimMkC2RX_l9LVs5VFPanUDMdeL1');
echo '&nbsp;&nbsp;&nbsp;mp3:<a href="'.$mp3.'">download it.</a>';
echo "<br/>";
echo '<textarea name="content" wrap="on" cols="75%" rows="20">'.$text.'</textarea>';
echo '<textarea name="content" wrap="on" cols="25%" rows="20">'.'</textarea>';
//print_r($res[2]);


}

?>

