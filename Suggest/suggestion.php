// Suggestion Page 
<!DOCTYPE html>
<html>
<head>
    <title>Suggestions</title>
    <script>
        async function loadSuggestion() {
            const res = await fetch('suggest.php');
            const data = await res.json();
            document.getElementById('suggestion').textContent = data.suggestion;
        }
        window.onload = loadSuggestion;
    </script>
</head>
<body>
    <h1>Your Sustainable Suggestion</h1>
    <p id="suggestion">Loading...</p>
</body>
</html>

