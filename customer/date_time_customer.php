<?php
/** @var array $appointments */
/** @var $db */
/** @var $timeSlots */
/** @var $userId */

//Require database in this file
require_once '../includes/connection.php';
//Require times and days of the week in this file
require_once '../includes/data.php';

//get employee id from url
$employeeId = mysqli_escape_string($db, $_GET['id']);

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
                  WHERE date = '$date' AND user_id = '$employeeId'";

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
    <div class="section centered">
        <div class="content-customer bigger-content has-text-centered">
            <h1 class="title">Kies datum en tijd</h1>
            <hr style="height:2px;border-width:0;background-color:#A6B523;margin:auto">
            <div class="top-calender">
                <div>
                    <?php if (($prev['week'] >= $currentWeek && $year == $currentYear) || $year > $currentYear ){?>
                        <a href="?year=<?=$year;?>&week=<?=$prev['week'];?>&id=<?=$employeeId?>" class="button mt-3 button-navigation"><</a>
                    <?php } else {?>
                        <a disabled class="button button-navigation mt-3"><</a>
                    <?php } ?>
                    <a href="?year=<?=$year;?>&week=<?=$next['week'];?>&id=<?=$employeeId?>" class="button mt-3 button-navigation">></a>
                </div>
                <h2 class="title mt-3"><?=$month?> <?=$year?></h2>
                <a href="date_time_customer.php?id=<?=$employeeId?>" class="button mt-3">Huidige week</a>

            </div>

            <div class="centered">
                <table class="fixed_header">
                    <thead>
                    <tr>
                        <?php foreach ($week as $day) {
                            if ($today == $day['date']) {?>
                                <th class="has-text-success has-text-centered"><?=$day['day'];?> <br> <?=$day['dateNumber'];?></th>
                            <?php } else {?>
                                <th class="has-text-centered" ><?=$day['day'];?> <br> <?= $day['dateNumber'];?></th>
                            <?php } ?>
                        <?php }?>
                    </tr>
                    </thead>
                    <tbody class="date_time">
                    <?php for ($x = 0; $x <= 16; $x++) {?>
                        <tr>
                            <?php foreach ($availableTimesWeek as $date => $availableTimesDay){
                                if (($availableTimesDay[$x] =='Bezet' || $availableTimesDay[$x] < $currentTime && $date == $today) || $date < $today ) {?>
                                    <td><a class="button disabled" disabled><?=$availableTimesDay[$x]; ?></a></td>
                                <?php } else {?>
                                    <td><a class="button" href="create_customer.php?id=<?=$employeeId?>&time=<?=$availableTimesDay[$x];?>&date=<?=$date?>&year=<?=$year?>&week=<?=$yearWeek?>"><?=$availableTimesDay[$x];?></a></td>
                                <?php } ?>
                            <?php }?>
                        </tr>
                    <?php }?>
                    </tbody>
                </table>
            </div>
            <div class="is-flex-direction-row center is-justify-content-space-between">
                <a class="button grey mt-5" href="start.php?id=<?=$employeeId?>">&laquo; Stap terug</a>
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