<?php

require "ZiilaaOcr.php";

function parseSaveData($data)
{
	$str = strtoupper ($data);
	
	$pos = strpos ($str, "[UNKNOWN]");
	if ($pos === false)	{	return "error";	}
	$str = substr ($str, $pos + 9);
	
	$str = str_replace (" ", "", $str);		//remove blanks
	$str = str_replace ("\n", "", $str);	//remove CR
	$str = str_replace ("<BR>", "", $str);	//remove <BR>
	$str = str_replace ("O", "1", $str);	//O => 1
	$str = str_replace (".", "0", $str);	//. => 0	
	return $str;
}

$action = $_POST['action'];
$templates = $_POST['templates'];
$ocr = new ZiilaaOcr();
$ocr->loadTemplate($templates);

if ($action == "save")
{
	$data = parseSaveData (urldecode ($_POST['data']));
	$realvalue = $_POST['realvalue'];
	$ocr->saveTemplate($templates, "$realvalue:$data");
	echo "[$realvalue] saved.";
	exit();
}

////this is common code for all actions
{
	$hexdata = $_POST['data'];
	$threshold = intval ($_POST['threshold']);
	$fontWidth = intval ($_POST['fontWidth']);
	$startX    = intval ($_POST['startX']);
	$negateImage = strtolower ($_POST['negateImage']);
	$centerFont  = strtolower ($_POST['centerFont']);
	$likenessMin = floatval ($_POST['likenessMin']);
	$likenessMax = floatval ($_POST['likenessMax']);
	
	$ocr->loadImage ($hexdata, $threshold);	
	$ocr->setFontWidth ($fontWidth, $startX);
	if ($negateImage == "true")		$ocr->negateImage ();
	if ($centerFont == "true")		$ocr->centerFont ();
}

if ($action == "filter")
{
	echo $ocr->showImage();
	$result = $ocr->matchAll($likenessMax, $likenessMin, true);
	echo "result: [$result]";
}

if ($action == "train")
{
	$result = $ocr->matchAll($likenessMax, $likenessMin, true);
	if ($result == -1)
	{	echo $ocr->showUnknown();	}
}

if ($action == "read")
{
	$result = $ocr->matchAll($likenessMax, $likenessMin, false);
	echo $result;
}

?>
