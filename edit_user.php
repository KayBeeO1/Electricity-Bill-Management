<?php
session_start();

if (!isset($_SESSION["admin"])) {
    header("Location: admin.php");
    exit;
}

$servername = "localhost";
$username = "root";
$password = "123456";
$database = "ebilldb";

$conn = new mysqli($servername, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $account_no = $_POST["account_no"];
    $username = $_POST["username"];
    $address = $_POST["address"];
    $previous_reading = $_POST["previous_reading"];
    $current_reading = $_POST["current_reading"];
    $tariff_type = $_POST["tariff_type"];
    $meter_number = $_POST["meter_number"];
    $due_date = $_POST["due_date"];
    $email = $_POST["email"];

    $sql = "UPDATE users SET 
            username='$username', 
            address='$address', 
            previous_reading='$previous_reading', 
            current_reading='$current_reading', 
            tariff_type='$tariff_type', 
            meter_number='$meter_number',
            due_date='$due_date',
            email='$email'
            WHERE account_no='$account_no'";

    if ($conn->query($sql) === TRUE) {
        header("Location: dashboard.php?success=1");
        exit();
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

$account_no = $_GET["account_no"];

$sql = "SELECT * FROM users WHERE account_no='$account_no'";
$result = $conn->query($sql);

if ($result->num_rows == 1) {
    $row = $result->fetch_assoc();
} else {
    echo "User not found.";
    exit;
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit User</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }

        .container {
            max-width: 600px;
            margin: 50px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        h2 {
            text-align: center;
        }

        .form-group {
            margin-bottom: 15px;
        }

        label {
            display: block;
            margin-bottom: 5px;
        }

        input[type="text"],
        input[type="number"],
        input[type="email"],
        input[type="date"] {
            width: 100%;
            padding: 8px;
            box-sizing: border-box;
        }

        button {
            display: block;
            width: 100%;
            padding: 10px;
            background-color: #2ecc71;
            border: none;
            color: white;
            border-radius: 5px;
            cursor: pointer;
        }

        button:hover {
            background-color: #27ae60;
        }
    </style>
</head>

<body>
    <div class="container">
        <h2>Edit User</h2>
        <form method="post" action="edit_user.php">
            <input type="hidden" name="account_no" value="<?php echo $row['account_no']; ?>">

            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" value="<?php echo $row['username']; ?>" required>
            </div>

            <div class="form-group">
                <label for="address">Address</label>
                <input type="text" id="address" name="address" value="<?php echo $row['address']; ?>" required>
            </div>

            <div class="form-group">
                <label for="previous_reading">Previous Reading</label>
                <input type="number" id="previous_reading" name="previous_reading" value="<?php echo $row['previous_reading']; ?>" required>
            </div>

            <div class="form-group">
                <label for="current_reading">Current Reading</label>
                <input type="number" id="current_reading" name="current_reading" value="<?php echo $row['current_reading']; ?>" required>
            </div>

            <div class="form-group">
                <label for="tariff_type">Tariff Type</label>
                <input type="text" id="tariff_type" name="tariff_type" value="<?php echo $row['tariff_type']; ?>" required>
            </div>

            <div class="form-group">
                <label for="meter_number">Meter Number</label>
                <input type="text" id="meter_number" name="meter_number" value="<?php echo $row['meter_number']; ?>" required>
            </div>

            <div class="form-group">
                <label for="due_date">Due Date</label>
                <input type="date" id="due_date" name="due_date" value="<?php echo $row['due_date']; ?>" required>
            </div>

            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" value="<?php echo $row['email']; ?>" required>
            </div>

            <button type="submit">Update User</button>
        </form>
    </div>
</body>

</html>
