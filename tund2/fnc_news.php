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
    $stmt= $conn->prepare("INSERT INTO vr20_news (userID, Title, Content) VALUES(?, ?, ?)");
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

function readNews($limit)
{
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
        $response .= '<p class="lead">' . $dateFromDB . '</p>'; //uudise avaldamise kuupäev
        $response .= '<hr class="my-4">'; 
        $response .= '<p>' . $contentFromDB . '</p>'; //uudise sisu
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
function getStudyTopicsOptions()
{

    $response = null;

    $conn = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUserName"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
    mysqli_set_charset($conn, "utf8");

    $stmt = $conn->prepare("SELECT id, course, time FROM vr20_studytopics order by course asc");
    echo $conn->error;

    $stmt->bind_result($idFromDB, $courseNameFromDB, $dateFromDB);
    $stmt->execute();


    while ($stmt->fetch()) {
        $response .= '<option value="' . $idFromDB . '">' . $courseNameFromDB . '</option>\n';
    }

    if ($response == null) {
        $response = "Kursuste nimed puuduvad!";
    }

    $stmt->close();
    $conn->close();
    return $response;
}

function getStudyActivitiesOptions()
{

    $response = null;

    $conn = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUserName"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
    mysqli_set_charset($conn, "utf8");

    $stmt = $conn->prepare("SELECT id, course, activity FROM vr20_studyactivities order by activity asc");
    echo $conn->error;

    $stmt->bind_result($idFromDB, $courseNameFromD, $activityNameFromDB);
    $stmt->execute();


    while ($stmt->fetch()) {
        $response .= '<option value="' . $idFromDB . '">' . $activityNameFromDB . '</option>\n';
    }

    if ($response == null) {
        $response = "Tegevused puuduvad!";
    }

    $stmt->close();
    $conn->close();
    return $response;
}

function saveStudy($studyTopicId, $studyActivity, $elapsedTime)
{

    $response = null;
    //Loon andmebaasi ühenduse
    $conn = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUserName"], $GLOBALS["serverPassword"], $GLOBALS["database"]);

    //Valmistan ette SQL päringu
    $stmt = $conn->prepare("INSERT INTO vr20_studylog (course, activity, time) VALUES (?, ?, ?)");
    echo $conn->error;

    //Seon päringuga pärisandmed

    // i - integer
    // s - string
    // d - decimal
    $stmt->bind_param("isd", $studyTopicId, $studyActivity, $elapsedTime);

    if ($stmt->execute()) {
        $response = 1;
    } else {
        $response = 0;
        echo $stmt->error;
    }

    //Sulgen päringu ja andmebaasi ühenduse.
    $stmt->close();
    $conn->close();
    return $response;
}

function getStudyTableHTML()
{

    $response = null;

    $conn = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUserName"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
    mysqli_set_charset($conn, "utf8");

    $stmt = $conn->prepare("SELECT sl.id, sl.course, sl.activity, sl.time, sa.time 
                                FROM vr20_studylog sl 
                                JOIN vr20_studytopics st on sl.course=st.id
                                JOIN vr20_studyactivities sa on sl.activity=sa.id
                                order by id asc");
    echo $conn->error;

    $stmt->bind_result($idFromDB, $courseNameFromDB, $activityNameFromDB, $elapsedTimeFromDB, $dateFromDB);
    $stmt->execute();

    $rowCount = 1;
    while ($stmt->fetch()) {

        $response .= '<tr>
        <th scope="row">' . $rowCount . '</th>
        <td>' . $courseNameFromDB . '</td>
        <td>' . $activityNameFromDB . '</td>
        <td>' . $elapsedTimeFromDB . '</td>
        <td>' . $dateFromDB . '</td>
        </tr>';

        $rowCount += 1;
    }

    if ($response == null) {
        echo "<p>Ühtegi tegevust ei ole lisatud!</p>";
    }

    $stmt->close();
    $conn->close();
    return $response;
}