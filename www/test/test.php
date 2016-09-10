<!--a test -->
<form method="GET" action="http://uems.sysu.edu.cn/jwxt/ria_http_export.do?method=download&expfname=xscj.xls&filepath=/opt/jwglxt/upload/1341458475453.xls">
	
</form>

<form id="form1" name="form1" method="POST" action="">
	<label>youName:
		<input type="text" name="name" id="name"/>
	</label>
	<label for="age">youAge:</label>
	<input type="text" name="aaage" id="age"/>
	<input type="submit" name="Submit" value="OK"/>
</form>
<?php
echo "<p>this my php's test</p>";
?>
<form id="form2" name="form2" method="GET" action="">
	<label for="school">yourSchool:</label>
	<input type="text" name="sumSchool" id="school"/>
	<label>grade:
		<input type="text" name="subGrade" id="grade"/>
	</label>
	<input type="submit" name="subSbmit" value="adf"/>
</form>
<?php
echo "my test";

if(isset($_POST["name"]) and isset($_POST["aaage"])){
echo "<p>i'm ".$_POST["name"]." and my age is ".$_POST["aaage"]." i'm glad to see you.</p>";
}
if(isset($_GET["sumSchool"]) and isset($_GET["subGrade"])){
echo "<p>get:school:".$_GET["school"]." grade:".$_GET[subGrade]." ok!</p>";
}
?>

