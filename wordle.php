<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GuessGrid</title>
    <link rel="stylesheet" href="wordle.css">
    <script src="wordle.js" defer></script>
    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
    <style>
        /* Basic styling for dark mode */
        body[data-theme='dark'] {
            background-color: #121212;
            color: #ffffff;
        }

        /* Theme toggle button */
        #theme-toggle {
            background: none;
            border: none;
            cursor: pointer;
            font-size: 24px;
        }

        /* Restart button styling */
        #restart-button {
            margin: 20px auto;
            padding: 10px 20px;
            font-size: 18px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
            display: block;
            text-align: center;
        }

        #restart-button:hover {
            background-color: #45a049;
            box-shadow: 0 0 0 4px rgba(0, 0, 0, 0.2);
            transform: scale(1.05);
            border-color: #45a049;
            color: #ffffff;
        }

        /* Score display */
        #score {
            font-size: 1.5em;
            margin: 20px auto;
            padding: 10px;
            background-color: #F5D100;
            color: #333;
            border-radius: 5px;
            border: 1px solid #ddd;
            text-align: center;
            width: fit-content;
        }

        /* Hide audio elements */
        audio {
            display: none;
        }

        /* Modal styles */
        #rules-modal {
            display: none; /* Initially hidden */
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.7); /* Darker semi-transparent background */
            justify-content: center;
            align-items: center;
            z-index: 1000; /* Above other content */
        }

        #rules-content {
            background: #2C2C2C; /* Modal background color */
            color: white; /* Text color */
            padding: 20px;
            border-radius: 8px;
            max-width: 500px;
            width: 90%;
            text-align: center;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        #rules-content h2 {
            margin-top: 0;
        }

        #close-rules {
            margin-top: 20px;
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        #close-rules:hover {
            background-color: #45a049;
        }

        /* Tile colors */
        body[data-theme='dark'] .correct {
            background-color: #6bb9f0; /* Light blue for correct letters */
            color: white;
            border-color: white;
        }

        body[data-theme='dark'] .present {
            background-color: #f1c40f; /* Yellow for present letters */
            color: white;
            border-color: white;
        }

        body[data-theme='dark'] .absent {
            background-color: #e74c3c; /* Red for absent letters */
            color: white;
            border-color: white;
        }

        /* Modal colors */
        #rules-content ul li span {
            font-weight: bold;
        }

        #rules-content ul li span.correct-color {
            color: #6bb9f0; /* Light blue */
        }

        #rules-content ul li span.present-color {
            color: #f1c40f; /* Yellow */
        }

        #rules-content ul li span.absent-color {
            color: #e74c3c; /* Red */
        }

        /* Icons */
        .icon-light-mode::before {
            content: "\f185"; /* Sun icon */
            font-family: "Font Awesome 6 Free";
            font-weight: 900;
            color: #f1c40f; /* Yellow */
            margin-right: 2px;
        }

        .icon-dark-mode::before {
            content: "\f186"; /* Moon icon */
            font-family: "Font Awesome 6 Free";
            font-weight: 900;
            color: #6bb9f0; /* Light blue */
            margin-right: 2px;
        }

        /* Settings menu */
        #settings-icon {
            position: fixed;
            top: 20px;
            right: 20px;
            font-size: 24px;
            cursor: pointer;
        }

        #settings-menu {
            position: fixed;
            top: 20px;
            right: 60px;
            background: #333;
            padding: 20px;
            border-radius: 8px;
            color: white;
            display: none;
        }

        #settings-menu.show-menu {
            display: block;
        }

        #settings-menu label {
            margin-right: 10px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1 id="title">GuessGrid</h1>
        <hr>
        <button id="theme-toggle" aria-label="Toggle Dark/Light Mode">
            <i id="theme-icon" class="fas fa-sun"></i> <!-- Default icon -->
        </button>
        <div id="score">Score: 0</div> <!-- Score Display -->
        <div id="board"></div>
        <button id="restart-button">Restart Game</button>
        <h1 id="answer"></h1>
    </div>

    <!-- Settings Icon and Menu -->
    <div id="settings-icon">
        <i class="fas fa-cog"></i>
    </div>
    <div id="settings-menu">
        <label for="music-toggle">Play Background Music</label>
        <input type="checkbox" id="music-toggle" checked> <!-- Music Toggle -->
        <button id="close-settings">Close</button>
    </div>

    <!-- Rules Modal -->
    <div id="rules-modal">
        <div id="rules-content">
            <h2>Game Rules</h2>
            <p>Here are the rules for GuessGrid:</p>
            <ul>
                <li>Guess the hidden word in 6 tries.</li>
                <li>Each guess must be a valid word.</li>
                <li>The letters in the guessed word will be highlighted:</li>
                <li><span class="correct-color">Light blue</span> for correct letters in the correct position.</li>
                <li><span class="present-color">Yellow</span> for correct letters in the wrong position.</li>
                <li><span class="absent-color">Red</span> for incorrect letters.</li>
                <li>There will also be a definition of the word displayed to help you understand what the word means.</li>
                <li>You can restart the game by simply pressing the restart button which will generate a new word for you to guess.</li>
                <li>You can also toggle between <span class="icon-light-mode"></span> light mode and <span class="icon-dark-mode"></span> dark mode depending on your preference.</li>
                <li>You get 5 points when you guess the correct word.</li>
                <li>Enjoy!</li>
            </ul>
            <button id="close-rules">Play Game</button>
        </div>
    </div>

    <!-- Audio elements -->
    <audio id="background-music" src="neon.mp3" loop></audio>
    <audio id="correct-sound" src="correct.mp3.mp3"></audio>
    <audio id="wrong-sound" src="wrong.mp3.mp3"></audio>
</body>
</html>
