<?php
$db=mysql_connect("localhost","root");
if(!$db){die("Could not connect: ".mysql_error());}
mysql_select_db("mydb",$db);
//**********end connect to db****
if($_GET["id"])$id=$_GET["id"];
if($_POST['submit'])$submit=$_POST['submit'];
if($id){
	$result=mysql_query("select * from employees where id=$id",$db);
	$myrow=mysql_fetch_array($result);
	printf("first name: %s <br>\n",$myrow["first"]);

}else{
	$result=mysql_query("select * from employees",$db);
	if($myrow=mysql_fetch_array($result)){
		do{
			printf("<a href=\"%s?id=%s\">%s %s</a><br>\n",$PHP_SELF,$myrow["id"],$myrow["first"],$myrow["last"],$myrow["id"]);
		}while($myrow=mysql_fetch_array($result));

	}else{
		echo "Sorry,no records were found!";
	}


}
phpinfo();
mysql_close($db);
?>
