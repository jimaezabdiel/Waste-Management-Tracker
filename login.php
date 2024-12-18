<?php
// Initialize the $message variable
$message = "";

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

// Handle login request
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get data from form
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Prepare SQL query to fetch user data
    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->bind_param("s", $email); 
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if user exists
    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        // Verify the password
        if (password_verify($password, $user['password'])) {
            // Successful login, start session
            session_start();
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];

            // Set the message to trigger SweetAlert2
            $message = "success";
        } else {
            // Set the error message for invalid password
            $message = "error_invalid_password";
        }
    } else {
        // Set the error message for no user found
        $message = "error_no_user_found";
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
    <title>Login</title>
    <link rel="stylesheet" href="home.css">
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.6.5/dist/sweetalert2.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;600&family=Roboto:wght@300;400&display=swap" rel="stylesheet">
</head>
<body>
    <div class="login-container">
        <header>
            <div class="logo">
                <img src="logo.png" alt="Logo">
            </div>
        </header>

        <main>
            <div class="form-section">
                <div class="box">
                    <h1>Login</h1>
                    <form action="login.php" method="POST">
                        <div class="field">
                            <label for="email">Email</label>
                            <input type="email" name="email" id="email" required>
                        </div>

                        <div class="field">
                            <label for="password">Password</label>
                            <input type="password" name="password" id="password" required>
                        </div>

                        <button class="btn" type="submit" name="submit">Login</button>

                        <div class="links">
                            Don't have an account? <a href="register.php">Sign Up Now</a>
                        </div>
                    </form>
                </div>
            </div>
        </main>

        <footer>
            <p>&copy; 2024 TRASH SCaNS: Waste Management Tracker</p>
        </footer>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.6.5/dist/sweetalert2.all.min.js"></script>

    <script>
        <?php if ($message == "success"): ?>
            Swal.fire({
                title: 'Login Successful!',
                text: 'Directing to homepage...',
                icon: 'success',
                showConfirmButton: false,
                timer: 2000 
            }).then(() => {
                window.location.href = "home.php"; 
            });
        <?php elseif ($message == "error_invalid_password"): ?>
            Swal.fire({
                title: 'Error',
                text: 'Invalid password!',
                icon: 'error',
                confirmButtonText: 'OK'
            });
        <?php elseif ($message == "error_no_user_found"): ?>
            Swal.fire({
                title: 'Error',
                text: 'No user found with this email!',
                icon: 'error',
                confirmButtonText: 'OK'
            });
        <?php endif; ?>
    </script>
</body>
</html>
