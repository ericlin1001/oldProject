<a href="index.php">PrivateHome</a>
<hr/>
<?
    
    $filename="notepad.file"; //这是记事本的文本文件,而且一开始就要有此文件,属性为777,可以为空文件
    $password="1"; //管理员密码
    //$passSet=array("1","2");

    $Pas=$_POST[Pas];
    $con=$_POST[con];
    $E=$_POST[E];
//    print $Pas.",".$con.",".$E;
//    print_r($E);

$delim="*{|}*";
fclose(fopen($filename,"a"));
/*
$isUser=false;
foreach ($passSet as $i){
	if($i==$Pas){$isUser=true;break;}
}
 */
    if ($Pas == $password)
    {
	    echo "true pass";
        $fd=fopen($filename,"r"); //打开文本
	$memo="";
	if(filesize($filename)>0)
        $memo=fread($fd,filesize($filename)); //将文本里内容读到$memo里
//	echo "memo=".$memo."<br/>";
        fclose($fd);  //关闭文件

        $entry=explode($delim,$memo); //多条纪录在文本中以$delim分割.在这里,将文本用$delim分开后,赋值给数组$entry
        $memo=""; //清空$memo

        for ($index=1;$index<count($entry);$index++)
        {
            if ($E[$index]!="on") $memo=$memo.$delim.$entry[$index]; //对每一条纪录,如果没用被选中,表明没有被删除,便合并到$memo中
        }
        if ($con!="") $memo=$memo.$delim.$con; //如果有新的纪录,便将之并入
        $fd=fopen($filename,"w"); //再次打开文本
        fwrite($fd,$memo); //写入
        fclose($fd);
    }else{
	    echo "false password";
    }
    if ($memo == "") {
        $fd=fopen($filename,"r"); 
	if(filesize($filename)>0)
        $memo=fread($fd,filesize($filename));
        fclose($fd); //如果$memo没有被赋值,便读取文件
    }
    $entry=explode($delim,$memo); //读每一条纪录
    echo "<form name=Memo method=post>"; //开始显显示表单
    for ($index=1;$index<count($entry);$index++)
    {
        echo "<INPUT TYPE=checkbox NAME="."E[$index]>"; //加一个可以删除的单选框
	echo "<textarea>".$entry[$index]."</textarea><br/>\n";
    }
    echo "notepad:<br/><textarea name=con cols='50%' rows='10'></textarea>";//添新纪录用,多条纪录间用$delim分割
    echo "<br/>password:<INPUT TYPE=password NAME=Pas SIZE=5>";//添加或删除要输密码
    echo "<br/><input type=submit value=submit>";
    echo "</form>";
    //
    //
?> 
