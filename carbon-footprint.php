<?php
// Emission factors in kg CO2e per unit (approximate values)
$emission_factors = [
    "organic-waste" => 2.5,
    "hazardous-waste" => 15,
    "solid-waste" => 5,
    "liquid-waste" => 3,
    "recyclable-waste" => 1.5
];

// Initialize variables
$total_carbon_footprint = 0;
$waste_breakdown = [];

// Handle form submission and calculate the carbon footprint
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get user input from the form and calculate the carbon footprint
    $waste_type = isset($_POST['waste-type']) ? $_POST['waste-type'] : '';
    $weight = isset($_POST['weight']) ? (float) $_POST['weight'] : 0;
    $disposal_method = isset($_POST['disposal-method']) ? $_POST['disposal-method'] : '';

    if ($waste_type && isset($emission_factors[$waste_type]) && $weight > 0) {
        // Calculate the carbon footprint based on the selected waste type and weight
        $carbon_footprint = $emission_factors[$waste_type] * $weight;
        $total_carbon_footprint = $carbon_footprint;
        $waste_breakdown = [
            'waste_type' => $waste_type,
            'weight' => $weight,
            'disposal_method' => $disposal_method,
            'carbon_footprint' => $carbon_footprint
        ];
    }
}

// Carbon Offset Recommendations
function getCarbonOffsetRecommendations($footprint) {
    $recommendations = [];

    if ($footprint > 100) {
        $recommendations[] = "Plant 10 trees to offset your carbon footprint.";
        $recommendations[] = "Switch to reusable containers to reduce waste.";
        $recommendations[] = "Walk or bike short distances instead of driving.";
        $recommendations[] = "Support eco-friendly companies and products.";
    } elseif ($footprint > 50) {
        $recommendations[] = "Plant 5 trees this year to help offset your footprint.";
        $recommendations[] = "Avoid single-use plastics in your daily routine.";
        $recommendations[] = "Take public transport or carpool to reduce emissions.";
    } else {
        $recommendations[] = "Maintain your low footprint by composting organic waste.";
        $recommendations[] = "Continue supporting sustainable practices in your community.";
        $recommendations[] = "Consider biking instead of driving for short trips.";
    }

    return $recommendations;
}

$recommendations = getCarbonOffsetRecommendations($total_carbon_footprint);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Carbon Footprint Estimator</title>
    <link rel="stylesheet" href="home.css">
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;600&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.0.0/dist/sweetalert2.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Open Sans', sans-serif;
        }
        .main-content {
            display: flex;
            justify-content: space-between;
            gap: 20px;
            padding: 20px;
        }
        .left-column, .right-column {
            width: 48%;
        }
        .form-group {
            margin-bottom: 10px;
        }
        input[type="number"], select {
            padding: 5px;
            width: 100%;
            margin-top: 5px;
        }
        button {
            padding: 10px;
            background-color: #28a745;
            color: white;
            border: none;
            cursor: pointer;
        }
        button:hover {
            background-color: #218838;
        }
        .summary-section, .details, .waste-logs {
            margin-top: 20px;
        }
        .summary-section p, .details ul, .waste-logs ul {
            margin: 0;
        }
        .left-column {
            border-right: 2px solid #ddd;
            padding-right: 20px;
        }
        .calculator-box {
            border: 2px solid #ddd;
            padding: 20px;
            background-color: #f9f9f9;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .left-column ul {
            list-style-type: none;
            padding: 0;
        }
        .left-column li {
            margin-bottom: 10px;
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
            <a href="carbon-footprint.php">Carbon Footprint Estimator</a>
        </div>
    </header>

    <main class="main-content">
        <div class="left-column">
            <h2>Estimated Carbon Footprint per kg of Waste</h2>
            <ul>
                <li><strong>Organic Waste:</strong> <?= number_format($emission_factors['organic-waste'], 2) ?> kg CO₂e per kg</li>
                <li><strong>Hazardous Waste:</strong> <?= number_format($emission_factors['hazardous-waste'], 2) ?> kg CO₂e per kg</li>
                <li><strong>Solid Waste:</strong> <?= number_format($emission_factors['solid-waste'], 2) ?> kg CO₂e per kg</li>
                <li><strong>Liquid Waste:</strong> <?= number_format($emission_factors['liquid-waste'], 2) ?> kg CO₂e per kg</li>
                <li><strong>Recyclable Waste:</strong> <?= number_format($emission_factors['recyclable-waste'], 2) ?> kg CO₂e per kg</li>
            </ul>
            <h2>Disposal Method and Impact</h2>
            <ul>
                <li><strong>Composting:</strong> Low environmental impact.</li>
                <li><strong>Landfill:</strong> High carbon emissions due to methane production.</li>
                <li><strong>Incineration:</strong> Significant carbon emissions but reduces waste volume.</li>
                <li><strong>Recycling:</strong> Reduces carbon footprint by reusing materials.</li>
            </ul>
        </div>

        <div class="right-column">
            <h1>Carbon Footprint Estimator</h1>
            
            <div class="calculator-box">
                <form method="POST" action="">
                    <div class="form-group">
                        <label for="waste-type">Waste Type:</label>
                        <select id="waste-type" name="waste-type">
                            <option value="organic-waste">Organic Waste</option>
                            <option value="hazardous-waste">Hazardous Waste</option>
                            <option value="solid-waste">Solid Waste</option>
                            <option value="liquid-waste">Liquid Waste</option>
                            <option value="recyclable-waste">Recyclable Waste</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="weight">Weight (kg):</label>
                        <input type="number" id="weight" name="weight" step="any" value="0">
                    </div>
                    <div class="form-group">
                        <label for="disposal-method">Disposal Method:</label>
                        <select id="disposal-method" name="disposal-method">
                            <option value="composting">Composting</option>
                            <option value="landfill">Landfill</option>
                            <option value="incineration">Incineration</option>
                            <option value="recycling">Recycling</option>
                        </select>
                    </div>
                    <button type="submit">Calculate Estimated Carbon Footprint</button>
                </form>
            </div>
        </div>
    </main>

    <footer>
        <p>&copy; 2024 Waste Management Tracker</p>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.0.0/dist/sweetalert2.min.js"></script>

    <script>
    <?php if ($_SERVER['REQUEST_METHOD'] == 'POST'): ?>
        const recommendations = <?= json_encode($recommendations) ?>.map(function(item) {
            return `<li>${item}</li>`;
        }).join('');
        Swal.fire({
            title: 'Carbon Footprint Calculated!',
            html: `
                <p>Your estimated carbon footprint is <?= number_format($total_carbon_footprint, 2) ?> kg CO₂e.</p>
                <p><strong>Suggestions:</strong></p>
                <ul style="list-style-type: disc; padding-left: 20px; text-align: left;">
                    ${recommendations}
                </ul>
            `,
            icon: 'success',
            confirmButtonText: 'Close',
            width: 'auto',
            padding: '20px'
        });
    <?php endif; ?>
</script>


</body>
</html>
