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
require_once '../includes/data.php';
//Require database in this file
require_once '../includes/connection.php';

//get data from url
//get employee id from url
$employeeId = mysqli_escape_string($db, $_GET['id']);
$time = mysqli_escape_string($db, $_GET['time']);
$date = mysqli_escape_string($db, $_GET['date']);

$year = $_GET['year'];
$week = $_GET['week'];


//Check if Post isset, else do nothing
if (isset($_POST['submit'])) {

    //require form-validation
    require_once '../includes/form_validation.php';

    //if everything is filled in correctly by the user
    if (empty($errors)) {
        //save filled in data in the database
        $query = "INSERT INTO `appointments` (name, phone_number, email, address, type_appointment_id, machine_number, time, date, comment, user_id)
                  VALUES ('$name', '$phoneNumber', '$email', '$address', '$typeAppointment', '$machineNumber', '$time', '$date', '$comment', '$employeeId' )";
        $result = mysqli_query($db, $query) or die('Error: '.mysqli_error($db). ' with query ' . $query);

        //Close connection with the database
        mysqli_close($db);

        // Redirect to thank you page
        header("Location: thank_you.php?id=$employeeId");
        $employeeId = "";
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
<div class="height-2">
    <div class="section center">
        <div class="content-customer bigger-content">
            <h1 class="title has-text-centered">Gegevens invullen</h1>
            <hr style="height:2px;border-width:0;background-color:#A6B523;margin:auto">
            <section class="columns mt-3 centered">
                <form class="column is-8" action="" method="post" enctype="multipart/form-data">

                    <div class="field is-horizontal">
                            <div class="field-label is-normal">
                                <label class="label" for="name">Naam*</label>
                            </div>
                            <div class="field-body">
                                <div class="field">
                                    <div class="control">
                                        <input class="input" id="name" type="text" name="name" value="<?=isset($name) ? htmlentities($name) : '' ?>"/>
                                    </div>
                                    <p class="help is-danger">
                                        <?= $errors['name'] ?? ''?>
                                    </p>
                                </div>
                            </div>
                        </div>

                    <div class="field is-horizontal">
                            <div class="field-label is-normal">
                                <label class="label" for="phoneNumber">Telefoonnummer*</label>
                            </div>
                            <div class="field-body">
                                <div class="field">
                                    <div class="control">
                                        <input class="input" id="phoneNumber" type="text" name="phoneNumber" value="<?=isset($phoneNumber) ? htmlentities($phoneNumber) : '' ?>"/>
                                    </div>
                                    <p class="help is-danger">
                                        <?= $errors['phoneNumber'] ?? '' ?>
                                    </p>
                                </div>
                            </div>
                        </div>

                    <div class="field is-horizontal">
                            <div class="field-label is-normal">
                                <label class="label" for="email">Email*</label>
                            </div>
                            <div class="field-body">
                                <div class="field">
                                    <div class="control">
                                        <input class="input" id="email" type="email" name="email" value="<?=isset($email) ? htmlentities($email) : '' ?>"/>
                                    </div>
                                    <p class="help is-danger">
                                        <?= $errors['email'] ?? '' ?>
                                    </p>
                                </div>
                            </div>
                        </div>

                    <div class="field is-horizontal  ml-8">
                            <div class="field-label is-normal">
                                <label class="label" for="street">Straat*</label>
                            </div>
                            <div class="field-body mr-4">
                                <div class="field">
                                    <div class="control">
                                        <input class="input" id="street" type="text" name="street" value="<?=isset($street) ? htmlentities($street) : '' ?>"/>
                                    </div>
                                    <p class="help is-danger">
                                        <?= $errors['street'] ?? '' ?>
                                    </p>
                                </div>
                            </div>
                            <div class="field-label is-normal">
                                <label class="label" for="addressNumber">Huisnummer*</label>
                            </div>
                            <div class="field-body">
                                <div class="field">
                                    <div class="control">
                                        <input class="input" style="width: 30%;"  id="addressNumber" type="number" name="addressNumber" value="<?=isset($addressNumber) ? htmlentities($addressNumber) : '' ?>"/>
                                    </div>
                                    <p class="help is-danger">
                                        <?= $errors['addressNumber'] ?? '' ?>
                                    </p>
                                </div>
                            </div>
                        </div>

                    <div class="field is-horizontal  ml-8">
                            <div class="field-label is-normal">
                                <label class="label" for="city">Plaats*</label>
                            </div>
                            <div class="field-body mr-4">
                                <div class="field">
                                    <div class="control">
                                        <input class="input" id="city" type="text" name="city" value="<?=isset($city) ? htmlentities($city) : '' ?>"/>
                                    </div>
                                    <p class="help is-danger">
                                        <?= $errors['city'] ?? '' ?>
                                    </p>
                                </div>
                            </div>
                            <div class="field-label is-normal">
                                <label class="label" for="zipCode">Postcode*</label>
                            </div>
                            <div class="field-body">
                                <div class="field">
                                    <div class="control">
                                        <input class="input" style="width: 50%;"  id="zipCode" type="text" name="zipCode" value="<?=isset($zipCode) ? htmlentities($zipCode) : '' ?>"/>
                                    </div>
                                    <p class="help is-danger">
                                        <?= $errors['zipCode'] ?? '' ?>
                                    </p>
                                </div>
                            </div>
                        </div>

                    <div class="field is-horizontal">
                            <div class="field-label is-normal">
                                <label class="label" for="typeAppointment">Type afspraak*</label>
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
                                        <input class="input" id="machineNumber" type="text" name="machineNumber" value="<?=isset($machineNumber) ? htmlentities($machineNumber) : '' ?>"/>
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
                                        <textarea class="input" id="comment" name="comment"><?=isset($comment) ? htmlentities($comment) : '' ?></textarea>
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
            <div class="is-flex-direction-row center is-justify-content-space-between">
                <a class="button grey mt-5" href="date_time_customer.php?id=<?=$employeeId?>&year=<?=$year?>&week=<?=$week?>">&laquo; Stap terug</a>
                <p class="mt-5">* = verplicht</p>
            </div>
        </div>
    </div>
</div>
<footer class="is-flex-direction-row is-justify-content-space-between">
    <div class="m-1">
        <h1><b>DUIJNDAM MACHINES</b></h1>
        <h2 class="mb-2"><b>Specialist in gebruikte land- en tuinbouwmachines</b></h2>
        <a class="button white mt-5" href="http://www.duijndam-machines.com"><b>Bezoek onze site>></b></a>
    </div>
    <div class="m-1">
        <h1 class="title"><b>Heeft u een vraag?</b></h1>
        <ul>
            <li>We zullen deze graag beantwoorden!</li>
            <li>(+31) (0)180 632 088</li>
            <li>info@duijndam-machines.com</li>
        </ul>
    </div>
    <div class="m-1">
        <h1 class="title"><b>Openingstijden</b></h1>
        <p>8:00-17:00 (maandag - vrijdag)</p>
    </div>
    <div class="m-1">
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