<?php
session_start();
include "db.php";

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['image'])) {
    $maxFileSize = 1 * 1024 * 1024; // 1MB in bytes
    $fileSize = $_FILES['image']['size'];
    $targetDir = "uploads/";
    $filename = basename($_FILES["image"]["name"]);
    $targetFile = $targetDir . $filename;

    if ($fileSize > $maxFileSize) {
        $error = "File size must not exceed 1 MB!";
    } else {
        if (move_uploaded_file($_FILES["image"]["tmp_name"], $targetFile)) {
            $group = "mySlides2";
            $stmt = $conn->prepare("INSERT INTO slider_images (filename, slider_group) VALUES (?, ?)");
            $stmt->bind_param("ss", $filename, $group);
            $stmt->execute();
            $success = "Image uploaded successfully!";
        } else {
            $error = "Upload failed!";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Upload Image - mme_slider</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <style>
    * {
      box-sizing: border-box;
    }

    body {
      font-family: 'Segoe UI', sans-serif;
      background: linear-gradient(135deg, #f0f4ff, #ffffff);
      margin: 0;
      padding: 0;
    }

    .navbar {
      background-color: #1e1e2f;
      color: white;
      padding: 16px 32px;
      display: flex;
      justify-content: space-between;
      align-items: center;
    }

    .navbar a {
      color: white;
      text-decoration: none;
      margin-left: 20px;
      font-weight: bold;
    }

    .container {
      max-width: 500px;
      margin: 60px auto;
      background-color: white;
      padding: 30px;
      border-radius: 12px;
      box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
    }

    h2 {
      text-align: center;
      color: #1e1e2f;
      margin-bottom: 25px;
    }

    input[type="file"] {
      width: 100%;
      margin-bottom: 20px;
      padding: 8px;
      border: 1px solid #ccc;
      border-radius: 6px;
    }

    input[type="submit"] {
      width: 100%;
      padding: 12px;
      background-color: #1e1e2f;
      color: white;
      border: none;
      border-radius: 6px;
      font-size: 16px;
      cursor: pointer;
    }

    input[type="submit"]:hover {
      background-color: #34344e;
    }

    .message, .error {
      text-align: center;
      font-weight: bold;
      margin-bottom: 16px;
    }

    .message {
      color: green;
    }

    .error {
      color: red;
    }
  </style>
</head>
<body>

<div class="navbar">
  <div><strong>Make My Event</strong></div>
  <div>
    <a href="index.php">Home</a>
    <a href="logout.php">Logout</a>
  </div>
</div>

<div class="container">
  <h2>Upload Image</h2>
  <?php if (isset($success)): ?><p class="message"><?= $success ?></p><?php endif; ?>
  <?php if (isset($error)): ?><p class="error"><?= $error ?></p><?php endif; ?>
  <form method="POST" enctype="multipart/form-data">
    <input type="file" name="image" accept="image/*" required>
    <input type="submit" value="Upload">
  </form>
</div>

</body>
</html>
