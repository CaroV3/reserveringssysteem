<?php

$name   = mysqli_escape_string($db, $_POST['name']);
$phoneNumber = mysqli_escape_string($db, $_POST['phoneNumber']);
$email  = mysqli_escape_string($db, $_POST['email']);
$street = mysqli_escape_string($db, $_POST['street']);
$addressNumber =  mysqli_escape_string($db, $_POST['addressNumber']);
$city = mysqli_escape_string($db, $_POST['city']);
$zipCode = mysqli_escape_string($db, $_POST['zipCode']);
$address = $street." ".$addressNumber.", ".$zipCode." ".$city;
$typeAppointment   = mysqli_escape_string($db, $_POST['typeAppointment']);
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