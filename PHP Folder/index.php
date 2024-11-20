<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = pg_escape_string($conn, $_POST['email']);
    $password = pg_escape_string($conn, $_POST['password']);

    $result = pg_query_params($conn, "SELECT * FROM users WHERE email = $1", array($email));

    if ($result) {
        $user = pg_fetch_assoc($result);
        
        if ($user) {
            if (password_verify($password, $user['password'])) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['email'] = $user['email'];
                $_SESSION['user_role'] = $user['user_role'];
                
                if ($user['user_role'] === 'admin') {
                    header('Location: admindashboard.php');
                } else {
                    header('Location: main.php');
                }
                exit;
            } else {
                $message = "Invalid Email or Password";
            }
        } else {
            $message = "No Email Found";
        }
    } else {
        $message = "Error!" . pg_last_error($conn);
    }
}
?>