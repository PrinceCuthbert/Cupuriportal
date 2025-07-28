<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$resources = [
    ['title' => 'Exam Guidelines', 'file' => 'guidelines.pdf'],
    ['title' => 'Sample Exam 2023', 'file' => 'sample_exam_2023.pdf'],
];
?>


<!-- http://localhost/login_system/resources.php -->


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Resources</title>
  <style>
    body {
      font-family: 'Segoe UI', sans-serif;
      background: #f9fafb;
      margin: 0; padding: 2rem;
      color: #333;
    }
    h1 {
      color: #4c51bf;
      margin-bottom: 1.5rem;
    }
    ul {
      list-style: none;
      padding: 0;
      max-width: 400px;
      background: white;
      border-radius: 12px;
      box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    }
    li {
      padding: 1rem 1.5rem;
      border-bottom: 1px solid #e2e8f0;
    }
    li:last-child {
      border-bottom: none;
    }
    a {
      text-decoration: none;
      color: #667eea;
      font-weight: 600;
      transition: color 0.3s ease;
    }
    a:hover {
      color: #4c51bf;
    }
    a.back {
      display: inline-block;
      margin-top: 2rem;
      color: #667eea;
      font-weight: 600;
      text-decoration: none;
      transition: color 0.3s ease;
    }
    a.back:hover {
      color: #4c51bf;
    }
  </style>
</head>
<body>
  <h1>Resources</h1>
  <ul>
    <?php foreach ($resources as $resource): ?>
      <li><a href="files/<?= htmlspecialchars($resource['file']) ?>" target="_blank"><?= htmlspecialchars($resource['title']) ?></a></li>
    <?php endforeach; ?>
  </ul>

  <a href="dashboard.php" class="back">← Back to Dashboard</a>
</body>
</html>
