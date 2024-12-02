<?php
// Waste analysis
require_once 'Database.php';

// Initialize database connection
$db = new Database();
$conn = $db->getConnect();

// Get most frequent waste
$user_id = 1; // Example user ID
$query = "SELECT waste_type, SUM(quantity) AS total 
          FROM waste_logs WHERE user_id = ? 
          GROUP BY waste_type ORDER BY total DESC LIMIT 1";
$stmt = $conn->prepare($query);
$stmt->execute([$user_id]);
$result = $stmt->fetch();

$suggestions = [
    'plastic_bottle' => "Use a reusable bottle.",
    'plastic_bag' => "Switch to reusable bags.",
    'paper' => "Try using less paper or recycled paper.",
    'food_waste' => "Start composting food scraps."
];

$suggestion = $result ? ($suggestions[$result['waste_type']] ?? "Great work! Keep it up.") : "No waste logged yet.";
echo json_encode(['suggestion' => $suggestion]);
?>

