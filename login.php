<?php
session_start();
include "db.php";

$msg = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT id, password FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows == 1) {
        $stmt->bind_result($user_id, $hashed_password);
        $stmt->fetch();

        if (password_verify($password, $hashed_password)) {
            $_SESSION['user_id'] = $user_id;
            $_SESSION['username'] = $username;
            header("Location: index.php");
            exit();
        } else {
            $msg = "Invalid password.";
        }
    } else {
        $msg = "User not found.";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
  <title>Login</title>
  <style>
    body {font-family: Arial; background: #f2f2f2; padding: 50px;}
    .container {
      max-width: 400px; background: white; padding: 30px;
      margin: auto; border-radius: 8px; box-shadow: 0 0 10px rgba(0,0,0,0.1);
    }
    input[type=text], input[type=password] {
      width: 100%; padding: 10px; margin: 8px 0; box-sizing: border-box;
    }
    input[type=submit] {
      width: 100%; background-color: #4CAF50; color: white;
      padding: 10px; border: none; border-radius: 4px;
    }
    .msg { margin-top: 10px; color: red; }
  </style>
</head>
<body>
  <div class="container">
    <h2>Login</h2>
    <form method="post">
      <input type="text" name="username" placeholder="Username" required>
      <input type="password" name="password" placeholder="Password" required>
      <input type="submit" value="Login">
    </form>
    <div class="msg"><?= $msg ?></div>
    <p>No account? <a href="signup.php">Sign up</a></p>
  </div>
</body>
</html>
