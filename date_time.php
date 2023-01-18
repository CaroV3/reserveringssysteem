<?php
/** @var array $appointments */
/** @var $db */
/** @var $timeSlots */
/** @var $userId */

//Require database in this file
require_once 'includes/connection.php';
//Require times and days of the week in this file
require_once 'includes/data.php';
//Check if user is logged in
require_once 'includes/login_check.php';

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
foreach ($week as $day){
    $date = $day['date']; //store current date in variable

    //Select all data from the database with the selected date
    $query = "SELECT *
                  FROM `appointments`
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

    // run through all the times (9:00-17:00)
    foreach ($timeSlots as $time) {
        $time = strtotime($time);
        $occurs = false;
        // run through all appointments and compare the time
        // to the times of all the appointments that day
        foreach ($appointments as $appointment) {
            //store the time of the appointment in a variable
            $occupiedTime = strtotime($appointment['time']);
            //if the time is equal to the occupied time then that certain timeslot is occupied
            if ($time == $occupiedTime) {
                $occurs = true;
            }
        } //if timeslot isn't occupied, store that timeslot in the array
        if (!$occurs) {
            $availableTimes[] = date('H:i', $time);
        } else { //if the timeslot is occupied store 'bezet' in the array
            $availableTimes[]= 'Bezet';
        }
    } //push the array with the available times in the week array to create a multidimensional array
    $availableTimesWeek[$day['date']] = $availableTimes;
}
//Close connection with the database
mysqli_close($db);
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Afspraken</title>
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
                <a class="navbar-link is-active">
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
    <h1 class="title mt-4">Kies datum en tijd</h1>
    <hr style="height:2px;border-width:0;background-color:#A6B523;margin:auto">
    <?php if (($prev['week'] >= $currentWeek && $year == $currentYear) || $year > $currentYear ){?>
        <a href="?year=<?=$year;?>&week=<?=$prev['week'];?>" class="button mt-5 button-navigation"><</a>
    <?php } else {?>
        <a disabled class="button button-navigation mt-5"><</a>
    <?php } ?>
    <a href="?year=<?=$year;?>&week=<?=$next['week'];?>" class="button mt-5 button-navigation">></a>
    <a href="date_time.php" class="button mt-5">Huidige week</a>

    <h2 class="title mt-4 has-text-centered"><?=$month?> <?=$year?></h2>
    <div class="centered">
        <table class="fixed_header">
            <thead>
            <tr>
                <?php foreach ($week as $day) {
                    if ($today == $day['date']) {?>
                        <th class="has-text-success has-text-centered"><?=$day['day'];?> <br> <?= $day['dateNumber'];?></th>
                    <?php } else {?>
                        <th class="has-text-centered" ><?=$day['day'];?> <br> <?= $day['dateNumber'];?></th>
                    <?php } ?>
                <?php }?>
            </tr>
            </thead>
            <tbody>
            <?php for ($x = 0; $x <= 16; $x++) {?>
                <tr>
                    <?php foreach ($availableTimesWeek as $date => $availableTimesDay){
                        if (($availableTimesDay[$x] =='Bezet' || $availableTimesDay[$x] < $currentTime && $date == $today) || $date < $today ) {?>
                            <td><a class="button disabled" disabled><?=$availableTimesDay[$x]; ?></a></td>
                        <?php } else {?>
                            <td><a class="button" href="create.php?time=<?=$availableTimesDay[$x]?>&date=<?=$date?>&year=<?=$year?>&week=<?=$yearWeek?>"><?=$availableTimesDay[$x];?></a></td>
                        <?php } ?>
                    <?php }?>
                </tr>
            <?php }?>
            </tbody>
        </table>
    </div>
</div>
</body>
</html>