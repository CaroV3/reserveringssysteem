<?php
/** @var array $appointments */
/** @var $db */

//Require database in this file
require_once 'includes/connection.php';
//Check if user is logged in
require_once 'includes/login_check.php';

//get data from url
$id = mysqli_escape_string($db, $_GET['id']);
// store week and year of selected appointment
// to be able to redirect to the index page with that year and week again
$year = $_GET['year'];
$week = $_GET['week'];

//edit
if(isset($_POST['editItem'])) {

//Postback with the data showed to the user, first retrieve data from 'Super global'
    $name   = mysqli_escape_string($db, $_POST['name']);
    $phoneNumber = mysqli_escape_string($db, $_POST['phoneNumber']);
    $email = mysqli_escape_string($db, $_POST['email']);
    $address = mysqli_escape_string($db, $_POST['address']);
    $typeAppointment = mysqli_escape_string($db, $_POST['typeAppointment']);
    $machineNumber = mysqli_escape_string($db, $_POST['machineNumber']);
    $comment = mysqli_escape_string($db, $_POST['comment']);


//Check if data is valid & generate error if not so
    $errors = [];
    if ($name == "") {
        $errors['name'] = 'cannot be empty';
    }

    if ($phoneNumber == "") {
        $errors['phoneNumber'] = 'cannot be empty';
    }

    if ($email == "") {
        $errors['email'] = 'cannot be empty';
    }
    if ($address == "") {
        $errors['address'] = 'cannot be empty';
    }

    if ($typeAppointment == "default") {
        $errors['typeAppointment'] = 'cannot be empty';
    }

    //if data is valid, save data to the database
    if (empty($errors)) {
        $queryEdit = "UPDATE `appointments` 
                    SET name = '$name', phone_number = '$phoneNumber', email = '$email', address = '$address', 
                        type_appointment_id = '$typeAppointment', machine_number = '$machineNumber', comment = '$comment'
                    WHERE id = '$id'";
        $result = mysqli_query($db, $queryEdit)
        or die('Error ' . mysqli_error($db) . ' with query ' . $queryEdit);

        //redirect to details page with the right appointment id
        // and send the right year and week through the url
        // to be able to redirect from the details page to the index page
        // with the right year and week
        header("Location: details.php?id=$id&year=$year&week=$week");
        exit;
    }

} else {
    $query = "SELECT * FROM `appointments` WHERE id = '$id'";
    $result = mysqli_query($db, $query)
    or die('Error ' . mysqli_error($db) . ' with query ' . $query);

    $appointment = mysqli_fetch_assoc($result);
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
                            <input class="input" id="name" type="text" name="name" value="<?=isset($name) ? htmlentities($name) : htmlentities($appointment['name'])?>"/>
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
                            <input class="input" id="phoneNumber" type="text" name="phoneNumber" value="<?=isset($phoneNumber) ? htmlentities($phoneNumber) : htmlentities($appointment['phone_number']) ?>"/>
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
                            <input class="input" id="email" type="email" name="email" value="<?=isset($email) ? htmlentities($email) : htmlentities($appointment['email'])?>"/>
                        </div>
                        <p class="help is-danger">
                            <?= $errors['email'] ?? '' ?>
                        </p>
                    </div>
                </div>
            </div>

            <div class="field is-horizontal">
                <div class="field-label is-normal">
                    <label class="label" for="address">Adres</label>
                </div>
                <div class="field-body mr-4">
                    <div class="field">
                        <div class="control">
                            <input class="input" id="address" type="text" name="address" value="<?=isset($address) ? htmlentities($address) : htmlentities($appointment['address'])?>"/>
                        </div>
                        <p class="help is-danger">
                            <?= $errors['address'] ?? '' ?>
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
                                <?php if ($appointment['type_appointment_id'] == 1) {?>
                                    <option selected value="1">Machine bezichtigen</option>
                                    <option value="2">Videobellen</option>
                                <?php } else {?>
                                    <option value="1">Machine bezichtigen</option>
                                    <option selected value="2">Videobellen</option>
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
                            <input class="input" id="machineNumber" type="text" name="machineNumber" value="<?=isset($machineNumber) ? htmlentities($machineNumber) : htmlentities($appointment['machine_number'])?>"/>
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
                            <textarea class="input" id="comment" name="comment"><?=isset($comment) ? htmlentities($comment) : htmlentities($appointment['comment'])?></textarea>
                        </div>
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
    <div>
        <a class="button grey" href="details.php?id=<?= $appointment['id'] ?>&year=<?= $year?>&week=<?= $week ?>">Annuleer</a>
    </div>
</body>
</html>
