<?php
require_once 'includes/connection.php';
/** @var $db */

//get data from url
$id = mysqli_escape_string($db, $_GET['id']);

// store week and year of selected appointment
// to be able to redirect to the index page with that year and week again


// store time and date to save in the database
$time = mysqli_escape_string($db, $_GET['time']);
$date = mysqli_escape_string($db, $_GET['date']);

// store week and year of selected appointment
// to be able to redirect to the index page with that year and week again
$week = mysqli_escape_string($db, $_GET['week']);
$year = mysqli_escape_string($db, $_GET['year']);

//save new time and date in the database
    $queryEdit = "UPDATE `appointments` 
                    SET time = '$time', date = '$date'
                    WHERE id = '$id'";
    $result = mysqli_query($db, $queryEdit)
    or die('Error ' . mysqli_error($db) . ' with query ' . $queryEdit);

    //redirect to index page with the right year and week
    header("Location: index.php?year=$year&week=$week");
    exit;
