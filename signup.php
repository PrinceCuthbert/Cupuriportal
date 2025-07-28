<?php
include 'config.php';

$error = '';
$success = '';

if ($_POST) {
    // Sanitize and gather inputs
    $full_name = trim($_POST['full_name']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $country_code = $_POST['country_code'] ?? '';
    $contact_number = trim($_POST['contact_number']);
    $address = trim($_POST['address']);
    $birthdate = $_POST['birthdate'];
    $occupation = trim($_POST['occupation']);
    $role = $_POST['role'];
    $civil_status = $_POST['civil_status'];
    $gender = $_POST['gender'];
    $religion = trim($_POST['religion']);
    $bio = trim($_POST['bio']);

    $full_contact_number = $country_code . $contact_number;

    // Validation
    if (
        empty($full_name) || empty($email) || empty($password) || empty($confirm_password) ||
        empty($country_code) || empty($contact_number) || empty($address) || empty($birthdate) ||
        empty($occupation) || empty($civil_status) || empty($gender) || empty($religion)
    ) {
        $error = "All required fields must be filled.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Invalid email format.";
    } elseif (strlen($password) < 6) {
        $error = "Password must be at least 6 characters.";
    } elseif ($password !== $confirm_password) {
        $error = "Passwords do not match.";
    } else {
        // Check for existing email
        $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $error = "Email already registered.";
        } else {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
           $insert = $conn->prepare("
    INSERT INTO users 
    (full_name, email, password, contact_number, address, birthdate, occupation, civil_status, gender, religion, bio, role)
    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
");
$insert->bind_param("ssssssssssss", $full_name, $email, $hashed_password, $full_contact_number, $address, $birthdate, $occupation, $civil_status, $gender, $religion, $bio, $role);


            if ($insert->execute()) {
                $success = "Registration successful! <a href='login.php'>Login now</a>.";
            } else {
                $error = "Something went wrong. Try again.";
            }
        }
        
    }}
?>




<!-- http://localhost/cupuriportal/signup.php -->

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up - User System</title>
    <style>
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    body {
        font-family: 'Arial', sans-serif;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 20px; /* added to avoid cutoff on small screens */
    }

    .container {
        background: white;
        padding: 40px;
        border-radius: 10px;
        box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
        width: 100%;
        max-width: 400px;
    }

    .form-title {
        text-align: center;
        margin-bottom: 30px;
        color: #333;
        font-size: 28px;
        font-weight: bold;
    }

    .form-group {
        margin-bottom: 20px;
    }

    .form-group label {
        display: block;
        margin-bottom: 5px;
        color: #555;
        font-weight: bold;
    }

    .form-group input {
        width: 100%;
        padding: 12px;
        border: 2px solid #ddd;
        border-radius: 5px;
        font-size: 16px;
        transition: border-color 0.3s;
    }

    .form-group input:focus {
        outline: none;
        border-color: #667eea;
    }

    .btn {
        width: 100%;
        padding: 12px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border: none;
        border-radius: 5px;
        font-size: 16px;
        font-weight: bold;
        cursor: pointer;
        transition: transform 0.2s;
    }

    .btn:hover {
        transform: translateY(-2px);
    }

    .error {
        background: #ffe6e6;
        color: #d63031;
        padding: 10px;
        border-radius: 5px;
        margin-bottom: 20px;
        border-left: 4px solid #d63031;
    }

    .error-msg {
     color: #d63031;
     font-size: 13px;
     margin-top: 5px;
     }

 .invalid {
  border: 2px solid #e74c3c !important;
  animation: shake 0.3s ease-in-out;
}

@keyframes shake {
  0% { transform: translateX(0); }
  25% { transform: translateX(-4px); }
  50% { transform: translateX(4px); }
  75% { transform: translateX(-4px); }
  100% { transform: translateX(0); }
}

.error-msg {
  font-size: 13px;
  color: #e74c3c;
  margin-top: 3px;
}


    .success {
        background: #e6ffe6;
        color: #00b894;
        padding: 10px;
        border-radius: 5px;
        margin-bottom: 20px;
        border-left: 4px solid #00b894;
    }

    .login-link {
        text-align: center;
        margin-top: 20px;
    }

    .login-link a {
        color: #667eea;
        text-decoration: none;
        font-weight: bold;
    }

    .login-link a:hover {
        text-decoration: underline;
    }
   .form-group select,
    .form-group textarea {
      width: 100%;
      padding: 12px;
      border: 2px solid #ddd;
      border-radius: 5px;
      font-size: 16px;
      resize: vertical;
    }

    .form-group select:focus,
    .form-group textarea:focus {
      outline: none;
      border-color: #667eea;
    }

   .form-row {
  display: grid;
  grid-template-columns: 0.4fr 0.6fr; /* First column 40%, second column 60% */
  gap: 10px;
}

.form-row .form-group {
  margin-bottom: 20px;
}


    /* Media Queries for smaller screens */
    @media (max-width: 500px) {
        .container {
            padding: 25px;
        }

        .form-title {
            font-size: 22px;
        }

        .form-group input,
        .btn {
            padding: 10px;
            font-size: 14px;
        }
    }
</style>

</head>

<body>
  <div class="container">
    <h2 class="form-title">Create Account</h2>

    <?php if ($error): ?>
      <div class="error"><?php echo $error; ?></div>
    <?php endif; ?>

    <?php if ($success): ?>
      <div class="success"><?php echo $success; ?></div>
    <?php endif; ?>

    <form method="POST" action="" novalidate>
  <div class="form-group">
    <label for="full_name">Full Name:</label>
    <input
      type="text"
      id="full_name"
      name="full_name"
      required
      value="<?php echo htmlspecialchars($_POST['full_name'] ?? ''); ?>"
      pattern=".{3,100}"
      title="Full name must be between 3 and 100 characters"
    />
  </div>

  <div class="form-group">
    <label for="email">Email Address:</label>
    <input
      type="email"
      id="email"
      name="email"
      required
      value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>"
      pattern="^[a-zA-Z0-9._%+-]+@gmail\.com$"
      title="Email must be a valid @gmail.com address"
    />
  </div>

  <div class="form-group">
    <label for="password">Password:</label>
    <input
      type="password"
      id="password"
      name="password"
      required
      pattern="^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_])[^\s]{10,}$"
      title="At least 10 characters, with uppercase, lowercase, number, special character, and no spaces"
    />
  </div>

  <div class="form-group">
    <label for="confirm_password">Confirm Password:</label>
    <input
      type="password"
      id="confirm_password"
      name="confirm_password"
      required
    />
  </div>

 <div class="form-row">
  <div class="form-group">
    <label for="country_code">Country Code:</label>
    <select id="country_code" name="country_code" required>
      <option value="" disabled <?= empty($_POST['country_code']) ? 'selected' : '' ?>>Select code</option>
      <option value="+250" <?= ($_POST['country_code'] ?? '') === '+250' ? 'selected' : '' ?>>+250 (Rwanda)</option>
      <option value="+254" <?= ($_POST['country_code'] ?? '') === '+254' ? 'selected' : '' ?>>+254 (Kenya)</option>
      <option value="+256" <?= ($_POST['country_code'] ?? '') === '+256' ? 'selected' : '' ?>>+256 (Uganda)</option>
    </select>
  </div>

  <div class="form-group">
    <label for="contact_number">Contact Number:</label>
   <input
  type="text"
  id="contact_number"
  name="contact_number"
  required
  pattern="^[1-9]\d{8}$"
  title="Must be 9 digits and not start with 0"
  value="<?php echo htmlspecialchars($_POST['contact_number'] ?? ''); ?>"
/>

  </div>
</div>


  <div class="form-group">
    <label for="address">Address:</label>
    <input
      type="text"
      id="address"
      name="address"
      required
      value="<?php echo htmlspecialchars($_POST['address'] ?? ''); ?>"
    />
  </div>

  <div class="form-group">
    <label for="birthdate">Birthdate:</label>
    <input
      type="date"
      id="birthdate"
      name="birthdate"
      required
      max="2004-12-31"
      value="<?php echo htmlspecialchars($_POST['birthdate'] ?? ''); ?>"
    />
  </div>
  <div class="form-group">
    <label for="role">Role:</label>
    <select id="role" name="role" required>
        <option value="user" selected>User</option>
        <option value="admin">Admin</option>
    </select>
</div>


  <div class="form-group">
    <label for="occupation">Occupation:</label>
    <input
      type="text"
      id="occupation"
      name="occupation"
      required
      value="<?php echo htmlspecialchars($_POST['occupation'] ?? ''); ?>"
    />
  </div>

  <div class="form-group">
    <label for="civil_status">Civil Status:</label>
    <select id="civil_status" name="civil_status" required>
      <option value="" disabled <?= empty($_POST['civil_status']) ? 'selected' : '' ?>>Choose...</option>
      <option value="single" <?= ($_POST['civil_status'] ?? '') === 'single' ? 'selected' : '' ?>>Single</option>
      <option value="married" <?= ($_POST['civil_status'] ?? '') === 'married' ? 'selected' : '' ?>>Married</option>
      <option value="divorced" <?= ($_POST['civil_status'] ?? '') === 'divorced' ? 'selected' : '' ?>>Divorced</option>
      <option value="widowed" <?= ($_POST['civil_status'] ?? '') === 'widowed' ? 'selected' : '' ?>>Widowed</option>
    </select>
  </div>

  <div class="form-group">
    <label for="gender">Gender:</label>
    <select id="gender" name="gender" required>
      <option value="" disabled <?= empty($_POST['gender']) ? 'selected' : '' ?>>Choose...</option>
      <option value="male" <?= ($_POST['gender'] ?? '') === 'male' ? 'selected' : '' ?>>Male</option>
      <option value="female" <?= ($_POST['gender'] ?? '') === 'female' ? 'selected' : '' ?>>Female</option>
      <option value="other" <?= ($_POST['gender'] ?? '') === 'other' ? 'selected' : '' ?>>Other</option>
    </select>
  </div>

  <div class="form-group">
    <label for="religion">Religion:</label>
    <input
      type="text"
      id="religion"
      name="religion"
      required
      value="<?php echo htmlspecialchars($_POST['religion'] ?? ''); ?>"
    />
  </div>

  <div class="form-group">
    <label for="bio">Bio:</label>
    <textarea id="bio" name="bio" rows="3"><?php echo htmlspecialchars($_POST['bio'] ?? ''); ?></textarea>
  </div>

  <button type="submit" class="btn">Sign Up</button>
</form>



    <div class="login-link">
      Already have an account? <a href="login.php">Login here</a>
    </div>
  </div>
</body>

<script>
document.querySelector("form").addEventListener("submit", function (e) {
  const form = e.target;
  let hasErrors = false;

  // Clear previous errors
  form.querySelectorAll(".error-msg").forEach(el => el.remove());
  form.querySelectorAll(".invalid").forEach(el => {
    el.classList.remove("invalid");
    el.placeholder = el.getAttribute("data-original-placeholder") || "";
  });

  const fields = form.querySelectorAll("input, select");

  // Password match check
  if (form.password.value !== form.confirm_password.value) {
    markError(form.confirm_password, "Passwords must match");
    hasErrors = true;
  }

  // HTML5 validation
  fields.forEach(field => {
    if (!field.checkValidity()) {
      const placeholder = field.getAttribute("placeholder") || "Please correct this";
      markError(field, placeholder);
      hasErrors = true;
    }
  });

 if (!hasErrors) {
  const countryCode = document.getElementById("country_code").value;
  const phoneField = document.getElementById("contact_number");
  let phone = phoneField.value.trim();

 // Remove any existing +250 or 250 at the start of the number
  phone = phone.replace(/^(\+?250)/, "");
} else {
  e.preventDefault();
}


  function markError(field, message) {
    field.classList.add("invalid");
    field.setAttribute("data-original-placeholder", field.placeholder);
    field.placeholder = message;

    const errorMsg = document.createElement("div");
    errorMsg.className = "error-msg";
    errorMsg.innerText = message;
    field.parentNode.appendChild(errorMsg);
  }
});
</script>


</html>


