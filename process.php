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

if (isset($_POST["create"])) {
    $FirstName = mysqli_real_escape_string($conn, $_POST["firstname"]);
    $LastName = mysqli_real_escape_string($conn, $_POST["lastname"]);
    $DateOfBirth = date('Y-m-d', strtotime($_POST['date']));
    $Gender = mysqli_real_escape_string($conn, $_POST["gender"]);
    $ContactNumber = mysqli_real_escape_string($conn, $_POST["contactnumber"]);
    $Email = mysqli_real_escape_string($conn, $_POST["email"]);
    $Diagnosis = $_POST["Diagnosis"];
    $Medications = $_POST["Medications"];

    // Check if the patient already exists
    $existingPatientID = 0;
    $checkStmt = $conn->prepare("SELECT PatientID FROM Patients WHERE FirstName = ? AND LastName = ?");
    $checkStmt->bind_param("ss", $FirstName, $LastName);
    $checkStmt->execute();
    $checkStmt->bind_result($existingPatientID);
    $checkStmt->fetch();
    $checkStmt->close();

    if ($existingPatientID) {
        // If the patient already exists, insert a new record in MedicalRecords with the same PatientID
        $stmt = $conn->prepare("INSERT INTO MedicalRecords (PatientID, Diagnosis, Medications) VALUES (?, ?, ?)");
        $stmt->bind_param("iss", $existingPatientID, $Diagnosis, $Medications);
        if ($stmt->execute()) {
            // Retrieve the generated RecordID
            $recordID = $conn->insert_id;

            // Get the DoctorID from $_SESSION['DoctorID']
            $doctorID = $_SESSION['DoctorID'];

            // Insert record into Appointments table
            $stmt = $conn->prepare("INSERT INTO Appointments (RecordID, DoctorID, PatientID) VALUES (?, ?, ?)");
            $stmt->bind_param("iii", $recordID, $doctorID, $existingPatientID);

            // Execute the statement
            if ($stmt->execute()) {
                // Insert data into PrescriptionDetails table
                if (isset($_POST['Medications'])) {
                    $medications = json_decode($_POST['Medications'], true);
                    foreach ($medications as $med) {
                        $medName = mysqli_real_escape_string($conn, $med['med_name']);
                        $scTime = mysqli_real_escape_string($conn, $med['sc_time']);
                        $meal = mysqli_real_escape_string($conn, $med['meal']);
                        $medPeriod = mysqli_real_escape_string($conn, $med['med_period']);

                        $prescriptionStmt = $conn->prepare("INSERT INTO PrescriptionDetails (PatientID, RecordID, MedName, ScTime, Meal, MedPeriod) VALUES (?, ?, ?, ?, ?, ?)");
                        $prescriptionStmt->bind_param("iissss", $existingPatientID, $recordID, $medName, $scTime, $meal, $medPeriod);
                        if (!$prescriptionStmt->execute()) {
                            // Log error if PrescriptionDetails insertion fails
                            $_SESSION["create"] = "PrescriptionDetails insertion failed: " . mysqli_error($conn);
                            header("Location:index.php");
                            exit();
                        }
                        $prescriptionStmt->close();
                    }
                }
                session_start();
                $_SESSION["create"] = "Medical Records Inserted Successfully!";
                header("Location:index.php");
            } else {
                die("Something went wrong with Appointments insertion.");
            }
        } else {
            die("Something went wrong with inserting medical records.");
        }
    } else {
        // If the patient doesn't exist, insert a new patient and their MedicalRecords
        $stmt = $conn->prepare("INSERT INTO Patients (FirstName, LastName, DateOfBirth, Gender, ContactNumber, Email) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssss", $FirstName, $LastName, $DateOfBirth, $Gender, $ContactNumber, $Email);
        // Start a transaction
        $conn->begin_transaction();

        if ($stmt->execute()) {
            // Retrieve the generated PatientID
            $patientID = $conn->insert_id;

            // Insert patient succeeded, now insert medical records with the new PatientID
            $stmt = $conn->prepare("INSERT INTO MedicalRecords (PatientID, Diagnosis, Medications) VALUES (?, ?, ?)");
            $stmt->bind_param("iss", $patientID, $Diagnosis, $Medications);

            if ($stmt->execute()) {
                // Retrieve the generated RecordID
                $recordID = $conn->insert_id;

                // Get the DoctorID from $_SESSION['DoctorID']
                $doctorID = $_SESSION['DoctorID'];

                // Insert record into Appointments table
                $stmt = $conn->prepare("INSERT INTO Appointments (RecordID, DoctorID, PatientID) VALUES (?, ?, ?)");
                $stmt->bind_param("iii", $recordID, $doctorID, $patientID);

                // Execute the statement
                if ($stmt->execute()) {
                    // Insert data into PrescriptionDetails table
                    if (!empty($_POST['Medications'])) {
                        $medications = json_decode($_POST['Medications'], true);
                        foreach ($medications as $med) {
                            $medName = mysqli_real_escape_string($conn, $med['med_name']);
                            $scTime = mysqli_real_escape_string($conn, $med['sc_time']);
                            $meal = mysqli_real_escape_string($conn, $med['meal']);
                            $medPeriod = mysqli_real_escape_string($conn, $med['med_period']);

                            $prescriptionStmt = $conn->prepare("INSERT INTO PrescriptionDetails (PatientID, RecordID, MedName, ScTime, Meal, MedPeriod) VALUES (?, ?, ?, ?, ?, ?)");
                            $prescriptionStmt->bind_param("iissss", $patientID, $recordID, $medName, $scTime, $meal, $medPeriod);
                            if (!$prescriptionStmt->execute()) {
                                // Log error if PrescriptionDetails insertion fails
                                $_SESSION["create"] = "PrescriptionDetails insertion failed: " . mysqli_error($conn);
                                header("Location:index.php");
                                exit();
                            }
                            $prescriptionStmt->close();
                        }
                    }
                    // Both inserts successful, commit the transaction
                    $conn->commit();
                    session_start();
                    $_SESSION["create"] = "Patient Recorded Successfully!";
                    header("Location:index.php");
                } else {
                    // Appointments insertion failed, roll back the transaction
                    $conn->rollback();
                    die("Something went wrong with Appointments insertion.");
                }
            } else {
                // Medical records insertion failed, roll back the transaction
                $conn->rollback();
                die("Something went wrong with medical records insertion.");
            }
        }
    }
}


// OLD CODE

/* include('connect.php');
if (isset($_POST["create"])) {
    $FirstName = mysqli_real_escape_string($conn, $_POST["firstname"]);
    $LastName = mysqli_real_escape_string($conn, $_POST["lastname"]);
    $DateOfBirth = date('Y-m-d', strtotime($_POST['date']));
    $Gender = mysqli_real_escape_string($conn, $_POST["gender"]);
    $ContactNumber = mysqli_real_escape_string($conn, $_POST["contactnumber"]);
    $Email = mysqli_real_escape_string($conn, $_POST["email"]);
    $Diagnosis = mysqli_real_escape_string($conn, $_POST["Diagnosis"]);
    $Medications = mysqli_real_escape_string($conn, $_POST["Medications"]);

    $sqlInsert = "INSERT INTO patients(FirstName , LastName , DateOfBirth , Gender , ContactNumber , Email) VALUES ('$FirstName','$LastName','$DateOfBirth','$Gender','$ContactNumber','$Email')";
    if(mysqli_query($conn,$sqlInsert)){
        session_start();
        $_SESSION["create"] = "Patitent Recorded Sucessfully!";
        header("Location:index.php");
    }else{
        die("Something went wrong");
    }

    $sqlInsert = "INSERT INTO medicalrecords(Diagnosis , Medications) VALUES ('$Diagnosis','$Medications')";
    if(mysqli_query($conn,$sqlInsert)){
        session_start();
        $_SESSION["create"] = "Patitent Recorded Sucessfully!";
        header("Location:index.php");
    }else{
        die("Something went wrong");
    }

} */


// CODE FOR EDITING (HAVE TO MAKE)


if (isset($_POST["edit"])) {
    $id = $_POST["id"];
    $FirstName = mysqli_real_escape_string($conn, $_POST["firstname"]);
    $LastName = mysqli_real_escape_string($conn, $_POST["lastname"]);
    $DateOfBirth = date('Y-m-d', strtotime($_POST['date']));
    $Gender = mysqli_real_escape_string($conn, $_POST["gender"]);
    $ContactNumber = mysqli_real_escape_string($conn, $_POST["contactnumber"]);
    $Email = mysqli_real_escape_string($conn, $_POST["email"]);
    $DiagnosisArray = $_POST["Diagnosis"];
    $MedicationsArray = $_POST["Medications"];
    $recordIDs = $_POST["recordIDs"];

    // Update patient's information
    $sqlUpdate = "UPDATE patients SET FirstName = '$FirstName', LastName = '$LastName', DateOfBirth = '$DateOfBirth', Gender = '$Gender', ContactNumber = '$ContactNumber', Email = '$Email' WHERE PatientID ='$id'";

    if (mysqli_query($conn, $sqlUpdate)) {
        // Patient information updated successfully

        // Update medical records for each record
        for ($i = 0; $i < count($DiagnosisArray); $i++) {
            $recordID = $recordIDs[$i];
            $Diagnosis = mysqli_real_escape_string($conn, $DiagnosisArray[$i]);
            $Medications = mysqli_real_escape_string($conn, $MedicationsArray[$i]);

            $sqlUpdateRecords = "UPDATE MedicalRecords SET Diagnosis = '$Diagnosis', Medications = '$Medications' WHERE RecordID ='$recordID'";

            if (mysqli_query($conn, $sqlUpdateRecords)) {
                // Medical records updated successfully for each record
            } else {
                die("Something went wrong with updating medical records: " . mysqli_error($conn));
            }
        }

        session_start();
        $_SESSION["update"] = "Patient Updated Successfully!";
        header("Location:index.php");
    } else {
        die("Something went wrong with updating patient information: " . mysqli_error($conn));
    }
}
