<?php
	require("classes/Session.class.php");
	require("classes/Photo_class.php");
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
	
	require("../../../../configuration.php");
	require("fnc_photo.php");


	
	//pildi üleslaadimine osa
	
	//var_dump($_POST);
	//var_dump($_FILES);
	
	$originalPhotoDir = "../../uploadOriginalPhoto/";
	$normalPhotoDir = "../../normalPhotoUpload/";
	$thumbnailDir = "../../uploadThumbnail";
	$error = null;
	$notice = null;
	$imageFileType = null;
	$fileUploadSizeLimit = 1048576;
	$fileNamePrefix = "vr_";
	$fileName = null;
	$maxWidth = 600;
	$maxHeight = 400;
	$thumbSize = 100;
	
	if(isset($_POST["photoSubmit"])){//  and !empty($_FILES["fileToUpload"]["tmp_name"])){
		$originalTarget = $originalPhotoDir .$_FILES["fileToUpload"]["tmp_name"]; //konfifail. paneb faili tigu kataloogi
		//	move_upload_file($_FILES["fileToUpload"]["tmp_name"], $originalTarget); //ajutine 
			//Pâriselus peaks küsima serveri haldajalt 6iguseid
		
		//kas üldse on pilt?
		$check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);//check on järjend
		if($check !== false){
			//failitüübi väljaselgitamine ja sobivuse kontroll
			if($check["mime"] == "image/jpeg"){
				$imageFileType = "jpg";
			} elseif ($check["mime"] == "image/png"){ //mime = multipurpose internet mail extensions
				$imageFileType = "png";
			} else {
				$error = "Ainult jpg ja png pildid on lubatud! "; 
			}
		} else {
			$error = "Valitud fail ei ole pilt! ";
		}
		
		//ega pole liiga suur
		if($_FILES["fileToUpload"]["size"] > $fileUploadSizeLimit){
			$error .= "Valitud fail on liiga suur! ";
		}
		
		//loome failile oma nime 
		$timestamp = microtime(1) * 10000;
		$fileName = $fileNamePrefix . $timestamp . "." .$imageFileType; //lisab failile nn ajatempli, st saame vr_+aeg.jpg/png
		
		//$originalTarget = $originalPhotoDir .$_FILES["fileToUpload"]["name"];
		$originalTarget = $originalPhotoDir .$fileName;
		
		//äkki on fail olemas?
		if(file_exists($originalTarget)){
			$error .= "Selline fail on juba olemas!";
		}
		
	//kui vigu pole
	if($error == null){
		$photoUp = new Photo($_FILES["fileToUpload"], $imageFileType);
		
		// if($imageFileType == "jpg"){
		// 	$myTempImage = imagecreatefromjpeg($_FILES["fileToUpload"]["tmp_name"]);
		// }
		// if($imageFileType == "png"){
		// 	$myTempImage = imagecreatefrompng($_FILES["fileToUpload"]["tmp_name"]);
		// }
			
		// $imageW = imagesx($myTempImage);
		// $imageH = imagesy($myTempImage);
			
		// if($imageW / $maxWidth > $imageH / $maxHeight){
		// 	$imageSizeRatio = $imageW / $maxWidth;
		// } else {
		// 	$imageSizeRatio = $imageH / $maxHeight;
		// }
			
		// $newW = round($imageW / $imageSizeRatio);
		// $newH = round($imageH / $imageSizeRatio);
			
			// //teen pildi väiksemaks
			// if($imageFileType == "jpg"){
			// 	$myTempImage = imagecreatefromjpeg($_FILES["fileToUpload"]["tmp_name"]);
			// }
			// if($imageFileType == "png"){
			// 	$myTempImage = imagecreatefrompng($_FILES["fileToUpload"]["tmp_name"]);
			// } 
			
			// $myNewImage = resizePhoto($myTempImage, $maxWidth, $maxHeight);
			
			// //salvestame vähendatud kujutise faili
			// if($imageFileType == "jpg"){
			// 	if(imagejpeg($myNewImage, $normalPhotoDir .$fileName, 90)){
			// 		$notice = "Pisipilt laeti üles! ";
			// 	} else {
			// 		$error = "Pisipildi salvestamisel tekkis viga!";
			// 	}
			// }
			// if($imageFileType == "png"){
			// 	if(imagepng($myNewImage, $normalPhotoDir .$fileName, 6)){
			// 		$notice = "Pisipilt laeti üles! ";
			// 	} else {
			// 		$error = "Pisipildi salvestamisel tekkis viga!";
			// 	}
			// }
			// if(move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $originalTarget)){
			// 	$notice .= "Originaalpilt laeti üles!";
			// } else {
			// 	$error .= " Pildi üleslaadimisel tekkis viga!";
			// }
			
			// imagedestroy($myTempImage);
			// imageDestroy($myNewImage);
			

			//minu vana kood
			 $photoUp->resizePhoto($maxWidth, $maxHeight);
			
			$result = saveImgToFile($photoUp->myNewImage, $normalPhotoDir .$fileName, $imageFileType);
			if($result == 1) {
				$notice = "Vähendatud pilt laeti üles! ";
			} else {
				$error = "Vähendatud pildi salvestamisel tekkis viga!";
			}
			
			$photoUp->resizePhoto($thumbSize, $thumbSize);

						
			//lõpetame vähendatud pildiga ja teeme thumbnail'i
			/* imageDestroy($myNewImage);
			// $myNewImage = resizePhoto($myTempImage, $thumbSize, $thumbSize); */
			$result = $photoUp->saveImgToFile($thumbnailDir .$fileName);
			if($result == 1) {
				$notice = "Pisipilt laeti üles! ";
			} else {
				$error .= " Pisipildi salvestamisel tekkis viga!";
			}
			
			// /* imageDestroy($myNewImage);
			// imagedestroy($myTempImage); */
			unset($photoUp);
			
			if(move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $originalTarget)){
				$notice .= "Originaalpilt laeti üles! ";
			} else {
				$error .= " Pildi üleslaadimisel tekkis viga!";
			}
			
			// //kui kõik hästi, salvestame info andmebaasi!!!
			if($error == null){
				$result = addPhotoData($fileName, $_POST["altText"], $_POST["privacy"], $_FILES["fileToUpload"]["name"]);
				if($result == 1){
					$notice .= "Pildi andmed lisati andmebaasi!";
				} else {
					$error .= " Pildi andmete lisamisel andmebaasi tekkis tehniline tõrge: " .$result;
				}
			}
			
			
		}//kui vigu pole
	}
?>
<!DOCTYPE html>
<html lang="et">
<head>
	<meta charset="utf-8">
	<title>Veebirakendused ja nende loomine 2020</title>
</head>
<body>
	<h1>Fotode üleslaadimine</h1>
	<p>See leht on valminud õppetöö raames</p>
	<p><?php echo $_SESSION["userFirstName"]. " " .$_SESSION["userLastName"] ."."; ?> Logi <a href="?logout=1">välja</a>!</p>
	<p>Tagasi <a href="home.php">avalehele</a>!</p>
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