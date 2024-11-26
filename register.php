<?php
// Database connection details
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "user_data";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Initialize message variable
$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the form inputs
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Check if the email already exists
    $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        // Email already exists
        $message = "error_existing_email";
    } else {
        // Hash the password
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Prepare the SQL statement to insert data
        $stmt = $conn->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $username, $email, $hashed_password);

        // Execute the prepared statement
        if ($stmt->execute()) {
            // Successful registration
            $message = "success";
        } else {
            // General error
            $message = "error";
        }
    }

    // Close the prepared statement
    $stmt->close();
}

// Close connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link rel="stylesheet" href="home.css">
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.6.5/dist/sweetalert2.min.css" rel="stylesheet">
</head>
<body>
    <header>
        <div class="logo">
            <img src="logo.png" alt="Logo">
        </div>
        <div class="header-links">
            <a href="login.php">Log In</a>
            <a href="register.php">Register</a>
        </div>
    </header>

    <main>
        <div class="form-section">
            <div class="box">
                <h1>Register</h1>
                <form action="register.php" method="POST">
                    <div class="field">
                        <label for="username">Username</label>
                        <input type="text" name="username" id="username" required>
                    </div>
                    <div class="field">
                        <label for="email">Email</label>
                        <input type="email" name="email" id="email" required>
                    </div>
                    <div class="field">
                        <label for="password">Password</label>
                        <input type="password" name="password" id="password" required>
                    </div>
                    <button type="submit" name="submit" class="btn">Register</button>
                    <div class="links">
                        Already have an account? <a href="login.php">Log In Now</a>
                    </div>
                </form>
            </div>
        </div>
    </main>

    <footer>
        <div class="footer-links">
            <ul>
                <li><a href="#">Privacy</a></li>
                <li><a href="#">Terms</a></li>
            </ul>
        </div>
        <p>&copy; 2024 Your Website. All Rights Reserved.</p>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.6.5/dist/sweetalert2.all.min.js"></script>

    <script>
        <?php if ($message == "success"): ?>
            Swal.fire({
                title: 'Registration Successful!',
                text: 'Redirecting to login page...',
                icon: 'success',
                showConfirmButton: false,
                timer: 2000
            }).then(() => {
                window.location.href = "login.php";
            });
        <?php elseif ($message == "error"): ?>
            Swal.fire({
                title: 'Error',
                text: 'There was an error with your registration. Please try again.',
                icon: 'error',
                confirmButtonText: 'OK'
            });
        <?php elseif ($message == "error_existing_email"): ?>
            Swal.fire({
                title: 'Email Already Exists!',
                text: 'An account with this email already exists. Would you like to log in or use a different email?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Log In',
                cancelButtonText: 'Use a Different Email',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = "login.php";
                } else if (result.dismiss === Swal.DismissReason.cancel) {
                    window.location.href = "register.php";
                }
            });
        <?php endif; ?>
    </script>
</body>
</html>
