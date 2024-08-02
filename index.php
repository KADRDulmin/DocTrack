<?php
// Start a session
ob_start();
session_start();

// Check if the user is not logged in, redirect to login.php
if (!isset($_SESSION['DoctorID'])) {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">
    <link rel="stylesheet" href="styles/dashboard.css">
    <title>Dashboard - DrCare</title>
    <link rel="icon" href="imgs/drcare.ico" type="image/x-icon">
</head>

<body style="color: white;">
    <?php include('header.php'); ?>
    <script type="text/javascript" src="js/light-dark.js"></script>



    <div class="container">
        <div class="sidebar sidebar--admin">
            <?php
            // Linking Database.php
            require "db/DataBase.php";
            $database = new DataBase();
            $conn = $database->dbConnect();
            if (!$conn) {
                die("Connection failed: " . mysqli_connect_error());
            }

            // Get the DoctorID from $_SESSION['DoctorID']
            $doctorID = $_SESSION['DoctorID'];
            // Showing All the Diagnosis and Medications relavant to the Patient

            $sql = "SELECT * FROM Doctors WHERE DoctorID = $doctorID";
            $result = mysqli_query($conn, $sql);
            while ($data = mysqli_fetch_array($result)) {
            ?>

                <!-- Profile Image !-->
                <div class="dpfp">
                    <img src="imgs/Logo.png" alt="Profile Image">
                </div>
                <!-- End of Profile Image -->
                <div class="h3sb">
                    <h3>Dr. <?php echo $data['FirstName']; ?> <?php echo $data['LastName']; ?></h3>
                </div>
            <?php
            }
            ?>

            <button class="button button--add" onclick="location.href='MyProfile.php'">My Profile</button>
        </div>
        <div class="main main--team">
            <section>
                <?php
                if (isset($_SESSION["create"])) {
                ?>
                    <div class="alert alert-success">
                        <?php
                        echo $_SESSION["create"];
                        ?>
                    </div>
                <?php
                    unset($_SESSION["create"]);
                }
                ?>
                <?php
                if (isset($_SESSION["update"])) {
                ?>
                    <div class="alert alert-success">
                        <?php
                        echo $_SESSION["update"];
                        ?>
                    </div>
                <?php
                    unset($_SESSION["update"]);
                }
                ?>
                <?php
                if (isset($_SESSION["delete"])) {
                ?>
                    <div class="alert alert-success">
                        <?php
                        echo $_SESSION["delete"];
                        ?>
                    </div>
                <?php
                    unset($_SESSION["delete"]);
                }
                ?>
                <ul class="numbers numbers--featured">
                    <li>
                        <button class="profile-button">
                            <div class="profile-content">
                                <div class="number-avatar">
                                    <img src="imgs/Logo.png" alt="Patient's Overview" class="profile-image">
                                </div>
                                <a href="patientlist.php">
                                <div class="number-meta">
                                    <span class="name button-text">Your Patient's Overview</span>
                                </div>
                                </a>
                            </div>
                        </button>&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp
                        <button class="profile-button">
                            <div class="profile-content">
                                <div class="number-avatar">
                                    <img src="imgs/Logo.png" alt="Add a New Patient" class="profile-image">
                                </div>
                                <a href="patientInfoIn.php">
                                    <div class="number-meta">
                                        <span class="name button-text">Add New Patient</span>
                                    </div>
                                </a>
                            </div>
                        </button>
                    </li>
                </ul>
            </section>

            <section class="has-top-border">
                <?php
                // Fetch patients treated by the logged-in doctor
                $sqlPatients = "SELECT p.*, m.* 
                FROM patients p 
                LEFT JOIN MedicalRecords m ON p.PatientID = m.PatientID 
                WHERE m.RecordID IN (SELECT MAX(RecordID) FROM MedicalRecords GROUP BY PatientID) 
                AND p.PatientID IN (SELECT PatientID FROM Appointments WHERE DoctorID = $doctorID)
                ORDER BY m.DateAdded DESC";

                $resultPatients = mysqli_query($conn, $sqlPatients);
                $prevPatientID = null; // Variable to keep track of the previous patient ID

                while ($row = mysqli_fetch_assoc($resultPatients)) {
                    // Check if it's a new patient
                    if ($row['PatientID'] != $prevPatientID) {
                        // Truncate the Diagnosis text if it's longer than 50 characters
                        $diagnosis = strlen($row['Diagnosis']) > 50 ? substr($row['Diagnosis'], 0, 50) . "...read more" : $row['Diagnosis'];
                ?>
                        <!-- Display patient details -->
                        <a href="PatientView.php?id=<?php echo $row['PatientID']; ?>">
                            <div class="recent-patients">
                                <h5 class="recent-patients-text"><?php echo $row['FirstName']; ?> <?php echo $row['LastName']; ?></h5>
                                <h6 class="recent-patients-text"><?php echo $row['Gender']; ?></h6>
                                <p class="recent-patients-text"><?php echo $diagnosis; ?></p>
                            </div>
                        </a>
                <?php
                        // Update the previous patient ID
                        $prevPatientID = $row['PatientID'];
                    }
                }
                ?>

            </section>

        </div>
    </div>
    <?php include('footer.php'); ?>
</body>

</html>
<?php
ob_end_flush();
?>