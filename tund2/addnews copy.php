<?php
require("../../../configuration.php");
require("fnc_news.php");

$newsTitle = null;
$newsContent = null;
$newsError = null;


function test_input($data) {
  $data = trim($data);
  $data = stripslashes($data);
  $data = htmlspecialchars($data);
  return $data;
}

if (isset($_POST["newsBtn"])) { //isset funktsioon tsekkab kas muutuja on olemas, mis täh et see peab olemas olema ja mitte NULL
	if(isset($_POST["newsTitle"]) and !empty(test_input($_POST["newsTitle"]))) {
		$newsTitle = test_input($_POST["newsTitle"]);
	} else {
		$newsError = "Uudise pealkiri on sisestamata!";
	}


	if(isset($_POST["newsEditor"]) and !empty(test_input($_POST["newsEditor"]))) {
		$newsContent =test_input($_POST["newsEditor"]);
	}

	else {
		$newsError .= "Uudise pealkiri on sisestamata!";
	}

	if (empty($newsError)) {
		$response = saveNews($newsTitle, $newsContent);

		if($response == 1) {
			$newsError = "Uudis on salvestatud!";
		} else {
			$newsError = "Uudise salvestamisel tekkis tõrge!";
		}
	}
	
	//echo $newsTitle ."\n";
	//echo $newsContent;
}
?>


<!DOCTYPE html>

<html lang="et">


<head>
	<meta charset="utf-8">

	<title>Veebirakendused ja nende loomine 2020</title>

</head>

<body>
	<h1>Uudise lisamine</h1>
	<p>See leht on valminud õppetöö raames!</p>
	<p>Loo endale <a href="newuser.php">kasutajakonto</a> </p>

	<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
	<label> Uudise pealkiri</label>
	<input type= "text" name = "newsTitle" placeholder ="Uudise pealkiri"> <br>
	<label> Uudise sisu</label>
	<textarea name="nameEditor" placeholder ="Uudis"><?php echo $newsContent;?></textarea>
    <br>
	<input type="submit" name="newsBtn" Value="Salvesta uudis!">
	<span><?php echo $newsError;?></span>
</form>
</body>
</html>
