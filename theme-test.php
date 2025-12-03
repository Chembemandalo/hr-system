<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Theme Test</title>
    <link rel="stylesheet" href="assets/css/style.css?v=<?php echo time(); ?>">
    <style>
        .test-container {
            padding: 2rem;
            max-width: 800px;
            margin: 0 auto;
        }
        .test-buttons {
            display: flex;
            gap: 1rem;
            margin: 2rem 0;
        }
        .test-btn {
            padding: 1rem 2rem;
            font-size: 1rem;
            cursor: pointer;
            border: none;
            border-radius: 0.5rem;
            font-weight: 600;
            transition: all 0.3s;
        }
        .test-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
        }
        .light-btn {
            background: #ffd700;
            color: #333;
        }
        .dark-btn {
            background: #1a1a1a;
            color: #fff;
        }
        .hybrid-btn {
            background: linear-gradient(90deg, #ffd700 50%, #1a1a1a 50%);
            color: #333;
        }
        .info-box {
            background: var(--card-bg);
            color: var(--text-color);
            padding: 1.5rem;
            border-radius: 0.5rem;
            margin: 1rem 0;
            border: 2px solid var(--border-color);
        }
        .sidebar-demo {
            background: var(--sidebar-bg);
            color: var(--sidebar-text);
            padding: 2rem;
            border-radius: 0.5rem;
            margin: 1rem 0;
        }
    </style>
</head>
<body>
    <div class="test-container">
        <h1 style="color: var(--text-color);">Theme Switcher Test Page</h1>
        <p style="color: var(--text-color);">Click the buttons below to test the theme switcher:</p>
        
        <div class="test-buttons">
            <button class="test-btn light-btn" onclick="setTheme('light')">
                ☀️ Light Mode
            </button>
            <button class="test-btn dark-btn" onclick="setTheme('dark')">
                🌙 Dark Mode
            </button>
            <button class="test-btn hybrid-btn" onclick="setTheme('hybrid')">
                ⚖️ Hybrid Mode
            </button>
        </div>

        <div class="info-box">
            <h3>Current Theme Info:</h3>
            <p>Background Color: <span id="bg-color"></span></p>
            <p>Text Color: <span id="text-color"></span></p>
            <p>Data-theme attribute: <span id="theme-attr"></span></p>
        </div>

        <div class="sidebar-demo">
            <h3>Sidebar Demo</h3>
            <p>This box uses sidebar colors (should be teal with white text)</p>
        </div>

        <div class="info-box">
            <h3>Instructions:</h3>
            <ul>
                <li>Click any theme button above</li>
                <li>The page colors should change immediately</li>
                <li>Open browser console (F12) to see debug logs</li>
                <li>Refresh the page - your theme should persist</li>
            </ul>
        </div>
    </div>

    <script src="assets/js/script.js?v=<?php echo time(); ?>"></script>
    <script>
        function updateInfo() {
            const styles = getComputedStyle(document.body);
            document.getElementById('bg-color').textContent = styles.backgroundColor;
            document.getElementById('text-color').textContent = styles.color;
            document.getElementById('theme-attr').textContent = document.documentElement.getAttribute('data-theme') || 'none';
        }

        // Update info on page load and after theme changes
        setInterval(updateInfo, 500);
        updateInfo();
    </script>
</body>
</html>
