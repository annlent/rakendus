<?php

$myname 		= 'Annika Lento';
$fulltimenow	= date("d.m.Y H:i:s");
$timeHTML		= "<p>Lehe avamise hetkel oli aeg: <strong> $fulltimenow </strong></p>";
$hourNow		= date("H");
$partOfDay		= "hägune aeg";

if ($hourNow < 10) {
	$partOfDay = 'hommik';
} elseif ($hourNow >= 10 and $hourNow < 18) {
	$partOfDay = 'aeg toimetada';
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
$picsDir = "../../pics/";
$photoTypesAllow = ["image/jpeg", "image/png"];
$allFiles = array_slice(scandir($picsDir), 2); //scandir teeb massiivi, 
$photoList = [];

foreach ($allFiles as $file) {
	$fileInfo = getimagesize($picsDir . $file);
	if (in_array($fileInfo["mime"], $photoTypesAllow)) {
		array_push($photoList, $file);
	}
}

$photoCount = count($photoList);

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

?>

<!DOCTYPE html>
<html lang="et">

<head>
	<meta charset="utf-8">
	<title>Veebirakendused ja nende loomine 2020</title>
</head>
<style>
	.morning {
		background-color: lightpink;
	}

	.night {
		background-color: lightblue;
	}
</style>
<body class=<?php echo $bgclass; ?>>
	<h1><?php echo $myname; ?></h1>
	<p>See leht on valminud! õppetöö raames!</p>
	<p>Loo endale <a href="newuser.php">kasutajakonto</a>!</p>

	<?php
	echo $timeHTML . $partOfDayHTML . $semesterProgressHTML . $randomImgHTML;

	?>
</body>

</html>