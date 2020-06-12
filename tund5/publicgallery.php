<?php
require("../../../../configuration.php");
//require("fnc_main.php");
require("fnc_user.php");

require("classes/Session.class.php");
require("fnc_gallery.php");

SessionManager::sessionStart("rakendus", 0, "/~annika.lentso/", "tigu.hk.tlu.ee");

$backpage = "page.php";
$page = 1;
$limit = 8;
$picCount = countPics(1);

if (isset($_SESSION["userid"])) {
    $backpage = "home.php";
}

if (!isset($_GET["page"]) or $_GET["page"] < 1) {
    $page = 1;
} elseif (round($_GET["page"] - 1) * $limit >= $picCount) {
    $page = ceil($picCount / $limit);
} else {
    $page = $_GET["page"];
}

$publicPhotoThumb = readAllMyPictureThumbsPage( $page, $limit);

?>

<!DOCTYPE html>
<html lang="et">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Veebirakendused ja nende loomine 2020</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    <link rel="stylesheet" href="css/style.css?1">
</head>

<body>
    <nav class="navbar fixed-top navbar-dark">
        <a class="navbar-brand" href=<?php echo $backpage ?>>Tagasi</a>
    </nav>
    <div id="publicgal" class="bg-image">
        <div class="container transparent" style="margin-top:10%;">

    <h1 class="font-weight-light text-center text-lg-left mt-4 mb-0" style="color: #ffffff;">See omb'gi galõrii</h1>
    <link rel="stylesheet" type="text/css" href="style/gallery.css">
    <link rel="stylesheet" type="text/css" href="style/modal.css">
    <script src="javascript/modal.js" defer></script>
    <div id="modalArea" class="modalArea">
        <!--Sulgemisnupp-->
        <span id="modalClose" class="modalClose">&times;</span>
        <!--pildikoht-->
        <div class="modalHorizontal">
            <div class="modalVertical">
                <p id="modalCaption"></p>
                <img src="tyhi.png" id="modalImg" class="modalImg" alt="galeriipilt">

                 </div>
            </div>
        </div>
            <?php
            if ($page > 1) {
                echo '<a href="?page=' . ($page - 1) . '">Eelmine</a> | ';
            } else {
                echo "<span>Eelmine</span> | ";
            }
            if ($page * $limit <= $picCount) {
                echo '<a href="?page=' . ($page + 1) . '">Järgmine</a>';
            } else {
                echo "<span> Järgmine</span>";
            }
            ?>

            <hr class="mt-2 mb-5">

            <div class="row text-center text-lg-left">
                <?php echo $publicPhotoThumb; ?>
            </div>

        </div>
    </div>



    <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>

</body>

</html>