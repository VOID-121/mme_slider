<?php
$host = "localhost";
$user = "root";
$pass = ""; // or your MySQL password
$dbname = "mme_slider";

$conn = new mysqli($host, $user, $pass, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
