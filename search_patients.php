<?php
// Linking Database.php
require "db/DataBase.php";
$database = new DataBase();
$conn = $database->dbConnect();
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

if(isset($_GET['query'])) {
    $search = $_GET['query'];
    
    // Query to search for patients
    $sql = "SELECT * FROM Patients 
            WHERE FirstName LIKE '%$search%' 
            OR LastName LIKE '%$search%' 
            OR FirstName LIKE '%$search%' AND LastName LIKE '%$search%'
            OR PatientID = '$search' 
            OR ContactNumber LIKE '$search' 
            OR Email LIKE '$search'";
    
    $result = mysqli_query($conn, $sql);

    // Fetch and return the results as JSON
    $patients = array();
    while($row = mysqli_fetch_assoc($result)) {
        $patients[] = $row['FirstName'] . ' ' . $row['LastName']. ' id - '. $row['PatientID'];
    }

    echo json_encode($patients);
}
?>