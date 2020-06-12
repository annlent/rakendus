<?php
	require("../../../../configuration.php");
	require("fnc_news.php");
	require("fnc_user.php");
	
	//sessiooni käivitamine või kasutamine
	//session_start();
	//var_dump($_SESSION);
	require("classes/Session.class.php");
	SessionManager::sessionStart("rakendus", 0, "/~annika.lentso/", "tigu.hk.tlu.ee");
	
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
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
	<title>Veebirakendused ja nende loomine 2020</title>
</head>
<body>
	<h1>Meie lombakas koduleht</h1>
	<p>Tere! <?php echo $_SESSION["userFirstName"] . " " .$_SESSION["userLastName"]; ?></p>
	<p>See leht on valminud õppetöö raames</p>
	<p>Logi <a class="btn btn-info" href="?logout=1">välja</a></p>
    <hr>
	<h2>Meie süsteemis leiad</h2>
	<ul>
		<li><a href="addnews.php">Uudiste lisamine</a></li>
		<li><a href="news.php">Uudiste lugemine</a></li>
		<li><a href="photoUpload.php">Fotode üleslaadimine</a></li>
		<li><a href="privategallery.php">Mo päevapildid</a></li>
		<li><a href="semipublicgallery.php">Kasutajate päevapildid</a></li>
	</ul>
	<p> Mine <a class="btn btn-info" href=page.php >tagasi </a></p>
</body>
</html>