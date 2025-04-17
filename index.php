<?php
session_start();
include "db.php";
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Make My Event Slider</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <style>
    * { box-sizing: border-box; margin: 0; padding: 0; }

    body {
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      background-color: #f0f2f5;
    }

    .navbar {
      background: #242943;
      color: white;
      padding: 15px 30px;
      display: flex;
      justify-content: space-between;
      align-items: center;
      box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    }

    .navbar a {
      color: white;
      text-decoration: none;
      margin-left: 20px;
      font-weight: 500;
    }

    .navbar a:hover {
      color: #ffd700;
    }

    h1 {
      text-align: center;
      margin: 40px 0 20px;
      color: #242943;
      font-size: 2.5rem;
    }

    .slideshow-container {
      max-width: 700px;
      margin: 0 auto;
      position: relative;
      overflow: hidden;
      height: 400px;
      background: white;
      border-radius: 12px;
      box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
    }

    .mySlides2 {
      display: none;
      justify-content: center;
      align-items: center;
      animation: fade 1s ease-in-out;
      height: 100%;
    }

    .mySlides2 img {
      width: 100%;
      height: 100%;
      object-fit: cover;
      border-radius: 12px;
    }

    @keyframes fade {
      from { opacity: 0.4; }
      to { opacity: 1; }
    }

    .prev, .next {
      cursor: pointer;
      position: absolute;
      top: 50%;
      transform: translateY(-50%);
      padding: 14px;
      color: white;
      font-size: 24px;
      font-weight: bold;
      transition: 0.3s ease;
      border-radius: 50%;
      background-color: rgba(0,0,0,0.4);
      z-index: 10;
    }

    .prev:hover, .next:hover {
      background-color: rgba(0,0,0,0.8);
    }

    .prev { left: 15px; }
    .next { right: 15px; }

    .dots {
      text-align: center;
      margin-top: 20px;
    }

    .dot {
      cursor: pointer;
      height: 12px;
      width: 12px;
      margin: 0 5px;
      background-color: #bbb;
      border-radius: 50%;
      display: inline-block;
      transition: background-color 0.3s ease;
    }

    .dot.active {
      background-color: #242943;
    }

    @media (max-width: 768px) {
      .slideshow-container {
        max-width: 95%;
        height: 280px;
      }
    }
  </style>
</head>
<body>

<!-- Navbar -->
<div class="navbar">
  <div><strong>Make My Event</strong></div>
  <div>
    <?php if (isset($_SESSION['username'])): ?>
      Welcome, <?= htmlspecialchars($_SESSION['username']) ?> |
      <a href="upload.php">Upload</a>
      <a href="logout.php">Logout</a>
    <?php else: ?>
      <a href="signup.php">Sign Up</a>
      <a href="login.php">Login</a>
    <?php endif; ?>
  </div>
</div>

<!-- Header -->
<h1>My Event Slider</h1>

<!-- Slider -->
<div class="slideshow-container">
  <?php
    $slider_group = 'mySlides2';
    $stmt = $conn->prepare("SELECT * FROM slider_images WHERE slider_group = ?");
    $stmt->bind_param("s", $slider_group);
    $stmt->execute();
    $result = $stmt->get_result();

    $valid_images = [];
    while ($row = $result->fetch_assoc()) {
        $filepath = "uploads/" . $row['filename'];
        if (file_exists($filepath)) {
            $valid_images[] = $filepath;
        } else {
            $delStmt = $conn->prepare("DELETE FROM slider_images WHERE id = ?");
            $delStmt->bind_param("i", $row['id']);
            $delStmt->execute();
        }
    }

    foreach ($valid_images as $imgPath):
  ?>
    <div class="mySlides2">
      <img src="<?= $imgPath ?>" alt="Slide Image">
    </div>
  <?php endforeach; ?>

  <a class="prev" onclick="plusSlides(-1)">&#10094;</a>
  <a class="next" onclick="plusSlides(1)">&#10095;</a>
</div>

<!-- Dot navigation -->
<div class="dots">
  <?php for ($i = 0; $i < count($valid_images); $i++): ?>
    <span class="dot" onclick="currentSlide(<?= $i + 1 ?>)"></span>
  <?php endfor; ?>
</div>

<!-- JavaScript -->
<script>
let slideIndex = 1;
showSlides(slideIndex);

function plusSlides(n) {
  showSlides(slideIndex += n);
}

function currentSlide(n) {
  showSlides(slideIndex = n);
}

function showSlides(n) {
  let i;
  const slides = document.getElementsByClassName("mySlides2");
  const dots = document.getElementsByClassName("dot");
  if (slides.length === 0) return;
  if (n > slides.length) {slideIndex = 1}
  if (n < 1) {slideIndex = slides.length}
  for (i = 0; i < slides.length; i++) {
    slides[i].style.display = "none";
  }
  for (i = 0; i < dots.length; i++) {
    dots[i].classList.remove("active");
  }
  slides[slideIndex-1].style.display = "flex";
  dots[slideIndex-1].classList.add("active");
}

// Auto Slide
setInterval(() => {
  plusSlides(1);
}, 6000); // Change every 5 seconds
</script>

</body>
</html>
