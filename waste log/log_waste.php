// Waste logging script 
<?php
require_once 'Database.php';

// Initialize database connection
$db = new Database();
$conn = $db->getConnect();

// Log waste
$user_id = 1; // Example user ID
$waste_type = $_POST['waste_type'];
$quantity = $_POST['quantity'];
$log_date = date('Y-m-d');

$query = "INSERT INTO waste_logs (user_id, waste_type, quantity, log_date)
          VALUES (?, ?, ?, ?)";
$stmt = $conn->prepare($query);
$stmt->execute([$user_id, $waste_type, $quantity, $log_date]);

echo "Waste logged successfully!";
?>


    
