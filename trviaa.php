<?php
// Array of trivia facts
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

// Randomly select a trivia
$randomFact = $triviaFacts[array_rand($triviaFacts)];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Random Trivia</title>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;600&family=Roboto:wght@300;400&display=swap" rel="stylesheet">
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
            background-color: #DDF1E4; /* Header background color */
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
            background: none;  /* No background for links */
            padding: 0;  /* No padding */
        }

        header .header-links a:hover {
            color: #d1e4c7;
        }

        main {
            padding: 40px 20px;
            flex-grow: 1;
            text-align: center; 
            background-color: #ffffff; 
            color: #333;
        }

        h1 {
            font-size: 36px;
            color: #2c6b3f;
        }

        p {
            font-size: 20px;
            color: #333;
            margin-top: 20px;
        }

        a {
            display: inline-block;
            margin-top: 30px;
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            font-size: 16px;
            transition: background-color 0.3s ease;
        }

        a:hover {
            background-color: #45a049;
        }

        .references {
            margin-bottom: 20px;
            text-align: left;
            padding: 20px;
            background: #ffffff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 4px 4px 8px rgba(0.1, 0.1, 0.1, 0.1);
            font-size: 16px;
            margin-bottom: 15px;
        }

        .references-content {
            background: none;
            padding: 0;
        }

        .references-content ul {
            list-style-type: disc; 
            padding-left: 20px; 
        }

        .references-content li {
            margin-bottom: 10px; 
            background: none;
            padding: 0;
        }

        .references-content a {
            text-decoration: none; /* Removed underline by default */
            color: #6a4c9c; /* Text color for link */
            font-size: 16px;
            transition: color 0.3s ease, text-decoration 0.3s ease;
            background: none; /* No background for the link */
        }

        .references-content a:hover {
            color: #4CAF50; /* Change color on hover */
            text-decoration: underline; /* Underline on hover */
        }

        footer {
            background-color: #DDF1E4; /* Updated footer background color */
            color: #2c6b3f;
            padding: 20px 0;
            text-align: center;
        }

        footer p {
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
            <a href="trivia.php">Trivia</a>
            <a href="quiz.php">Trivia Quiz</a>
            <a href="logout.php">Logout</a>
        </div>
    </header>

    <main>
        <h1>Random Trivia</h1>
        <p><?= $randomFact ?></p>
        <a href="">Show Another Trivia</a>

        <section class="references">
            <h2>References</h2>
            <div class="references-content">
                <ul>
                    <li><a href="https://www.un.org/en/exhibits/page/sdgs-17-goals-transform-world" target="_blank">UN SDGs Overview</a></li>
                    <li><a href="https://www.theworldcounts.com/challenges/planet-earth/state-of-the-planet/world-waste-facts" target="_blank">The worlds counts</a></li>
                    <li><a href="https://arrowaste.com/2020/01/13/25-mind-blowing-facts-about-garbage/" target="_blank">Arrowaste</a></li>
                    <li><a href="https://handyrubbish.co.uk/20-astounding-facts-about-waste-management/" target="_blank">Handy Rubbish</a></li>
                    <li><a href="https://www.un.org/sustainabledevelopment/sustainable-consumption-production/" target="_blank">Sustainable Development Goals</a></li>
                    <li><a href="https://datatopics.worldbank.org/sdgatlas/archive/2017/SDG-12-responsible-consumption-andproduction.html" target="_blank">The World Bank</a></li>
                    <li><a href="https://www.theguardian.com/environment/blog/2010/jun/04/carbon-footprint-definition" target="_blank">The Guardian</a></li>
                    <li><a href="https://sciencepark.com.ph/blog/waste-management-important/" target="_blank">Science Park</a></li>
                    <li><a href="https://discountdumpsterco.com/blog/consequences-of-improper-waste-disposal/" target="_blank">Discount Dumpster Trash Talk </a></li>
                    <li><a href="https://www.unep.org/explore-topics/resource-efficiency/what-we-do/cities/solid-waste-management" target="_blank">UN environment programme</a></li>
                    <li><a href="https://earth.org/plastic-pollution-in-the-ocean-facts/"target="_blank">Earth.Org</a></li>
                    <li><a href="https://givingcompass.org/article/10-facts-about-plastic-pollution-you-absolutely-need-to-know" target="_blank"> </a>givingcompass.org</li>
                    <li><a href="https://www.epa.gov/recycle/reducing-and-reusing-basics" target="_blank">EPA.gov</a></li>
                    <li><a href="https://www.citizensinformation.ie/en/environment/waste-and-recycling/reducing-waste/" target="_blank">citizensinformation.ie</a></li>
                    <li><a href="https://www.princegeorgescountymd.gov/departments-offices/environment/waste-recycling/waste-toolkit/recycling-tips/source-reduction"target="_blank">princegeorgescountymd.gov</a></li>
                    <li><a href="https://www.southernwasteinformationexchange.com/interesting-facts-about-recycling-why-you-should-reduce-reuse-recycle/" target="_blank">Southern Waste Information Exchange </a></li>
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