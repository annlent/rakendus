<?php

$myName ="Annika Lentso";
$fullTimeNow = date('d.m.Y. H:i:s');
//<p> Lehe avamise hetkel nii <strong> 31.01.20 </strong> 11:23:10 </p>
$timeHTML = "\n<p> Lehe avamise hetkel nii <strong>" . $fullTimeNow . "</strong>";
$hourNow = date("H");
$partOfDay = "hägune aeg";
if ($hourNow < 10){
	$partOfDay = "hommik";
}
if ($hourNow >= 10 and $hourNow < 18){
	$partOfDay = "tegutsemisaeg!";
}
$partOfDayHTML = "<p> Käes on ". $partOfDay . "</p> \n";
//info semestri kulgemise kohta
$semesterStart = new DateTime("2020-01-27");
$semesterEnd = new DateTime("2020-06-22");
$semesterDuration = $semesterStart -> diff($semesterEnd);
//var_dump($semesterEnd);
$today = new DateTime("now");
$fromSemesterStart = $semesterStart -> diff($today);
//<p> Semester on hoos: <meter.value = "" min="0" max="147"> </meter> </p>;
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
//loen etteantud pildikataloogist pildifailid
$picsdir = "../../pics/";
$AllFiles = array_slice(scandir($picsdir),2); // scandir loeb etteantud kataloogi sisu, array_slice($muutuja, 2) võtab ära kaks esimest rida nii, et jäävad massiivi ainult pildid
$PhotoTypesAllowed = ["image/jpeg", "image/png"]; //siin lubame erinevaid piltide formaate (võib rohkem lubada ja võib ka vähem)
$PhotoList = [];
foreach($AllFiles as $file) { //massiivide jaoks - käi kõik läbi ja tähista neid file
$fileInfo = getimagesize($picsdir.$file);
if (in_array($fileInfo["mime"], $PhotoTypesAllowed) == TRUE);
array_push($PhotoList,$file);
}
$PhotoCount=count($PhotoList); //siia võiks panna ka veel selle KODUS!, et kui pilte pole, siis lisada ei saa

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

$PhotoNum = mt_rand(0,$PhotoCount-1);
$randomImageHTML = '<img src = "'.$picsdir.$PhotoList[$PhotoNum].'" alt ="Juhuslik pilt Haapsalust">'."\n";
//var_dump($PhotoList);
//kolmesammuline üles laadimine, kuskil peab olema võimalus kirjutada ka kommentaare, lae üles. Githubis on juhend olemas
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
	}

	.night {
		background-color: darkblue;
		text-decoration-color: lightgray;
	}
</style>
<body>
	<h1><?php echo $myName; ?></h1>
	<p>See leht on valminud õppetöö raames</p>
<?php
echo $timeHTML;
echo $partOfDayHTML;
echo $semesterProgressHTML;
echo $randomImageHTML;
?>
</body>
</html>