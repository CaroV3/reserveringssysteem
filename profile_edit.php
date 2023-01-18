<?php
/** @var $db */
/** @var $userId */

//Require database in this file
require_once 'includes/connection.php';
//Check if user is logged in
require_once 'includes/login_check.php';

//get data from url
$id = mysqli_escape_string($db, $_GET['id']);

//edit
if(isset($_POST['editItem'])) {

//Postback with the data showed to the user, first retrieve data from 'Super global'
    $name = mysqli_escape_string($db, $_POST['name']);
    $phoneNumber = mysqli_escape_string($db, $_POST['phoneNumber']);
    $email = mysqli_escape_string($db, $_POST['email']);
    $info = mysqli_escape_string($db, $_POST['info']);


//Check if data is valid & generate error if not so
    $errors = [];
    if ($name == "") {
        $errors['name'] = 'cannot be empty';
    }

    if ($email == "") {
        $errors['email'] = 'cannot be empty';
    }


    //if data is valid, save data to the database
    if (empty($errors)) {
        $queryEdit = "UPDATE `users` 
                    SET name = '$name', email = '$email', phone_number = '$phoneNumber', info = '$info'
                    WHERE id = '$id'";
        $result = mysqli_query($db, $queryEdit)
        or die('Error ' . mysqli_error($db) . ' with query ' . $queryEdit);

        //redirect to details page with the right appointment id
        // and send the right year and week through the url
        // to be able to redirect from the details page to the index page
        // with the right year and week
        header("Location: profile.php?id=$id");
        exit;
    }

} else {
    $query = "SELECT * FROM `users` WHERE id = '$id'";
    $result = mysqli_query($db, $query)
    or die('Error ' . mysqli_error($db) . ' with query ' . $query);

    $user = mysqli_fetch_assoc($result);
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
            <a class="navbar-item is-active" href="profile.php">
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
                    <label class="label" for="name">Naam</label>
                </div>
                <div class="field-body">
                    <div class="field">
                        <div class="control">
                            <input class="input" id="name" type="text" name="name" value="<?=isset($name) ? htmlentities($name) : htmlentities($user['name'])?>"/>
                        </div>
                        <p class="help is-danger">
                            <?= $errors['name'] ?? '' ?>
                        </p>
                    </div>
                </div>
            </div>

            <div class="field is-horizontal">
                <div class="field-label is-normal">
                    <label class="label" for="phoneNumber">Telefoonnummer</label>
                </div>
                <div class="field-body">
                    <div class="field">
                        <div class="control">
                            <input class="input" id="phoneNumber" type="text" name="phoneNumber" value="<?=isset($phoneNumber) ? htmlentities($phoneNumber) : htmlentities($user['phone_number'])?>"/>
                        </div>
                        <p class="help is-danger">
                            <?= $errors['phoneNumber'] ?? '' ?>
                        </p>
                    </div>
                </div>
            </div>

            <div class="field is-horizontal">
                <div class="field-label is-normal">
                    <label class="label" for="email">Email</label>
                </div>
                <div class="field-body">
                    <div class="field">
                        <div class="control">
                            <input class="input" id="email" type="email" name="email" value="<?=isset($email) ? htmlentities($email) : htmlentities($user['email'])?>"/>
                        </div>
                        <p class="help is-danger">
                            <?= $errors['email'] ?? '' ?>
                        </p>
                    </div>
                </div>
            </div>
            <div class="field is-horizontal">
                <div class="field-label is-normal">
                    <label class="label" for="info">info</label>
                </div>
                <div class="field-body mr-4">
                    <div class="field">
                        <div class="control">
                            <input class="input" id="info" type="text" name="info" value="<?=isset($info) ? htmlentities($info) : htmlentities($user['info'])?>"/>
                        </div>
                        <p class="help is-danger">
                            <?= $errors['info'] ?? '' ?>
                        </p>
                    </div>
                </div>
            </div>

            <div class="field is-horizontal">
                <div class="field-label is-normal"></div>
                <div class="field-body">
                    <button class="button is-link is-fullwidth" type="submit" name="editItem">Bewaar wijzigingen</button>
                </div>
            </div>
        </form>
</section>
    <a class="button grey" href="profile.php">Annuleer</a>
</div>
</body>
</html>
