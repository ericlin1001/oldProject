<a href="index.php">PrivateHome</a>
<hr/>
<?
    
    $filename="notepad.file"; //���Ǽ��±����ı��ļ�,����һ��ʼ��Ҫ�д��ļ�,����Ϊ777,����Ϊ���ļ�
    $password="1"; //����Ա����
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
        $fd=fopen($filename,"r"); //���ı�
	$memo="";
	if(filesize($filename)>0)
        $memo=fread($fd,filesize($filename)); //���ı������ݶ���$memo��
//	echo "memo=".$memo."<br/>";
        fclose($fd);  //�ر��ļ�

        $entry=explode($delim,$memo); //������¼���ı�����$delim�ָ�.������,���ı���$delim�ֿ���,��ֵ������$entry
        $memo=""; //���$memo

        for ($index=1;$index<count($entry);$index++)
        {
            if ($E[$index]!="on") $memo=$memo.$delim.$entry[$index]; //��ÿһ����¼,���û�ñ�ѡ��,����û�б�ɾ��,��ϲ���$memo��
        }
        if ($con!="") $memo=$memo.$delim.$con; //������µļ�¼,�㽫֮����
        $fd=fopen($filename,"w"); //�ٴδ��ı�
        fwrite($fd,$memo); //д��
        fclose($fd);
    }else{
	    echo "false password";
    }
    if ($memo == "") {
        $fd=fopen($filename,"r"); 
	if(filesize($filename)>0)
        $memo=fread($fd,filesize($filename));
        fclose($fd); //���$memoû�б���ֵ,���ȡ�ļ�
    }
    $entry=explode($delim,$memo); //��ÿһ����¼
    echo "<form name=Memo method=post>"; //��ʼ����ʾ��
    for ($index=1;$index<count($entry);$index++)
    {
        echo "<INPUT TYPE=checkbox NAME="."E[$index]>"; //��һ������ɾ���ĵ�ѡ��
	echo "<textarea>".$entry[$index]."</textarea><br/>\n";
    }
    echo "notepad:<br/><textarea name=con cols='50%' rows='10'></textarea>";//���¼�¼��,������¼����$delim�ָ�
    echo "<br/>password:<INPUT TYPE=password NAME=Pas SIZE=5>";//��ӻ�ɾ��Ҫ������
    echo "<br/><input type=submit value=submit>";
    echo "</form>";
    //
    //
?> 
