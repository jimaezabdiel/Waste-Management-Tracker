<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset = "UTF-8">
        <meta http-equiv = "X-UA-Compatible" content = "IE=edge">
        <meta name = "viewport" content = "width-device-width, initial-scale=1.0">
        <link rel = "stylesheet" href = "forms-design.css">
        <title>User Profile Update</title>
    </head>
    <body>
        <div class="user-profile-update">
            <h1>User Profile Update</h1>
            <form action="" method="post">
                <label for="full-name">Full Name:</label>
                <input type="text" id="full-name" name="full-name" required>

                <label for="email">Email Address:</label>
                <input type="email" id="email" name="email" required>

                <label for="phone">Phone Number:</label>
                <input type="tel" id="phone" name="phone">

                <label for="address">Address:</label>
                <textarea id="address" name="address" rows="3" required></textarea>

                <label for="password">Change Password (Optional):</label>
                <input type="password" id="password" name="password">

                <button type="submit">Update Profile</button>
            </form>


            <footer>
                <p><a href="menu.php">Back to Dashboard</a></p> 
            </footer>

        </div>
    </body>
</html>