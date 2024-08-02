<?php
// Start a session
ob_start();
session_start();

// Check if the user is not logged in, redirect to login.php
if (!isset($_SESSION['DoctorID'])) {
    header("Location: login.php");
    exit();
}

// Linking Database.php
require "db/DataBase.php";
$database = new DataBase();
$conn = $database->dbConnect();
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FORMS</title>
    <link rel="stylesheet" href="styles/doctordescription.css">
</head>

<body>
    <?php include('header.php'); ?>
    <script type="text/javascript" src="js/light-dark.js"></script>
    <div class="container">
        <div class="detail-container">
            <h1>Doctor Details</h1>
            <form action="edit_doctor_process.php" class="form" method="post" enctype="multipart/form-data">
                <div class="input-box">
                    <label>First Name* :</label>
                    <input type="text" name="firstname" placeholder="Enter First Name" />
                </div>
                <div class="input-box">
                    <label>Last Name* :</label>
                    <input type="text" name="lastname" placeholder="Enter Last Name" />
                </div>

                <div class="input-box">
                <label>Profile Photo Link* :</label>
                    <input type="text" name="profile_photo" placeholder="Paste the image link here" />
                </div>

                <div class="gender-box">
                    <label>Gender</label>
                    <div class="gender-option">
                        <div class="gender">
                            <input type="radio" id="check-male" name="gender" value="Male" checked />
                            <label for="check-male">Male</label>
                        </div>
                        <div class="gender">
                            <input type="radio" id="check-female" name="gender" value="Female" />
                            <label for="check-female">Female</label>
                        </div>
                        <div class="gender">
                            <input type="radio" id="check-other" name="gender" value="Prefer not to say" />
                            <label for="check-other">Prefer not to say</label>
                        </div>
                    </div>
                </div>

                <div class="input-box">
                    <label>Hospital that you are assigned to* :</label>
                    <input type="text" name="hospital" placeholder="Working Hospital" required />
                </div>
                <div class="input-box">
                    <label>The Clinic That you are treating your patients* :</label>
                    <input type="text" name="clinic" placeholder="Clinic Name" required />
                </div>
                <div class="input-box">
                    <label>A small description about yourself* :</label>
                    <input type="text" name="description" placeholder="Description" required />
                </div>
                <div class="input-box">
                    <label>Specialized In: (This will be Displayed)*</label>
                    <input type="text" name="specialization1" placeholder="Specialized In" required />
                </div>
                <div class="input-box">
                    <label>Specialized In:</label>
                    <input type="text" name="specialization2" placeholder="Specialized In" />
                </div>
                <div class="input-box">
                    <label>Specialized In:</label>
                    <input type="text" name="specialization3" placeholder="Specialized In" />
                </div>
                <div class="input-box">
                    <label>Working Experience:</label>
                    <input type="text" name="experience" placeholder="Experience" />
                </div>
                <div class="input-box">
                    <label>Contact* :</label>
                    <input type="text" name="contact" placeholder="Contact Number" pattern="[0-9]{10}" minlength="10" maxlength="10" required />
                </div>

                <div class="">
                    <input type="submit" name="add" value="Update Details" class="button1">
                </div>
            </form>




        </div>
    </div>

    <?php include('footer.php'); ?>
</body>

</html>