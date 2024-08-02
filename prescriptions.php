<?php
// Start a session
ob_start();
session_start();

// Check if the user is not logged in, redirect to login.php
if (!isset($_SESSION['DoctorID'])) {
    header("Location: login.php");
    exit();
}

// Function to generate PDF from HTML content
function generatePDF($html)
{
    require_once 'vendor/autoload.php'; // Adjust the path if necessary

    $dompdf = new Dompdf\Dompdf();
    $dompdf->loadHtml($html);
    $dompdf->setPaper('A4', 'portrait');
    $dompdf->render();

    return $dompdf->output();
}

// Check if print button is clicked
if (isset($_POST['print_pdf'])) {
    // Generate PDF from HTML content
    $pdf_content = ob_get_clean();
    $pdf = generatePDF($pdf_content);

    // Send PDF file to browser for download
    header('Content-Type: application/pdf');
    header('Content-Disposition: attachment;filename="prescription.pdf"');
    header('Cache-Control: private, max-age=0, must-revalidate');
    header('Pragma: public');
    header('Expires: Sat, 26 Jul 1997 05:00:00 GMT');
    echo $pdf;
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">
    <link rel="stylesheet" href="styles/prescriptionoutput.css">
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
            <?php
            // Linking Database.php
            require "db/DataBase.php";
            $database = new DataBase();
            $conn = $database->dbConnect();
            if (!$conn) {
                die("Connection failed: " . mysqli_connect_error());
            }
            $id = $_GET['id'];

            if ($id) {
                $sql = "SELECT 
                            D.FirstName AS DoctorFirstName, 
                            D.LastName AS DoctorLastName, 
                            P.*, 
                            MR.DateAdded,
                            TIMESTAMPDIFF(YEAR, P.DateOfBirth, CURDATE()) AS Age
                        FROM 
                            Appointments A
                        INNER JOIN 
                            MedicalRecords MR ON A.RecordID = MR.RecordID
                        INNER JOIN 
                            Doctors D ON A.DoctorID = D.DoctorID
                        INNER JOIN 
                            Patients P ON A.PatientID = P.PatientID
                    
                        WHERE 
                            A.RecordID = $id";

                $result = mysqli_query($conn, $sql);
                while ($row = mysqli_fetch_array($result)) {
            ?>
                    <section class="prescription-letterhead">

                        <h2> Medical Prescription </h2>

                        <div class="letterhead-info">
                            <div class="doctor-name">
                                <h4>Dr. <?php echo $row["DoctorFirstName"]; ?> <?php echo $row["DoctorLastName"]; ?></h4>
                            </div>

                            <div class="date">
                                <h4><?php echo $row['DateAdded']; ?></h4>
                            </div>
                        </div>
                    </section>


                    <section class="info-border">
                        <section class="contact">
                            <h5> Contact: 074-777-2222</h5>
                        </section>
                        <div class="divider">

                        </div>
                        <section class="email">
                            <h5> Email: <?php echo $row['Email']; ?></h5>
                        </section>

                    </section>

                    <section class="prescription-details">
                        <div class="prescription-details-box">
                            <!--  Patient Information -->
                            <div class="patient">
                                <div class="patientName">
                                    <h4><?php echo $row["FirstName"]; ?> <?php echo $row["LastName"]; ?></h4>
                                </div>
                                <div class="patientName"></div>
                                <div class="patientAge">
                                    <h5>Age: <?php echo $row['Age']; ?> Years</h5>
                                </div>
                            </div>

                            <!-- Prescription Details -->
                            <?php
                            $prescription_sql = "SELECT * FROM PrescriptionDetails WHERE RecordID = $id";
                            $prescription_result = mysqli_query($conn, $prescription_sql);
                            while ($prescription_row = mysqli_fetch_array($prescription_result)) {
                            ?>
                                <div class="Meds">
                                    <div class="MedName">
                                        <h5><?php echo $prescription_row['MedName']; ?></h5>
                                        <p><?php echo $prescription_row['ScTime']; ?></p>
                                    </div>
                                    <div class="MedName"></div>
                                    <div class="Meal">
                                        <h6><?php echo $prescription_row['Meal']; ?></h6>
                                        <p><?php echo $prescription_row['MedPeriod']; ?></p>
                                    </div>
                                </div>
                            <?php
                            }
                            ?>
                        </div>
                    </section>
            <?php
                }
            }
            ?>

            <a class="print-button" href="prescriptionprint.php?id=<?php echo $id; ?>">
                <div class="button button--add">
                    <h6>Print</h6>
                </div>
            </a>


        </div>
    </div>

    <?php include('footer.php'); ?>
</body>

</html>
<?php
ob_end_flush();
?>