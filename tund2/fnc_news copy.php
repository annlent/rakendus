<?php 
function saveNews($newsTitle, $newsContent) {
    $response = null;
    $conn = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUserName"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
    $stmt= $conn->prepare("INSERT INTO vr20_news(userID, Title, Content) VALUES(?, ?, ?)");
    echo $conn->error;
    // seon päringuga tegelikud andmed
    $userID = 1;
    $stmt -> bind_param("iss", $userID, $newsTitle, $newsContent); // i on integer, s on string ja d on decimal. Tegemist on kolm erinevat andmetüüpi
    if ($stmt -> execute()) {
        $response = 1;
    }
    else {
        $response = 0;
        echo $stmt->error; 

    // sulgen päringu ja andmebaasi ühenduse
    $stmt->close();
    $conn->close();
    return $response;
}
function readNews() {
    $response = null;
    $conn = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUserName"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
    $stmt = $conn("SELECT Title, Content FROM vr20_news");
    echo $conn->error;
    $stmt->bind_result($titleFromDB, $ContentFromDB);
    $stmt->execute();
    while ($stmt -> fetch()){
        $response.= "<h2>".$titleFromDB."</h2> \n";
        $response.= "<p>" .$ContentFromDB ."</p> \n";
// sulgen päringu ja andmebaasi ühenduse
    $stmt->close();
    $conn->close();
    return $response;
    }