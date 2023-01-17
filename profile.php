<?php
/** @var $db */
/** @var $userId */

//Require database in this file
require_once 'includes/connection.php';
//Check if user is logged in
require_once 'includes/login_check.php';

//Select all rows from database of the logged in user
$query = "SELECT * FROM `users` WHERE id = '$userId' ";
$result = mysqli_query($db, $query)
or die('Error '.mysqli_error($db).' with query '.$query);

// Store the user in an array
$user= mysqli_fetch_assoc($result);

// Close the connection with the database
mysqli_close($db);

?>

<!doctype html>
<html lang="en">
<head>
    <title>Mijn gegevens</title>
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
            <a class="navbar-item is-active" href="index.php">
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
        <h1 class="title mt-5">Mijn gegevens</h1>
    </div>
    <hr style="height:2px;border-width:0;background-color:#A6B523;">
    <section class="content">
        <ul>
            <li>Naam: <?= $user['name'] ?></li>
            <li>Telefoonnummer: <?= $user['phone_number'] ?? '' ?></li>
            <li>Email: <?= $user['email'] ?></li>
            <li>Info: <?= $user['info'] ?? '' ?></li>
        </ul>
        <div>
            <a class="button" href="profile_edit.php?id=<?=$user['id']?>">Bewerk profiel</a>
            <a class="button" href="password_edit.php?id=<?=$user['id']?>">Wijzig wachtwoord</a>
            <a class="button" href="index.php">Terug naar agenda</a>
        </div>
    </section>
</div>
</body>
</html>

