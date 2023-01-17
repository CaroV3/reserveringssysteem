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


//get data from url
$id= mysqli_escape_string($db, $_GET['id']);
// store week and year of selected appointment
// to be able to redirect to the index page with that year and week again
$weekAp= mysqli_escape_string($db, $_GET['weekAp']);
$yearAp= mysqli_escape_string($db, $_GET['yearAp']);

//get data of selected appointment from database
$query = "SELECT * FROM `appointments` WHERE id = '$id' AND user_id = '$userId'";
$result = mysqli_query($db, $query)
or die('Error '.mysqli_error($db).' with query '.$query);

//safe data of selected appointment in variable
$appointment= mysqli_fetch_assoc($result);

//store current time and date of selected appointment
//to be able to show the time that is currently selected for this appointment
$selectedTime = $appointment['time'];
$selectedDate = $appointment['date'];

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


// create array to store all the available times per day
$availableTimesWeek  = [];

//run through all the days in the week
foreach ($week as $day){
    $date = $day['date']; //store current date in variable

    //Select all data from the database with the selected date
    $query = "SELECT *
                  FROM `appointments`
                  WHERE date = '$date' AND user_id = '$userId'" ;

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
        }//if timeslot isn't occupied store that time in the array
        if (!$occurs) {
            $availableTimes[] = date('H:i', $time);
        } else { //if the timeslot is occupied store 'bezet' in the array
            $availableTimes[]= 'Bezet';
        }
    }//push the array with the available times in the week array to create a multidimensional array
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
            <a class="navbar-item" href="index.php">
                Mijn gegevens
            </a>
            <a class="navbar-item" href="logout.php">
                Log uit
            </a>
        </div>
    </div>
</nav>
<div class="container px-4 full-height">
    <h1 class="title mt-4">Wijzig datum of tijd van afspraak met <?=$appointment['name']?></h1>
    <hr>
    <?php if (($prev['week'] >= $currentWeek && $year == $currentYear) || $year > $currentYear ){?>
        <a href="?id=<?=$id?>&year=<?=$year;?>&week=<?=$prev['week'];?>&yearAp=<?=$yearAp?>&weekAp=<?=$weekAp?>" class="button button-navigation"><</a>
    <?php } else {?>
        <a disabled class="button button-navigation"><</a>
    <?php } ?>

    <a href="?id=<?=$id?>&year=<?=$year;?>&week=<?=$next['week'];?>&yearAp=<?=$yearAp?>&weekAp=<?=$weekAp?>" class="button button-navigation">></a>
    <a href="date_time_edit.php?id=<?=$id?>&yearAp=<?=$yearAp?>&weekAp=<?=$weekAp?>" class="button">Huidige week</a>
    <a class="button" href="details.php?id=<?=$id?>&year=<?=$yearAp?>&week=<?=$weekAp?>">Annuleer</a>
    <h2 class="title mt-4 has-text-centered"><?=$month?> <?=$year?></h2>
    <div class="center">
        <table class="fixed_header">
            <thead>
            <tr>
                <?php foreach ($week as $day) {
                    if ($today == $day['date']) {?>
                        <th class="has-text-success has-text-centered"><?=$day['day'];?> <br> <?= $day['dateNumber'];?></th>
                    <?php } else {?>
                        <th class="has-text-centered"><?=$day['day'];?><br> <?= $day['dateNumber'];?></th>
                    <?php } ?>
                <?php }?>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($timeSlots as $x => $time) { ?>
                <tr>
                    <?php foreach ($availableTimesWeek as $date => $availableTimesDay){
                        if (($availableTimesDay[$x] =='Bezet' && ($selectedTime != $time && $selectedDate != $date)
                                || $availableTimesDay[$x] < $currentTime && $date == $today) || $date < $today ) {?>
                            <td><a class="button disabled" disabled><?=$availableTimesDay[$x]; ?></a></td>
                        <?php } elseif ($selectedTime == $time && $selectedDate == $date ) {?>
                            <td><a class="button selected" href="edit_function.php?id=<?=$id?>&time=<?=$time;?>&date=<?=$date;?>&week=<?=$yearWeek?>&year=<?=$year?>">
                                    <?= $timeDisplay = substr($time, 0, 5);?>
                                </a></td>
                        <?php }
                        else { ?>
                            <td><a class="button" href="edit_function.php?id=<?=$id?>&time=<?=$time;?>&date=<?=$date;?>&week=<?=$yearWeek?>&year=<?=$year?>" >
                                    <?=$availableTimesDay[$x];?>
                                </a></td>
                        <?php }?>
                    <?php }?>
                </tr>
            <?php }?>
            </tbody>
        </table>
    </div>
</div>
</body>
</html>
