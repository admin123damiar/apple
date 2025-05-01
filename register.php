<?php
// Display MySQLi errors for easier debugging
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

// Database connection
$host = "localhost";
$username = "root"; // default phpMyAdmin username
$password = "";     // default phpMyAdmin password (often empty)
$database = "registration";

$conn = new mysqli($host, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Process form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_name = trim($_POST["username"]);
    $email = trim($_POST["email"]);
    $password = trim($_POST["password"]);

    // Validate input
    if (empty($user_name) || empty($email) || empty($password)) {
        die("Username, email, and password are required.");
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        die("Invalid email format.");
    }

    // Check if email already exists
    $check_email = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $check_email->bind_param("s", $email);
    $check_email->execute();
    $check_email->store_result();

    if ($check_email->num_rows > 0) {
        die("Email is already registered.");
    }
    $check_email->close();

    // Hash password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Insert into database (NOW including username)
    $insert = $conn->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
    $insert->bind_param("sss", $user_name, $email, $hashed_password);

    if ($insert->execute()) {
    echo "<p style='color:white; text-align:center; font-size:10px;'>Successfully registered.<br>Welcome Dream Bridal Wedding!</p>";
} else {
    echo "<p style='color:red; text-align:center;'>Something went wrong. Please try again.</p>";
}

    $insert->close();
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Register</title>
  <style>
    body {
      margin: 0;
      padding: 0;
      font-family: Arial, sans-serif;
      background: url('Imagebg.jpg') no-repeat center center fixed;
      background-size: cover;
      display: flex;
      flex-direction: column;
      min-height: 100vh;
    }

    header {
      background-color: #8B5E3B;
      color: white;
      text-align: center;
      padding: 10px 0;
    }

    .navbar {
      background-color: #8B5E3B;
      overflow: hidden;
      padding: 15px;
      text-align: center;
    }

    .navbar a {
      color: white;
      text-decoration: none;
      padding: 14px 20px;
      display: inline-block;
      font-size: 18px;
    }

    .navbar a:hover,
    .navbar a.active {
      background-color: #654321;
    }

    main {
      flex: 1;
      display: flex;
      justify-content: center;
      align-items: center;
    }

    .register-container {
      width: 100%;
      max-width: 500px;
      padding: 30px;
      background-color: rgba(255, 255, 255, 0.95);
      border-radius: 8px;
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
    }

    .register-container h2 {
      text-align: center;
      font-family: 'Georgia', serif;
      color: #987554;
      font-size: 30px;
    }

    .register-container input[type="text"],
    .register-container input[type="email"],
    .register-container input[type="password"] {
      width: 100%;
      padding: 10px;
      margin: 10px 0;
      border: 1px solid #ccc;
      border-radius: 4px;
      font-size: 16px;
    }

    .register-container input[type="submit"] {
      background-color: #987554;
      color: white;
      padding: 10px;
      width: 100%;
      border: none;
      border-radius: 10px;
      cursor: pointer;
      font-size: 16px;
    }

    .register-container input:focus {
      outline: none;
      border-color: #6f4f29;
    }

    footer {
      background-color: #8B5E3B;
      color: black;
      text-align: center;
      padding: 10px 0;
    }
  </style>
</head>
<body>

  <header>
    <h1>Dream Bridal Wedding</h1>
    <div class="navbar">
      <a href="web.html">Home</a>
      <a href="mywebAboutUsPage.html">About Us</a>
      <a href="mywebCategoriesPage.html">Categories</a>
      <a href="mywebContactUs.html">Contact</a>
      <a href="LD1FINALS_2D_DAMIAR.html">Log In</a>
      <a href="register.html">Register</a>
    </div>
  </header>

  <main>
    <form action="register.php" method="POST" class="register-container">
      <h2>Register</h2>
      <input type="text" name="username" placeholder="Username" required>
      <input type="email" name="email" placeholder="Enter your email" required>
      <input type="password" name="password" placeholder="Enter your password" required>
      <input type="submit" value="Register">
    </form>
  </main>

  <footer>
    &copy; 2025 Dream Bridal Wedding. All rights reserved.
  </footer>

</body>
</html>
