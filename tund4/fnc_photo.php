<?php

require("../../../../configuration.php");
require("main.php");

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
// function createThumbnail($src, $dest, $targetWidth, $targetHeight = null) {

// 	// 1. Load the image from the given $src
// 	// - see if the file actually exists
// 	// - check if it's of a valid image type
// 	// - load the image resource

// 	// get the type of the image
// 	// we need the type to determine the correct loader
// 	$type = exif_imagetype($src);

// 	// if no valid type or no handler found -> exit
// 	if (!$type || !IMAGE_HANDLERS[$type]) {
// 		return null;
// 		}

// 	// load the image with the correct loader
// 	$image = call_user_func(IMAGE_HANDLERS[$type]['load'], $src);

// 	// no image found at supplied location -> exit
// 	if (!$image) {
// 		return null;
// 	}


// 	// 2. Create a thumbnail and resize the loaded $image
// 	// - get the image dimensions
// 	// - define the output size appropriately
// 	// - create a thumbnail based on that size
// 	// - set alpha transparency for GIFs and PNGs
// 	// - draw the final thumbnail

// 	// get original image width and height
// 	$width = imagesx($image);
// 	$height = imagesy($image);

// 	// maintain aspect ratio when no height set
// 	if ($targetHeight == null) {

// 		// get width to height ratio
// 		$ratio = $width / $height;

// 		// if is portrait
// 		// use ratio to scale height to fit in square
// 		if ($width > $height) {
// 			$targetHeight = floor($targetWidth / $ratio);
// 		}
// 		// if is landscape
// 		// use ratio to scale width to fit in square
// 		else {
// 			$targetHeight = $targetWidth;
// 			$targetWidth = floor($targetWidth * $ratio);
// 		}
// 	}

// 	// create duplicate image based on calculated target size
// 	$thumbnail = imagecreatetruecolor($targetWidth, $targetHeight);

// 	// set transparency options for GIFs and PNGs
// 	if ($type == IMAGETYPE_GIF || $type == IMAGETYPE_PNG) {

// 		// make image transparent
// 		imagecolortransparent(
// 			$thumbnail,
// 			imagecolorallocate($thumbnail, 0, 0, 0)
// 		);

// 		// additional settings for PNGs
// 		if ($type == IMAGETYPE_PNG) {
// 			imagealphablending($thumbnail, false);
// 			imagesavealpha($thumbnail, true);
// 		}
// 	}

// 	// copy entire source image to duplicate image and resize
// 	imagecopyresampled(
// 		$thumbnail,
// 		$image,
// 		0, 0, 0, 0,
// 		$targetWidth, $targetHeight,
// 		$width, $height
// 	);


// 	// 3. Save the $thumbnail 
	
// 	$myNewImage = imagecreatetruecolor($newW, $newH);
// 	//kui on läbipaistvusega png pildid, siis on vaja säilitada läbipaistvusega
//     imagesavealpha($myNewImage, true);
//     $transColor = imagecolorallocatealpha($myNewImage, 0, 0, 0, 127);
//     imagefill($myNewImage, 0, 0, $transColor);
// 	imagecopyresampled($myNewImage, $src, 0, 0, $cutX, $cutY, $newW, $newH, $cutSizeW, $cutSizeH);
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
		}
		return $notice;
	}
?>