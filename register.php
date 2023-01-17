<?php
if (isset($_POST['submit'])) {
    /** @var mysqli $db */
    require_once "includes/connection.php";

// Get form data
    $name = mysqli_escape_string($db, $_POST['name']);
    $email = mysqli_escape_string($db, $_POST['email']);
    $password = $_POST['password'];

    $query = "SELECT * FROM `users` WHERE email='$email'";
    $result = mysqli_query($db, $query);

// Server-side validation
    $errors = [];
    if ($name == "") {
        $errors['name'] = 'Vul alstublieft uw naam in';
    }
    if ($email == "") {
        $errors['email'] = 'Vul alstublieft uw email in';
    }

    // check if the email is already used
    if ($email != "" && mysqli_num_rows($result) == 1) {
        $errors['email'] = 'Dit e-mailadres is al in gebruik';
    }

    require_once 'includes/password_validation.php';


// If data valid
    if (empty($errors)) {
        // create a secure password, with the PHP function password_hash()
        $password = password_hash($password, PASSWORD_DEFAULT);

        // store the new user in the database.
        $query = "INSERT INTO users (name, email, password)
                  VALUES ('$name', '$email', '$password')";
        $result = mysqli_query($db, $query) or die('Error: ' . mysqli_error($db) . ' with query ' . $query);

        // If query succeeded
        if ($result) {
            // Redirect to login page
            header('Location: login.php');

            // Exit the code
            exit;
        }
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
    <link rel="stylesheet" href="css/style.css"/>
    <title>Registreren</title>
</head>
<body class="background-image">
<nav>
    <div class="navbar-brand centered">
        <a href="https://www.duijndam-machines.com/nl/" target="_blank">
            <img src="img/logo.png" alt="Logo Duijndam" width="210" height="84">
        </a>
        <p class="navbar-item addition-logo">Reserveringssysteem</p>
    </div>
</nav>
<div class="section full-height">
    <form action="" method="post">

        <div class=" mt-6 ml-6 register-login">
            <h2 class="title has-text-centered">Registreren</h2>
            <div class="is-flex-direction-column">
                <div class="login-field ">
                    <label class="label ml-3" for="email">Naam</label>
                    <div class="control has-icons-left">
                        <input class="input " id="name" type="text" name="name" value="<?= $name ?? '' ?>"/>
                        <span class="icon is-small is-left"><i class="fas fa-user"></i></span>
                    </div>
                    <p class="ml-3 help is-danger">
                        <?= $errors['name'] ?? '' ?>
                    </p>
                </div>

                <div class="login-field mt-3">
                    <label class="label ml-3" for="password">Email</label>
                    <div class="control has-icons-left">
                        <input class="input " id="email" type="text" name="email" value="<?= $email ?? '' ?>"/>
                        <span class="icon is-small is-left"><i class="fas fa-envelope"></i></span>
                    </div>
                    <p class="ml-3 help is-danger">
                        <?= $errors['email'] ?? '' ?>
                    </p>
                </div>

                <div class="login-field mt-3">
                    <label class="label ml-3" for="password">Wachtwoord</label>
                    <div class="control has-icons-left">
                        <input class="input " id="password" type="password" name="password"/>
                        <span class="icon is-small is-left"><i class="fas fa-lock"></i></span>
                    </div>
                    <p class="ml-3 help is-danger">
                        <?= $errors['password'] ?? '' ?>
                    </p>
                </div>
                <div class="center">
                    <button class="button button-cell  mt-3" type="submit" name="submit">Registreer</button>
                </div>
            </div>

        </div>

        <div class="center is-flex-direction-column register-login mt-6 ml-6">
            <p class="has-text-centered">Al een account?</p>
            <a class="button button-cell grey center mt-3" href="login.php">Log in</a>
        </div>
    </form>

</div>
</body>
</html>