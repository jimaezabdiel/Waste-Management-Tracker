<?php
session_start();

// Define predefined goals with steps
$predefinedGoals = [
    "Reduce weekly plastic waste by 10%" => ["Use reusable bags", "Avoid single-use plastics", "Recycle bottles and containers"],
    "Reduce paper usage by 20%" => ["Switch to digital documents", "Use both sides of paper", "Recycle paper waste"],
    "Compost organic waste" => ["Set up a compost bin", "Segregate organic waste", "Use compost for gardening"],
    "Reduce energy consumption" => ["Turn off lights when not in use", "Use energy-efficient appliances", "Unplug devices when not in use"],
    "Minimize water waste" => ["Fix leaking taps", "Use water-saving devices", "Recycle greywater for plants"]
];

// Initialize progress if not set
if (!isset($_SESSION['progress'])) {
    $_SESSION['progress'] = [];
}

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Set a new goal
    if (isset($_POST['set_goal'])) {
        $goal = $_POST['set_goal'];
        $_SESSION['progress'][$goal] = [
            'steps' => $predefinedGoals[$goal],
            'completed' => 0
        ];
    }

    // Track progress of a goal
    if (isset($_POST['track_progress'])) {
        $goal = $_POST['track_progress'];
        $_SESSION['current_goal'] = $goal; // Store current goal for tracking
    }

    // Handle step completion
    if (isset($_POST['current_goal']) && isset($_POST['next_step'])) {
        $goal = $_POST['current_goal'];
        $nextStepIndex = (int)$_POST['next_step'];
        $_SESSION['progress'][$goal]['completed'] = $nextStepIndex + 1;
    }

    // Reset goal progress
    if (isset($_POST['reset_goal'])) {
        $_SESSION['progress'] = [];
        unset($_SESSION['current_goal']);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Goal Tracker</title>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;600&family=Roboto:wght@300;400&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        body {
            font-family: 'Open Sans', sans-serif;
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            background-color: #f9f9f9;
            height: 100vh;
        }

        header {
            background-color: #DDF1E4; 
            color: #2c6b3f;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 20px 40px;
            box-shadow: 0 4px 6px rgba(0.1, 0.1, 0.1, 0.1);
        }

        header .logo img {
            width: 120px;
        }

        header .header-links {
            display: flex;
            gap: 25px;
        }

        header .header-links a {
            text-decoration: none;
            color: #2c6b3f;
            font-weight: 600;
            font-size: 16px;
            transition: color 0.3s ease;
        }

        header .header-links a:hover {
            color: #d1e4c7;
        }

        main {
            padding: 40px 20px;
            flex-grow: 1;
            background-color: #ffffff;
            color: #333;
        }

        .display {
            text-align: center;
            margin-bottom: 40px;
            background: #ffffff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 4px 4px 8px rgba(0.1, 0.1, 0.1, 0.1);
        }

        table {
            width: 100%;
            margin-top: 20px;
            border-collapse: collapse;
        }

        th, td {
            padding: 10px;
            border: 1px solid #ddd;
            text-align: left;
        }

        th {
            background-color: #DDF1E4;
            color: #2c6b3f;
        }

        td {
            background-color: #f9f9f9;
        }

        .progress-bar {
            width: 100%;
            background-color: #ddd;
            border-radius: 5px;
            height: 10px;
        }

        .progress-bar div {
            height: 100%;
            background-color: #2c6b3f;
            border-radius: 5px;
        }

        .goal-section {
            margin-top: 30px;
        }

        .step-popup {
            padding: 20px;
            background-color: #ffffff;
            border-radius: 8px;
            box-shadow: 4px 4px 8px rgba(0.1, 0.1, 0.1, 0.1);
        }

        footer {
            background-color: #DDF1E4;
            color: #2c6b3f;
            padding: 20px 0;
            text-align: center;
        }

        footer p {
            font-size: 14px;
        }

        @media (max-width: 768px) {
            header {
                flex-direction: column;
                align-items: flex-start;
            }

            header .header-links {
                margin-top: 20px;
                flex-direction: column;
            }

            main {
                padding: 20px;
            }
        }
    </style>
</head>
<body>

<header>
    <div class="logo">
        <img src="logo.png" alt="Logo">
    </div>
    <div class="header-links">
        <a href="home.php">Home</a>
        <a href="user-update.php">Update Profile</a>
        <a href="carbon-footprint.php">Carbon Footprint Summary</a>
        <a href="waste-collection.php">Waste Collection Form</a>
        <a href="incident-report.php">Incident Report Form</a> 
        <a href="goal.php">Management Goals</a>
    </div>
</header>

<main>
    <div class="display">
        <h2>Set or Track Goals</h2>

        <!-- Goal Table -->
        <table>
            <tr>
                <th>Goal</th>
                <th>Action</th>
                <th>Progress</th>
            </tr>
            <?php foreach ($predefinedGoals as $goal => $steps): ?>
                <tr>
                    <td><?= htmlspecialchars($goal) ?></td>
                    <td>
                        <?php if (isset($_SESSION['progress'][$goal])): ?>
                            <!-- Track Progress Button -->
                            <form method="POST" style="display:inline;">
                                <button type="submit" name="track_progress" value="<?= htmlspecialchars($goal) ?>">Track Progress</button>
                            </form>
                        <?php else: ?>
                            <!-- Set Goal Button -->
                            <form method="POST" style="display:inline;">
                                <button type="submit" name="set_goal" value="<?= htmlspecialchars($goal) ?>">Set Goal</button>
                            </form>
                        <?php endif; ?>
                    </td>
                    <td>
                        <?php if (isset($_SESSION['progress'][$goal])): ?>
                            <?php
                            $completedSteps = $_SESSION['progress'][$goal]['completed'];
                            $totalSteps = count($steps);
                            $progressPercentage = ($completedSteps / $totalSteps) * 100;
                            ?>
                            <div class="progress-bar">
                                <div style="width: <?= $progressPercentage ?>%;"></div>
                            </div>
                        <?php else: ?>
                            <div class="progress-bar"><div></div></div>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
    </div>

    <?php
    // Display steps and track progress for the selected goal
    if (isset($_SESSION['current_goal']) && isset($_SESSION['progress'][$_SESSION['current_goal']])) {
        $goal = $_SESSION['current_goal'];
        $steps = $_SESSION['progress'][$goal]['steps'];
        $completedSteps = $_SESSION['progress'][$goal]['completed'];

        echo "<form method='POST'><button type='submit' name='reset_goal'>Reset Goals</button></form>";
        echo "<div class='goal-section'>";
        echo "<h3>Goal: " . htmlspecialchars($goal) . "</h3>";
        

        // Display next step if available
        if ($completedSteps < count($steps)) {
            echo "<div class='step-popup'>";
            echo "<p>Step " . ($completedSteps + 1) . ": " . htmlspecialchars($steps[$completedSteps]) . "</p>";
            echo "<form method='POST' id='step-form'>";
            echo "<input type='hidden' name='current_goal' value='" . htmlspecialchars($goal) . "'>";
            echo "<input type='hidden' name='next_step' value='$completedSteps'>";
            echo "<button type='button' id='mark-step-btn'>Mark Step as Completed</button>";
            echo "</form>";
            echo "</div>";
        } else {
            // Goal completed
            echo "<script>Swal.fire('Congratulations!', 'You have completed this goal!', 'success');</script>";
        }
        echo "</div>";
    }
    ?>

</main>

<footer>
    <p>&copy; 2024 TRASH SCaNS: Waste Management Tracker</p>
</footer>

<script>
// SweetAlert2 integration for marking step as completed
document.getElementById('mark-step-btn').addEventListener('click', function() {
    Swal.fire({
        title: 'Are you sure?',
        text: "Do you want to mark this step as completed?",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yes, mark it!',
        cancelButtonText: 'No, cancel',
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById('step-form').submit();  // Submit the form to mark the step
        }
    });
});
</script>

</body>
</html>
