<?php
// Start session to track score, tries, and question index across pages
session_start();

// Array of trivia facts with multiple choice options or true/false
$quiz = [
    [
        "question" => "Goal 12 is about ensuring sustainable consumption and production patterns, which is key to sustain the livelihoods of current and future generations. Is this true or false?",
        "options" => ["True", "False"],
        "answer" => "True"
    ],
    [
        "question" => "What does Goal 12 aim at?",
        "options" => ["Doing more and better with less", "Increasing waste production", "Promoting deforestation"],
        "answer" => "Doing more and better with less"
    ],
    [
        "question" => "As we burn fossil fuels and cut down forests, high concentrations of greenhouse gases, specifically carbon dioxide, threaten to raise the average surface temperature of the planet to intolerable levels. Is this true or false?",
        "options" => ["True", "False"],
        "answer" => "True"
    ],
    [
        "question" => "What is the impact of poor waste management?",
        "options" => ["Air and land pollution", "Increased food production", "Improved water quality"],
        "answer" => "Air and land pollution"
    ],
    [
        "question" => "The term 'carbon footprint' is a metaphor for the total impact something has on climate change. 'Carbon' refers to all the greenhouse gases that contribute to global warming. Is this true or false?",
        "options" => ["True", "False"],
        "answer" => "True"
    ],
    [
        "question" => "Goal 12 helps sustain the livelihoods of current and future generations by efficiently using natural resources and reducing waste. Is this true or false?",
        "options" => ["True", "False"],
        "answer" => "True"
    ],
    [
        "question" => "What percentage of plastic produced is recycled globally?",
        "options" => ["9%", "25%", "50%"],
        "answer" => "9%"
    ],
    [
        "question" => "How much waste does the world produce annually?",
        "options" => ["1.5 billion tons", "2.12 billion tons", "5 billion tons"],
        "answer" => "2.12 billion tons"
    ],
    [
        "question" => "What happens when animals mistake plastic debris for food?",
        "options" => ["They can eat it safely", "It can lead to starvation", "It enhances their digestion"],
        "answer" => "It can lead to starvation"
    ],
    [
        "question" => "How does improper waste disposal affect the environment?",
        "options" => ["It prevents pollution", "It leads to air, water, and soil contamination", "It helps reduce greenhouse gas emissions"],
        "answer" => "It leads to air, water, and soil contamination"
    ]
];

// Handle "retry" functionality to reset the quiz
if (isset($_GET['retry']) && $_GET['retry'] === 'true') {
    session_unset(); // Clear session data
    header("Location: " . $_SERVER['PHP_SELF']); // Reload page
    exit;
}

// Initialize session variables if not already set
if (!isset($_SESSION['questionIndex'])) {
    $_SESSION['questionIndex'] = 0;
    $_SESSION['score'] = 0;
    $_SESSION['questionTries'] = array_fill(0, count($quiz), 0);
}

// Check if all questions have been answered
$allAnswered = $_SESSION['questionIndex'] >= count($quiz);

// Prevent accessing undefined array key
$currentQuestion = !$allAnswered ? $quiz[$_SESSION['questionIndex']] : null;

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !$allAnswered) {
    $userAnswer = trim($_POST['answer']);
    $correctAnswer = $currentQuestion['answer'];
    $resultMessage = "";

    // Track tries for the current question
    $_SESSION['questionTries'][$_SESSION['questionIndex']]++;

    // Check if the user's answer is correct
    if ($userAnswer === $correctAnswer) {
        $resultMessage = "Correct!";
        $_SESSION['score']++; // Increase score
        $_SESSION['questionIndex']++; // Move to the next question
    } else {
        $resultMessage = "Incorrect.";
    }

    // Store the result message in session for SweetAlert
    $_SESSION['resultMessage'] = $resultMessage;
    header("Location: " . $_SERVER['PHP_SELF']); // Prevent form resubmission
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Interactive Trivia Quiz</title>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        :root {
            --primary-color: #4CAF50;
            --bg-color: #f4f4f9;
            --text-color: #333;
        }
        body {
            font-family: Arial, sans-serif;
            background-color: var(--bg-color);
            text-align: center;
            padding: 20px;
            color: var(--text-color);
        }
        .question-container {
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            max-width: 400px;
            margin: 0 auto;
        }
        .button {
            display: inline-block;
            margin-top: 20px;
            padding: 10px 20px;
            background-color: var(--primary-color);
            color: white;
            text-decoration: none;
            border-radius: 5px;
            cursor: pointer;
        }
    </style>
</head>
<body>

<h1>Trivia Quiz</h1>

<?php if ($allAnswered): ?>
    <div>
        <p>Your Score: <?= $_SESSION['score'] ?> / <?= count($quiz) ?></p>
        <p>Tries per Question: <?= implode(", ", $_SESSION['questionTries']) ?></p>
        <a href="<?= $_SERVER['PHP_SELF'] ?>?retry=true" class="button">Retry Quiz</a>
    </div>
<?php else: ?>
    <div class="question-container">
        <form method="POST">
            <p><?= $currentQuestion['question'] ?></p>
            <?php foreach ($currentQuestion['options'] as $option): ?>
                <label>
                    <input type="radio" name="answer" value="<?= $option ?>" required> <?= $option ?>
                </label><br>
            <?php endforeach; ?>
            <button type="submit" class="button">Submit</button>
        </form>
    </div>
<?php endif; ?>

<script>
<?php if (isset($_SESSION['resultMessage'])): ?>
    Swal.fire({
        title: "<?= $_SESSION['resultMessage'] ?>",
        icon: "<?= strpos($_SESSION['resultMessage'], 'Correct') !== false ? 'success' : 'error' ?>",
        confirmButtonText: "<?= $allAnswered ? 'Finish' : 'Next' ?>",
    }).then(() => {
        window.location.href = "<?= $_SERVER['PHP_SELF'] ?>";
    });
    <?php unset($_SESSION['resultMessage']); ?>
<?php endif; ?>
</script>

</body>
</html>
