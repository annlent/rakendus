<?php
	require("../../../../configuration.php");
	
	//sessiooni käivitamine või kasutamine
	//session_start();
	//var_dump($_SESSION);
	require("classes/Session.class.php");
	SessionManager::sessionStart("vr20", 0, "/~annika.lentso/", "tigu.hk.tlu.ee");
	
	//kas pole sisseloginud
	if(!isset($_SESSION["userid"])){
		//jõuga avalehele
		header("Location: page.php");
	}
	
	//login välja
	if(isset($_GET["logout"])){
		session_destroy();
		header("Location: page.php");
	}
	
	/* require("fnc_news.php");
	
	$newsHTML = readNewsPage(5); */
?>
<!DOCTYPE html>
<html lang="et">
<head>
	<meta charset="utf-8">
	<title>Veebirakendused ja nende loomine 2020</title>
</head>
<body>
	<h1>Mingi kodukas</h1>
	<p>Ahoi,  <?php echo $_SESSION["userFirstName"] . " " .$_SESSION["userLastName"]; ?></p>
	<p>See leht on valminud õppetöö raames</p>
	<p>Logi <a href="?logout=1">välja</a>!</p>
    <hr>
	
</body>
</html>