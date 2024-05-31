<?php

$servername = "localhost";
$username = "root";
$password = "123456";
$database = "ebilldb";

$conn = new mysqli($servername, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$username = $_POST['username'];
$password = $_POST['password'];
$address = $_POST['address'];
$previous_reading = $_POST['previous_reading'];
$current_reading = $_POST['current_reading'];
$tariff_type = $_POST['tariff_type'];
$meter_number = $_POST['meter_number'];
$due_date = $_POST['due_date'];

$sql = "INSERT INTO users (username, password, address, previous_reading, current_reading, tariff_type, meter_number, due_date)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

$stmt = $conn->prepare($sql);
$stmt->bind_param("sssdssss", $username, $password, $address, $previous_reading, $current_reading, $tariff_type, $meter_number, $due_date);

if ($stmt->execute()) {
    echo "New record created successfully";
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

$stmt->close();
$conn->close();
?>