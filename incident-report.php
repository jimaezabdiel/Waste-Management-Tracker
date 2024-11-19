<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Incident Report Form</title>
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
            <div class="incident-report-form box">
                <h1>Incident Report Form</h1>
                <form action="" method="post">
                    <div class="field">
                        <label for="incident-type">Incident Type:</label>
                        <select id="incident-type" name="incident-type" required>
                            <option value="missed-pickup">Missed Pickup</option>
                            <option value="illegal-dumping">Illegal Dumping</option>
                            <option value="vehicle-breakdown">Vehicle Breakdown</option>
                        </select>
                    </div>

                    <div class="field">
                        <label for="incident-location">Incident Location:</label>
                        <input type="text" id="incident-location" name="incident-location" required>
                    </div>

                    <div class="field">
                        <label for="incident-date">Incident Date:</label>
                        <input type="datetime-local" id="incident-date" name="incident-date" required>
                    </div>

                    <div class="field">
                        <label for="incident-description">Incident Description:</label>
                        <textarea id="incident-description" name="incident-description" rows="2" required></textarea>
                    </div>

                    <div class="field">
                        <label for="incident-photo">Upload Photo (Optional):</label>
                        <input type="file" id="incident-photo" name="incident-photo">
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
