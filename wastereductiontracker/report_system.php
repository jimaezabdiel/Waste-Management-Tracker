<?php
// Database connection
$servername = "localhost";
$username = "root"; // Update as needed
$password = ""; // Update as needed
$dbname = "waste_management";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch all activities
$sql = "SELECT * FROM user_activity ORDER BY date DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reports & Analytics</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        table, th, td { border: 1px solid black; }
        th, td { padding: 10px; text-align: center; }
        th { background-color: #f4f4f4; }
    </style>
</head>
<body>
    <h1>Reports & Analytics</h1>

    <h2>User Activity History</h2>
    <?php if ($result->num_rows > 0): ?>
        <table>
            <tr>
                <th>ID</th>
                <th>User Name</th>
                <th>Activity</th>
                <th>Date</th>
            </tr>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $row['id']; ?></td>
                    <td><?php echo $row['user_name']; ?></td>
                    <td><?php echo $row['activity']; ?></td>
                    <td><?php echo $row['date']; ?></td>
                </tr>
            <?php endwhile; ?>
        </table>
    <?php else: ?>
        <p>No activity records found.</p>
    <?php endif; ?>

    <h2>Add New Activity</h2>
    <form action="report_system.php" method="POST">
        <label for="user_name">User Name:</label>
        <input type="text" id="user_name" name="user_name" required><br><br>

        <label for="activity">Activity:</label>
        <input type="text" id="activity" name="activity" required><br><br>

        <button type="submit" name="add_activity">Add Activity</button>
    </form>

    <?php
    // Add new activity
    if (isset($_POST['add_activity'])) {
        $user_name = $_POST['user_name'];
        $activity = $_POST['activity'];

        $insert_sql = "INSERT INTO user_activity (user_name, activity) VALUES ('$user_name', '$activity')";
        if ($conn->query($insert_sql) === TRUE) {
            echo "<p>Activity added successfully!</p>";
            header("Location: report_system.php"); // Refresh the page
            exit();
        } else {
            echo "Error: " . $conn->error;
        }
    }

    $conn->close();
    ?>
</body>
</html>
