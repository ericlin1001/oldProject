<?php

$title="hello word";
include("header.php");
/*****/
$result=mysql_query("select * from employees",$db);
while($myrow=mysql_fetch_row($result)){
	printf("<p>%s</p>\n",$myrow[1]);
}



/******/
include("footer.php");
?>
