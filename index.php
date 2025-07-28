<?php
session_start();
if (isset($_SESSION['user_id'])) {
    header("Location: dashboard.php");
    exit();
}
?>

<!-- http://localhost/login_system/ -->

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Welcome to MyPortal</title>
  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    body {
      font-family: 'Segoe UI', sans-serif;
      background: #f9fafb;
      color: #333;
      line-height: 1.6;
    }

    header {
      background: linear-gradient(90deg, #667eea, #764ba2);
      color: white;
      padding: 2rem;
      text-align: center;
    }

    header h1 {
      font-size: 2.5rem;
      margin-bottom: 0.5rem;
    }

    header p {
      font-size: 1.1rem;
      opacity: 0.9;
    }

    nav {
      margin-top: 1rem;
    }

    nav a {
      color: white;
      text-decoration: none;
      margin: 0 1rem;
      font-weight: bold;
    }

    .hero {
      padding: 3rem 2rem;
      text-align: center;
      background: #edf2f7;
    }

    .hero h2 {
      font-size: 2rem;
      color: #4c51bf;
      margin-bottom: 1rem;
    }

    .hero p {
      max-width: 600px;
      margin: auto;
      color: #555;
      font-size: 1.1rem;
    }

    .actions {
      margin-top: 2rem;
    }

    .btn {
      display: inline-block;
      padding: 0.75rem 1.5rem;
      border-radius: 8px;
      font-weight: 600;
      cursor: pointer;
      text-decoration: none;
      margin: 0.5rem;
      transition: background 0.3s ease;
      border: 2px solid transparent;
    }

    .btn.primary {
      background: linear-gradient(90deg, #667eea, #764ba2);
      color: white;
      border-color: #667eea;
    }

    .btn.primary:hover {
      background: linear-gradient(90deg, #5a67d8, #6b46c1);
    }

    .btn.secondary {
      background: #eee;
      color: #4c51bf;
      border-color: #ccc;
    }

    .btn.secondary:hover {
      background: #ddd;
    }

    section {
      padding: 3rem 2rem;
      max-width: 900px;
      margin: auto;
    }

    section h3 {
      color: #4c51bf;
      font-size: 1.8rem;
      margin-bottom: 1rem;
      text-align: center;
    }

    section p {
      color: #555;
      font-size: 1.05rem;
    }

    /* Testimonial styles */
    .testimonial-slider {
      position: relative;
      overflow: hidden;
    }

    .testimonial-container {
      display: flex;
      transition: transform 0.5s ease-in-out;
    }

    .testimonial {
      min-width: 100%;
      padding: 2rem;
      box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
      background: white;
      border-radius: 12px;
      text-align: center;
    }

    .testimonial img {
      width: 80px;
      height: 80px;
      border-radius: 50%;
      object-fit: cover;
      margin-bottom: 1rem;
    }

    .testimonial h4 {
      margin-top: 0.5rem;
      font-size: 1.2rem;
      color: #333;
    }

    .stars {
      color: gold;
      margin-bottom: 1rem;
    }

    .testimonial-nav {
      text-align: center;
      margin-top: 1rem;
    }

    .testimonial-nav button {
      background: #4c51bf;
      color: white;
      border: none;
      margin: 0 0.5rem;
      padding: 0.5rem 1rem;
      border-radius: 6px;
      cursor: pointer;
    }

    .testimonial-nav button:hover {
      background: #5a67d8;
    }

    footer {
      background: #2d3748;
      color: #ddd;
      text-align: center;
      padding: 1.5rem 1rem;
      margin-top: 3rem;
      font-size: 0.95rem;
    }
  </style>
</head>
<body>

  <header>
    <h1>MyPortal</h1>
    <p>Your gateway to exams, resources, and more</p>
    <nav>
      <a href="#about">About</a>
      <a href="#contact">Contact</a>
    </nav>
  </header>

  <section class="hero">
    <h2>Empowering Learners & Institutions</h2>
    <p>Access past papers, exam resources, and tools to help you succeed academically. MyPortal makes preparation simple, efficient, and centralized.</p>
    <div class="actions">
      <a href="login.php" class="btn primary">Login</a>
      <a href="signup.php" class="btn secondary">Sign Up</a>
    </div>
  </section>

  <section id="about">
    <h3>About Us</h3>
    <p>MyPortal is a smart exam management system designed for universities, colleges, and students. Whether you're preparing for midterms or finals, or uploading departmental resources, MyPortal streamlines the process and improves accessibility. We aim to bridge the gap between students and academic success.</p>
  </section>

  <section id="testimonials">
    <h3>What Our Users Say</h3>
    <div class="testimonial-slider">
      <div class="testimonial-container" id="testimonialContainer">
        <div class="testimonial">
          <img src="https://randomuser.me/api/portraits/women/44.jpg" alt="User 1">
          <div class="stars">★★★★★</div>
          <p>"MyPortal saved me during finals week! Everything I needed was in one place."</p>
          <h4>Jane Doe, Student</h4>
        </div>
        <div class="testimonial">
          <img src="https://randomuser.me/api/portraits/men/32.jpg" alt="User 2">
          <div class="stars">★★★★☆</div>
          <p>"Our department uses MyPortal to distribute materials. It's been very effective."</p>
          <h4>Dr. John Smith, Lecturer</h4>
        </div>
        <div class="testimonial">
          <img src="https://randomuser.me/api/portraits/women/68.jpg" alt="User 3">
          <div class="stars">★★★★★</div>
          <p>"I love how clean and simple the platform is. I highly recommend it to students."</p>
          <h4>Sarah Kamali, Graduate</h4>
        </div>
      </div>
    </div>
    <div class="testimonial-nav">
      <button onclick="prevTestimonial()">&#10094; Prev</button>
      <button onclick="nextTestimonial()">Next &#10095;</button>
    </div>
  </section>

  <section id="contact">
    <h3>Contact Us</h3>
    <p>Email: support@myportal.com<br>
    Phone: +250 780 000 000<br>
    Address: Kigali Innovation City, Rwanda</p>
  </section>

  <footer>
    &copy; <?php echo date('Y'); ?> MyPortal. All rights reserved.
  </footer>

  <!-- Internal JS for testimonial slider -->
  <script>
    const container = document.getElementById("testimonialContainer");
    let currentIndex = 0;

    function showTestimonial(index) {
      const width = container.clientWidth;
      container.style.transform = `translateX(-${index * 100}%)`;
    }

    function nextTestimonial() {
      currentIndex = (currentIndex + 1) % container.children.length;
      showTestimonial(currentIndex);
    }

    function prevTestimonial() {
      currentIndex = (currentIndex - 1 + container.children.length) % container.children.length;
      showTestimonial(currentIndex);
    }

    // Optional: auto-slide
    setInterval(nextTestimonial, 7000);
  </script>

</body>
</html>
