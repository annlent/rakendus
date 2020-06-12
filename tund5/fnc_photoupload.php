<?php

	function addPhotoData($fileName, $alt, $privacy, $origName){
		$notice = null;
		$conn = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUserName"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
		$stmt = $conn->prepare("INSERT INTO vr20_photos (userid, filename, alttext, privacy, origname) VALUES (?, ?, ?, ?, ?)");
		echo $conn->error;
		$stmt->bind_param("issis", $_SESSION["userid"], $fileName, $alt, $privacy, $origName);
		if($stmt->execute()){
		  $notice = 1;
		} else {
		  $notice = $stmt->error;
		}
		
		$stmt->close();
		$conn->close();
		return $notice;
	}
	 
	function resizePhoto($src, $w, $h, $keepOrigProportion = true){
		$imageW = imagesx($src);
		$imageH = imagesy($src);
		$newW = $w;
		$newH = $h;
		$cutX = 0;
		$cutY = 0;
		$cutSizeW = $imageW;
		$cutSizeH = $imageH;
		
		if($w == $h){
			if($imageW > $imageH){
				$cutSizeW = $imageH;
				$cutX = round(($imageW - $cutSizeW) / 2);
			} else {
				$cutSizeH = $imageW;
				$cutY = round(($imageH - $cutSizeH) / 2);
			}	
		} elseif($keepOrigProportion){//kui tuleb originaaproportsioone säilitada
			if($imageW / $w > $imageH / $h){
				$newH = round($imageH / ($imageW / $w));
			} else {
				$newW = round($imageW / ($imageH / $h));
			}
		} else { //kui on vaja kindlasti etteantud suurust, ehk pisut ka kärpida
			if($imageW / $w < $imageH / $h){
				$cutSizeH = round($imageW / $w * $h);
				$cutY = round(($imageH - $cutSizeH) / 2);
			} else {
				$cutSizeW = round($imageH / $h * $w);
				$cutX = round(($imageW - $cutSizeW) / 2);
			}
		}
		
		//loome uue ajutise pildiobjekti
		$myNewImage = imagecreatetruecolor($newW, $newH);
		
		//kui on läbipaistvusega png pildid, siis on vaja säilitada läbipaistvusega
	    imagesavealpha($myNewImage, true);
	    $transColor = imagecolorallocatealpha($myNewImage, 0, 0, 0, 127);
	    imagefill($myNewImage, 0, 0, $transColor);
		imagecopyresampled($myNewImage, $src, 0, 0, $cutX, $cutY, $newW, $newH, $cutSizeW, $cutSizeH);
		return $myNewImage;
	}
	// Link image type to correct image loader and saver
// - makes it easier to add additional types later on
// - makes the function easier to read
// const IMAGE_HANDLERS = [
//     IMAGETYPE_JPEG => [
//         'load' => 'imagecreatefromjpeg',
//         'save' => 'imagejpeg',
//         'quality' => 100
//     ],
//     IMAGETYPE_PNG => [
//         'load' => 'imagecreatefrompng',
//         'save' => 'imagepng',
//         'quality' => 0
//     ],
//     IMAGETYPE_GIF => [
//         'load' => 'imagecreatefromgif',
//         'save' => 'imagegif'
//     ]
// ];

// 	function createThumbnail($src, $dest, $targetWidth, $targetHeight = null) {

// 		// 1)lae pilt alla soovitud kohast ($src) -> kas eksisteerib (pilt), kas on õige tüüp, laadi üles

	
// 		// otsi pildi tüüp
// 		// we need the type to determine the correct loader
// 		$type = exif_imagetype($src);
	
// 	// kui ühtegi korrektset tüüpi v handlerit ei ole, siis exit
// 		if (!$type || !IMAGE_HANDLERS[$type]) {
// 			return null;
// 		}
	
// 	// 	// laadi pilt korrektselt üles
// 		$image = call_user_func(IMAGE_HANDLERS[$type]['load'], $src);
	
// 	// 	// kui pakutud asukohas pilti ei ole siis -> exit
// 		if (!$image) {
// 			return null;	
// 		}
// 	// 	// 2. Loo pöidlapilt ja lae vähendatud pilt( $image) üles (leia pildi suurus, defineeri väljundi suurus, loo pöidlapilt sellest suurusest lähtuvalt, sea gifidele ja pngdele alpha transparency, joonista viimane versiooni pöidlapildist)
	
// 	// 	// originaal oma  suuruses ja laiusega
// 		$width = imagesx($image);
// 		$height = imagesy($image);
	
// 		// algselt on loodav kõrgus null
// 		if ($targetHeight == null) {
	
// 			// milline on kõrguse ja laiuse proportsioon
// 			$ratio = $width / $height;
	
// 			// portreemõõdud (püstine a4)
// 			// kasut muutujat $ratio et määrata proportsioonid ja mahtuda ruutu
// 			if ($width > $height) {
// 				$targetHeight = floor($targetWidth / $ratio);
// 			}
// 			// kui landscape (pikali a4)
// 			// kasut muutujat $ratio et määrata proportsioonid ja mahtuda ruutu
// 			else {
// 				$targetHeight = $targetWidth;
// 				$targetWidth = floor($targetWidth * $ratio);
// 			}
// 		}
	
// 	// 	// teeme pildist duplikaadi eelnevalt leitud suuruse järgi
// 		$thumbnail = imagecreatetruecolor($targetWidth, $targetHeight);
	
	// 	// gifidele ja pngdele läbipaistvuse sätted
	// 	if ($type == IMAGETYPE_GIF || $type == IMAGETYPE_PNG) {
	
	// 		// pilt läbipaistvaks
	// 		imagecolortransparent(
	// 			$thumbnail,
	// 			imagecolorallocate($thumbnail, 0, 0, 0)
	// 		);
	
	// 		// PNG erikohtlemine
	// 		if ($type == IMAGETYPE_PNG) {
	// 			imagealphablending($thumbnail, false);
	// 			imagesavealpha($thumbnail, true);
	// 		}
		// }
	
	// 	// kopeerimine terve pildi, et saaksime muuta selle proportsioone ja muuta selle pisipildiks
	// 	imagecopyresampled(
	// 		$thumbnail,
	// 		$image,
	// 		0, 0, 0, 0,
	// 		$targetWidth, $targetHeight,
	// 		$width, $height
	// 	);
	

	// // 	// 3) Salvesta pöidlapilt 
		
	// 	$myNewImage = imagecreatetruecolor($targetWidth, $targetHeight);

	// 	// //kui on läbipaistvusega png pildid, siis on vaja säilitada läbipaistvusega
	//     // imagesavealpha($myNewImage, true);
	//     // $transColor = imagecolorallocatealpha($myNewImage, 0, 0, 0, 127);
	//     // imagefill($myNewImage, 0, 0, $transColor);
	// 	// imagecopyresampled($myNewImage, $src, 0, 0, $cutX, $cutY, $newW, $newH, $cutSizeW, $cutSizeH);
	// 	return $myNewImage;
		
	// }	


	
function saveImgToFile($myNewImage, $target, $imageFileType){
		$notice = null;
		if($imageFileType == "jpg"){
			if(imagejpeg($myNewImage, $target, 90)){
				$notice = 1;
			} else {
				$notice = 0;
			}
		}
		if($imageFileType == "png"){
			if(imagepng($myNewImage, $target, 6)){
				$notice = 1;
			} else {
				$notice = 0;
			}

		return $notice;
	}
}