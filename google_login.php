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

$data = json_decode(file_get_contents('php://input'), true);
$email = $data['email'];
$name = $data['name'];

$sql = "SELECT * FROM users WHERE username='$name'";
$result = $conn->query($sql);

if ($result->num_rows == 1) {
    $_SESSION["username"] = $name;
    echo json_encode(["success" => true]);
} else {
    $sql = "INSERT INTO users (username, password) VALUES ('$name', '')";
    if ($conn->query($sql) === TRUE) {
        $_SESSION["username"] = $name;
        echo json_encode(["success" => true]);
    } else {
        echo json_encode(["success" => false]);
    }
}

$conn->close();
?>
