<?php
	function saveNews($newsTitle, $newsContent){
		$response = null;
		//loon andmebaasiühenduse
		$conn = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUserName"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
		//valmistan ette SQL päringu
		$stmt = $conn->prepare("INSERT INTO vr20_news (userid, title, content) VALUES (?, ?, ?)");
		echo $conn->error;
		//seon päringuga tegelikud andmed
		//$userid = 1;
		//i -integer  s - string d - decimal
		$stmt->bind_param("iss", $_SESSION["userid"], $newsTitle, $newsContent);
		if($stmt->execute()){
			$response = 1;
		} else {
			$response = 0;
			echo $stmt->error;
		}
		//sulgen päringu ja andmebaasiühenduse
		$stmt->close();
		$conn->close();
		return $response;
	}

	function readNewsPage($limit){
		if($limit == null){
			$limit = 1;
		}
		$response = null;
		//loon andmebaasiühenduse
		$conn = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUserName"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
		//$stmt = $conn->prepare("SELECT title, content FROM vr20_news");
		$stmt = $conn->prepare("SELECT title, content, created FROM vr20_news WHERE deleted IS NULL ORDER BY id DESC LIMIT ?");
		echo $conn->error;
		$stmt->bind_param("i", $limit);
		$stmt->bind_result($titleFromDB, $contentFromDB, $createdFromDB);
		$stmt->execute();
		//if($stmt->fetch())
		//<h2>uudisepealkiri</h2>
		//<p>uudis</p>
		while ($stmt->fetch()){
			$addedDate = new DateTime($createdFromDB);
			$response .= "<h3>" .$titleFromDB ."</h3> \n";
			$response .= "<p>Lisatud: " .$addedDate->format("d.m.Y H:i:s") ."</p> \n";
			$response .= "<p>" .$contentFromDB ."</p> \n";
		}
		if($response == null){
			$response = "<p>Kahjuks uudised puuduvad!</p> \n";
		}
		
		//sulgen päringu ja andmebaasiühenduse
		$stmt->close();
		$conn->close();
		return $response;
	}
	
	function readNews(){
		$response = null;
		//loon andmebaasiühenduse
		$conn = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUserName"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
		$stmt = $conn->prepare("SELECT title, content FROM vr20_news");
		echo $conn->error;
		$stmt->bind_result($titleFromDB, $contentFromDB);
		$stmt->execute();
		//if($stmt->fetch())
		//<h2>uudisepealkiri</h2>
		//<p>uudis</p>
		while ($stmt->fetch()){
			$response .= "<h2>" .$titleFromDB ."</h2> \n";
			$response .= "<p>" .$contentFromDB ."</p> \n";
		}
		if($response == null){
			$response = "<p>Kahjuks uudiseid pole!</p> \n";
		}
		
		//sulgen päringu ja andmebaasiühenduse
		$stmt->close();
		$conn->close();
		return $response;
	}
	