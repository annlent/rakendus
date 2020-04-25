<?php
function signUp($name, $surname, $email, $gender, $birthDate, $password) {
    $notice = null;
    $conn = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUserName"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
    $stmt = $conn ->prepare("INSERT INTO vr20_users (firstname, lastname, birthdate, gender, email, password) VALUES(?,?,?,?,?,?)"); //nüüd on ühendus ja valmistan ette andmebaasi ühenduse käsuga prepare; järjekord ei ole oluline, väärtused tuleb panna ? 
    echo $conn ->error; //juhuks kui midagi on valesti läinud, siis annaks ikka errorit ka

    //krüpteerin parooli
	$options = ["cost" => 12, "salt" => substr(sha1(rand()), 0, 22)];
	$pwdhash = password_hash($password, PASSWORD_BCRYPT, $options);
	
	$stmt->bind_param("sssiss", $name, $surname, $birthDate, $gender, $email, $pwdhash);
	
	if($stmt->execute()){
		$notice = "ok";
	} else {
		$notice = $stmt->error;
	}
	
	$stmt->close();
	$conn->close();
    return $notice;
}