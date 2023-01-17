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

//Select all rows from database of the logged in user
$query = "SELECT * FROM `appointments` WHERE user_id = '$userId' ";
$result = mysqli_query($db, $query)
or die('Error '.mysqli_error($db).' with query '.$query);

// Store the appointments in an array
$appointments = [];
while($row = mysqli_fetch_assoc($result))
{
    $appointments[] = $row;
}

// Close the connection with the database
mysqli_close($db);

//create a date and time object
$dateTime = new DateTime;

//create variable that contains today's date in the format year-month-day
$today = $dateTime->format("Y-m-d");

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
$month = $dateTime->format("F");  //create variable with name of the month
$yearWeek = $dateTime->format('W'); //create variable with the number of the week

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

?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Afspraken</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.9.4/css/bulma.min.css">
    <link rel="stylesheet" href="css/style.css"/>
</head>
<body class="normal">
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

<div class="container px-4">
    <h1 class="title mt-5">Mijn afspraken</h1>
    <hr style="height:2px;border-width:0;background-color:#A6B523">
    <div class="top-calender mb-5">
        <div>
            <a href="?year=<?=$year;?>&week=<?=$prev['week'];?>" class="button button-navigation"><</a>
            <a href="?year=<?=$year;?>&week=<?=$next['week'];?>" class="button button-navigation">></a>
            <a href="index.php" class="button">Huidige week</a>
        </div>
        <div class="title-calender">
            <h2 class="title"><?=$month?> <?=$year?></h2>
        </div>
        <div class="search-bar">
            <label for="search-bar">
                <img  src="img/search.png" alt="search icon" class="icon mt-2 mr-2"/>
            </label>
            <form>
                <input class="input"  id=search-bar placeholder="Zoek in afspraken" value="<?= $street ?? '' ?>">
            </form>
        </div>
    </div>

    <div class="columns is-centered">
        <div class="column is-narrow">
            <table class="calender">
                <thead>
                <tr>
                    <th></th>
                    <?php foreach ($week as $day) {
                        if ($today == $day['date']) {?>
                            <th class="has-text-success "><?=$day['day'];?>  <?= $day['dateNumber'];?></th>
                        <?php } else {?>
                            <th><?=$day['day'];?>  <?= $day['dateNumber'];?></th>
                        <?php } ?>
                    <?php } ?>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($timeSlots as $time) { ?>
                    <tr>
                        <td><?= $timeDisplay = substr($time, 0, 5) ?></td>
                        <?php foreach ($week as $day) { ?>
                            <td class="is-center is-vcentered"><?php foreach ($appointments as $appointment) {
                                    if ($appointment['date'] == $day['date']) {
                                        if ($appointment['time'] == $time) {?>
                                            <a class="button button-cell" href="details.php?id=<?=$appointment['id']?>&year=<?=$year?>&week=<?=$yearWeek?>">
                                                <p class="if-overflow"><?= $appointment['name'];?></p>
                                            </a>
                                        <?php } ?>
                                    <?php }?>
                                <?php } ?>
                            </td>
                        <?php } ?>
                    </tr>
                <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
</body>
</html>