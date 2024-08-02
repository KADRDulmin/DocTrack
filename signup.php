<?php
ob_start();
require "db/DataBase.php";

// Start the session
session_start();

// Check if the user is already logged in
if (isset($_SESSION['DoctorID'])) {
    // If logged in, redirect to a dashboard or home page
    header("Location: index.php");
    exit();
}

$db = new DataBase();
if ($db->dbConnect()) {
?>  
    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Dr-care</title>
        <link rel="stylesheet" href="styles/new_style.css">

    </head>

    <body>
        <?php include('header.php'); ?>
        <script type="text/javascript" src="js/light-dark.js"></script>


        <div class="login-page">
            <div class="form">
                <img src="imgs/logo.png" style="width: 80%; height: auto; margin-bottom: 1.2rem;">
                <form class="login-form" id="login-form" name="Login" action="" method="POST">
                    <input type="text" name="FirstName" required placeholder="First Name" />
                    <input type="text" name="LastName" required placeholder="Last Name" />
                    <input type="email" name="Email" required placeholder="Email" />
                    <input type="text" name="Specialization" required placeholder="Specialist In" />
                    <input type="password" name="Password" required placeholder="Password" />
                    <input type="submit" class="login-submit" value="create">
                    <p class="message">Already registered? <a href="login.php">Sign In</a></p>

                <div class="errortext">

                <?php
                if (isset($_POST['FirstName']) && isset($_POST['LastName']) && isset($_POST['Email']) && isset($_POST['Specialization']) && isset($_POST['Password'])) {
                    if ($db->signUp("doctors", $_POST['Email'], $_POST['Password'], $_POST['FirstName'], $_POST['LastName'], $_POST['Specialization'])) {
                        echo "Sign Up Success";
                        header("Location: login.php");
                    } else echo "<br> Sign up Failed";
                } else echo "All fields are required";
            } else echo "Error: Database connection";
                ?>
                </div>
                </form>
            </div>
        </div>
        <script>
            $('.message a').click(function() {
                $('form').animate({
                    height: "toggle",
                    opacity: "toggle"
                }, "slow");
            });
        </script>

        <?php include('footer.php'); ?>
    </body>

    </html>