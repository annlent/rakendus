<?php
	require("../../../../configuration.php");
	require("fnc_news.php");
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


	
	$newsHTML = readNewsPage(5);
?>
<!DOCTYPE html>
<html lang="et">
<head>
	<meta charset="utf-8">
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
	<title>Veebirakendused ja nende loomine 2020</title>
</head>
<body>
	<h1>Uudised</h1>
	<p>See leht on valminud õppetöö raames</p>
	<p><?php echo $_SESSION["userFirstName"]. " " .$_SESSION["userLastName"] ."."; ?> Logi <a class="btn btn-warning" href="?logout=1">välja</a></p>
	<p>Tagasi <a class="btn btn-success" href="home.php">avalehele</a></p>
	<hr>
    <div>
		<?php echo $newsHTML; ?>
	</div>
	<hr>
</body>
</html>