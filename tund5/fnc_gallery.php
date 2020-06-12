<?php
    function showUserPictures($userid, $page, $limit) {
        $response = null;
        $conn = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
        $stmt = $conn->prepare("SELECT vr20_photos.id, vr20_photos.userid, vr20_photos.filename, vr20_photos.alttext, vr20_users.id, vr20_users.firstname, vr20_users.lastname
                                FROM vr20_photos
                                JOIN vr20_users 
                                ON vr20_photos.userid = vr20_users.id 
                                WHERE vr20_photos.userid=?
                                LIMIT ?,?");
        $stmt->bind_param("iii", $userid, $page, $limit);
        $stmt->bind_result($photoId, $userid, $fileNameFromDB, $altTextFromDB, $idFromDB, $firstNameFromDB, $lastNameFromDB);
        $stmt->execute();
            
        while($stmt->fetch()) {
            $response .= '<div class="grid-item"><a href="' . $GLOBALS["originalPhotoDir"] . $fileNameFromDB . '">' . '<img src="' . $GLOBALS["thumbPhotoDir"] . $fileNameFromDB . '" alt="' . $altTextFromDB . '">' . "</a> \t";
            $response .= '<p>' . $firstNameFromDB . " " . $lastNameFromDB . '</p>';
            $response .= '<p>' . $altTextFromDB . '<p></div>';
            // $response .= '<div class="grid-item">' . '<img src="' . $GLOBALS["thumbPhotoDir"] . $fileNameFromDB . '" alt="' . $altTextFromDB  . ' class="thub" data-fn="' . $fileNameFromDB . '" data-id="'. $photoId . '">' . "</a> \t";
            // $response .= '<p>' . $firstNameFromDB . " " . $lastNameFromDB . '</p>';
            // $response .= '<p>' . $altTextFromDB . '<p></div>';
        }

        if($response == null) {
            $response = "Pildid puuduvad!";
        }

        $stmt->close();
        $conn->close();
        return $response; 
		}

	    function showPublicPictures($privacy, $page, $limit) {
			$response = null;
			$conn = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
			$stmt = $conn->prepare("SELECT vr20_photos.id, vr20_photos.userid, vr20_photos.filename, vr20_photos.alttext, vr20_photos.privacy, vr20_users.id, vr20_users.firstname, vr20_users.lastname 
									FROM vr20_photos 
									JOIN vr20_users 
									ON vr20_photos.privacy = ?
									WHERE deleted is null
									LIMIT ?,?");

			$stmt->bind_param("iii", $privacy, $page, $limit);
			$stmt->bind_result($photoId, $userid, $fileNameFromDB, $altTextFromDB, $privacyFromDB, $idFromDB, $firstNameFromDB, $lastNameFromDB);
			$stmt->execute();
	
			while($stmt->fetch()) {
				$response = '<div class="galleryelement">' ."\n";
				$response .= '<img src="' .$GLOBALS["thumbPhotoDir"] .$fileNameFromDB .'" alt="'.$altTextFromDB .'" class="thumb" data-fn="' .$fileNameFromDB .'" data-id="'. $photoId . '">' ."\n \t \t";
				$response .= "<p>" .$firstNameFromDB ." " .$lastNameFromDB ."</p> \n \t \t";
				// $response.= "<p> Hinne : " . $ratingFromFB . '<p>';
				$response .= "<p>" .$altTextFromDB . "</p>";
				$response .= "</div> \n \t \t";
			}
	
			if($response == null) {
				$response = "Pildid puuduvad!";
			}
	
			$stmt->close();
			$conn->close();
			return $response; 
			}
		

	function readAllMyPictureThumbs() 
	{

		$privacy = 3;
		$finalHTML = "";
		$html = "";
		$conn = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUserName"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
		$stmt = $conn->prepare("SELECT filename, alttext FROM vr20_photos WHERE userid=? AND deleted IS NULL");
		echo $conn->error;
		$stmt->bind_param("i", $_SESSION["userid"]);
		$stmt->bind_result($filenameFromDb, $altFromDb);
		$stmt->execute();
		while($stmt->fetch()){
			$html .= '<a href="' .$GLOBALS["normalPhotoDir"] .$filenameFromDb .'" target="_blank"><img src="' .$GLOBALS["thumbPhotoDir"] .$filenameFromDb .'" alt="'.$altFromDb .'"></a>' ."\n \t \t"; //otsin fotod andmebaasist
		}
		if($html != ""){
			$finalHTML = $html;
		} else {
			$finalHTML = "<p>Kahjuks pilte pole!</p>";
		}
		
		$stmt->close();
		$conn->close();
		return $finalHTML;
	}
	
	function readAllSemiPublicPictureThumbs(){
		$privacy = 2;
		$finalHTML = "";
		$html = "";
		$conn = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUserName"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
		$stmt = $conn->prepare("SELECT filename, alttext FROM vr20_photos WHERE privacy<=? AND deleted IS NULL");
		echo $conn->error;
		$stmt->bind_param("i", $privacy);
		$stmt->bind_result($filenameFromDb, $altFromDb);
		$stmt->execute();
		while($stmt->fetch()){
			$html .= '<div class="galleryelement">' ."\n";
			$html .= '<a href="' .$GLOBALS["normalPhotoDir"] .$filenameFromDb .'" target="_blank"><img src="' .$GLOBALS["thumbPhotoDir"] .$filenameFromDb .'" alt="'.$altFromDb .'" class="thumb"></a>' ."\n \t \t";

		}
		if($html != ""){
			$finalHTML = $html;
		} else {
			$finalHTML = "<p>Kahjuks pilte pole!</p>";
		}
		$stmt->close();
		$conn->close();
		return $finalHTML;
	 }
	 
	function countPics($privacy){
		$notice = null;
		$conn = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUserName"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
		$stmt = $conn->prepare("SELECT COUNT(id) FROM vr20_photos WHERE privacy<=? AND deleted IS NULL");
		echo $conn->error;
		$stmt->bind_param("i", $privacy);
		$stmt->bind_result($count);
		$stmt->execute();
		$stmt->fetch();
		$notice = $count;
		
		$stmt->close();
		$conn->close();
		return $notice;
	}

	function countPrivatePics(){
		$notice = null;
		$privacy = 3;
		$conn = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUserName"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
		$stmt = $conn->prepare("SELECT COUNT(id) FROM vr20_photos WHERE privacy<=? AND userid = ? AND deleted IS NULL");
		echo $conn->error;
		$stmt->bind_param("ii", $privacy, $_SESSION["userid"]);
		$stmt->bind_result($count);
		$stmt->execute();
		$stmt->fetch();
		$notice = $count;
		
		$stmt->close();
		$conn->close();
		return $notice;
	}


