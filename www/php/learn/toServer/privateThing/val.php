
<?php
include 'validate.php' ;
?>
<?php
$img='1.png';
$url='http://gz.jiajiao114.com/member/check_number.php';
grabImage($url,$img);
echo "<img src='".$img."'/>".getImgCode($img)."<br/>\n";
?>
