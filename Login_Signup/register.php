<?php

$user_file_path = "users.json";

// Handle the form submission
if (isset($_POST['submit'])) {
    // Get the user input
    $username = htmlspecialchars($_POST['username']);
    $email = htmlspecialchars($_POST['email']);
    $password = htmlspecialchars($_POST['password']);
    
    // Hash the password for storage
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    
    // Check if the users.json file exists
    if (file_exists($user_file_path)) {
        // Read the data from the file
        $json_data = file_get_contents($user_file_path);
        $users = json_decode($json_data, true); // Decode the JSON into an array
    } else {
        // If the file doesn't exist, initialize an empty array
        $users = [];
    }
    
    // Check if the username or email already exists
    foreach ($users as $user) {
        if ($user['email'] === $email || $user['username'] === $username) {
            // If username or email already exists, show an error message
            echo "Error: Email or Username already exists! Please try again.";
            exit; // Stop further execution
        }
    }
    
    // Prepare the new user data
    $new_user = [
        'username' => $username,
        'email' => $email,
        'password' => $hashed_password
    ];
    
    // Add the new user to the existing users array
    $users[] = $new_user;

    // Save the updated user data back to the file
    file_put_contents($user_file_path, json_encode($users, JSON_PRETTY_PRINT));

    // Registration success message with link to login page
    echo "<p>Registration successful! You can now <a href='login.php'>proceed to login</a>.</p>";
    exit; // Ensure the script stops execution after displaying the message
}
?>

<!DOCTYPE html>
<html lang = "en">
<head>
    <meta charset =  "UTF-8">
    <meta name = "viewport" content = "width=device-width, initial-scale=1.0">
    <title>Register</title>
</head>
<body>
    <h2>Register</h2>
    <form action = "register.php" method = "POST">
        <label for = "username">Username</label>
        <input type = "text" name = "username" id = "username" required><br><br>

        <label for = "email">Email</label>
        <input type = "email" name = "email" id = "email" required><br><br>

        <label for = "password">Password</label>
        <input type = "password" name = "password" id = "password" required><br><br>

        <button type = "submit" name = "submit">Register</button>
    </form>
</body>
</html>
