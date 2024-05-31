<?php
session_start();

$servername = "localhost";
$username = "root";
$password = "123456";
$database = "ebilldb";

if (!isset($_SESSION["login_attempts"])) {
    $_SESSION["login_attempts"] = 0;
}

if ($_SESSION["login_attempts"] >= 3) {
    if (isset($_SESSION["lock_time"]) && time() - $_SESSION["lock_time"] < 60) {
        header("Location: account_locked.php");
        exit;
    } else {
        $_SESSION["login_attempts"] = 0;
        $_SESSION["lock_time"] = null;
    }
}

$conn = new mysqli($servername, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $username = $_POST["username"];
    $password = $_POST["password"];

    $stmt = $conn->prepare("SELECT * FROM admin WHERE username = ? AND password = ?");
    $stmt->bind_param("ss", $username, $password);

    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $_SESSION["admin"] = $username;
        $_SESSION["login_attempts"] = 0; 
        header("Location: dashboard.php");
        exit;
    } else {
        $error = "Invalid username or password";
        $_SESSION["login_attempts"]++;

        if ($_SESSION["login_attempts"] >= 3) {
            $_SESSION["lock_time"] = time();
            $error .= " Account locked for 1 minute.";
        }
    }

    $stmt->close();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-color: #f4f4f4;
        }

        .login-container {
            background-color: #fff;
            padding: 50px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .form-group {
            margin-bottom: 20px;
        }

        label {
            display: block;
            margin-bottom: 5px;
        }

        input {
            width: 100%;
            padding: 10px;
            border: 1px solid #b6b5b5;
            border-radius: 5px;
        }

        button {
            text-align: center;
            padding: 12px 20px;
            background-color: #8e9396;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        button:hover {
            background-color: #4e5051;
        }

        .error-message {
            color: red;
            margin-top: 10px;
            text-align: center;
        }
    </style>
</head>

<body>
    <div class="login-container">
        <h2>Admin Login</h2>
        <?php if (isset($error)) echo "<p class='error-message'>$error</p>"; ?>
        <form id="loginForm" method="post" action="">
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" required>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required>
            </div>
            <div class="form-group">
                <button type="submit">Login</button>
            </div>
        </form>
        <a href="index.html">Back to Login page</a>
    </div>
</body>

</html>
