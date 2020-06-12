
<?php
	class Photo {
		private $picToUpload;
		private $imageFileType;
		private $fileSizeLimit;
		private $allowedFileTypes;
		private $timeStamp;
		public $fileName;
		public $error;
		public $photoDate;
		private $myTempImage;
		private $myNewImage;
		
		function __construct($picToUpload, $fileSizeLimit, $allowedFileTypes) 
		{
			$this->picToUpload = $picToUpload;
			$this->error = null;//1 - pole pildifail, 2 - liiga suur, pole lubatud tüüp
			$this->fileSizeLimit = $fileSizeLimit;
			$this->allowedFileTypes = $allowedFileTypes;
			$this->timeStamp = microtime(1) * 10000;
			$this->checkImageForUpload();
			
		}
		
		function __destruct() {
			if(isset($this->myTempImage)){
				imagedestroy($this->myTempImage);
			}
			if(isset($this->myNewImage)){
				@imagedestroy($this->myNewImage);
			}
		}
		
		public function checkPhotoSize() {
            $sizeCheck = null;
            if($_FILES["fileToUpload"]["size"] > $this->fileSizeLimit){
                $sizeCheck = 0;
            } else {
                $sizeCheck = 1;
            }

            return $sizeCheck;
		} 
		
		private function checkImageForUpload(){
			//kas on pilt
			$check = getimagesize($this->picToUpload["tmp_name"]);
			if($check == false){
			  $this->error = 1;
			}
			
			//kas sobiv suurus
			if ($this->error == null and $this->picToUpload["size"] > $this->fileSizeLimit) {
			  $this->error = 2;
			}
			
			if($this->error == null){
				//failitüüp
				if($check["mime"] == "image/jpeg"){
				  $this->imageFileType = "jpg";
				}
				if($check["mime"] == "image/png"){
				  $this->imageFileType = "png";
				}
				if($check["mime"] == "image/gif"){
				  $this->imageFileType = "gif";
				}
				
				//kas lubatud tüüp
				
				//if($this->imageFileType != "jpg" and $this->imageFileType != "png" and $this->imageFileType != "gif" ) {
				if(!in_array($check["mime"], $this->allowedFileTypes)) {
				  $this->error = 3;
				}
			}
			

			//kui kõik sobib, teeme vajaliku pildiobjekti
			if($this->error == null){
			  $this->myTempImage = $this->createImageFromFile($this->picToUpload["tmp_name"], $this->imageFileType);
			}
		  
		}//checkImageForUpload lõpp
		
		private function createImageFromFile($imageFile, $fileType){
			if($fileType == "jpg"){
				$image = imagecreatefromjpeg($imageFile);
			}
			if($fileType == "png"){
				$image = imagecreatefrompng($imageFile);
			}
			return $image;
		}
		
		public function createFileName($fileNamePrefix){ // ei anna otseselt ette eesliidet (see tuleb photoUplod.php)
		 $fileName = $fileNamePrefix .$this->timeStamp ."." .$this->imageFileType;
	    }
		
		public function resizePhoto($w, $h, $keepOrigProportion = true)
		{
		$imageW = imagesx($this->myTempImage);
		$imageH = imagesy($this->myTempImage);
		$newW = $w;
		$newH = $h;
		$cutX = 0;
		$cutY = 0;
		$cutSizeW = $imageW;
			$cutSizeH = $imageH;
			
		// if($w == $h){
		// 	if($imageW > $imageH){
		// 		$cutSizeW = $imageH;
		// 		$cutX = round(($imageW - $cutSizeW) / 2);
		// 	} else {
		// 		$cutSizeH = $imageW;
		// 		$cutY = round(($imageH - $cutSizeH) / 2);
		// 	}	
		// } elseif($keepOrigProportion){//kui tuleb originaalproportsioone säilitada
		// 	if($imageW / $w > $imageH / $h){
		// 		$newH = round($imageH / ($imageW / $w));
		// 	} else {
		// 		$newW = round($imageW / ($imageH / $h));
		// 	}
		// } else { //kui on vaja kindlasti etteantud suurust, ehk pisut ka kärpida
		// 	if($imageW / $w < $imageH / $h){
		// 		$cutSizeH = round($imageW / $w * $h);
		// 		$cutY = round(($imageH - $cutSizeH) / 2);
		// 	} else {
		// 		$cutSizeW = round($imageH / $h * $w);
		// 		$cutX = round(($imageW - $cutSizeW) / 2);
		// 	}
		// }
			
			//loome uue ajutise pildiobjekti
			$this->myNewImage = imagecreatetruecolor($newW, $newH);
			//kui on läbipaistvusega png pildid, siis on vaja säilitada läbipaistvusega
			imagesavealpha($this->myNewImage, true);
			$transColor = imagecolorallocatealpha($this->myNewImage, 0, 0, 0, 127);
			imagefill($this->myNewImage, 0, 0, $transColor);
			imagecopyresampled($this->myNewImage, $this->myTempImage, 0, 0, $cutX, $cutY, $newW, $newH, $cutSizeW, $cutSizeH);
		}
				
		public function addWatermark($wmFile, $wmLocation, $fromEdge)
		{
			$wmFileType = strtolower(pathinfo($wmFile,PATHINFO_EXTENSION));
			//$waterMark = imagecreatefrompng($wmFile);
		  $waterMark = $this->createImageFromFile($wmFile, $wmFileType);
		  $waterMarkW = imagesx($waterMark); //see on php enda funktsioon ja leiab pildi laiuse
		  $waterMarkH = imagesy($waterMark);
		  if($wmLocation == 1 or $wmLocation == 4){
			  $waterMarkX = $fromEdge;
		  }
		  if($wmLocation == 2 or $wmLocation == 3){
			  $waterMarkX = imagesx($this->myNewImage) - $waterMarkW - $fromEdge;
		  }
		  if($wmLocation == 1 or $wmLocation == 2){
			  $waterMarkY = $fromEdge;
		  }
		  if($wmLocation == 3 or $wmLocation == 4){
			  $waterMarkY = imagesy($this->myNewImage) - $waterMarkH - $fromEdge;
		  }
		  if($wmLocation == 5){
			  $waterMarkX = round((imagesx($this->myNewImage) - $waterMarkW) / 2, 0);
			  $waterMarkY = round((imagesy($this->myNewImage) - $waterMarkH) / 2, 0);
		  }
			imagecopy($this->myNewImage, $waterMark, $waterMarkX, $waterMarkY, 0, 0, $waterMarkW, $waterMarkH);
		}//addWatermark lõppeb 
		
		public function readExif(){
			// kõige ees on "@" märk, et vältida hoiatust
			@$exif = exif_read_data($this->picToUpload["tmp_name"], "ANY_TAG", 0, true);
			//var_dump($exif);
			//echo $exif["DateTimeOriginal"];
			if(!empty($exif["DateTimeOriginal"])){
				$this->photoDate = $exif["DateTimeOriginal"];
			} else {
				$this->photoDate = NULL;
			}
		}
		
		public function addText($size, $y, $textToImage){
			$textColor = imagecolorallocatealpha($this->myNewImage, 0,102,255, 60);//valge, 60% alpha
			//Kirjutatakse tekst pildile, parameetriteks: pildiobjekt, teksti suurus (näiteks 14), , nurk (teksti saab kaldu panna), x-koordinaat, y-koordinaat, teksti värv (eelnevalt defineeritud), TTF-fondi url, kirjutatav tekst
			imagettftext($this->myNewImage, $size, 0, 10, $y, $textColor, "./Soria.ttf", $textToImage);
		}
		
		public function saveImgToFile($target){
			$notice = null;
			if($this->imageFileType == "jpg"){
				if(imagejpeg($this->myNewImage, $target, 90)){
					$notice = 1;
				} else {
					$notice = 0;
				}
			}
			if($this->imageFileType == "png"){
				if(imagepng($this->myNewImage, $target, 6)){
					$notice = 1;
				} else {
					$notice = 0;
				}
			}
			if($this->imageFileType == "gif"){
				if(imagepng($this->myNewImage, $target)){
					$notice = 1;
				} else {
					$notice = 0;
				}
			}
			imagedestroy($this->myNewImage);
			return $notice;
		}
		
		public function saveOriginal($target){
			$notice = null;
			if (move_uploaded_file($this->picToUpload["tmp_name"], $target)) {
				$notice = "Originaalfaili salvestamine õnnestus!";
			} else {
				$notice = "Originaalfaili salvestamine ei õnnestunud!";
			}
			return $notice;
		}
		
	}//klass lõppeb