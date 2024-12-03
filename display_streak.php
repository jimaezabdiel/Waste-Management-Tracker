//add rewards 
<?php
require_once 'Database.php';

$db = new Database();
$conn = $db->getConnect();

// Example user idd
$user_id = 1;

// Fetch the user's streaks
$query = "SELECT action_type, streak_count FROM eco_streaks WHERE user_id = ?";
$stmt = $conn->prepare($query);
$stmt->execute([$user_id]);
$streaks = $stmt->fetchAll();

foreach ($streaks as $streak) {
    if ($streak['streak_count'] === 7) {
        echo "Congratulations! You achieved a 7-day streak for " . htmlspecialchars($streak['action_type']) . "!<33";
    }
}
?>


