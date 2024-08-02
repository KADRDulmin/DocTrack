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

// Get the DoctorID from $_SESSION['DoctorID']
$doctorID = $_SESSION['DoctorID'];
// Showing All the Diagnosis and Medications relavant to the Patient

$sql = "SELECT * FROM Doctors WHERE DoctorID = $doctorID";
$result = mysqli_query($conn, $sql);
while ($data = mysqli_fetch_array($result)) {
?>
  <!DOCTYPE html>
  <html lang="en">

  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> Patients of Dr. <?php echo $data['FirstName']; ?> <?php echo $data['LastName']; ?></title>
    <link rel="stylesheet" href=styles/patientlist.css>
  </head>

  <body>
    <?php include('header.php'); ?>
    <script type="text/javascript" src="js/light-dark.js"></script>


    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <h2 style="text-align: center; color: white;"> Patients of Dr. <?php echo $data['FirstName']; ?> <?php echo $data['LastName']; ?></h2>
  <?php
}
  ?>

  <button class="add-patient-button" onclick="location.href='patientInfoIn.php'"><img src="https://cdn-icons-png.flaticon.com/512/6620/6620101.png" alt="Logo">Add New Patient </button>

  <div class="container" style="height: auto; max-height: 600px; overflow-y: auto;">
    <ul>
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
          $diagnosis = strlen($row['Diagnosis']) > 50 ? substr($row['Diagnosis'], 0, 200) . "...read more" : $row['Diagnosis'];
      ?>
          <a href="PatientView.php?id=<?php echo $row['PatientID']; ?>" style="text-decoration: none;">
            <li>
              <div class="patient-tab" onclick="showPatientDetails('patient1')"><b><?php echo $row['FirstName']; ?> <?php echo $row['LastName']; ?></b>
                <br>
                <span style="font-size: 15px;"><?php echo $row['Gender']; ?></span> <br>
                <span style="font-size: 13px;"><?php echo $diagnosis; ?></span>
                <br> <br>
              </div>
            </li>
          </a>
      <?php
          // Update the previous patient ID
          $prevPatientID = $row['PatientID'];
        }
      }
      ?>
    </ul>
  </div>

  <script>
    function showPatientDetails(patientId) {
      // Here you can implement functionality to display patient details based on the patientId
      // For now, let's just log the patient id to console
      console.log("Clicked patient: " + patientId);
    }
  </script>
  <?php include('footer.php'); ?>
  </body>

  </html>
  <?php
  ob_end_flush();
  ?>