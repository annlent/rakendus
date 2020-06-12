<?php

	
	//sessiooni käivitamine või kasutamine
	//session_start();
	//var_dump($_SESSION);
	require("classes/Session.class.php");
	require("../../../../configuration.php");
	require("fnc_gallery.php");
	SessionManager::sessionStart("rakendus", 0, "/~annika.lentso/", "tigu.hk.tlu.ee");

	$backpage = "page.php";
	$page = 1;
	$limit = 8;
	$picCount = countPics(2);
	
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


	
	// $page = 1; //vaikimisi määran lehe numbriks 1 (see on vajalik näiteks siis, kui esimest korda galerii avatakse ja lehtedega pole veel tegeletud)
	// $limit = 10;//mitu pilti ühele lehele soovin mahutada. Reaalelus oleks normaalne palju suurem number, näiteks 30 jne
	// $picCount = countPics(2);//küsin kõigi näidatavate piltide arvu, et teada, palju lehekülgi üldse olla võiks. Parameetriks piltide privaatsus. Funktsioon ise näitena allpool.
	// //echo $picCount;
	// //kui nüüd tuli ka lehe aadressis GET meetodil parameeter page, siis kontrollin, kas see on reaalne ja, kui pole, siis pane jõuga lehe numbriks 1 või viimase võimaliku lehe numbri
	if(!isset($_GET["page"]) or $_GET["page"] < 1){
	  $page = 1;
	} elseif(round($_GET["page"] - 1) * $limit >= $picCount){
	  $page = ceil($picCount / $limit);
	}	else {
	  $page = $_GET["page"];
	}
	
	$gallery = readAllSemiPublicPictureThumbs();
?>
<!DOCTYPE html>
<html lang="et">
<head>
	<meta charset="utf-8">
	<title>Veebirakendused ja nende loomine 2020</title>
</head>
<body>
	<h1>Kasutajate avaldatavad pildid</h1>
	<p>See leht on valminud õppetöö raames</p>
	<p><?php echo $_SESSION["userFirstName"]. " " .$_SESSION["userLastName"] ."."; ?> Logi <a href="?logout=1">välja</a></p>
	<p>Tagasi <a href="home.php">avalehele</a>!</p>
	<hr>
	<div id="modalArea" class="modalArea">
	<!--Sulgemisnupp-->
	<span id="modalClose" class="modalClose">&times;</span>
	<!--pildikoht-->
	<div class="modalHorizontal">
		<div class="modalVertical">
		<p id="modalCaption">Ilus pilt</p>
			<img src="empty.png" id="modalImg" class="modalImg" alt="galeriipilt">
			
		</div>
	</div>
  </div>  

    <div class="gallery" id="gallery">
		<?php echo $gallery; ?>
	</div>
	<hr>
</body>
</html>