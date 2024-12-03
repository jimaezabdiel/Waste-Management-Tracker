
// log Eco Action 
<?php
require_once 'Database.php';

$db = new Database();
$conn = $db->getConnect();

// User input
$user_id = 1; // Example user ID
$action_type = 'log_waste'; // Example action type

// Today's date
$today = date('YY-mm-dd');

// Check if the user has a streak 
$query = "SELECT * FROM eco_streaks WHERE user_id = ? AND action_type = ?";
$stmt = $conn->prepare($query);
$stmt->execute([$user_id, $action_type]);
$streak = $stmt->fetch();

if ($streak) {

    // Calculate the difference in days from the last update
    $last_update = new DateTime($streak['last_updated']);
    $current_date = new DateTime($today);
    $diff = $last_update->diff($current_date)->days;

    if ($diff === 1) {

        // Increment streak if the action was performed consecutively
        $streak['streak_count']++;
        $update_query = "UPDATE eco_streaks SET streak_count = ?, last_updated = ? WHERE id = ?";
        $stmt = $conn->prepare($update_query);
        $stmt->execute([$streak['streak_count'], $today, $streak['id']]);
    } elseif ($diff > 1) {

        // Reset streak if action was missed
        $update_query = "UPDATE eco_streaks SET streak_count = 1, last_updated = ? WHERE id = ?";
        $stmt = $conn->prepare($update_query);
        $stmt->execute([$today, $streak['id']]);
    }
} else {

    // Start a new streak if no previous streak exists
    $insert_query = "INSERT INTO eco_streaks (user_id, action_type, action_date, streak_count, last_updated) 
                     VALUES (?, ?, ?, 1, ?)";
    $stmt = $conn->prepare($insert_query);
    $stmt->execute([$user_id, $action_type, $today, $today]);
}

echo "Action logged successfully!";
?>
