<?php
$servername = "localhost";
$username = "root";   
$password = "02345";         
$dbname = "waste_management"; 


$conn = new mysqli($servername, $username, $password, $dbname);


if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


function createGoal($conn, $user_id, $waste_type, $target_percentage, $end_date) {
    $start_date = date('Y-m-d H:i:s');
    $stmt = $conn->prepare("INSERT INTO waste_goals (user_id, waste_type, target_percentage, start_date, end_date, current_progress) VALUES (?, ?, ?, ?, ?, 0)");
    $stmt->bind_param("isiss", $user_id, $waste_type, $target_percentage, $start_date, $end_date);

    if ($stmt->execute()) {
        echo "Goal for reducing $waste_type by $target_percentage% created.<br>";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}

function logWasteDisposal($conn, $user_id, $waste_type, $waste_amount) {
    $disposal_date = date('Y-m-d H:i:s');
    $stmt = $conn->prepare("INSERT INTO user_waste_logs (user_id, waste_type, waste_amount, disposal_date) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("isds", $user_id, $waste_type, $waste_amount, $disposal_date);

    if ($stmt->execute()) {
        echo "Logged $waste_amount kg of $waste_type waste.<br>";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}


function updateGoalProgress($conn, $user_id, $waste_type) {
    
    $stmt = $conn->prepare("SELECT id, target_percentage, start_date FROM waste_goals WHERE user_id = ? AND waste_type = ? AND goal_status = 'active'");
    $stmt->bind_param("is", $user_id, $waste_type);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $goal = $result->fetch_assoc();
        $goal_id = $goal['id'];
        $target_percentage = $goal['target_percentage'];
        $start_date = $goal['start_date'];

       
        $stmt_logs = $conn->prepare("SELECT SUM(waste_amount) as total_waste FROM user_waste_logs WHERE user_id = ? AND waste_type = ? AND disposal_date >= ?");
        $stmt_logs->bind_param("iss", $user_id, $waste_type, $start_date);
        $stmt_logs->execute();
        $log_result = $stmt_logs->get_result();
        $log = $log_result->fetch_assoc();

        $total_waste = $log['total_waste'] ?? 0;

        
        $progress = ($total_waste * 100) / $target_percentage;
        $progress = 100 - $progress; 

       
        $stmt_update = $conn->prepare("UPDATE waste_goals SET current_progress = ? WHERE id = ?");
        $stmt_update->bind_param("di", $progress, $goal_id);

        if ($stmt_update->execute()) {
            echo "Progress for $waste_type updated: $progress% complete.<br>";
        } else {
            echo "Error updating progress: " . $stmt_update->error;
        }

        $stmt_logs->close();
        $stmt_update->close();
    } else {
        echo "No active goal for $waste_type found.<br>";
    }

    $stmt->close();
}

function displayGoals($conn, $user_id) {
    $stmt = $conn->prepare("SELECT * FROM waste_goals WHERE user_id = ? ORDER BY goal_status DESC");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        echo "<h3>Your Waste Reduction Goals:</h3>";
        while ($row = $result->fetch_assoc()) {
            $progress = $row["current_progress"];
            $progress = $progress > 100 ? 100 : $progress;
            echo "Goal: Reduce " . $row["waste_type"] . " by " . $row["target_percentage"] . "%<br>";
            echo "Progress: " . $row["current_progress"] . "%<br>";
            echo "<progress value='$progress' max='100'></progress><br>";
            echo "Goal Status: " . $row["goal_status"] . "<br><hr>";
        }
    } else {
        echo "No goals found.<br>";
    }

    $stmt->close();
}


$user_id = 1; 


createGoal($conn, $user_id, "plastic", 20, "2024-12-31");


logWasteDisposal($conn, $user_id, "plastic", 5);


updateGoalProgress($conn, $user_id, "plastic");


displayGoals($conn, $user_id);

$conn->close();
?>
