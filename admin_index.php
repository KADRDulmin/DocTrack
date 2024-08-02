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

    <title>Admin Dashboard</title>
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
    <div class="container my-4" style="padding-top: 5%;">
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
            <header class="d-flex justify-content-between my-4">

                <h1>Welcome <?php echo $data['FirstName']; ?> <?php echo $data['LastName']; ?></h1>

            <?php
        }
            ?>
            <h2>Patient List</h2>
            <div>
                <a href="patientInfoIn.php" class="btn btn-primary">Add New Patient</a>
            </div>
            <div>
                <form method="post" action="">
                    <input type="submit" class="btn btn-primary" name="logout" value="Logout">

                    <?php
                    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['logout'])) {
                        $database = new DataBase();
                        $database->Logoutbutton();
                    }
                    ?>
                </form>
            </div>
            </header>
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

            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>PatientID</th>
                        <th>FirstName</th>
                        <th>LastName</th>
                        <th>Gender</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>

                    <?php



                    $sqlSelect = "SELECT * FROM patients";
                    $result = mysqli_query($conn, $sqlSelect);
                    while ($data = mysqli_fetch_array($result)) {
                    ?>
                        <tr>
                            <td><?php echo $data['PatientID']; ?></td>
                            <td><?php echo $data['FirstName']; ?></td>
                            <td><?php echo $data['LastName']; ?></td>
                            <td><?php echo $data['Gender']; ?></td>
                            <td>
                                <a href="PatientView.php?id=<?php echo $data['PatientID']; ?>" class="btn btn-info">Read More</a>
                                <a href="PatientInfoEdit.php?id=<?php echo $data['PatientID']; ?>" class="btn btn-warning">Edit</a>
                                <a href="PatientDelete.php?id=<?php echo $data['PatientID']; ?>" class="btn btn-danger">Delete Patient</a>
                            </td>
                        </tr>
                    <?php
                    }
                    ?>
                </tbody>
            </table>
    </div>
    <?php include('footer.php'); ?>
</body>

</html>
<?php
ob_end_flush();
?>