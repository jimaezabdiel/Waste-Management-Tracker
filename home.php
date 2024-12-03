<?php
session_start();

// Ensure the user is logged in
if (!isset($_SESSION['user_id'])) {
    // If the user is not logged in, redirect to the login page
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Waste Management Tracker</title>
    <link rel="stylesheet" href="home.css">
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;600&family=Roboto:wght@300;400&display=swap" rel="stylesheet">
</head>
<body>
    <header>
        <div class="logo">
            <img src="logo.png" alt="Logo">
        </div>
        <div class="header-links">
            <a href="home.php">Home</a>
            <a href="profile.php">Profile</a>
            <a href="reuse_hub.php">Reuse Hub</a>
            <a href="logout.php">Logout</a> 
        </div>
    </header>

    <main>
        <section class="intro">
            <h1>Welcome to TRASH SCaNS</h1>
            <p>A Waste Management Tracker System that focuses on SDG 12: Responsible Consumption and Production</p>
        </section>

        <section class="overview">
            <h2>SDG 12: Responsible Consumption and Production Overview</h2>
            <div class="overview-content">
                <p>According to the United Nations, achieving sustainable consumption and production patterns is crucial for ensuring a balanced future.</p>
                <p>If the global population reaches 9.8 billion by 2050, nearly three Earths will be needed to meet the resource demands of current lifestyles.</p>
                <p>Recent global crises have led to a significant increase in fossil fuel subsidies, which nearly doubled from 2020 to 2021.</p>
                <p>In 2021, governments allocated an estimated $732 billion to support coal, oil, and gas industries, compared to $375 billion in 2020.</p>
                <p>These subsidies are inconsistent with the goal of transitioning towards sustainable energy sources.</p>
                <p>Despite global efforts to address hunger, approximately 828 million people were facing food insecurity in 2021.</p>
                <p>Meanwhile, about 13.2% of the world’s food was lost post-harvest due to inefficiencies in the supply chain, from farm to consumer.</p>
                <p>This points to the urgent need to reduce food waste and improve food distribution systems.</p>
                <p>The trend towards sustainability reporting continues to rise, with about 70% of monitored companies publishing sustainability reports in 2021.</p>
                <p>This reflects an increasing recognition of the importance of transparency and accountability in sustainability practices.</p>
                <p>In 2022, 67 national governments reported to the United Nations Environment Programme on the progress of sustainable public procurement policies and action plans, representing a 50% increase from 2020.</p>
                <p>These efforts are essential in promoting environmentally responsible public spending.</p>
                <p>By 2030, it is crucial to provide support to developing countries to help them transition to more sustainable consumption practices.</p>
                <p>This support will be key in achieving the broader goals of SDG 12 and ensuring that all nations contribute to a more sustainable future.</p>
            </div>
        </section>

        <section class="tips">
            <h2>Tips for Responsible Consumption</h2>
            <div class="tips-content">
                <ul>
                    <li>Use reusable bags and containers.</li>
                    <li>Compost organic waste.</li>
                    <li>Choose products with minimal packaging.</li>
                </ul>
            </div>
        </section>

        <section class="references">
            <h2>References</h2>
            <div class="references-content">
                <ul>
                    <li><a href="https://www.un.org/en/exhibits/page/sdgs-17-goals-transform-world" target="_blank">UN SDGs Overview</a></li>
                </ul>
            </div>
        </section>
    </main>

    <footer>
        <div>
            <p>&copy; 2024 TRASH SCaNS: Waste Management Tracker</p>
        </div>
    </footer>
</body>
</html>
