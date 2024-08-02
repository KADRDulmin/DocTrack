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

if (isset($_POST["add"])) {
    $doctorID = $_SESSION['DoctorID'];
    // Retrieve data from the form
    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];
    $gender = $_POST['gender'];
    $profile_photo = $_POST['profile_photo'];
    $hospital = $_POST['hospital'];
    $clinic = $_POST['clinic'];
    $description = $_POST['description'];
    $specialization1 = $_POST['specialization1'];
    $specialization2 = $_POST['specialization2'];
    $specialization3 = $_POST['specialization3'];
    $experience = $_POST['experience'];
    $contact = $_POST['contact'];

    // Check if data already exists in any of the fields
    $sql_check = "SELECT * FROM Doctors WHERE DoctorID='$doctorID'";
    $result_check = $conn->query($sql_check);

    if ($result_check->num_rows > 0) {
        // Update existing record
        $sql_update = "UPDATE Doctors SET FirstName='$firstname', LastName='$lastname', sex='$gender', profile_photo='$profile_photo', Experience='$experience', description='$description', specialization2='$specialization2', specialization3='$specialization3', clinic='$clinic', Hospital='$hospital', Contact='$contact' WHERE DoctorID='$doctorID'";

        if ($conn->query($sql_update) === TRUE) {
            // Log success message if update is successful
            $_SESSION["add"] = "Doctor updated successfully";
            header("Location: MyProfile.php");
            exit();
        } else {
            // Log error if update fails
            $_SESSION["add"] = "Error updating record: " . $conn->error;
            header("Location: MyProfile.php");
            exit();
        }
    } else {
        // Insert new record
        $sql_insert = "INSERT INTO Doctors (FirstName, LastName, sex, profile_photo, Experience, description, specialization1, specialization2, specialization3, clinic, Hospital, Contact) VALUES ('$firstname', '$lastname', '$gender', '$target_file', '$experience', '$description', '$specialization1', '$specialization2', '$specialization3', '$clinic', '$hospital', '$contact')";

        if ($conn->query($sql_insert) === TRUE) {
            // Log success message if insertion is successful
            $_SESSION["add"] = "New record created successfully";
            header("Location: MyProfile.php");
            exit();
        } else {
            // Log error if insertion fails
            $_SESSION["add"] = "Error: " . $sql_insert . "<br>" . $conn->error;
            header("Location: MyProfile.php");
            exit();
        }
    }

    $conn->close();
} else {
    // If form submission is not set, roll back the transaction
    $conn->rollback();
    die("Something went wrong with the form submission.");
}
?>
