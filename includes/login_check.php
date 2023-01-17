<?php
session_start();

//May the user visit the page?
if (!isset($_SESSION['loggedInUser'])) {
    header("Location: login.php");
    exit;
}

//Get id of logged in user
$userId= $_SESSION['loggedInUser']['id'];

