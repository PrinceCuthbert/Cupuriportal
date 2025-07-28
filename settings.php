<?php
session_start();

// Optional: block unauthenticated users
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
?>


<!-- http://localhost/login_system/settings.php -->

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Settings - Cupuri Portal</title>
  <style>
    body {
      font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
      margin: 0;
      padding: 0;
      background: #f0f2f5;
    }

    .navbar {
      background: #003366;
      padding: 1rem 2rem;
      color: white;
    }

    .navbar h1 {
      margin: 0;
      font-size: 1.5rem;
    }

    .container {
      max-width: 800px;
      margin: 40px auto;
      background: #fff;
      padding: 30px;
      border-radius: 10px;
      box-shadow: 0 0 10px rgba(0,0,0,0.1);
    }

    h2 {
      margin-bottom: 20px;
      color: #003366;
    }

    label {
      display: block;
      margin-top: 15px;
      font-weight: 500;
    }

    input[type="text"],
    input[type="email"],
    input[type="password"] {
      width: 100%;
      padding: 10px;
      margin-top: 5px;
      border: 1px solid #ccc;
      border-radius: 6px;
    }

    button {
      margin-top: 20px;
      background: #003366;
      color: white;
      border: none;
      padding: 10px 16px;
      border-radius: 5px;
      cursor: pointer;
    }

    .back-link {
      display: inline-block;
      margin-top: 20px;
      text-decoration: none;
      color: #003366;
      font-weight: bold;
    }

    .back-link:hover {
      text-decoration: underline;
    }
  </style>
</head>
<body>
  <div class="navbar">
    <h1>⚙️ Settings</h1>
  </div>

  <div class="container">
    <h2>Update Profile Settings</h2>

    <form method="POST" action="update_settings.php">
      <label for="full_name">Full Name</label>
      <input type="text" id="full_name" name="full_name" placeholder="Enter your full name" required />

      <label for="email">Email Address</label>
      <input type="email" id="email" name="email" placeholder="Enter your email" required />

      <label for="password">New Password</label>
      <input type="password" id="password" name="password" placeholder="Enter new password" />

      <button type="submit">Save Changes</button>
    </form>

    <a class="back-link" href="dashboard.php">⬅️ Back to Dashboard</a>
  </div>
</body>
</html>
