<?php
	require("../../../../configuration.php");
	require("fnc_photoupload.php");
	require("classes/Photo.class.php");
	require("fnc_main.php");
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
	

	
	//pildi üleslaadimine osa
	
	//var_dump($_POST);
	//var_dump($_FILES);
	
	// $originalPhotoDir = "../../uploadOriginalPhoto/";
	// $normalPhotoDir = "../../normalPhotoUpload/";
	// $thumbnailDir = "../../uploadThumbnail";
	$error = null;
	$notice = null;
	$imageFileType = null;
	$fileUploadSizeLimit = 1048576;
	$allowedFileTypes = ["image/jpeg", "image/png"];
	$fileSizeLimit = 1048576;
	$fileNamePrefix = "vr_";
	$fileName = null;
	$maxWidth = 600;
	$maxHeight = 400;
	$thumbSize = 100;
	
	if(isset($_POST["photoSubmit"]) and !empty($_FILES["fileToUpload"]["tmp_name"])){

		// $originalTarget = $originalPhotoDir .$_FILES["fileToUpload"]["tmp_name"];
		//Kõigepealt testime kas on pilt, kas suurus on, ehk on juba olemas kaustas ning siis kutsume klassi välja
		//kas üldse on pilt?
		// $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
		// if($check !== false){
		// 	//failitüübi väljaselgitamine ja sobivuse kontroll
		// 	if($check["mime"] == "image/jpeg"){
		// 		$imageFileType = "jpg";
		// 	} elseif ($check["mime"] == "image/png"){
		// 		$imageFileType = "png";
		// 	} else {
		// 		$error = "Ainult jpg ja png pildid on lubatud! "; 
		// 	}
		// } else {
		// 	$error = "Valitud fail ei ole pilt! ";
		// }
		
		// //ega pole liiga suur
		// if($_FILES["fileToUpload"]["size"] > $fileUploadSizeLimit){
		// 	$error .= "Valitud fail on liiga suur! ";
		// }
		
		// //loome oma nime failile
		// $timestamp = microtime(1) * 10000;
		// $fileName = $fileNamePrefix . $timestamp . "." .$imageFileType;
		
		// //$originalTarget = $originalPhotoDir .$_FILES["fileToUpload"]["name"];
		// $originalTarget = $originalPhotoDir .$fileName;
		
		// //äkki on fail olemas?
		// if(file_exists($originalTarget)){
		// 	$error .= "Selline fail on juba olemas!";
		// }
		
		//kui vigu pole
		// if($error == null){
		$photoUp = new Photo($_FILES["fileToUpload"], $fileUploadSizeLimit, $allowedFileTypes);
		
		if ($photoUp->error == null) {

			$photoUp->createFileName($fileNamePrefix);
	
			$photoUp->resizePhoto($maxWidth, $maxHeight);
	
			//lisan vesimĆ¤rgi
			$photoUp->addWatermark("vr_watermark.png", 3, 10);
	
			//loen EXIF
			$photoUp->readExif();
			if ($photoUp->photoDate != null) {
				$size = 14;
				$y = 20;
				$textToImage = "Pildistatud " . $photoUp->photoDate;
				$photoUp->addText($size, $y, $textToImage);
			} else {
				echo "Pildistamiskuupäev pole teada!";
			}
			// $sizeCheck = $photoUp->checkPhotoSize(); //klasssis defineeritud funktsiooni suuruse hindamiseks
			
			// if($sizeCheck == 0) {
			// 	$error = "Valitud pilt on liiga suur!";
			// }
			// //teen pildi väiksemaks
			// if($imageFileType == "jpg"){
			// 	$myTempImage = imagecreatefromjpeg($_FILES["fileToUpload"]["tmp_name"]);
			// }
			// if($imageFileType == "png"){
			// 	$myTempImage = imagecreatefrompng($_FILES["fileToUpload"]["tmp_name"]);
			// } 
			
			// $myNewImage = resizePhoto($myTempImage, $maxWidth, $maxHeight);
			// $photoUp->resizePhoto($maxWidth, $maxHeight);
			
			$result = $photoUp->saveImgToFile($normalPhotoDir . $photoUp->fileName);
			if ($result == 1) {
				$notice = "Vähendatud pilt laeti üles! ";
			} else {
				$error = "Vähendatud pildi salvestamisel tekkis viga!";
			}
	
			$photoUp->resizePhoto($thumbSize, $thumbSize);
			$result = $photoUp->saveImgToFile($thumbnailDir . $photoUp->fileName);
			if ($result == 1) {
				$notice .= "Pisipilt laeti üles! ";
			} else {
				$error .= " Pisipildi salvestamisel tekkis viga!";
			}
			
			//$result = saveImgToFile($photoUp->myNewImage, $normalPhotoDir .$fileName, $imageFileType);
			// $result = saveImgToFile($photoUp->myNewImage, $normalPhotoDir .$fileName, $imageFileType);
			// if($result == 1) {
			// 	$notice = "Vähendatud pilt laeti üles! ";
			// } else {
			// 	$error = "Vähendatud pildi salvestamisel tekkis viga!";
			// }
			
			//$photoUp->resizePhoto($thumbSize, $thumbSize);
						
			//lõpetame vähendatud pildiga ja teeme thumbnail'i
			/* imageDestroy($myNewImage); */
			// $myNewImage = resizePhoto($myTempImage, $thumbSize, $thumbSize);
			// $photoUp->resizePhoto($thumbSize, $thumbSize);
			// $result = $photoUp->saveImgToFile($thumbnailDir .$fileName);
			//enne: $result = saveImgToFile($photoUp->myNewImage, $thumbPhotoDir .$fileName, $imageFileType);

			//$result = $photoUp->saveImgToFile($thumbPhotoDir .$photoUp->fileName);
			// if($result == 1) {
			// 	$notice = "Pisipilt laeti üles! ";
			// } else {
			// 	$error .= " Pisipildi salvestamisel tekkis viga!";
			// }
			
			/* imageDestroy($myNewImage);
			imagedestroy($myTempImage); */
			// $photoUp->addWatermark("vr_watermark.png", 3, 10);
			// unset($photoUp);
			
			// if(move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $originalTarget)){
			// 	$notice .= "Originaalpilt laeti üles! ";
			// } else {
			// 	$error .= "Pildi üleslaadimisel tekkis viga!";
			// }
			
			//kui kõik hästi, salvestame info andmebaasi!!!
			if($error == null){
				$result = addPhotoData($photoUp->fileName, $_POST["altText"], $_POST["privacy"], $_FILES["fileToUpload"]["name"]);
				if($result == 1){
					$notice .= "Pildi andmed lisati andmebaasi!";
				} else {
					$error .= " Pildi andmete lisamisel andmebaasi tekkis tehniline tõrge: " .$result;
				}
			}

		}	else {
				//1 - pole pildifail, 2 - liiga suur, pole lubatud tüüp
				if($photoUp->error == 1){
					$notice = "Üleslaadimiseks valitud fail pole pilt!";
				}
				if($photoUp->error == 2){
					$notice = "Üleslaadimiseks valitud fail on liiga suure failimahuga!";
				}
				if($photoUp->error == 3){
					$notice = "Üleslaadimiseks valitud fail pole lubatud tüüpi (vaid jpg ja png on lubatud)!";
				}
				
			}
			// lisan vesimärgi
		
		//kui vigu pole
		//echo $error;
		unset($photoUp);
		
	}
?>
<!DOCTYPE html>
<html lang="et">
<head>
	<meta charset="utf-8">
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
	<title>Veebirakendused ja nende loomine 2020</title>
</head>
<body>
	<h1>Fotode üleslaadimine</h1>
	<p>See leht on valminud õppetöö raames</p>
	<p><?php echo $_SESSION["userFirstName"]. " " .$_SESSION["userLastName"] ."."; ?> Logi <a class="btn btn-warning" href="?logout=1">välja</a></p>
	<p>Tagasi <a class="btn btn-success" href="home.php">avalehele</a></p>
	<hr>
	
    <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" enctype="multipart/form-data">
		<label>Vali pildifail! </label><br>
		<input type="file" name="fileToUpload"><br>
		<label>Alt tekst: </label><input type="text" name="altText"><br>
		<label>Privaatsus</label><br>
		<label for="priv1">Privaatne</label><input id="priv1" type="radio" name="privacy" value="3" checked><br>
		<label for="priv2">Sisseloginud kasutajatele</label><input id="priv2" type="radio" name="privacy" value="2"><br>
		<label for="priv3">Avalik</label><input id="priv3" type="radio" name="privacy" value="1"><br>
		
		<input type="submit" name="photoSubmit" value="Lae valitud pilt üles!">
		<span><?php echo $error; echo $notice; ?></span>
	</form>
	
	<br>
	<hr>
</body>
</html>