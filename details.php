<?php
// Get index of album from url (GET)
/** @var array $appointments */
/** @var $db */

//Require database in this file
require_once 'includes/connection.php';
//Check if user is logged in
require_once 'includes/login_check.php';

//get data from url
$id = mysqli_escape_string($db, $_GET['id']);

$week = mysqli_escape_string($db, $_GET['week']);
$year = mysqli_escape_string($db, $_GET['year']);

$weekAp=  mysqli_escape_string($db, $_GET['week']);
$yearAp=  mysqli_escape_string($db, $_GET['year']);

//get data of selected appointment from database
$query = "SELECT appointments.*, types_appointment.name AS type_name FROM appointments 
          LEFT JOIN types_appointment ON types_appointment.id = appointments.type_appointment_id
          WHERE appointments.id = '$id'";
$result = mysqli_query($db, $query)
or die('Error '.mysqli_error($db).' with query '.$query);

//safe data of selected appointment in array
$appointment= mysqli_fetch_assoc($result);

// Close the connection
mysqli_close($db);

?>

<!doctype html>
<html lang="en">
<head>
    <title>Afspraakdetails</title>
    <meta charset="utf-8"/>
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.9.4/css/bulma.min.css">
    <link rel="stylesheet" href="css/style.css"/>
</head>
<body>
<nav class="navbar">
    <div class="navbar-brand">
        <a href="https://www.duijndam-machines.com/nl/" target="_blank">
            <img src="img/logo.png" alt="Logo Duijndam" width="210" height="84">
        </a>
        <p class="navbar-item addition-logo">Reserveringssysteem</p>
        <div class="navbar-burger" data-target="navbarExampleTransparentExample">
            <span></span>
            <span></span>
            <span></span>
        </div>
    </div>

    <div id="navbarExampleTransparentExample" class="navbar-menu">
        <div class="navbar-end">
            <a class="navbar-item" href="index.php">
                Mijn afspraken
            </a>
            <div class="navbar-item has-dropdown is-hoverable">
                <a class="navbar-link">
                    Plan afspraak
                </a>
                <div class="navbar-dropdown is-boxed">
                    <a class="navbar-item" href="index.php">
                        Privé
                    </a>
                    <a class="navbar-item" href="date_time.php">
                        Klant
                    </a>
                </div>
            </div>
            <a class="navbar-item" href="index.php">
                Mijn gegevens
            </a>
            <a class="navbar-item" href="logout.php">
                Log uit
            </a>
        </div>
    </div>
</nav>
<div class="container px-4 full-height">
    <div class="side-by-side">
        <h1 class="title mt-5"><?=$appointment['date']?> <?=$appointment['time']?> afspraak met <?= $appointment['name'] ?></h1>
        <a class="button ml-5 is-align-self-center" href="date_time_edit.php?id=<?=$appointment['id']?>&year=<?=$year?>&week=<?=$week?>&yearAp=<?=$yearAp?>&weekAp=<?=$weekAp?>">Wijzig datum of tijd</a>
    </div>
    <hr style="height:2px;border-width:0;background-color:#A6B523;margin:auto">
        <section class="content">
            <ul>
                <li>Telefoonnummer: <?= $appointment['phone_number'] ?></li>
                <li>Email: <?= $appointment['email'] ?></li>
                <li>Adres: <?= $appointment['address'] ?></li>
                <li>Type afspraak: <?= $appointment['type_name'] ?></li>
                <li>Machine: <?= $appointment['machine_number'] ?></li>
                <li>Opmerking: <?= $appointment['comment'] ?></li>
            </ul>
            <div>
                <a class="button" href="delete.php?id=<?=$appointment['id']?>&year=<?=$year?>&week=<?=$week?>">Verwijder afspraak</a>
                <a class="button" href="edit.php?id=<?=$appointment['id']?>&year=<?=$year?>&week=<?=$week?>">Bewerk details</a>
                <a class="button" href="index.php?&year=<?=$year?>&week=<?=$week?>">Terug naar agenda</a>
            </div>
        </section>
</body>
</html>
