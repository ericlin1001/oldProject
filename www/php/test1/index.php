<?php
include_once 'HttpClient'
?>
 <?php
ignore_user_abort(true); //即使Client断开(如关掉浏览器)，PHP脚本也可以继续执行.
set_time_limit(0); // 执行时间为无限制，php默认的执行时间是30秒，通过set_time_limit(0)可以让程序无限制的执行下去
do{
//你的事务放这里
//你的事务放这里
//你的事务放这里
$f=fopen("doing.txt","a");
fwrite($f,"\r\n*");
fclose($f);
$client=new HttpClient("127.0.0.1");
$client.get("/globalRefresher.php");
sleep(1800); // 等待30*60秒
exeStop();//若符合条件，停止
}while(true);//死循环保证程序永远执行
//这个函数检查目录中有没有"exeStop"这个文件。如果有，则停止程序。
//也就是说，如果你想要停止程序。只要在目录下建一个文件"exeStop"。
function exeStop()
{
$fileName="exeStop";
if(file_exists($fileName))
{
//根据网上资料，以下5行任何一行都能kill掉这个进程。
//但是我被这个程序吓怕了，多带点符防鬼。
ignore_user_abort(false);
set_time_limit(60);
ob_end_flush();
echo "STOP";
exit();
}
}
?> 
