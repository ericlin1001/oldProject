<form method="post" action="">
pass:<input type="password" name="pass"><br/>
<input type="submit">
</form>

<?php
$mypass='111qqqaa';
if(isset($_POST['pass']) and $_POST['pass']!=""){
if($_POST['pass']==$mypass){
echo "<script>document.location='http://file-manager.yupage.com/1/index.php?login_hash=mcaWHefLiHcEPPb%2FA0RJMIRV%2FvlzrxDLctIKApPk3%2BPtuKq%2F5SXfGLF0vvSKn0mJGUybjRoeOqM9EpGepP9Nqlv0njle0lCHtZ6q1G2LLl0ATbkeHws4qt4FHr%2FxnPWQOwxYHWkK5fquMkiSs1trS5LpCeYwh9%2FVAD3OdI3uX%2FQheMlgulRUa1AGssVSV8Aun6u5DQlMpjC2ESSMpMtoSBGiQ6SWei88uEEut%2FycNcT0iexesjgQ4hd0AT2rwV%2FI';</script>";
}else{
echo "<div style='{color:#ff0000;}'>password wrong!</div><br/>";
}
}
?>
<br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/>
