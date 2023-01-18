<?php
/** @var $db */
//Require database in this file
require_once '../includes/connection.php';

$employeeId = mysqli_escape_string($db, $_GET['id']);

if(empty($employee)) {
    $employee="";
}

//Select all rows from database of the logged in user
$query = "SELECT * FROM `users` WHERE id = '$employeeId' ";
$result = mysqli_query($db, $query)
or die('Error '.mysqli_error($db).' with query '.$query);

// Store the user in an array
$employee= mysqli_fetch_assoc($result);

// Close the connection with the database
mysqli_close($db);
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css"/>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.9.4/css/bulma.min.css">
    <link rel="stylesheet" href="../css/style.css"/>
    <title>Afspraak maken</title>
</head>
<body class="background-image">
<nav>
    <div class="navbar-brand centered">
        <a href="https://www.duijndam-machines.com/nl/" target="_blank">
            <img src="../img/logo.png" alt="Logo Duijndam" width="210" height="84">
        </a>
        <p class="navbar-item addition-logo">Reserveringssysteem</p>
    </div>
</nav>
<div class="height-1">
    <div class="section center">
        <div class="content-customer">
            <h1 class="title has-text-centered">Afspraak maken met <?=htmlentities($employee['name'])?></h1>
            <hr style="height:2px;border-width:0;background-color:#A6B523;margin:auto">
            <div class="is-flex-direction-row mt-3 ml-5">
                <img class="profile" src="https://thispersondoesnotexist.com/image" alt="profile picture" width="220" height="300">
                <div class="content-profile ml-6 mt-3">
                    <div class="contact">
                        <p><b>Telefoonnummer:</b><br><?= htmlentities($employee['phone_number'])?></p>
                        <p class="mt-2"><b>Email:</b> <br><?= htmlentities($employee['email'])?></p>
                    </div>
                </div>
            </div>
            <p class="info mt-3 ml-5"><?= htmlentities($employee['info'])?></p>
            <div class="has-text-centered">
                <a href="date_time_customer.php?id=<?=$employeeId?>" class="button mt-3 ml-6 mr-6">Maak afspraak >></a>
            </div>
        </div>
    </div>
</div>
<footer class="is-flex-direction-row is-justify-content-space-between">
    <div>
        <h1><b>DUIJNDAM MACHINES</b></h1>
        <h2 class="mb-2"><b>Specialist in gebruikte land- en tuinbouwmachines</b></h2>
        <a class="button white mt-5" href="http://www.duijndam-machines.com"><b>Bezoek onze site >></b></a>
    </div>
    <div>
        <h1 class="title"><b>Heeft u een vraag?</b></h1>
        <ul>
            <li>We zullen deze graag beantwoorden!</li>
            <li>(+31) (0)180 632 088</li>
            <li>info@duijndam-machines.com</li>
        </ul>
    </div>
    <div>
        <h1 class="title"><b>Openingstijden</b></h1>
        <p>8:00-17:00 (maandag - vrijdag)</p>
    </div>
    <div>
        <h1 class="title"><b>Adres</b></h1>
        <ul>
            <li>Tweede Tochtweg 127</li>
            <li>2913 LR Nieuwerkerk a/d IJssel</li>
            <li>(Regio Rotterdam)</li>
            <li>Nederland</li>
        </ul>
    </div>
</footer>
</body>
</html>
