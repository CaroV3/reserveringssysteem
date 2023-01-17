<?php
/** @var mysqli $db */
/** @var $timeSlots */
/** @var $userId */

//require times and days of the week in this file
require_once '../includes/data.php';
//Check if user is logged in
require_once '../includes/login_check.php';
require_once '../includes/connection.php';

//get data from url
$date = $_GET['date'];

$selectedTime = '';

//create a date and time object
$dateTime = new DateTime;

//create variables that contains the time and date of today
$today = $dateTime->format("Y-m-d"); //year-month-day
$currentWeek = $dateTime->format("W"); //number of the week
$currentYear = $dateTime->format("o"); //number of the year
$currentTime = $dateTime->format("G:i"); //time 24-hour format with minutes

//if the next or previous is pressed
if (isset($_GET['year']) && isset($_GET['week'])) {
    //Determine which year and week the variable $dateTime contain
    //Everytime $dateTime is used now, it outputs the altered date and time
    //Make sure the every week starts on monday
    $dateTime->setISODate($_GET['year'], $_GET['week']);
} else {
    $dateTime->setISODate($dateTime->format('o'), $dateTime->format('W'));
}

//create variables with the altered $dateTime variable
$year = $dateTime->format('o'); //create variable with the number of year
$yearWeek = $dateTime->format('W'); //create variable with name of the month
$month = $dateTime->format("F"); //create variable with the number of the week

//adds or subtracts the week by one
$prev = ['week' => $yearWeek-1];
$next = ['week' => $yearWeek+1];

//determine the amount of days shown
$day_count = 5;

$week = []; //create an array to store the days
$day = []; //create an array to store certain data about that day

for ($x = 1; $x <= $day_count; $x++) {

    $day['day'] = $dateTime->format('l'); //create name of the day of the week

    $day['dateNumber'] = $dateTime->format('jS'); //create number of the day

    $day['date'] = $dateTime->format("Y-m-d"); //create date in format year-month-day

    //push array $day in array $week to create multidimensional array
    array_push($week, $day);

    //empty array $day
    $day = [];

    //Go to the next day
    $dateTime->modify('+1 day');
}

//create array to store all the available times per day
$availableTimesWeek  = [];

//run through all the days in the week
if (isset($_GET['date']) && !empty($_GET['date'])) {
// Haal de datum op
$date = mysqli_escape_string($db, $_GET['date']);

// Haal de reserveringen uit de database voor een specifieke datum
$query = "SELECT *
              FROM `afspraken`
              WHERE date = '$date'";

$result = mysqli_query($db, $query);

if ($result) {
    $reservations = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $appointments[] = $row;
    }
}

    //Select all data from the database with the selected date
    $query = "SELECT *
                  FROM `afspraken`
                  WHERE date = '$date' AND user_id = '$userId'";

    $result = mysqli_query($db, $query);

    if ($result) {
        $appointments = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $appointments[] = $row;
        }
    }

    // create array to store available times
   $availableTimes = [];

    // doorloop alle tijden (van 9:00 - 17:00)
    foreach ($timeSlots as $time) {
        $time = strtotime($time);
        $occurs = false;
        // controleer de tijd tegen ALLE reserveringen van die dag
        foreach ($appointments as $appointment) {
            $startTime = strtotime($appointment['start_time']);
            $endTime = strtotime($appointment['end_time']);
            // ALS de tijd van de begintijd tot de eindtijd van
            // een reservering valt voeg deze tijd ($time) niet
            // toe aan availableTimes
            if ($time >= $startTime &&
                $time < $endTime) {
                $occurs = true;
            }
        }

        if (!$occurs) {
            $availableTimes[] = date('H:i', $time);
        }
    }
}

//Check if Post isset, else do nothing
if (isset($_POST['submit'])) {
    //Require database in this file
    require_once "includes/connection.php";

    //Postback with the data showed to the user, first retrieve data from 'Super global'
    $name   = $_POST['name'];
    $phoneNumber = $_POST['phoneNumber'];
    $email  = $_POST['email'];
    $street = $_POST['street'];
    $addressNumber = $_POST['addressNumber'];
    $city = $_POST['city'];
    $zipCode = $_POST['zipCode'];
    $address = $street." ".$addressNumber.", ".$zipCode." ".$city;
    $typeAppointment   = $_POST['typeAppointment'];
    $machineNumber = $_POST['machineNumber'];
    $comment = $_POST['comment'];


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
    if ($street == "") {
        $errors['street'] = 'cannot be empty';
    }

    if ($addressNumber == "") {
        $errors['addressNumber'] = 'cannot be empty';
    }

    if ($city == "") {
        $errors['city'] = 'cannot be empty';
    }

    if ($zipCode == "") {
        $errors['zipCode'] = 'cannot be empty';
    }

    if ($typeAppointment == "default") {
        $errors['typeAppointment'] = 'cannot be empty';
    }
    //if everything is filled in correctly by the user
    if (empty($errors)) {
        //save filled in data in the database
        $query = "INSERT INTO afspraken (name, phone_number, email, address, type_appointment_id, machine_number, time, date, comment, user_id)
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
    <link rel="stylesheet" href="../css/style.css">
    <title>Afspraak plannen</title>
</head>
<body>
<div class="container px-4">
    <h1 class="title mt-4">Plan privé afspraak</h1>

    <section class="columns">
        <form class="column is-6" action="" method="post" enctype="multipart/form-data">
            <div class="field is-horizontal">
                <div class="field-label is-normal">
                    <label class="label" for="title">Titel</label>
                </div>
                <div class="field-body">
                    <div class="field">
                        <div class="control">
                            <input class="input" id="title" type="text" name="title" value="<?= $title ?? '' ?>"/>
                        </div>
                        <p class="help is-danger">
                            <?= $errors['title'] ?? ''?>
                        </p>
                    </div>
                </div>
            </div>

            <?php if (($prev['week'] >= $currentWeek && $year == $currentYear) || $year > $currentYear ){?>
                <a href="?year=<?=$year;?>&week=<?=$prev['week'];?>" class="button button-navigation"><</a>
            <?php } else {?>
                <a disabled class="button button-navigation"><</a>
            <?php } ?>
            <a href="?year=<?=$year;?>&week=<?=$next['week'];?>" class="button button-navigation">></a>
            <h2 class="title mt-4"><?=$month?> <?=$year?></h2>
            <div class="field is-horizontal">
                <div class="field-label is-normal">
                    <label class="label" for="date">Datum</label>
                </div>
                <?php foreach ($week as $day) {
                    if ($today == $day['date']) {?>
                        <a class="button button-cell grey has-text-success" href="create_private.php?date=<?=$day["date"]?><?=$day['day'];?>"><?= $day['dateNumber'];?></a>
                    <?php } else {?>
                        <a class="button button-cell grey" href="create_private.php?date=<?=$day["date"]?><?=$day['day'];?>"><?= $day['dateNumber'];?></a>
                    <?php } ?>
                <?php } ?>
            </div>

            <div class="field is-horizontal">
                <div class="field-label is-normal">
                    <label class="label" for="time">Tijd</label>
                </div>
                <div class="field-body">
                    <div class="field">
                        <label class="label" for="email">Van</label>
                        <div class="control">
                            <select>
                                <?php foreach ($availableTimes as $availableTime) { ?>
                                    <option value="<?= $availableTime ?>" <?= $selectedTime == $availableTime ? 'selected' : '' ?>><?= $availableTime ?></option>
                                <?php } ?>
                            </select>
                        </div>
                        <p class="help is-danger">
                            <?= $errors['email'] ?? '' ?>
                        </p>
                        <label class="label" for="email">Tot</label>
                        <div class="control">
                            <select>
                                <?php foreach ($availableTimes as $availableTime) { ?>
                                    <option value="<?= $availableTime ?>" <?= $selectedTime == $availableTime ? 'selected' : '' ?>><?= $availableTime ?></option>
                                <?php } ?>
                            </select>
                        </div>
                        <p class="help is-danger">
                            <?= $errors['email'] ?? '' ?>
                        </p>
                    </div>
                </div>
            </div>

            <div class="field is-horizontal">
                <div class="field-label is-normal">
                    <label class="label" for="description">Beschrijving</label>
                </div>
                <div class="field-body mr-4">
                    <div class="field">
                        <div class="control">
                            <input class="input" id="description" type="text" name="description" value="<?= $street ?? '' ?>"/>
                        </div>
                        <p class="help is-danger">
                            <?= $errors['street'] ?? '' ?>
                        </p>
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
    <a class="button mt-4" href="../date_time.php">&laquo; Andere datum en tijd kiezen</a>
    <a class="button mt-4" href="../index.php">&laquo; Terug naar afspraken overzicht</a>
</div>
</body>
</html>