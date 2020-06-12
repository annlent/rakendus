<?php
require("../../../../configuration.php");
require("classes/Session.class.php");
require("fnc_news.php");
require("fnc_user.php");

	
SessionManager::sessionStart("vr20", 0, "/~annika.lentso/", "tigu.hk.tlu.ee");

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
$photoList = []; //loome fotode massiivi
$allFiles = array_slice(scandir($picsDir), 2); //scandir teeb massiivi, array_slice on sisseehitet funktsioon ning eraldab mingi osa massiivist välja


foreach($allFiles as $file){
	$fileInfo = getimagesize($picsDir .$file);
	if(in_array($fileInfo["mime"], $photoTypesAllow) == true) {
		array_push($photoList, $file);
	}
}
$photoCount = count($photoList);

if ($photoCount != 0) {
	$randomIMGList = [];
	$randomImgHTML = '';

	do {
		$randomIMG = $photoList[mt_rand(0, $photoCount - 1)];
		if (!in_array($randomIMG, $randomIMGList)) {
			array_push($randomIMGList, $randomIMG);
			$randomImgHTML .= '<img src="' . $picsDir . $randomIMG . '" alt="Juhuslik Pilt Haapsalust"></img>' . "\n";
		}
	} while (count($randomIMGList) <= 2);
} else {
	$randomImgHTML = '<p>Ühtegi pilti pole, mida kuvada</p>';
}


// foreach ($allFiles as $file) {
// 	$fileInfo = getimagesize($picsDir . $file);
// 	if (in_array($fileInfo["mime"], $photoTypesAllow)) {
// 		array_push($photoList, $file);
// 	}
// }

// $photoCount = array();
// while (count($photoCount)<3){
// 	$photoCount =mt_rand(0, count($photoList)-1);
// 	if(!in_array($photoNum, $photosCount)) array_push($photoCount, $photoNum);
// }
// $randomImageHTML ="";
// foreach($photoCount as $photoNum) {
// 	$randomImageHTML.="\t" . '<img src=' . $picsDir . $photoList . $photoList[$photoNum] . '"alt="pilt">' . "\n";
// }


// }
//TEISTMOODI KELLAAJAST SÕLTUV VÄRVILAHENDUS
//kellaajast sõltuv värvi osa
// $bgColor = "#FFFFFF";
// $txtColor = "#000000";
// if($hourNow > 21 or $hourNow < 7){
// 	$bgColor = "#191970";
// 	$txtColor = "#F5FFFA";
// } elseif ($hourNow >= 7 and $hourNow < 12){
// 	$bgColor = "#FFFFE0";
// 	$txtColor = "#191970";
// } elseif ($hourNow >= 12 and $hourNow < 18){
// 	$bgColor= "#FAF0E6";
// 	$txtColor = "#191970";
// } else {
// 	$bgColor = "#778899";
// 	$txtColor = "#FFFFE0";
// }
// $styleHTML = "<style> \n .timeBackground { \n background-color: ";
// $styleHTML .= $bgColor;
// $styleHTML .= "; \n color: ";
// $styleHTML .= $txtColor;
// $styleHTML .= "; \n } \n </style> \n";

// $newsHTML = readNewsPage(2);

$notice = null;
$email = null;
$emailError = null;
$passwordError = null;

if(isset($_POST["login"])){
	if (isset($_POST["email"]) and !empty($_POST["email"])){
	  $email = test_input($_POST["email"]);
	} else {
	  $emailError = "Palun sisesta kasutajatunnusena e-posti aadress!";
	}
  
	if (!isset($_POST["password"]) or strlen($_POST["password"]) < 8){
	  $passwordError = "Palun sisesta parool, vähemalt 8 märki!";
	}
  
	if(empty($emailError) and empty($passwordError)){
	   $notice = signIn($email, $_POST["password"]);
	} else {
		$notice = "Ei saa sisse logida!";
	}
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

	<h2>Logi sisse </h2>
	<form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
    <label>E-mail (kasutajatunnus):</label><br>
	  <input type="email" name="email" value="<?php echo $email; ?>"><span><?php echo $emailError; ?></span><br>
	  <label>Salasõna:</label><br>
	  <input name="password" type="password"><span><?php echo $passwordError; ?></span><br>
    <input name="login" type="submit" value="Logi sisse"><?php echo $notice; ?></span>
  </form>
	<h1><?php echo $myname; ?></h1>
	<p>See leht on valminud! õppetöö raames!</p>
	<p>Loo endale <a href="newuser.php">kasutajakonto</a>!</p>

	<?php
	echo $timeHTML;
	echo $partOfDayHTML;
	echo $semesterProgressHTML;
	echo $randomImgHTML;
	echo ('<div class="uudised">' . "\n");
    echo ("<h1>Uudised</h1> \n");
    echo readNewsPage(1);
    echo ("</div> \n");


	?>
</body>
<footer>
<div class="container text-center darkcolor" id="footer" style="margin-bottom:0">
<p>copyright ©Annika 2020</p>
</div>
</footer>

</html>