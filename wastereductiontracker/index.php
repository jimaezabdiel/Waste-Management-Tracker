<!DOCTYPE html>
<html lang = "en">
<head>
    <meta charset = "UTF-8">
    <meta name = "viewport" content = "width=device-width, initial-scale=1.0">
    <title>Waste Reduction Tracker</title>
    <link rel = "stylesheet" href = "styles.css">
    <script src = "https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <div class = "container">
        <h1>Waste Reduction Tracker</h1>
        <canvas id = "wasteChart"></canvas>
    </div>

    <script>
        // Fetch waste reduction data from the server
        fetch('get_waste_data.php')
            .then(response => response.json())
            .then(data => {
                // Initialize the chart with the fetched data
                const ctx = document.getElementById('wasteChart').getContext('2d');
                new Chart(ctx, {
                    type: 'line', // Line chart to show trends over time
                    data: {
                        labels: data.dates, // Dates for X-axis
                        datasets: [{
                            label: 'Waste Reduced (kg)',
                            data: data.amounts, // Amount of waste reduced
                            borderColor: 'rgba(75, 192, 192, 1)',
                            backgroundColor: 'rgba(75, 192, 192, 0.2)',
                            borderWidth: 2,
                            tension: 0.4
                        }]
                    },
                    options: {
                        responsive: true,
                        plugins: {
                            legend: {
                                position: 'top',
                            },
                            tooltip: {
                                callbacks: {
                                    label: function(tooltipItem) {
                                        return tooltipItem.raw + ' kg';
                                    }
                                }
                            }
                        },
                        scales: {
                            x: {
                                title: {
                                    display: true,
                                    text: 'Date'
                                }
                            },
                            y: {
                                title: {
                                    display: true,
                                    text: 'Amount (kg)'
                                },
                                beginAtZero: true
                            }
                        }
                    }
                });
            })
            .catch(error => console.error('Error fetching data:', error));
    </script>
</body>
</html>
