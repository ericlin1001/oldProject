<html> 
<body>
<?php
if($_GET["id"])$id=$_GET["id"];
if($_POST['submit'])$submit=$_POST['submit'];
if($_POST['first'])$first=$_POST['first'];
if($_POST['last'])$last=$_POST['last'];
if($_POST['address'])$address=$_POST['address'];
if($_POST['position'])$position=$_POST['position'];
?>
<?php 
$db = mysql_connect("localhost", "root"); 
mysql_select_db("mydb",$db); 
if ($submit) { 
	// here if no ID then adding else we're editing 
	if ($id) { 
		$sql = "UPDATE employees SET first='$first',last='$last',address='$address',position='$position' 
			WHERE id=$id"; 
	} else { 
		$sql ="INSERT INTO employees (first,last,address,position) VALUES ('$first','$last','$address','$position')"; 
	} 
	// run SQL against the DB 
	$result = mysql_query($sql); 
	echo "Record updated/edited!<p>"; 
} elseif ($delete) { 
	// delete a record 
	$sql = "DELETE FROM employees WHERE id=$id"; 
	$result = mysql_query($sql); 
	echo "$sql Record deleted!<p>"; 
} else { 
	// this part happens if we don't press submit 
	if (!$id) { 
		// print the list if there is not editing 
		$result = mysql_query("SELECT * FROM employees",$db); 
		while ($myrow = mysql_fetch_array($result)) { 
			printf("<a href=\"%s?id=%s\">%s %s</a> 
				\n", $PHP_SELF, $myrow["id"], $myrow["first"], 
				$myrow["last"]); 
			printf("<a href=\"%s?id=%s&delete=yes\">(DELETE)</a><br>", $PHP_SELF, 
				$myrow["id"]); 
		} 
	} 
?> 
  <P>
  <a href="<?php echo $PHP_SELF?>">ADD A RECORD</a>
  <P>
  <form method="post" action="<?php echo $PHP_SELF?>"> 
<?php 
	if ($id) { 
		// editing so select a record 
		$sql = "SELECT * FROM employees WHERE id=$id"; 
		$result = mysql_query($sql); 
		$myrow = mysql_fetch_array($result); 
		$id = $myrow["id"]; 
		$first = $myrow["first"]; 
		$last = $myrow["last"]; 
		$address = $myrow["address"]; 
		$position = $myrow["position"]; 
		// print the id for editing 
?> 
    <input type=hidden name="id" value="<?php echo $id ?>"> 
<?php 
		
	} 
?> 
  First name:<input type="Text" name="first" value="<?php echo $first ?>"><br> 
  Last name:<input type="Text" name="last" value="<?php echo $last ?>"><br> 
  Address:<input type="Text" name="address" value="<?php echo $address ?>"><br> 
  Position:<input type="Text" name="position" value="<?php echo $position ?>"><br> 
  <input type="Submit" name="submit" value="Enter information"> 
  </form> 
<?php 
} 
?> 
</body>
</html>
