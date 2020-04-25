<?php
require("../../../../configuration.php");
require("fnc_news.php");

$newsTitle = null;
$newsContent = null;
$newsError = null;

//function test_input($data) {
//	$data = trim($data);
//	$data = stripslashes($data);
//	$data = htmlspecialchars($data);
//	return $data;
//}

if (isset($_POST["newsBtn"])) {
//isset funktsioon vaatab kas muutuja on üleüldse olemas (ei võrdu NULLiga). Fct on true, kui muutuja on olemas ja false kui ei ole
	if (isset($_POST["newsTitle"]) and !empty(test_input($_POST["newsTitle"]))) {
		$newsTitle = test_input($_POST["newsTitle"]);

	} else {
		$newsError = "Uudise pealkiri on puudu! ";
	}

	if (isset($_POST["newsEditor"]) and !empty(test_input($_POST["newsEditor"]))) {
		$newsContent = test_input($_POST["newsEditor"]);
	} else {
		$newsError .= "Uudise sisu on puudu! "; 

	}
	//saadame uudised andmebaasi

	if (empty($newsError)) {

		$response = saveNews($newsTitle, $newsContent);

		if ($response == 1) {
			$newsError = "Uudis on salvestet! ";

		} else {
			$newsError = "Uudise salvestamisel tekkis viga! ";
		}
	}
}

?>
<!DOCTYPE html>
<html lang="et">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0"> <!-- meta funktsioon ütleb brauserile mismoodi peab sisu kuvama-->
	<!-- wisth=device-width ütleb seda, et kuvataks lähtuvalt seadmest ning esmane suurus määratakse brauseris kuva järgi (initial-scale=1.0)-->
	<title>Veebirakendused ja nende loomine 2020</title>

</head>

<body>

<div class ="container" style="max-width: 50%; margin-top:90px;">

	<section class="text-left">
		<h1 class="jumbotron-heading">Uudise lisamine</h1> <!--jumbotron on selleks, et täiendavat tähelepanu tõmmata (paneme teksti kasti sisse)-->

		

	<p class="lead text-warning"> See leht on valminud õppetöö raames!</p>
	<br>
	</section>


	<form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
<!-- htmlspecialchars($_SERVER["PHP_SELF"]) kasutame selleks, et vältida serverisse pahatahtlikke tegelaste tekkimist
htmspecialchars funtsioon muudav spec muutujad (charasters) HTML sisenditeks. Kui kasutaja püüab PHP_SELF muutujat pahasti kasutada, ei lähe see tal läbi.-->
	<label> Uudise pealkiri:</label>
		<br>
	
		<input type= "text" class ="form-control" name = "newsTitle" placeholder ="Uudise pealkiri" value="<?php echo $newsTitle; ?>">  
		<br>

		<label> Uudise sisu</label>
		<br>

		<textarea class= "form-control" name="newsEditor" placeholder ="It's corona-time! " rows="6" cols="40"><?php echo $newsContent;?></textarea>
    	<br>
		<input type="submit" class="btn btn-success" name="newsBtn" Value="Salvesta uudis! ">
		<!-- bootstrapis on võimalik erinevas stiilis nuppe vormistada ja .btn-success on üks võimalustest https://www.w3schools.com/bootstrap/bootstrap_buttons.asp--> 
		<span><?php echo $newsError; ?></span>
	</form>


</body>

</html>