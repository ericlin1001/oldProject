<?php
include_once 'HttpClient'
?>
 <?php
ignore_user_abort(true); //��ʹClient�Ͽ�(��ص������)��PHP�ű�Ҳ���Լ���ִ��.
set_time_limit(0); // ִ��ʱ��Ϊ�����ƣ�phpĬ�ϵ�ִ��ʱ����30�룬ͨ��set_time_limit(0)�����ó��������Ƶ�ִ����ȥ
do{
//������������
//������������
//������������
$f=fopen("doing.txt","a");
fwrite($f,"\r\n*");
fclose($f);
$client=new HttpClient("127.0.0.1");
$client.get("/globalRefresher.php");
sleep(1800); // �ȴ�30*60��
exeStop();//������������ֹͣ
}while(true);//��ѭ����֤������Զִ��
//����������Ŀ¼����û��"exeStop"����ļ�������У���ֹͣ����
//Ҳ����˵���������Ҫֹͣ����ֻҪ��Ŀ¼�½�һ���ļ�"exeStop"��
function exeStop()
{
$fileName="exeStop";
if(file_exists($fileName))
{
//�����������ϣ�����5���κ�һ�ж���kill��������̡�
//�����ұ�������������ˣ�����������
ignore_user_abort(false);
set_time_limit(60);
ob_end_flush();
echo "STOP";
exit();
}
}
?> 
