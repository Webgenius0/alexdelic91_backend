<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Ultimate Cool Glitchy Text - Instant</title>
    <link href="https://fonts.googleapis.com/css2?family=Russo+One&display=swap" rel="stylesheet">
    <style>
        body {
            padding:  0 120px;
            margin: 0;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            background: #111;
            overflow: hidden;
            font-family: 'Russo One', sans-serif;
            color: #fff;
        }

        .wrapper {
            position: relative;
            text-align: center;
        }

        h1 {
            font-size: 4rem;
            text-transform: uppercase;
            letter-spacing: 10px;
            color: white;
            text-shadow:
                0 0 10px rgba(0, 255, 255, 0.6),
                0 0 20px rgba(0, 255, 255, 0.8),
                0 0 30px rgba(0, 255, 255, 1),
                0 0 40px rgba(0, 255, 255, 1),
                0 0 50px rgba(0, 255, 255, 1),
                0 0 75px rgba(0, 255, 255, 1);
            animation: glitch 1.5s infinite linear, laser 1.2s infinite alternate;
        }

        /* Glitch effect */
        @keyframes glitch {
            0% {
                transform: translate(0, 0);
                text-shadow:
                    0 0 10px rgba(0, 255, 255, 0.6),
                    0 0 20px rgba(0, 255, 255, 0.8),
                    0 0 30px rgba(0, 255, 255, 1),
                    0 0 40px rgba(0, 255, 255, 1),
                    0 0 50px rgba(0, 255, 255, 1),
                    0 0 75px rgba(0, 255, 255, 1);
            }
            25% {
                transform: translate(-10px, 10px);
                text-shadow:
                    2px 2px 3px rgba(0, 255, 0, 0.7),
                    0 0 15px rgba(0, 255, 0, 1),
                    0 0 30px rgba(0, 255, 0, 0.7);
            }
            50% {
                transform: translate(5px, -5px);
                text-shadow:
                    4px 4px 5px rgba(255, 0, 255, 0.7),
                    0 0 25px rgba(255, 0, 255, 1),
                    0 0 50px rgba(255, 0, 255, 0.8);
            }
            75% {
                transform: translate(-5px, -5px);
                text-shadow:
                    -2px -2px 3px rgba(0, 255, 255, 0.6),
                    0 0 20px rgba(0, 255, 255, 0.7),
                    0 0 40px rgba(0, 255, 255, 1);
            }
            100% {
                transform: translate(0, 0);
                text-shadow:
                    0 0 10px rgba(0, 255, 255, 0.6),
                    0 0 20px rgba(0, 255, 255, 0.8),
                    0 0 30px rgba(0, 255, 255, 1),
                    0 0 40px rgba(0, 255, 255, 1),
                    0 0 50px rgba(0, 255, 255, 1),
                    0 0 75px rgba(0, 255, 255, 1);
            }
        }

        /* Laser beam effect */
        @keyframes laser {
            0% {
                text-shadow:
                    0 0 10px rgba(0, 255, 255, 0.6),
                    0 0 20px rgba(0, 255, 255, 0.8),
                    0 0 30px rgba(0, 255, 255, 1);
            }
            50% {
                text-shadow:
                    0 0 20px rgba(255, 0, 255, 1),
                    0 0 30px rgba(255, 0, 255, 1),
                    0 0 50px rgba(0, 255, 255, 1);
            }
            100% {
                text-shadow:
                    0 0 30px rgba(0, 255, 255, 1),
                    0 0 50px rgba(255, 0, 255, 1),
                    0 0 75px rgba(0, 255, 255, 1);
            }
        }
    </style>
</head>
<body>
<div class="wrapper">
    <h1>Download the app from app store and play store</h1>
</div>
</body>
</html>
