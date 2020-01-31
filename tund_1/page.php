<?php

$myName ="Annikas Lentso";
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
$semesterProgressHTML = '<p> Semester on hoos: <meter min="0" max ="';
$semesterProgressHTML .= $semesterDuration->format("%r%a"); //%r teeb vajadusel negatiivse numbri positiivseks, %a kahe kuupäeva vahe
$semesterProgressHTML .= '" value="';
$semesterProgressHTML .= $fromSemesterStart->format("%r%a");
$semesterProgressHTML .= '"></meter></p>'. "\n";
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
<body>
	<h1><?php echo $myName; ?></h1>
	<p>See leht on valminud õppetöö raames!</p>
<?php
echo $timeHTML;
echo $partOfDayHTML;
echo $semesterProgressHTML;
echo $randomImageHTML;
?>
</body>
</html>