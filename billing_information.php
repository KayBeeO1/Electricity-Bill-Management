<?php
session_start();

if (!isset($_SESSION["username"])) {
    header("Location: login.php");
    exit();
}

$servername = "localhost";
$username = "root";
$password = "123456";
$database = "ebilldb";

$conn = new mysqli($servername, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$username = $_SESSION["username"];

$sql = "SELECT * FROM users WHERE username='$username'";
$result = $conn->query($sql);

if ($result->num_rows == 1) {
    $row = $result->fetch_assoc();
    $address = $row["address"];
    $tariffType = $row["tariff_type"];

    switch ($tariffType) {
        case "residential":
            $tariffRate = 6;
            break;
        case "commercial":
            $tariffRate = 8;
            break;
        case "industrial":
            $tariffRate = 11;
            break;
        default:
            $tariffRate = 6;
    }
} else {
    echo "User not found.";
}

$previousReading = $row["previous_reading"];
$currentReading = $row["current_reading"];
$unitsConsumed = $currentReading - $previousReading;
$billAmount = $unitsConsumed * $tariffRate;

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Electricity Bill Details</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }
        .container {
            max-width: 800px;
            margin: 50px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        h3 {
            font-family: Georgia, 'Times New Roman', Times, serif;
            font-size: larger;
            color: white;
            padding: 10px;
            background-color: #2ecc71;
            border: 2px solid black;
            text-align: center;
        }
        h2{
            color: black;
        }
        p {
            margin: 10px 0;
        }
        strong {
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="container">
        <h3>Electricity Bill Details</h3>
        <h2>User Details</h2>
        <p><strong>Name:</strong> <?php echo $username; ?></p>
        <p><strong>Address:</strong> <?php echo $address; ?></p>

        <h2>Electricity Bill</h2>
        <p><strong>Previous Reading:</strong> <?php echo $previousReading; ?></p>
        <p><strong>Current Reading:</strong> <?php echo $currentReading; ?></p>
        <p><strong>Units Consumed:</strong> <?php echo $unitsConsumed; ?></p>
        <p><strong>Tariff Type:</strong> <?php echo $tariffType; ?></p>
        <p><strong>Rate:</strong> ₹<?php echo $tariffRate; ?> per unit</p>
        <p><strong>Bill Amount:</strong> ₹<?php echo number_format($billAmount, 2); ?></p>
    </div>
</body>
</html>
