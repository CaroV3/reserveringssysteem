<?php
/** @var $db */
/** @var $userId */

//Require database in this file
require_once 'includes/connection.php';
//Check if user is logged in
require_once 'includes/login_check.php';

//get data from url
$id = mysqli_escape_string($db, $_GET['id']);


$query = "SELECT * FROM `users` WHERE id = '$id'";
$result = mysqli_query($db, $query)
or die('Error ' . mysqli_error($db) . ' with query ' . $query);

$user = mysqli_fetch_assoc($result);

//edit
if(isset($_POST['editItem'])) {

//Postback with the data showed to the user, first retrieve data from 'Super global'
    $oldPassword = $_POST['old-password'];
    $password = $_POST['new-password'];

//Check if data is valid & generate error if not so
    $errors = [];
    if ($oldPassword == "") {
        $errors['oldPassword'] = 'cannot be empty';
    }

    require_once 'includes/password_validation.php';


    //if data is valid, save data to the database
    if (empty($errors)) {
        if (password_verify($oldPassword, $user['password'])) {

            $password = password_hash($password, PASSWORD_DEFAULT);

            $queryEdit = "UPDATE `users` 
                    SET password = '$password'
                    WHERE id = '$id'";
            $result = mysqli_query($db, $queryEdit)
            or die('Error ' . mysqli_error($db) . ' with query ' . $queryEdit);

            //redirect to profile page
            header("Location: profile.php?id=$id");
            exit;
        } else {
            //error incorrect log in
            $errors['wrongPassword'] = 'Uw oude wachtwoord is onjuist';
        }
    }
}

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
            <a class="navbar-item" href="profile.php">
                Mijn gegevens
            </a>
            <a class="navbar-item" href="logout.php">
                Log uit
            </a>
        </div>
    </div>
</nav>
<div class="container full-height px-4 mt-5">
    <section class="columns">
        <form class="column is-6" action="" method="post" enctype="multipart/form-data">

            <div class="field is-horizontal">
                <div class="field-label is-normal">
                    <label class="label" for="old-password">Oud wachtwoord</label>
                </div>
                <div class="field-body mr-4">
                    <div class="field">
                        <div class="control">
                            <input class="input" id="old-password" type="password" name="old-password"/>
                        </div>
                        <p class="help is-danger">
                            <?= $errors['oldPassword'] ?? '' ?>
                        </p>
                    </div>
                </div>
            </div>
            <div class="field is-horizontal">
                <div class="field-label is-normal">
                    <label class="label" for="new-password">Nieuw wachtwoord</label>
                </div>
                <div class="field-body mr-4">
                    <div class="field">
                        <div class="control">
                            <input class="input" id="new-password" type="password" name="new-password"/>
                        </div>
                        <p class="help is-danger">
                            <?= $errors['password'] ?? '' ?>
                        </p>
                    </div>
                </div>
            </div>
            <?php if (isset($errors['wrongPassword'])) { ?>
                <div class="notification is-danger mt-4">
                    <?= $errors['wrongPassword'] ?>
                </div>
            <?php } ?>

            <div class="field is-horizontal">
                <div class="field-label is-normal"></div>
                <div class="field-body">
                    <button class="button is-link is-fullwidth" type="submit" name="editItem">Bewaar nieuw wachtwoord</button>
                </div>
            </div>
        </form>
    </section>
    <a class="button grey" href="profile.php">Annuleer</a>
</div>
</body>
</html>
