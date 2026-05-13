<?php defined('APP') or die('Accesso Negato') ?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Accesso Richiesto</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Georgia, serif;
            background: #f3e7d3;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }

        .access-denied-container {
            background: white;
            padding: 50px 40px;
            border-radius: 12px;
            text-align: center;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            max-width: 500px;
            width: 90%;
        }

        .icon {
            font-size: 80px;
            margin-bottom: 20px;
            color: #de1f26;
        }

        h1 {
            font-size: 28px;
            color: #2c2c2c;
            margin-bottom: 15px;
        }

        p {
            font-size: 16px;
            color: #555;
            margin-bottom: 30px;
            line-height: 1.6;
        }

        .button-group {
            display: flex;
            gap: 15px;
            justify-content: center;
            flex-wrap: wrap;
        }

        .btn {
            padding: 12px 25px;
            font-size: 16px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
            transition: 0.3s ease;
        }

        .btn-primary {
            background: #de1f26;
            color: white;
        }

        .btn-primary:hover {
            background: #b81820;
            transform: translateY(-2px);
        }

        .btn-secondary {
            background: #ddd;
            color: #333;
            border: 1px solid #999;
        }

        .btn-secondary:hover {
            background: #ccc;
            transform: translateY(-2px);
        }
    </style>
</head>
<body>

    <div class="access-denied-container">
        <div class="icon">🔒</div>
        <h1>Accesso Richiesto</h1>
        <p>Questa sezione è disponibile solo per gli utenti registrati. Accedi al tuo account o registrati per continuare.</p>

        <div class="button-group">
            <a href="index.php?page=login&action=login" class="btn btn-primary">Accedi</a>
            <a href="index.php?page=login&action=registration" class="btn btn-secondary">Registrati</a>
        </div>
    </div>

</body>
</html>
