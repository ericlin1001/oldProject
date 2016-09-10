<?php
$rootPass="ericlin";
$showPrivate=false;
?>

<?php

if(isset($_POST["pass"]) && $_POST["pass"]!=""){
if($_POST["pass"]==$rootPass){
$showPrivate=true;
}else{
echo "password Wrong!<br/>";
}
}

if($showPrivate){
?>
<a href="/SFSadmin/root/admin/">SFSAdmin</a><br/>
<?php
}else {


?>

<form method="post">
password:<input type="password" name="pass"><br/>
<input type="submit"><br/>
</form>
<?php
}

?>