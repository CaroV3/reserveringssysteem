<?php
session_start();

if (isset($_POST['submit'])) {
    /** @var mysqli $db */
    require_once "includes/connection.php";

    // Get form data
    $email = mysqli_escape_string($db, $_POST['email']);
    $password = $_POST['password'];

    // Server-side validation
    $errors = [];
    if ($email == '') {
        $errors['email'] = 'Vul alstublieft uw email in';
    }
    if ($password == '') {
        $errors['password'] = 'Vul alstublieft uw wachtwoord in';
    }

    // If data valid
    if (empty($errors)) {
        // SELECT the user from the database, based on the email address.
        $query = "SELECT * FROM users WHERE email='$email'";
        $result = mysqli_query($db, $query);

        // check if the user exists
        if (mysqli_num_rows($result) == 1) {
            // Get user data from result
            $user = mysqli_fetch_assoc($result);

            // Check if the provided password matches the stored password in the database
            if (password_verify($password, $user['password'])) {

                // Store the user in the session
                $_SESSION['loggedInUser'] = [
                    'id'    => $user['id'],
                    'name'  => $user['name'],
                    'email' => $user['email'],
                ];

                // Redirect to secure page
                header('Location: index.php');
                exit;
            } else {
                //error incorrect log in
                $errors['loginFailed'] = 'Wachtwoord of gebruikersnaam onjuist';
            }
        } else {
            //error incorrect log in
            $errors['loginFailed'] = 'Wachtwoord of gebruikersnaam onjuist';
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
    <title>Log in</title>
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
                <h2 class="title has-text-centered">Welkom!</h2>
                <div class="is-flex-direction-column">
                    <div class="login-field ">
                        <label class="label ml-3" for="email">Email</label>
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
                        <div class="control is-expanded has-icons-left">
                            <input class="input" id="password" type="password" name="password"/>
                            <span class="icon is-small is-left"><i class="fas fa-lock"></i></span>

                        </div>
                    </div>
                    <?php if (isset($errors['loginFailed'])) { ?>
                        <div class="notification is-danger mt-4">
                            <?= $errors['loginFailed'] ?>
                        </div>
                    <?php } ?>
                    <p class="ml-3 help is-danger">
                        <?= $errors['password'] ?? '' ?>
                    </p>
                    <div class="center is-flex-direction-column">
                        <button class="button button-cell center mt-3" type="submit" name="submit">Log in</button>
                        <a class="password-link mt-4" href="login.php" >Wachtwoord vergeten?</a>
                    </div>

                </div>

            </div>

            <div class="center is-flex-direction-column register-login mt-6 ml-6">
                <p class="has-text-centered">Nog geen account?</p>
                <a class="button button-cell grey center mt-3" href="register.php">Registreer</a>
            </div>
        </form>
    </div>

</body>
</html>
