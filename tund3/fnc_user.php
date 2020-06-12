<?php
function signUp($name, $surname, $email, $gender, $birthDate, $password) {
    $notice = null;
    $conn = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUserName"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
    $stmt = $conn ->prepare("INSERT INTO vr20_users (firstname, lastname, birthdate, gender, email, password) VALUES(?,?,?,?,?,?)"); //nüüd on ühendus ja valmistan ette andmebaasi ühenduse käsuga prepare; järjekord ei ole oluline, väärtused tuleb panna ? 
    echo $conn ->error; //juhuks kui midagi on valesti läinud, siis annaks ikka errorit ka

    //krüpteerin parooli
	$options = ["cost" => 12, "salt" => substr(sha1(rand()), 0, 22)]; //salt teeb parooli juhuslikumaks (krüpteerimine kipub olema regulaarne ja seega lahtikrüptida) shal on krüpteerimise algoritm ning substr on üks osa shal(rand()) antud räsist (muidu liiga pikk).
	$pwdhash = password_hash($password, PASSWORD_BCRYPT, $options);
	
	$stmt->bind_param("sssiss", $name, $surname, $birthDate, $gender, $email, $pwdhash);
	
	if($stmt->execute()){
		$notice = "jaa";
	} else {
		$notice = $stmt->error;
	}
	
	$stmt->close();
	$conn->close();
    return $notice;
}
function signIn($email, $password){
	$notice = null;
	$conn = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUserName"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
	$stmt = $conn->prepare("SELECT id, firstname, lastname, password FROM vr20_users WHERE email=?");
	$stmt->bind_param("s", $email);
	$stmt->bind_result($idFromDB, $firstnameFromDB, $lastnameFromDB, $passwordFromDB);
	echo $conn->error;
	$stmt->execute();
	if($stmt->fetch()){
		if(password_verify($password, $passwordFromDB)){
			$_SESSION["userid"] = $idFromDB;
			$_SESSION["userFirstName"] = $firstnameFromDB;
			$_SESSION["userLastName"] = $lastnameFromDB;
			
			$stmt->close();
			$conn->close();
			header("Location: home.php");
			exit();
		} else {
			$notice = "Vale salasõna!";
		}
	} else {
		$notice = "Sellist kasutajat (" .$email .") ei leitud!";
	}
	
	$stmt->close();
	$conn->close();
	return $notice;
}