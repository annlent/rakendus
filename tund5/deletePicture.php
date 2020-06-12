<?php
	require("classes/Session.class.php");
	SessionManager::sessionStart("rakendus", 0, "/~annika.lentso/", "tigu.hk.tlu.ee");
	$id = $_REQUEST["photoid"];

	$conn = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUserName"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
    $stmt = $conn->prepare("UPDATE vr20_photos SET deleted=now() WHERE id=? AND userid=?");

	$stmt->bind_param("ii", $id, $_SESSION["userid"]);
	$stmt->execute();
    $stmt->close();
    header("Location: " . $_SERVER['HTTP_REFERER']);