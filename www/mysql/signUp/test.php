<?php
function db_query($sql){
	global $db;
	return mysql_query($sql,$db);
}

class UserInfo{
	var $name="??";
	var $QQ="";
	var $phone="";
	var $id;
	function UserInfo(){

	}
	function setID($v){$this->id=$v;}
		function setName($v){$this->name=$v;$this->getInfo();return;}
		function setQQ($v){$this->QQ=$v;$this->getInfo();}
		function setPhone($v){$this->phone=$v;$this->getInfo();}
		function checkValidation(){
			return 1;
		}
	function getInfo(){
		return;
		$str="";
		echo "info************<br/>";
		echo "set name:".$this->name;
		echo "<br/>";
		echo "set QQ:".$this->QQ;
		echo "<br/>";
		echo "set phone:".$this->phone;
		echo "<br/>";
		$sql="insert into users values (0,'".$this->name."','".$this->phone."','".$this->QQ."')";
		//echo $sql;
echo "<br/>";
		echo "info************<br/>";


	}	
	function addToDB(){
		$this->getInfo();
		//checkValidation();
		//	$sql="update users set name='$name',QQ='$QQ',phone='$phone' where id=$id";
		$sql="insert into users values (0,'".$this->name."','".$this->phone."','".$this->QQ."')";
		//echo $sql;
		return 	db_query($sql);
	}

};

?>
<?php
$title="Sign up for XXX";
include("header.php");
/*****/

function showAllUserInfos(){
	$res=db_query("select * from users");
	echo "<table>";
	echo "<tr><td>ID</td><td>name</td><td>phone</td><td>QQ</td></tr>";
	while($myrow=mysql_fetch_array($res)){
		printf("<tr><td>%s</td><td>%s</td><td>%s</td><td>%s</td></tr>",$myrow["id"],$myrow["name"],$myrow["phone"],$myrow["QQ"]);

	}
	echo "</table>";

}

/********real start***/
$state=0;
if($_POST['state'])$state=$_POST['state'];

switch($state){
case 0:
?>
	<form method="post" action="<?php echo $PHP_SELF?>">
	<input type="hidden" name="id" value="0"><br/>
	name:<input type="text" name="name"><br/>
	phone:<input type="text" name="phone"><br/>
	QQ:<input type="text" name="QQ"><br/>
	<input type="submit" name="sign up">
	<input type="hidden" name="state" value="1">
</form>
<?php
	break;
case 1:
	$user=new UserInfo();
	$user->setName($_POST['name']);
	$user->setID($_POST['id']);
	$user->setQQ($_POST['QQ']);
	$user->setPhone($_POST['phone']);
	if($user->addToDB()){
		echo "Thank you!";
	}else{
		echo "Sorry,we can't add your inforamtion to database.";
	}
	break;
case 2:
	$rootPass=$_POST['rootPass'];
	$realRootPass='eric';
	if($rootPass==$realRootPass){
		showAllUserInfos();
	}
	
	break;
}




/******/
include("footer.php");
?>
