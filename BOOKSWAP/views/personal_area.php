<?php defined('APP') or die('Accesso Negato') ?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Area Personale</title>
    <style>
        body {
            margin: 0;
            font-family: Georgia, serif;
            background: #f3e7d3;
        }

        .personal-area-container {
            padding: 50px 5%;
            max-width: 1200px;
            margin: 0 auto;
        }

        h1 {
            font-size: 32px;
            color: #2c2c2c;
            margin-bottom: 10px;
        }

        .subtitle {
            font-size: 14px;
            color: #888;
            margin-bottom: 30px;
        }

        .content {
            background: white;
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            text-align: center;
            min-height: 400px;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .content p {
            font-size: 18px;
            color: #555;
        }
    </style>
</head>
<body>

    <div class="personal-area-container">
        <h1>Area Personale</h1>
        <p class="subtitle">Benvenuto, <?= htmlspecialchars($_SESSION['nome'] ?? 'Utente') ?>!</p>

        <div class="content">
            <p>La tua area personale sarà disponibile a breve. 📚</p>
        </div>
    </div>

</body>
</html>
