<html> 
<body>
<?php 
if($_GET["id"])$id=$_GET["id"];
if($_POST['submit'])$submit=$_POST['submit'];
if($_POST['first'])$first=$_POST['first'];
if($_POST['last'])$last=$_POST['last'];
if($_POST['address'])$address=$_POST['address'];
if($_POST['position'])$position=$_POST['position'];
$db = mysql_connect("localhost", "root"); 
mysql_select_db("mydb",$db); 
if ($id) { 
	if($submit){
		$sql="update employees set first='$first',last='$last',address='$address',position='$position' where id=$id";
		$result=mysql_query($sql);
		echo "Thank you! Information updated.\n";
	}else{
		// query the DB 
		$sql = "SELECT * FROM employees WHERE id=$id"; 
		$result = mysql_query($sql); 
		$myrow = mysql_fetch_array($result); 
?> 
  <form method="post" action="<?php echo $PHP_SELF?>"> 
  <input type=hidden name="id" value="<?php echo $myrow["id"] ?>"> 
  First name:<input type="Text" name="first" value="<?php echo $myrow["first"] ?>"><br> 
  Last name:<input type="Text" name="last" value="<?php echo $myrow["last"] ?>"><br> 
  Address:<input type="Text" name="address" value="<?php echo $myrow["address"] ?>"><br> 
  Position:<input type="Text" name="position" value="<?php echo $myrow["position"] ?>"><br> 
  <input type="Submit" name="submit" value="Enter information"> 
  </form> 
<?php 
	}
}
else 
{ 
	// display list of employees 
	$result = mysql_query("SELECT * FROM employees",$db); 
	while ($myrow = mysql_fetch_array($result)) { 
		printf("<a href=\"%s?id=%s\">%s %s</a><br>\n", $PHP_SELF,
			$myrow["id"],$myrow["first"], $myrow["last"]); 
	} 
} 
?> 
</body> 
</html>
