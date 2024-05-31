<?php
session_start();

if (!isset($_SESSION["admin"])) {
    header("Location: admin.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }

        #Heading {
            font-style: italic;
            font-size: 30px;
            text-align: center;
            font-family: Georgia, 'Times New Roman', Times, serif;
        }

        .navbar {
            background-color: #2ecc71;
            padding: 15px 0;
            text-align: center;
            border: 2px solid black;
        }

        .navbar ul {
            list-style-type: none;
            padding: 0;
            margin: 0;
        }

        .navbar li {
            display: inline;
            margin-right: 20px;
        }

        .navbar a {
            text-decoration: none;
            color: #fff;
            padding: 10px 20px;
            border-radius: 5px;
            transition: background-color 0.3s;
        }

        .navbar a:hover {
            background-color: #27ae60;
        }

        .success-message {
            background-color: #7FFF00;
            color: #000;
            padding: 10px;
            border-radius: 5px;
            text-align: center;
            margin-bottom: 20px;
        }

        .container {
            max-width: 800px;
            margin: 20px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        table th, table td {
            padding: 8px;
            text-align: left;
            border-bottom: 1px solid #ddd;
            border: 1px solid grey;
        }

        table th {
            background-color: beige;
        }

        .hide {
            display: none;
        }

        .logout-container {
            text-align: center;
            margin-top: 20px;
        }

        #logoutButton {
            padding: 10px 20px;
            background-color: #FF6347;
            border: none;
            color: white;
            border-radius: 5px;
            cursor: pointer;
        }

        #logoutButton:hover {
            background-color: #FF4500;
        }
    </style>
</head>

<body>
    <div>
        <p id="Heading">SK Bill services</p>
    </div>
    <div class="navbar">
        <ul>
            <li><a href="add_customer.html">Add Customer</a></li>
            <li><a href="#" id="showTable">View Customers</a></li>
            <li><a href="delete_customer.php">Delete Account</a></li>
            <li><a href="update_readings.php">Update Readings</a></li>
        </ul>
    </div>
    <div class="container">
        <h2>Welcome, <?php echo $_SESSION['admin']; ?>!</h2>
        <p>This is the dashboard.</p>

        <?php
        if (isset($_GET['success']) && $_GET['success'] == 1) {
            echo '<div class="success-message">Account created successfully!</div>';
        }
        ?>

        <table id="customerTable" class="hide">
            <thead>
                <tr>
                    <th>Account No</th>
                    <th>Username</th>
                    <th>Address</th>
                    <th>Previous Reading</th>
                    <th>Current Reading</th>
                    <th>Tariff Type</th>
                    <th>Meter Number</th>
                    <th>Due Date</th>
                    <th>E-mail</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $servername = "localhost";
                $username = "root";
                $password = "123456";
                $database = "ebilldb";

                $conn = new mysqli($servername, $username, $password, $database);

                if ($conn->connect_error) {
                    die("Connection failed: " . $conn->connect_error);
                }

                $sql = "SELECT account_no, username, address, previous_reading, current_reading, tariff_type, meter_number, due_date, email FROM users";
                $result = $conn->query($sql);

                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>
                                <td>{$row['account_no']}</td>
                                <td>{$row['username']}</td>
                                <td>{$row['address']}</td>
                                <td>{$row['previous_reading']}</td>
                                <td>{$row['current_reading']}</td>
                                <td>{$row['tariff_type']}</td>
                                <td>{$row['meter_number']}</td>
                                <td>{$row['due_date']}</td>
                                <td>{$row['email']}</td>
                                <td><a href='edit_user.php?account_no={$row['account_no']}'>Edit</a></td>
                              </tr>";
                    }
                } else {
                    echo "<tr><td colspan='10'>No customers found</td></tr>";
                }
                $conn->close();
                ?>
            </tbody>
        </table>

        <div class="logout-container">
            <button id="logoutButton">Logout</button>
        </div>
    </div>

    <script>
        // Toggle customer table visibility
        document.getElementById("showTable").addEventListener("click", function() {
            var table = document.getElementById("customerTable");
            if (table.classList.contains("hide")) {
                table.classList.remove("hide");
            } else {
                table.classList.add("hide");
            }
        });

        // Logout functionality
        document.getElementById("logoutButton").addEventListener("click", function() {
            window.location.href = "logout.php";
        });

        // Automatically log out when the backspace key is pressed
        document.addEventListener("keydown", function(event) {
            if (event.key === "Backspace") {
                window.location.href = "logout.php";
            }
        });
    </script>
</body>

</html>
