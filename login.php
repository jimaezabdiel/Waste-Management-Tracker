<?php
// Start the session
session_start();

// Path to the JSON file where user data is stored
$user_file_path = "users.json";
$message = ''; // To store any messages (success or error)

// Check if the form is submitted
if (isset($_POST['submit'])) {
    // Get form data
    $username = htmlspecialchars($_POST['username']);
    $password = htmlspecialchars($_POST['password']);
    
    // Check if the users.json file exists
    if (file_exists($user_file_path)) {
        // Read the existing data from the file
        $json_data = file_get_contents($user_file_path);
        $users = json_decode($json_data, true); // Decode JSON into an associative array

        // Check if username exists
        $user_found = false;
        foreach ($users as $user) {
            if ($user['username'] === $username) {
                $user_found = true;
                // Verify the password
                if (password_verify($password, $user['password'])) {
                    // Login success: set session variable
                    $_SESSION['user_id'] = $username;
                    $_SESSION['user_email'] = $user['email'];

                    // Set success message
                    $message = "You have successfully logged in.";
                    $redirect_button = "<a href='home.php'><button>Go to Home</button></a>";

                    // Output message and button and stop further execution
                    echo $message . "<br>" . $redirect_button;
                    exit; // Stop further execution after setting the message
                } else {
                    // Incorrect password
                    $message = "Error: Invalid password. Please try again.";
                    $redirect_button = "<a href='login.php'><button>Try Again</button></a>";
                    echo $message . "<br>" . $redirect_button;
                    exit;
                }
            }
        }

        // Username not found
        if (!$user_found) {
            $message = "Error: Username not found. Please try again.";
            $redirect_button = "<a href='login.php'><button>Try Again</button></a>";
            echo $message . "<br>" . $redirect_button;
            exit;
        }
    } else {
        $message = "Error: No user data found. Please try again later.";
        $redirect_button = "<a href='login.php'><button>Try Again</button></a>";
        echo $message . "<br>" . $redirect_button;
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
</head>
<body>
    <h2>Login</h2>
    
    <?php if (!$message): ?>
        <!-- Form displayed only when there are no messages -->
        <form action="login.php" method="POST">
            <label for="username">Username</label>
            <input type="text" name="username" id="username" required><br><br>

            <label for="password">Password</label>
            <input type="password" name="password" id="password" required><br><br>

            <button type="submit" name="submit">Login</button>

            <div class = "links">
            Don't have account? <a href = "register.php">Sign Up Now</a>
            </div>
        </form>
    <?php endif; ?>
</body>
</html>
