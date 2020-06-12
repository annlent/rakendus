<?php

require("../../../../configuration.php");
require("fnc_news.php");

$studyTopicsOptions = getStudyTopicsOptions();
$studyActivitiesOptions = getStudyActivitiesOptions();

$studyTopicId = null;
$studyActivity = null;
$elapsedTime = null;
$studyError = null;

if (isset($_POST['studyBtn'])) {

    if (isset($_POST["studyTopicId"]) and !empty($_POST["studyTopicId"])) {
        $studyTopicId = ($_POST["studyTopicId"]);
    } else {
        $studyError .= "Õppeaine ei ole valitud! ";
    }

    if (isset($_POST["studyActivity"]) and !empty($_POST["studyActivity"])) {
        $studyActivity = $_POST["studyActivity"];
    } else {
        $studyError .= "Tegevus ei ole valitud! ";
    }

    if (isset($_POST["elapsedTime"]) and !empty($_POST["elapsedTime"]) and $_POST["elapsedTime"] != 0) {
        $elapsedTime = $_POST["elapsedTime"];
    } else {
        $studyError .= "Tegevusele kulunud aeg ei ole määratud! ";
    }

    //Saadan andmebaasi
        if (empty($studyError)) {
            $response = saveStudy($studyTopicId, $studyActivity, $elapsedTime);

            if ($response == 1) {
             $studyError = "Tegevus on salvestet!";
             } else {
            $studyError = "Tegevuse salvestamisel tekkis viga!";
            }
        }
}

?>



<!DOCTYPE html>
<html lang="et">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Veebirakendused ja nende loomine 2020</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
</head>

<body>

    <div class="container" style="max-width: 60%; margin-top:100px;">
        <section class="text-center">

            <section class="text-center">
                <h1 class="jumbotron">Õpingute sisestamine</h1>
                <p class="lead text-info">See leht on valminud õppetöö raames</p>
                <br>
            </section>
            <div>
                <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                    <div class="form-row">
                        <div class="col">
                            <select class="form-control" name="studyTopicId">
                                <option value="">Õppeaine</option>
                                <option value="1">Üld- ja sotsiaalpsühholoogia</option>
                                <option value="2">Veebirakendused ja nende loomine</option>
                                <option value="3">Programmeerimine I</option>
                                <option value="4">Disaini alused</option>
                                <option value="5">Videomängude disain</option>
                                <option value="6">Andmebaasid</option>
                                <option value="7">Sissejuhatus tarkvaraarendusse</option>
                                <option value="8">Sissejuhatus informaatikasse</option>
                                <?php echo $studyTopicsOptions; ?>
                            </select>
                        </div>
                        <div class="col">
                            <select class="form-control" name="studyActivity">
                                <option value="" selected disabled>Tegevus</option>
                                <option value="1">Iseseisva materjali loomine</option>
                                <option value="2">Koduste ülesannete lahendamine</option>
                                <option value="3">Kordamine</option>
                                <option value="4">Rühmatöö</option>
                                <?php echo $studyActivitiesOptions; ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-2">
                            <br>
                            <label>Tegevusele kulunud aeg:</label>
                            <input class="form-control" type="number" min=".25" max="24" step=".25" name="elapsedTime"value="<?php echo $elapsedTime; ?>">
                        </div>
                    </div><br>
                    <input type="submit" class="btn btn-info" name="studyBtn" value="Salvesta tegevus!"><br><br>
                    <span><?php echo $studyError; ?></span>
                    <div>
            <a class="btn btn-info" href="studyinfo.php">Vaata varasemat</a>
        </div>
                </form>
            </div>
        </section>
    </div>

    <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>


</body>

</html>