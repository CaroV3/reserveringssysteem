<?php
/** @var mysqli $db */
/** @var $timeSlots */
/** @var $userId */
/** @var $name */
/** @var $phoneNumber */
/** @var $email */
/** @var $address */
/** @var $typeAppointment */
/** @var $machineNumber */
/** @var $comment */

//require times and days of the week in this file
require_once 'includes/data.php';
//Check if user is logged in
require_once 'includes/login_check.php';
//Require database in this file
require_once "includes/connection.php";

//get data from url
$year = mysqli_escape_string($db, $_GET['year']);
$week = mysqli_escape_string($db, $_GET['week']);
$time = mysqli_escape_string($db, $_GET['time']);
$date = mysqli_escape_string($db, $_GET['date']);


//Check if Post isset, else do nothing
if (isset($_POST['submit'])) {

    //require form validation
    require_once 'includes/form_validation.php';

    //if everything is filled in correctly by the user
    if (empty($errors)) {
        //save filled in data in the database
        $query = "INSERT INTO `appointments` (name, phone_number, email, address, type_appointment_id, machine_number, time, date, comment, user_id)
                  VALUES ('$name', '$phoneNumber', '$email', '$address', '$typeAppointment', '$machineNumber', '$time', '$date', '$comment', '$userId' )";
        $result = mysqli_query($db, $query) or die('Error: '.mysqli_error($db). ' with query ' . $query);

        //Close connection with the database
        mysqli_close($db);

        // Redirect to index.php with correct year and week of the appointment
        header("Location: index.php?year=$year&week=$week");
        exit;
    }
}
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.9.4/css/bulma.min.css">
    <link rel="stylesheet" href="css/style.css">
    <title>Afspraak plannen</title>
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
    <h1 class="title mt-4">Plan afspraak op <?=$date?> om <?=$time?></h1>

    <section class="columns">
        <form class="column is-6" action="" method="post" enctype="multipart/form-data">
            <div class="field is-horizontal">
                <div class="field-label is-normal">
                    <label class="label" for="name">Naam</label>
                </div>
                <div class="field-body">
                    <div class="field">
                        <div class="control">
                            <input class="input" id="name" type="text" name="name" value="<?= $name ?? '' ?>"/>
                        </div>
                        <p class="help is-danger">
                            <?= $errors['name'] ?? ''?>
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
                            <input class="input" id="phoneNumber" type="text" name="phoneNumber" value="<?= $phoneNumber ?? '' ?>"/>
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
                            <input class="input" id="email" type="email" name="email" value="<?= $email ?? '' ?>"/>
                        </div>
                        <p class="help is-danger">
                            <?= $errors['email'] ?? '' ?>
                        </p>
                    </div>
                </div>
            </div>

            <div class="field is-horizontal">
                <div class="field-label is-normal">
                    <label class="label" for="street">Straat</label>
                </div>
                <div class="field-body mr-4">
                    <div class="field">
                        <div class="control">
                            <input class="input" id="street" type="text" name="street" value="<?= $street ?? '' ?>"/>
                        </div>
                        <p class="help is-danger">
                            <?= $errors['street'] ?? '' ?>
                        </p>
                    </div>
                </div>
                <div class="field-label is-normal">
                    <label class="label" for="addressNumber">Huisnummer</label>
                </div>
                <div class="field-body">
                    <div class="field">
                        <div class="control">
                            <input class="input" style="width: 30%;"  id="addressNumber" type="number" name="addressNumber" value="<?= $addressNumber ?? '' ?>"/>
                        </div>
                        <p class="help is-danger">
                            <?= $errors['addressNumber'] ?? '' ?>
                        </p>
                    </div>
                </div>
            </div>

            <div class="field is-horizontal">
                <div class="field-label is-normal">
                    <label class="label" for="city">Plaats</label>
                </div>
                <div class="field-body mr-4">
                    <div class="field">
                        <div class="control">
                            <input class="input" id="city" type="text" name="city" value="<?= $city ?? '' ?>"/>
                        </div>
                        <p class="help is-danger">
                            <?= $errors['city'] ?? '' ?>
                        </p>
                    </div>
                </div>
                <div class="field-label is-normal">
                    <label class="label" for="zipCode">Postcode</label>
                </div>
                <div class="field-body">
                    <div class="field">
                        <div class="control">
                            <input class="input" style="width: 50%;"  id="zipCode" type="text" name="zipCode" value="<?= $zipCode ?? '' ?>"/>
                        </div>
                        <p class="help is-danger">
                            <?= $errors['zipCode'] ?? '' ?>
                        </p>
                    </div>
                </div>
            </div>

            <hr>

            <div class="field is-horizontal">
                <div class="field-label is-normal">
                    <label class="label" for="typeAppointment">Type afspraak</label>
                </div>
                <div class="field-body">
                    <div class="field">
                        <div class="control">
                            <select class="select input" id="typeAppointment" name="typeAppointment">
                                <?php if (empty($typeAppointment)) $typeAppointment = 3;
                                    if ($typeAppointment == 1) {?>
                                        <option selected value="1">Machine bezichtigen</option>
                                        <option value="2">Videobellen</option>
                                    <?php } elseif ($typeAppointment == 2) {?>
                                        <option value="1">Machine bezichtigen</option>
                                        <option selected value="2">Videobellen</option>
                                    <?php } else {?>
                                        <option value="default" selected>Kies een type afspraak</option>
                                        <option value="1">Machine bezichtigen</option>
                                        <option value="2">Videobellen</option>
                                    <?php } ?>
                            </select>
                        </div>
                        <p class="help is-danger">
                            <?= $errors['typeAppointment'] ?? '' ?>
                        </p>
                    </div>
                </div>
            </div>

            <div class="field is-horizontal">
                <div class="field-label is-normal">
                    <label class="label" for="machineNumber">Machinenummer</label>
                </div>
                <div class="field-body">
                    <div class="field">
                        <div class="control">
                            <input class="input" id="machineNumber" type="text" name="machineNumber" value="<?= $machineNumber ?? '' ?>"/>
                        </div>
                    </div>
                </div>
            </div>

            <div class="field is-horizontal">
                <div class="field-label is-normal">
                    <label class="label" for="comment">Opmerking</label>
                </div>
                <div class="field-body">
                    <div class="field">
                        <div class="control">
                            <textarea class="input" id="comment" name="comment"><?= $comment ?? '' ?></textarea>
                        </div>
                    </div>
                </div>
            </div>

            <div class="field is-horizontal">
                <div class="field-label is-normal"></div>
                <div class="field-body">
                    <button class="button is-link is-fullwidth" type="submit" name="submit">Plan afspraak</button>
                </div>
            </div>

        </form>
    </section>
    <a class="button mt-4" href="date_time.php">&laquo; Andere datum en tijd kiezen</a>
    <a class="button mt-4" href="index.php">&laquo; Terug naar afspraken overzicht</a>
</div>
</body>
</html>
