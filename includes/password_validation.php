<?php
/** @var $password */

if ($password == "") {
    $errors['password'] = 'Vul alstublieft uw wachtwoord in';
}

if ($password != "" && strlen($password) < 12 ) {
    $errors['password'] = 'Het wachtwoord moet minstens 12 tekens lang zijn';
}

if ($password != "" && preg_match('/\s/',$password) > 0) {
    $errors['password'] = 'Het wachtwoord mag geen spaties bevatten';
}

if($password != "" && preg_match('/\s/',$password) < 1 && preg_match("/\d/",$password) < 1 && strlen($password) >= 12) {
    $errors['password'] = 'Het wachtwoord moet een getal bevatten';
}

if($password != "" && preg_match('/\s/',$password) < 1 && strlen($password) >= 12 && preg_match('/[A-Z]/', $password) < 1){
    $errors['password'] = 'Het wachtwoord moet een hoofdletter bevatten';
}
