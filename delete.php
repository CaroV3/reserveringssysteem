<?php
/** @var array $appointments */
/** @var $db */

//Require database in this file
require_once 'includes/connection.php';
//Check if user is logged in
require_once 'includes/login_check.php';

//get data from url
$id = mysqli_escape_string($db, $_GET['id']);
$year =  $_GET['year'];
$week = $_GET['week'];


//delete
if(isset($_POST['deleteItem'])) {
    $queryDelete = "DELETE FROM `appointments` WHERE `id` ='$id'";
    $result = mysqli_query($db, $queryDelete)
    or die('Error '.mysqli_error($db).' with query '.$queryDelete);

    //redirect to index page with correct year and week
    header("Location: index.php?year=$year&week=$week");
    exit;
} else {
    //get data from database of the selected appointment
    $querySelect = "SELECT * FROM `appointments` WHERE id = '$id'";
    $result = mysqli_query($db, $querySelect)
    or die('Error '.mysqli_error($db).' with query '.$querySelect);

//save data of the selected appointment in array
    $appointment= mysqli_fetch_assoc($result);
}

// Close the connection with the database
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
            <a class="navbar-item" href="profile.php">
                Mijn gegevens
            </a>
            <a class="navbar-item" href="logout.php">
                Log uit
            </a>
        </div>
    </div>
</nav>
<div class="container is-flex-direction-column full-height px-4">
    <span class="icon is-size-2 ml-1 mt-6"><i class="fas fa-trash-can"></i></span>
    <h1 class="content mt-6"> Weet u zeker dat u de afspraak met <b><?=htmlentities($appointment['name'])?></b>
        op <b><?=htmlentities($appointment['date'])?></b>
        om <b><?=htmlentities($appointment['time'])?></b> wilt verwijderen?</h1>
    <div class="columns">
        <form action="" method="post">
            <button class="button" type="submit" name="deleteItem">Bevestig</button>
        </form>
        <a class="button grey" href="details.php?id=<?=$appointment['id']?>&year=<?=$year?>&week=<?=$week?>">Annuleer</a>
    </div>
</body>
</html>

