<?php defined('APP') or die('Accesso Negato') ?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BookSwap | ISIT BOOKS</title>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@600&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">
    <style>
        :root { color-scheme: dark; }

        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: 'DM Sans', sans-serif;
            min-height: 100vh;
            background: linear-gradient(rgba(15,10,8,0.9), rgba(15,10,8,0.9)), 
                        url('https://images.unsplash.com/photo-1507842217343-583bb7270b66?auto=format&fit=crop&q=80&w=3000');
            background-size: cover; background-attachment: fixed; color: #f5f0e8;
            display: flex; flex-direction: column; align-items: center;
        }

        /* Navigazione Superiore Ripristinata */
        header {
            width: 100%;
            padding: 20px;
            display: flex;
            justify-content: center;
            gap: 30px;
            background: rgba(0,0,0,0.3);
            backdrop-filter: blur(10px);
            margin-bottom: 20px;
        }
        header a {
            color: #a89880;
            text-decoration: none;
            text-transform: uppercase;
            font-size: 0.75rem;
            letter-spacing: 2px;
            transition: 0.3s;
        }
        header a:hover, header a.active {
            color: #9c6b3c;
        }
        
        .annuncio-card {
            background: rgba(12, 9, 7, 0.96); backdrop-filter: blur(25px); border-radius: 20px;
            border: 1px solid rgba(156,107,60,0.15); padding: 2.5rem; width: 100%; max-width: 580px; margin: 20px 0;
        }

        h2 { font-family: 'Cormorant Garamond', serif; font-size: 2.8rem; margin-bottom: 2rem; text-align: center; }
        label { display: block; font-size: 0.68rem; letter-spacing: 2px; text-transform: uppercase; color: #a89880; margin-bottom: 10px; }

        input {
            width: 100%; background: rgba(255,255,255,0.03); border: 1px solid rgba(156,107,60,0.2);
            border-radius: 8px; padding: 14px; color: #f5f0e8; outline: none; transition: 0.3s;
        }
        input::placeholder { color: rgba(168, 152, 128, 0.5); font-size: 0.85rem; }

        /* Dropdown e Prezzo */
        .select-custom-wrapper { position: relative; cursor: pointer; }
        .select-trigger {
            width: 100%; background: rgba(255,255,255,0.03); border: 1px solid rgba(156,107,60,0.2);
            border-radius: 8px; padding: 14px; color: #f5f0e8; display: flex; justify-content: space-between; align-items: center;
        }
        .select-options {
            position: absolute; top: calc(100% + 5px); left: 0; width: 100%; 
            background: #120e0c; border: 1px solid rgba(156,107,60,0.4); border-radius: 8px;
            z-index: 10000; display: none; box-shadow: 0 10px 30px rgba(0,0,0,0.8);
            max-height: 220px; overflow-y: auto;
        }
        .select-options div { padding: 12px 16px; border-bottom: 1px solid rgba(156,107,60,0.05); transition: 0.2s; color: #d1c7b7; }
        .select-options div:hover { background: #9c6b3c; color: #fff; }

        .price-wrapper { position: relative; display: flex; align-items: center; }
        .price-controls { position: absolute; right: 10px; display: flex; flex-direction: column; }
        .arrow-btn { background: transparent; border: none; color: #9c6b3c; width: 22px; cursor: pointer; font-size: 10px; }

        /* Immagini */
        .upload-container {
            border: 1.5px dashed rgba(156,107,60,0.2); border-radius: 12px; padding: 30px;
            text-align: center; cursor: pointer; background: rgba(156,107,60,0.02);
        }
        .upload-container.disabled { border-color: rgba(156,107,60,0.1); opacity: 0.5; cursor: not-allowed; }
        .error-msg { color: #9c6b3c; font-size: 0.85rem; margin-top: 10px; text-align: center; display: none; }
        
        .preview-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 12px; margin-top: 15px; }
        .preview-item { position: relative; padding-top: 100%; border-radius: 8px; overflow: hidden; border: 1px solid rgba(156,107,60,0.2); }
        .preview-item img { position: absolute; top: 0; left: 0; width: 100%; height: 100%; object-fit: cover; }
        .remove-img { position: absolute; top: 5px; right: 5px; background: #c0392b; color: white; border: none; border-radius: 50%; width: 20px; height: 20px; cursor: pointer; z-index: 10; font-size: 10px; }

        .btn-submit {
            width: 100%; margin-top: 2.5rem; padding: 1.3rem; background: linear-gradient(135deg, #a67c52, #7d5a36);
            border: none; border-radius: 10px; color: white; letter-spacing: 3px; text-transform: uppercase; cursor: pointer; font-weight: 600;
        }
    </style>
</head>
<body>
    <header>
        <a href="index.php?page=annunci">Annunci</a>
        <a href="index.php?page=annunci&action=create" class="active">Nuovo</a>
        <a href="index.php?page=annunci&action=personal">I miei annunci</a>
    </header>

    
    <main>
        <section>
                <?php
                $action = $_GET['action'] ?? 'index';
                    if ($action == 'personal'){
                        include 'table_personal.php';
                    } else {
                        include 'table.php';
                    }       
                ?>
        </section>


        <?php if(!empty($view)) include $view; ?>
    </main>

    <footer>
            <hr>
            FOOTER &copy; 2026
        </footer>
</body>
</html>