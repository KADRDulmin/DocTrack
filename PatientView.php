<?php
// Start a session
ob_start();
session_start();

// Check if the user is not logged in, redirect to login.php
if (!isset($_SESSION['DoctorID'])) {
    header("Location: login.php");
    exit();
}

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
            <link rel="stylesheet" href="styles/patientprofile.css">
            <title>Prescriptions</title>
            <link rel="icon" href="imgs/drcare.ico" type="image/x-icon">

            <style>
                body {
                    color: white;
                }

                table td,
                table th {
                    vertical-align: middle;
                    text-align: right;
                    padding: 20px !important;
                    color: white;
                }
            </style>
        </head>

        <body>
            <?php include('header.php'); ?>
            <script type="text/javascript" src="js/light-dark.js"></script>

            <div class="container">

                <div class="main main--team">

                    <section class="prescription-letterhead">

                        <div class="letter-head-buttons">
                            <a class="info-button" href="PatientInfoEdit.php?id=<?php echo $id; ?>">
                                <div class="button button--add2">
                                    <h6>Edit Info<h6>
                                </div>
                            </a>

                            <a class="delete-button" href="PatientDelete.php?id=<?php echo $id; ?>">
                                <div class="button button--add3">
                                    <h6>Delete Profile</h6>
                                </div>
                            </a>
                        </div>



                        <!-- Profile Image !-->
                        <div class="dpfp">
                            <img src="imgs/patient.png" alt="Profile Image">
                        </div>
                        <!-- End of Profile Image -->
                        <div class="h3sb">
                            <h3><?php echo $row["FirstName"]; ?> <?php echo $row["LastName"]; ?></h3>
                        </div>


                    </section>


                    <section class="info-border">
                        <section class="contact">
                            <h5> Contact: <?php echo $row["ContactNumber"]; ?></h5>
                        </section>
                        <div class="divider">

                        </div>
                        <section class="email">
                            <h5> Email: <?php echo $row["Email"]; ?></h5>
                        </section>

                    </section>

                    <a class="add-record-button" href="AddRecord.php?id=<?php echo $id; ?>">
                        <section class="add-record">

                            <h5> Add Record</h5>

                        </section>
                    </a>

                    <section class="prescription-details">

                        <div class="patient-container">
                            <ul>
                                <?php

                                // Showing All the Diagnosis and Medications relavant to the Patient

                                $sql = "SELECT * FROM MedicalRecords WHERE PatientID = $id";
                                $result = mysqli_query($conn, $sql);
                                while ($data = mysqli_fetch_array($result)) {
                                ?>
                                    <li>
                                        <div class="patient-tab"><b>Diagnosis <?php echo $data['RecordID']; ?>:</b>
                                            <br>
                                            <span style="font-size: 15px;"><?php echo $data["Diagnosis"]; ?></span> <br>
                                            <span style="font-size: 13px;">Medications <?php echo $data['RecordID']; ?>:</span>
                                            <br>
                                            <span style="font-size: 13px;">
                                                <div>
                                                    <a href="prescriptions.php?id=<?php echo $data['RecordID']; ?>" class="btn btn-primary">View Prescription</a>
                                                </div>
                                            </span>
                                            <br> <br>
                                        </div>
                                    </li>
                                    </a>
                        <?php
                                }
                            }
                        } else {
                            echo "<h3>No Patient found</h3>";
                        }
                        ?>

                            </ul>
                        </div>


                    </section>

                </div>
            </div>

            <?php include('footer.php'); ?>
        </body>

        </html>