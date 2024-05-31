<?php
session_start();

$servername = "localhost";
$username = "root";
$password = "123456";
$database = "ebilldb";

$conn = new mysqli($servername, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $password = $_POST["password"];

    $sql_check_email = "SELECT * FROM users WHERE username='$username'";
    $result_check_email = $conn->query($sql_check_email);

    if ($result_check_email->num_rows > 0) {
        $row = $result_check_email->fetch_assoc();
        $stored_password = $row["password"];

        if ($password == $stored_password) {
            $_SESSION["username"] = $username;
            header("Location: billing_information.php");
            exit();
        } else {
            $error_message = urlencode("Invalid password.");
            header("Location: index.html?error=$error_message");
            exit();
        }
    } else {
        $error_message = urlencode("User does not exist.");
        header("Location: index.html?error=$error_message");
        exit();
    }
}

$conn->close();
?>
