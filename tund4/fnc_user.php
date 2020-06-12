<?php

//sessiooni käivitamine või kasutamine
//session_start();
// function test_input($data) {
// 		$data = trim($data);
// 		$data = stripslashes($data);
// 		$data = htmlspecialchars($data);
// 		return $data;
// }

function signUp($name, $surname, $email, $gender, $birthDate, $password) {
    $notice = null;
    $conn = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUserName"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
    $stmt = $conn ->prepare("INSERT INTO vr20_users (firstname, lastname, birthdate, gender, email, password) VALUES(?,?,?,?,?,?)"); //nüüd on ühendus ja valmistan ette andmebaasi ühenduse käsuga prepare; järjekord ei ole oluline, väärtused tuleb panna ? 
	echo $conn ->error; //juhuks kui midagi on valesti läinud, siis annaks ikka errorit ka

	if ($stmt->fetch()) {
        $notice = "Selline e-postiaadress on juba kasutusel!";
        $stmt->close();
        $conn->close();

        return $notice;
    } else {
        $stmt->close();
        $stmt = $conn->prepare("INSERT INTO vr20_users (firstname, lastname, birthdate, gender, email, password) VALUES (?, ?, ?, ?, ?, ?)");
        echo $conn->error;

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