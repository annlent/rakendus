<?php
// function test_input($data)
// {
//     $data = trim($data);
//     $data = stripslashes($data);
//     $data = htmlspecialchars($data);
//     return $data;
// }
function saveNews($newsTitle, $newsContent) 
{

    $response = null; 
    //andmebaasiühenduse loomine

    $conn = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUserName"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
    
    //sql päring
    $stmt= $conn->prepare("INSERT INTO vr20_news(userID, Title, Content) VALUES(?, ?, ?)");
    echo $conn->error;

    // seon päringuga tegelikud andmed
    $userID = 1;
    $stmt -> bind_param("iss", $userID, $newsTitle, $newsContent); // i on integer, s on string ja d on decimal. Tegemist on kolm erinevat andmetüüpi
    
    if ($stmt -> execute()) {
        $response = 1;
    }    else {
        $response = 0;
        echo $stmt->error; 
    }

    // sulgen päringu ja andmebaasi ühenduse
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
    //andmebaasi ühenduse loomine
    $conn = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUserName"], $GLOBALS["serverPassword"], $GLOBALS["database"]);

    $stmt = $conn->prepare("SELECT id, title, content, created FROM vr20_news where order by id desc LIMIT ?"); //https://www.w3schools.com/php/php_mysql_prepared_statements.asp
    echo $conn->error;

    $stmt->bind_param("i", $limit); //see funktsioon seob muutuja SQL päringuga ning ütleb andmebaasile millised need muutujad/parameetrid on. i märgib siin integeri ehk numbrit (võimalus ka d double, s string b BLOB)
    $stmt->bind_result($idFromDB, $titleFromDB, $contentFromDB, $dateFromDB); //loome uued muutujad andmebaasist
    $stmt->execute();


    while ($stmt->fetch()) {

        $response .= '<div class="jumbotron">';
        $response .= '<h3 class="display-4">' . $titleFromDB . '</h3>'; //uudise pealkiri
        $response .= '<p class="lead">Lisatud:' . $dateFromDB->format("d.m.Y H:i:s") . '</p>'; //uudise avaldamise kuupäev
        $response .= '<hr class="my-4">'; 
        $response .= '<p>' . $contentFromDB  . '</p> \n'; //uudise sisu
        $response .= '<p class="lead">';
        $response .= '<form method="post" action=""><button class="btn btn-warning" type="submit" name="newsDelBtn" value="' . $idFromDB . '">Kustuta</button></from>'; //kustuta nupp HTMLis ja selleks on vaja luu vorm, sest muidu ei saa seda nõnda lisada
        $response .= '</p>';
        $response .= '</div>';
    }

    if ($response == null) {
        $response = "<p>Kahjuks uudiseid pole!</p>";
    }
//sulgen andmebaasi päringu ja andmebaasiühenduse
    $stmt->close();
    $conn->close();
    return $response;
}
function deleteNews($id)
{
    $response = null;

    $conn = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUserName"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
    $stmt = $conn->prepare("UPDATE vr20_news SET deleted = NOW() WHERE Id =?");

    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        $response = 1;
    } else {
        $response = 0;
        echo $stmt->error;
    }

    $stmt->close();
    $conn->close();
    return $response;
}