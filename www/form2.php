<?php
if(isset($_GET[pid]) and $_GET[pid]!=""  and isset($_GET[myusername]) and $_GET[myusername]!=""){
	$username=$_GET[myusername];
	$pid=$_GET[pid];
?>
<p>
<form id="loginform" action="http://soj.me/action.php?act=Login" method="post" name="loginform">

	<input name="username" type="hidden" id="username" size="10" maxlength="30" value="tempaccount"/>
	<input name="password" type="hidden" id="password" size="10" maxlength="16" value="tempaccount"/>
	<input id="lsession" name="lsession" type="hidden" value="1"/>
	<input type="submit" />   
</form>
</p>
<form action="http://soj.me/profile_edit.php" method="post" enctype="multipart/form-data">
    <h1>Personal Profile Edit</h1>
	<input type="hidden" name="uid" value="18981"/>
		    <input type="hidden" name="nickname" size="20" maxlength="20" id="nickname" value=""/>
		    <textarea rows="2" cols="75" name="signature" id="signature"></textarea>
		<input name="email" type="hidden" id="email" size="50" maxlength="30" value="tempaccount@1.1">
		<input name="phone" type="hidden" id="phone" size="50" maxlength="50" value="">
		<input name="address" type="hidden" id="address" size="50" maxlength="50" value="">
		<input name="student_id" type="hidden" id="cn_name" size="8" maxlength="8" value="">
		<input name="cn_name" type="hidden" id="cn_name" size="20" maxlength="20" value="">
		<input name="en_name" type="hidden" id="en_name" size="20" maxlength="20" value="">
		    <select name="gender">
			<option value="">Unknown</option>
			<option value="M" selected='selected'>Male</option>
			<option value="F">Female</option>
		    </select>
		    <input type="hidden" name="major" size="30" maxlength="50" id="major" value=""/>
		    <input type="hidden" name="grade" size="5" maxlength="5" id="grade" value="0"/>
		    <input type="hidden" name="class" size="10" maxlength="10" id="class" value=""/>
		<input type="submit"/> 
</form>
</p>
<hr>
<?php
}else{
?>

<form method="get">
<label for="#pid">pid:</label><input type="text" name="pid" id="pid">
<label for="#myusername">your username:</label><input type="text" name="myusername" id="myusername">
<p>The answer will show in your signature!<p>
<input type="submit">
</form>
<?php
}
?>
