<?php

$myname 		= 'Annika Lentso';
$fulltimenow	= date("d.m.Y H:i:s");
$timeHTML		= "<p>Lehe avamise hetkel oli aeg: <strong> $fulltimenow </strong></p>";
$hourNow		= date("H");
$partOfDay		= "hägune aeg";

if ($hourNow < 10) {
	$partOfDay = 'hommik';
} elseif ($hourNow >= 10 and $hourNow < 18) {
	$partOfDay = 'aeg toimetada';
} 
elseif ($hourNow > 18 ) {
	$partOfDay = 'aeg puhata';
} 


$partOfDayHTML = "<p>Käes on " .$partOfDay ."!</p> \n";

//tausta pildi muutmine, hommik / õhtu
if ($hourNow > 6 and $hourNow < 18) {
	$bgclass = '"morning"';
} else {
	$bgclass = '"night"';
}

// kus me semestris oleme
$semesterStart = new DateTime("2020-01-27"); //defineerime semestri alguse
$semesterEnd = new DateTime("2020-06-22"); //defineerime semestri lõpu
$semesterDuration = $semesterStart->diff($semesterEnd); //defineerime semestri kestuse
$today = new DateTime("now"); //tänane kuupäev
$fromSemesterStart = $semesterStart->diff($today);

if($today < $semesterStart) {
	$semesterProgressHTML = '<p>Semester ei ole veel alanud!</p>';
} elseif ($today > $semesterEnd) {
	$semesterProgressHTML = '<p>Semester on läbi!</p>';
} else {
	$semesterProgressHTML = '<p>Semester on hoos: <meter min="0" max="';
	$semesterProgressHTML .= $semesterDuration->format("%r%a"); //https://www.w3schools.com/php/func_date_interval_format.asp
	$semesterProgressHTML .= '" value="';
	$semesterProgressHTML .= $fromSemesterStart->format("%r%a");
	$semesterProgressHTML .= '"></meter></p>' . "\n";
}

// pildid
$picsDir = "../../pics/"; //määrame piltide asukoha
$photoTypesAllow = ["image/jpeg", "image/png"]; //määrame lubatava pildi formaadi
$allFiles = array_slice(scandir($picsDir), 2); //scandir teeb massiivi, array_slice on sisseehitet funktsioon ning eraldab mingi osa massiivist välja
$photoList = []; //loome fotode massiivi

foreach ($allFiles as $file) {
	$fileInfo = getimagesize($picsDir . $file);
	if (in_array($fileInfo["mime"], $photoTypesAllow)) {
		array_push($photoList, $file);
	}
}

$photoCount = count($photoList);
	$photosToShow = [];
	$photoCountLimit = 3;
	if($photoCount < 3) {
		$photoCountLimit = $photoCount;
	}
for ($i = 0; $i < $photoCountLimit; $i ++) {
	do {
		$photoNum =mt_rand(0, ($photoCount -1));
	}
	while (in_array($photoNum, $photosToShow) == true);
	array_push($photosToShow, $photoNum);
}

if($photoCount!=0){
	$randomIMGList = [];
	$randomImgHTML = '';

	do {
		$randomIMG = $photoList[mt_rand(0, $photoCount - 1)];
		if(!in_array($randomIMG, $randomIMGList)){
			array_push($randomIMGList, $randomIMG);
			$randomImgHTML .= '<img src="' . $picsDir . $randomIMG . '" alt="Juhuslik Pilt Haapsalust"></img>' . "\n";
		} 
	} while (count($randomIMGList)<=2);

} else {
	$randomImgHTML = '<p>Ühtegi pilti ei ole! </p>';
}

// //kellaajast sõltuv veebilehe värv
// $bgColor = "#FFFFF0";
// $txtColor = "#0000CD";
// if($hourNow > 21 or $hourNow < 7){
// 	$bgColor = "#4B0082";
// 	$txtColor = "#FFFFF0";
// } elseif ($hourNow >= 7 and $hourNow < 12){
// 	$bgColor = "#FFE4E1";
// 	$txtColor = "#191970";
// } elseif ($hourNow >= 12 and $hourNow < 18){
// 	$bgColor= "#FFE4E1";
// 	$txtColor = "#800000";
// } else {
// 	$bgColor = "#999999";
// 	$txtColor = "#000033";
// }
// $styleHTML = "<style> \n .timeBackground { \n background-color: ";
// $styleHTML .= $bgColor;
// $styleHTML .= "; \n color: ";
// $styleHTML .= $txtColor;
// $styleHTML .= "; \n } \n </style> \n";

?>

<!DOCTYPE html>
<html lang="et">

<head>
	<meta charset="utf-8">
	<title>Veebirakendused ja nende loomine 2020</title>
</head>
 <style>
	.morning {
		background-color: lightgoldenrodyellow;
		text-align:center;
		color: darkblue;
	}

	.night {
		background-color: darkblue;
		text-align:center;
		color: lightgoldenrodyellow;
	}
	
	
</style> 
<body class=<?php echo $bgclass; ?>>

	<h1> <?php echo $myname; ?></h1>
	<h2>See leht on valminud õppetöö raames</h2>

	<h3><?php
	echo $timeHTML . $partOfDayHTML . $semesterProgressHTML . $randomImgHTML;

	?></h3>
</body>
<footer>
<div class="container text-center darkcolor" id="footer" style="margin-bottom:0">
<p>copyright ©Annika 2020</p>
</div>
</footer>
</html>