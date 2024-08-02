<?php
require "DataBaseConfig.php";

class DataBase
{
    public $connect;
    public $data;
    private $sql;
    protected $servername;
    protected $username;
    protected $password;
    protected $databasename;

    public function __construct()
    {
        $this->connect = null;
        $this->data = null;
        $this->sql = null;
        $dbc = new DataBaseConfig();
        $this->servername = $dbc->servername;
        $this->username = $dbc->username;
        $this->password = $dbc->password;
        $this->databasename = $dbc->databasename;
    }

    function dbConnect()
    {
        $this->connect = mysqli_connect($this->servername, $this->username, $this->password, $this->databasename);
        return $this->connect;
    }

    function prepareData($data)
    {
        return mysqli_real_escape_string($this->connect, stripslashes(htmlspecialchars($data)));
    }

    function logIn($table, $email, $password)
    {
        $email = $this->prepareData($email);
        $password = $this->prepareData($password);
        $this->sql = "select * from " . $table . " where Email = '" . $email . "'";
        $result = mysqli_query($this->connect, $this->sql);
        $row = mysqli_fetch_assoc($result);
        if (mysqli_num_rows($result) != 0) {
            $dbemail = $row['Email'];
            $dbpassword = $row['Password'];
            if ($dbemail == $email && password_verify($password, $dbpassword)) {
                // Set session variables
                $_SESSION['DoctorID'] = $row['DoctorID'];
                $login = true;
            } else {
                $login = false;
                echo "Wrong Password";
            }
        } else {
            $login = false;
            echo "Wrong Email";
        }

        return $login;
    }

    function signUp($table, $email, $password, $firstname, $lastname, $specialization)
    {
        $email = $this->prepareData($email);
        $password = $this->prepareData($password);
        $firstname = $this->prepareData($firstname);
        $lastname = $this->prepareData($lastname);
        $specialization = $this->prepareData($specialization);
        $password = password_hash($password, PASSWORD_DEFAULT);

        // Check if email already exists
        $this->sql = "SELECT * FROM " . $table . " WHERE Email = '" . $email . "'";
        $result = mysqli_query($this->connect, $this->sql);
        if (mysqli_num_rows($result) > 0) {
            echo "The email already exists";
            return false;
        }
        // Insert new user if email does not exist
        $this->sql =
            "INSERT INTO " . $table . " (Email, Password, FirstName, LastName, Specialization) VALUES ('" . $email . "','" . $password . "','" . $firstname . "','" . $lastname . "','" . $specialization . "')";
        if (mysqli_query($this->connect, $this->sql)) {
            return true;
        } else return false;
    }


    // Function to check if the user is logged in
    function isLoggedIn()
    {
        return isset($_SESSION['DoctorID']);
    }


    // Function to logout
    function logout()
    {
        // Unset all session variables
        session_unset();

        // Destroy the session
        session_destroy();

        // Redirect to login page
        header("Location: login.php");
        exit();
    }
    // Check if the logout button is clicked
    function Logoutbutton()
    {
        // Check if the logout button is clicked
        if (isset($_POST['logout'])) {
            $this->logout();
        }
    }
        function checkLogout()
        {
        // Destroy session if the timeout is reached
        if (isset($_SESSION['timeout']) && time() > $_SESSION['timeout']) {
            $this->logout();
        }
    }
}


$database = new DataBase();
$database->checkLogout();
?>