<?php
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
$id = $_GET['id'];
if ($id) {
    $sql = "SELECT * FROM Patients WHERE PatientID = $id";
    $result = mysqli_query($conn, $sql);
    while ($row = mysqli_fetch_array($result)) {
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">

    <title>Patient Details</title>
    <link rel="icon" href="imgs/drcare.ico" type="image/x-icon">

    <style>
        .book-details {
            background-color: #f5f5f5;
        }
    </style>
</head>

<body>
    <div class="container my-4">
        <header class="d-flex justify-content-between my-4">
            <h1>Patient Details</h1>
            <div>
                <a href="AddRecord.php?id=<?php echo $id; ?>" class="btn btn-warning">Add New Record</a>
                <a href="index.php" class="btn btn-primary">Back</a>

            </div>
        </header>
        <div class="book-details p-5 my-4">
                    <h3>First Name:</h3>
                    <p><?php echo $row["FirstName"]; ?></p>
                    <h3>Last Name:</h3>
                    <p><?php echo $row["LastName"]; ?></p>
                    <h3>Date Of Birth:</h3>
                    <p><?php echo $row["DateOfBirth"]; ?></p>
                    <h3>Gender:</h3>
                    <p><?php echo $row["Gender"]; ?></p>
                    <h3>Contact Number:</h3>
                    <p><?php echo $row["ContactNumber"]; ?></p>
                    <h3>Email:</h3>
                    <p><?php echo $row["Email"]; ?></p>
                    <?php

                    // Showing All the Diagnosis and Medications relavant to the Patient

                    $sql = "SELECT * FROM MedicalRecords WHERE PatientID = $id";
                    $result = mysqli_query($conn, $sql);
                    while ($data = mysqli_fetch_array($result)) {
                    ?>
                        <h3>Diagnosis <?php echo $data['RecordID']; ?>:</h3>
                        <p><?php echo $data["Diagnosis"]; ?></p>
                        <h3>Medications <?php echo $data['RecordID']; ?>:</h3>
                        <div>
                            <a href="prescriptions.php?id=<?php echo $data['RecordID']; ?>" class="btn btn-primary">View Prescription</a>
                        </div>
            <?php
                    }
                }
            } else {
                echo "<h3>No Patient found</h3>";
            }
            ?>

        </div>
    </div>
</body>

</html>