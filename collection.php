<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Waste Collection Report Form</title>
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
            <a href="collection.php">Waste Collection</a>
            <a href="incident-report.php">Incident Report</a>
            <a href="about.php">About Us</a>
            <a href="contact.php">Contact</a>
        </div>
    </header>

    <main>
        <section class="form-section">
            <div class="collection-report-form box">
                <h1>Waste Collection Report Form</h1>
                <form action="" method="post">
                    <div class="field">
                        <label for="date">Date of Collection:</label>
                        <input type="date" id="date" name="date" required>
                    </div>

                    <div class="field">
                        <label for="waste-type">Waste Type:</label>
                        <select id="waste-type" name="waste-type" required>
                            <option value="organic-waste">Organic Waste</option>
                            <option value="hazardous-waste">Hazardous Waste</option>
                            <option value="solid-waste">Solid Waste</option>
                            <option value="liquid-waste">Liquid Waste</option>
                            <option value="recyclable-waste">Recyclable Waste</option>
                        </select>
                    </div>

                    <div class="field">
                        <label for="quantity">Number of Bags:</label>
                        <input type="number" id="quantity" name="quantity" min="1" max="10">
                    </div>

                    <div class="field">
                        <label for="location">Collection Location:</label>
                        <input type="text" id="location" name="location" required>
                    </div>

                    <div class="field">
                        <label for="disposal-method">Disposal Method:</label>
                        <select id="disposal-method" name="disposal-method" required>
                            <option value="recycling">Recycling</option>
                            <option value="composting">Composting</option>
                            <option value="incineration">Incineration</option>
                            <option value="sanitary-landfill">Sanitary Landfill</option>
                        </select>
                    </div>

                    <div class="field">
                        <label for="notes">Notes:</label>
                        <textarea id="notes" name="notes" rows="2"></textarea>
                    </div>

                    <button type="submit" class="btn">Submit Report</button>
                </form>
            </div>
        </section>
    </main>

    <footer>
        <div class="footer-links">
            <ul>
                <li><a href="#">Privacy Policy</a></li>
                <li><a href="#">Terms of Service</a></li>
                <li><a href="#">FAQ</a></li>
            </ul>
        </div>
        <div>
            <p>&copy; 2024 TRASH SCaNS: Waste Management Tracker</p>
        </div>
    </footer>
</body>
</html>
