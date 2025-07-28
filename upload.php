<?php
session_start();
include('config.php');

// Ensure only logged-in users can upload
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = trim($_POST['title']);
    $subject = trim($_POST['subject']);
    $lecture_name = trim($_POST['lecture_name']);
    $file = $_FILES['file'];

    if (
        empty($title) || empty($subject) || empty($lecture_name) || 
        empty($file['name']) || $file['error'] != 0
    ) {
        $error = "Please fill all required fields and choose a file.";
    } else {
        // Create uploads directory if not exists
        $uploadDir = 'uploads/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        $fileName = basename($file['name']);
        $targetPath = $uploadDir . time() . "_" . $fileName;

        if (move_uploaded_file($file['tmp_name'], $targetPath)) {
            $stmt = $conn->prepare("INSERT INTO downloads (title, subject, lecture_name, file_path) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("ssss", $title, $subject, $lecture_name, $targetPath);

            if ($stmt->execute()) {
                $success = "File uploaded successfully!";
            } else {
                $error = "Database error. Please try again.";
                unlink($targetPath); // rollback file save
            }
        } else {
            $error = "Failed to upload file.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Upload File - Cupuri Portal</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      background: #f5f5f5;
      padding: 40px;
    }
    .container {
      max-width: 600px;
      margin: auto;
      background: white;
      padding: 30px;
      border-radius: 8px;
      box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    }
    h2 {
      color: #003366;
      margin-bottom: 20px;
    }
    .form-group {
      margin-bottom: 16px;
    }
    label {
      display: block;
      margin-bottom: 6px;
      font-weight: bold;
    }
    input[type="text"],
    select,
    input[type="file"] {
      width: 100%;
      padding: 10px;
      border: 1px solid #ccc;
      border-radius: 4px;
    }
    button {
      background-color: #003366;
      color: white;
      padding: 10px 20px;
      border: none;
      border-radius: 5px;
      font-size: 16px;
      cursor: pointer;
    }
    button:hover {
      background-color: #002244;
    }
    .success, .error {
      padding: 10px;
      margin-bottom: 15px;
      border-radius: 4px;
    }
    .success {
      background-color: #e6ffe6;
      color: #2d862d;
    }
    .error {
      background-color: #ffe6e6;
      color: #cc0000;
    }
    .back-link {
      margin-top: 20px;
      display: inline-block;
      text-decoration: none;
      color: #003366;
      font-weight: bold;
    }
  </style>
</head>
<body>
  <div class="container">
    <h2>📤 Upload a New File</h2>

    <?php if ($error): ?>
      <div class="error"><?= $error ?></div>
    <?php endif; ?>

    <?php if ($success): ?>
      <div class="success"><?= $success ?></div>
    <?php endif; ?>

    <form method="POST" enctype="multipart/form-data">
      <div class="form-group">
        <label for="title">File Title</label>
        <input type="text" name="title" id="title" required>
      </div>

      <div class="form-group">
        <label for="subject">Subject</label>
        <input type="text" name="subject" id="subject" required>
      </div>

      <div class="form-group">
        <label for="lecture_name">Lecture Name</label>
        <input type="text" name="lecture_name" id="lecture_name" required>
      </div>

      <div class="form-group">
        <label for="file">Select File</label>
        <input type="file" name="file" id="file" required>
      </div>

      <button type="submit">Upload</button>
    </form>

    <a class="back-link" href="downloads.php">⬅️ Back to Downloads</a>
  </div>
</body>
</html>
