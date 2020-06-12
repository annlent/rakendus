<?php
    require ("../../../../configuration.php");
    require ("fnc_news.php");

    $logsHtml = getStudyTableHTML();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Õpingud</title>
</head>


<style>
    table {
  font-family: arial, sans-serif;
  border-collapse: collapse;
  width: 100%;
}

    td, th {
  border: 1px solid #dddddd;
  text-align: left;
  padding: 8px;
}

    tr:nth-child(even) {
  background-color: #dddddd;
}

</style>
<body>
    <table>
        <tr>
            <th>Õppeaine</th>
            <th>Tegevus</th>
            <th>Kulunud aeg</th>
        </tr>
        <?php echo $logsHtml; ?>

    </table>
</body>
</html>