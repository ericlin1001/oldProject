<form action="" method="POST">
<p>
<label>username:<input type="text" value="user" name="username" maxlength=16/></label><br>
<label>password:<input type="password"  name="password" maxlength=16/></label><br>
<input type="hidden" name="hiddenVar" value="hiddenValue">
<label>good:<input type="radio" name="book" value="good"></label>
<label><input type="radio" name="book" value="bad">bad!</label>
<br>

<label>good:<input type="radio" name="food" value="good" checked></label>
<label>good:<input type="radio" name="food" value="good"></label>

<br>

<p>What's your favourite color?"<br>
<input type="checkbox" name="color" value="red" id="c1">
<input type="checkbox" name="color" value="green" id="c2">
<input type="checkbox" name="color" value="blue" id="c3" checked>
<label for="c1">red</label>
<label for="c2">green</label>
<label for="c3">blue</label>
</p>

<br>
<input type="reset" value="����">
<input type="submit" name="sumbit" value="�ύ">
</p>
<?php
//if(isset($_POST["username"] and 
$two=array(
	"colors"=>array("red","green","blue"),
	"numbers"=>array(1,2,3,4,5,6,7,8,9,0)
);
print_r($two);
print_r($two[colors]);
foreach($two as $key=>$value){
	echo $key."=".$value.",";
}
?>