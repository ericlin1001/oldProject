<?php
//Get user ip
$ip = $_SERVER['REMOTE_ADDR'];
$time = gmdate("Y-M-d H:i:s",time()-6*3600);
$filename = $_SERVER['PHP_SELF'];
$file = "ip.txt";
$fp=fopen("ip.txt","a");

$txt = "$ip"." ---- "."$time"." ---- "."$filename"."\n";
fputs($fp,$txt);
 ?>


<a href="./flash/KanMap.swf">����ʵ��(kanmap)</a>


<div id="clustrmaps-widget"></div><script type="text/javascript">var _clustrmaps = {'url' : '172.18.157.121', 'user' : 1029422, 'server' : '4', 'id' : 'clustrmaps-widget', 'version' : 1, 'date' : '2012-07-14', 'lang' : 'zh', 'corners' : 'square' };(function (){ var s = document.createElement('script'); s.type = 'text/javascript'; s.async = true; s.src = 'http://www4.clustrmaps.com/counter/map.js'; var x = document.getElementsByTagName('script')[0]; x.parentNode.insertBefore(s, x);})();</script><noscript><a href="http://www4.clustrmaps.com/user/5c8fb52e"><img src="http://www4.clustrmaps.com/stats/maps-no_clusters/172.18.157.121-thumb.jpg" alt="Locations of visitors to this page" /></a></noscript>
