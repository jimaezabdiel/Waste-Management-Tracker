<?php
session_start();

// Base class: Abstraction for common functionality
abstract class TriviaQuizBase {
    protected $data; // Encapsulation: Data is kept private

    public function __construct($data) {
        $this->data = $data;
    }
}

// Trivia class: Handles trivia facts
class Trivia extends TriviaQuizBase {
    public function getRandomFact($seenFacts) {
        $unseenFacts = array_diff($this->data, $seenFacts);

        // If all facts are seen, return null
        if (empty($unseenFacts)) {
            return null;
        }

        $fact = $unseenFacts[array_rand($unseenFacts)];
        $_SESSION['seenFacts'][] = $fact;
        return $fact;
    }
}

// Quiz class: Handles quiz functionality
class Quiz extends TriviaQuizBase {
    private $score;
    private $questionIndex;
    private $questionTries;

    public function __construct($data) {
        parent::__construct($data);

        // Initialize session variables for quiz state
        $this->score = $_SESSION['score'] ?? 0;
        $this->questionIndex = $_SESSION['questionIndex'] ?? 0;
        $this->questionTries = $_SESSION['questionTries'] ?? array_fill(0, count($this->data), 0);
    }

    public function isQuizCompleted() {
        return $this->questionIndex >= count($this->data);
    }

    public function getCurrentQuestion() {
        return $this->isQuizCompleted() ? null : $this->data[$this->questionIndex];
    }

    public function handleAnswer($userAnswer) {
        if ($this->isQuizCompleted()) return;

        $currentQuestion = $this->getCurrentQuestion();
        $correctAnswer = $currentQuestion['answer'];

        $this->questionTries[$this->questionIndex]++;

        if ($userAnswer === $correctAnswer) {
            $this->score++;
            $this->questionIndex++;
        }

        $this->saveState();
    }

    public function resetQuiz() {
        $this->score = 0;
        $this->questionIndex = 0;
        $this->questionTries = array_fill(0, count($this->data), 0);
        $this->saveState();
    }

    private function saveState() {
        $_SESSION['score'] = $this->score;
        $_SESSION['questionIndex'] = $this->questionIndex;
        $_SESSION['questionTries'] = $this->questionTries;
    }

    public function getScore() {
        return $this->score;
    }

    public function getTries() {
        return $this->questionTries;
    }
}

// Trivia facts
$triviaFacts = [
    "Goal 12 is about ensuring sustainable consumption and production patterns, which is key to sustain the livelihoods of current and future generations.",
    "Goal 12 aims at 'doing more and better with less', increasing net welfare gains from economic activities by reducing resource use, degradation, and pollution.",
    "As we burn fossil fuels and cut down forests, high concentrations of greenhouse gases, specifically carbon dioxide, threaten to raise the average surface temperature of the planet to intolerable levels — and cause a host of life-threatening impacts.",
    "Waste management impact: Poor waste management practices can lead to air and land pollution, which can cause serious medical conditions in humans, animals, and plants.",
    "The term 'carbon footprint' is a metaphor for the total impact something has on climate change. 'Carbon' is a shorthand for all the greenhouse gases that contribute to global warming.",
    "The goal is to sustain the livelihoods of current and future generations by efficiently using natural resources and reducing waste.",
    "We generate more waste each year: We generate around 3% more waste each year than the previous year.",
    "Waste management: The waste management industry is one of the oldest in the world.",
    "Waste production: The world produces 2.12 billion tons of waste each year, and 3,825 tons of municipal waste every minute.",
    "Landfill space: Reducing waste reduces the amount of waste that ends up in landfills.",
    "Natural resources: Reducing waste helps preserve natural resources.",
    "Money savings: Reducing waste can save money through reduced spending and disposal costs.",
    "Jobs: Recycling creates jobs.",
    "Plastic pollution: Only 9% of all plastic produced is recycled.",
    "Animals mistake plastic for food: Plastic debris in the water can look like food to animals, which can prevent them from eating and lead to starvation.",
    "Poor waste management can lead to air pollution, water and soil contamination.",
    "Improper waste disposal can pollute the environment, cause health issues, damage the economy, and contribute to climate change."
];

// Quiz questions
$quizQuestions = [
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

// Instantiate objects
$trivia = new Trivia($triviaFacts);
$quiz = new Quiz($quizQuestions);

// Initialize seen facts if not set
if (!isset($_SESSION['seenFacts'])) {
    $_SESSION['seenFacts'] = [];
}

// Check if all questions have been answered
$allAnswered = $quiz->isQuizCompleted();
$currentQuestion = !$allAnswered ? $quiz->getCurrentQuestion() : null;

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !$allAnswered) {
    $userAnswer = trim($_POST['answer']);
    $quiz->handleAnswer($userAnswer);

    // Store the result of the answer (correct or incorrect)
    $message = $userAnswer === $currentQuestion['answer'] ? "Correct! Moving to the next question." : "Incorrect! Try again.";
    $icon = $userAnswer === $currentQuestion['answer'] ? "success" : "error";
    $_SESSION['alertMessage'] = $message;
    $_SESSION['alertIcon'] = $icon;

    // Redirect to avoid resubmission
    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}

// Handle retry functionality
if (isset($_GET['retry']) && $_GET['retry'] === 'true') {
    $quiz->resetQuiz();
    $_SESSION['seenFacts'] = [];
    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trivia and Quiz</title>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;600&display=swap" rel="stylesheet">
    <!-- Include SweetAlert2 -->
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
    background-color: #DDF1E4; /* Updated color */
    color:#2c6b3f;
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
    text-align: center;
}

        .question-container, .trivia-container {
            background-color: white;
            border-radius: 10px;
            padding: 20px;
            margin: 20px auto;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            max-width: 500px;
        }
        .button {
            background-color: #2c6b3f;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
        }
        .button:hover {
            background-color: #4CAF50;
        }
        footer {
    background-color: #DDF1E4; /* Updated color */
    color: #2c6b3f;
    padding: 20px 0;
    text-align: center;
}

footer .footer-links {
    margin-bottom: 10px;
}

footer .footer-links ul {
    list-style: none;
    padding: 0;
}

footer .footer-links ul li {
    display: inline;
    margin-right: 10px;
}

footer .footer-links ul li a {
    color: #2c6b3f;
    text-decoration: none;
    font-weight: 600;
}

footer .footer-links ul li a:hover {
    color: #2c6b3f; 
}

footer p {
    margin-top: 10px;
    font-size: 14px;
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
            <a href="profile.php">Profile</a>
            <a href="waste-calendar.php">Waste Calendar</a>
            <a href="reuse_hub.php">Reuse Hub</a>
            <a href="trivia.php">Trivia</a>
            <a href="logout.php">Logout</a> 
        </div>
    </header>
    <main>
    <h1>Trivia and Quiz</h1>
        <div class="trivia-container">
            <h2>Did You Know?</h2>
            <p><?= $trivia->getRandomFact($_SESSION['seenFacts']) ?></p>
            <a href="<?= $_SERVER['PHP_SELF'] ?>" class="button">Show Another Fact</a>
        </div>

        <?php if ($allAnswered): ?>
            <div class="question-container">
                <h2>Quiz Completed!</h2>
                <p>Your Score: <?= $quiz->getScore() ?> / <?= count($quizQuestions) ?></p>
                <p>Tries per Question: <?= implode(", ", $quiz->getTries()) ?></p>
                <a href="<?= $_SERVER['PHP_SELF'] ?>?retry=true" class="button">Retry Quiz</a>
            </div>
        <?php else: ?>
            <div class="question-container">
                <h2>Quiz Time</h2>
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
    </main>

    <footer>
        <p>&copy; 2024 TRASH SCaNS: Waste Management Tracker</p>
    </footer>

    <!-- SweetAlert2 Script to Show Messages -->
    <script>
        <?php if (isset($_SESSION['alertMessage'])): ?>
            Swal.fire({
                title: '<?= $_SESSION['alertMessage'] ?>',
                icon: '<?= $_SESSION['alertIcon'] ?>',
                confirmButtonText: 'Okay'
            });
            <?php unset($_SESSION['alertMessage'], $_SESSION['alertIcon']); ?>
        <?php endif; ?>
    </script>
</body>
</html>
