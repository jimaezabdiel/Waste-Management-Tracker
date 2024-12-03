<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$database = "waste_tracker";

$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch waste reduction data for the logged-in user (user_id = 1 as an example)
$user_id = 1; // Replace with dynamic user ID if applicable
$query = "SELECT date, SUM(amount) as total_amount FROM waste_logs WHERE user_id = ? GROUP BY date ORDER BY date";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

$dates = [];
$amounts = [];

while ($row = $result->fetch_assoc()) {
    $dates[] = $row['date'];
    $amounts[] = (float) $row['total_amount'];
}

$stmt->close();
$conn->close();

// Return data in JSON format
echo json_encode([
    'dates' => $dates,
    'amounts' => $amounts
]);
?>
