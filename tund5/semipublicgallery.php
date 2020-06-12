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
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
	<title>Veebirakendused ja nende loomine 2020</title>
</head>
<body>
	<h1>Kasutajate avaldatavad pildid</h1>
	<p>See leht on valminud õppetöö raames</p>
	<p><?php echo $_SESSION["userFirstName"]. " " .$_SESSION["userLastName"] ."."; ?> Logi <a class="btn btn-info" href="?logout=1">välja</a></p>
	<h1 class="font-weight-light text-center text-lg-left mt-4 mb-0" style="color: #ffffff;">Kasutajate fotod</h1>
	<p>Tagasi <a class="btn btn-info" href="home.php">avalehele</a>!</p>
	<link rel="stylesheet" type="text/css" href="style/gallery.css">
    <link rel="stylesheet" type="text/css" href="style/modal.css">
    <script src="javascript/modal.js" defer></script>
	<div id="modalArea" class="modalArea">
		<!--Sulgemisnupp-->
		<span id="modalClose" class="modalClose">&times;</span>
		<!--pildikoht-->
		<div class="modalHorizontal">
			<div class="modalVertical">
				<p id="modalCaption"></p>
				<img src="empty.png" id="modalImg" class="modalImg" alt="galeriipilt">

				<br>
				<div id="rating" class="modalRating">
					<label><input id="rate1" name="rating" type="radio" value="1">1</label>
					<label><input id="rate2" name="rating" type="radio" value="2">2</label>
					<label><input id="rate3" name="rating" type="radio" value="3">3</label>
					<label><input id="rate4" name="rating" type="radio" value="4">4</label>
					<label><input id="rate5" name="rating" type="radio" value="5">5</label>
					<button id="storeRating">Salvesta hinnang</button>
					<br>
					<p id="avgRating"></p>
					<button id="DeletePic"> Kustuta</button>
					<p id="DeleteConfirmation"></p>
				</div>
			</div>
		</div>
	</div>

        </div>
            <?php
            if ($page > 1) {
                echo '<a href="?page=' . ($page - 1) . '">Eelmine</a> | ';
            } else {
                echo "<span>Eelmine</span> | ";
            }
            if (($page + 1) * $limit <= $picCount) {
                echo '<a href="?page=' . ($page + 1) . '">Järgmine</a>';
            } else {
                echo "<span> Järgmine</span>";
            }
            ?>

            <hr class="mt-2 mb-5">

            <div class="row text-center text-lg-left">

    <div class="gallery" id="gallery">
		<?php echo $gallery; ?>
	</div>

	<hr>
    <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
</body>
</html>