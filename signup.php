<?php
session_start();
include "db.php";

$msg = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $email    = trim($_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);

    $stmt = $conn->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
    if ($stmt) {
        $stmt->bind_param("sss", $username, $email, $password);
        if ($stmt->execute()) {
            $msg = "Account created successfully. <a href='login.php'>Login here</a>.";
        } else {
            $msg = "Username or email already exists.";
        }
    } else {
        $msg = "Something went wrong.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #f0f4ff, #ffffff);
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .container {
            background-color: #ffffff;
            padding: 40px;
            border-radius: 8px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 500px;
        }

        h2 {
            text-align: center;
            color: #333;
            margin-bottom: 20px;
            font-size: 28px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        input[type="text"], input[type="email"], input[type="password"] {
            width: 100%;
            padding: 12px;
            margin-top: 8px;
            border: 1px solid #ccc;
            border-radius: 8px;
            font-size: 16px;
            background-color: #f9f9f9;
            transition: 0.3s;
        }

        input[type="text"]:focus, input[type="email"]:focus, input[type="password"]:focus {
            border-color: #4CAF50;
            background-color: #fff;
        }

        input[type="submit"] {
            width: 100%;
            padding: 14px;
            background-color: #4CAF50;
            color: white;
            font-size: 16px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            transition: background 0.3s ease;
        }

        input[type="submit"]:hover {
            background-color: #45a049;
        }

        .msg {
            text-align: center;
            margin-top: 15px;
            color: #4CAF50;
            font-size: 16px;
        }

        .msg a {
            color: #1e1e2f;
            text-decoration: none;
            font-weight: bold;
        }

        .msg a:hover {
            color: #4CAF50;
        }

        p {
            text-align: center;
            font-size: 16px;
        }

        p a {
            color: #4CAF50;
            font-weight: bold;
            text-decoration: none;
        }

        p a:hover {
            color: #45a049;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Sign Up</h2>
    <form method="post">
        <div class="form-group">
            <input type="text" name="username" placeholder="Username" required>
        </div>
        <div class="form-group">
            <input type="email" name="email" placeholder="Email" required>
        </div>
        <div class="form-group">
            <input type="password" name="password" placeholder="Password" required>
        </div>
        <input type="submit" value="Sign Up">
    </form>

    <div class="msg"><?= $msg ?></div>

    <p>Already have an account? <a href="login.php">Login</a></p>
</div>

</body>
</html>
