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

$week = $_GET['week'];
$year = $_GET['year'];

$weekAp= $_GET['week'];
$yearAp= $_GET['year'];

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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css"/>
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
            <a class="navbar-item is-active" href="index.php">
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
            <a class="navbar-item" href="profile.php">
                Mijn gegevens
            </a>
            <a class="navbar-item" href="logout.php">
                Log uit
            </a>
        </div>
    </div>
</nav>
<div class="container px-4 full-height">
    <a class="button grey mt-5 mb-2" href="index.php?&year=<?=$year?>&week=<?=$week?>">Terug naar agenda</a>

    <div class="side-by-side">
        <h1 class="title mt-5"><?=htmlentities($appointment['date'])?> <?=htmlentities($appointment['time'])?> afspraak met <?=htmlentities($appointment['name'])?></h1>
    </div>
    <hr style="height:2px;border-width:0;background-color:#A6B523;margin:auto">
        <section class="content">
            <ul>
                <li><b>Telefoonnummer:</b> <?= htmlentities($appointment['phone_number'])?></li>
                <li><b>Email: </b><?= htmlentities($appointment['email'] )?></li>
                <li><b>Adres:</b> <?= htmlentities($appointment['address'] )?></li>
                <li><b>Type afspraak:</b> <?= htmlentities($appointment['type_name']) ?></li>
                <li><b>Machine:</b> <?= htmlentities($appointment['machine_number'])?></li>
                <li><b>Opmerking:</b> <?= htmlentities($appointment['comment'])?></li>
            </ul>
            <div class="is-flex-direction-column">
                <div>
                    <a class="button is-align-self-center" href="date_time_edit.php?id=<?=$appointment['id']?>&year=<?=$year?>&week=<?=$week?>&yearAp=<?=$yearAp?>&weekAp=<?=$weekAp?>">Wijzig datum of tijd</a>
                    <a class="button" href="edit.php?id=<?=$appointment['id']?>&year=<?=$year?>&week=<?=$week?>">Bewerk details</a>
                    <a class="button grey" href="delete.php?id=<?=$appointment['id']?>&year=<?=$year?>&week=<?=$week?>">Verwijder afspraak</a>
                </div>
                <div>
                </div>
            </div>
        </section>
</body>
</html>
