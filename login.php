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
    <title>Login - DrCare</title>
    <link rel="stylesheet" href="styles/new_style.css">

  </head>

  <body>
    <?php include('header.php'); ?>
    <script type="text/javascript" src="js/light-dark.js"></script>


    <div class="login-page">
      <div class="form">
        <img src="imgs/logo.png" style="width: 80%; height: auto; margin-bottom: 1.2rem;">
        <!-- <form class="register-form">
      <input type="text" placeholder="name"/>
      <input type="password" placeholder="password"/>
      <input type="text" placeholder="email address"/>
      <button>create</button>
      <p class="message">Already registered? <a href="#">Sign In</a></p>
    </form> -->
        <form class="login-form" id="login-form" name="Login" action="" method="POST">
          <input type="Email" name="Email" placeholder="Email" />
          <input type="password" name="Password" placeholder="Password" />
          <input type="submit" class="login-submit" value="login">

          <div class="errortext">

          <?php
          if (isset($_POST['Email']) && isset($_POST['Password'])) {
            if ($db->logIn("doctors", $_POST['Email'], $_POST['Password'])) {
              $_SESSION['Email'] = $db->prepareData($_POST['Email']);
              // Set a temporary session timeout
              $_SESSION['timeout'] = time() + 3600;
              header("Location: index.php");
              exit();
            } else {
              // echo "Username or Password wrong";
            }
          } else echo "All fields are required";
        } else echo "Database Connection Error";
        ob_end_flush(); // Send the buffered output to the browser and turn off output buffering
          ?>
          </div>
          <p class="message">Not registered? <a href="signup.php">Create an account</a></p>
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