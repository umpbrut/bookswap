<?php
defined('APP') or die('Accesso Negato');
$quotes = [
    ['text' => 'Non ci sono scorciatoie per i posti dove vale la pena andare.', 'author' => 'Beverly Sills'],
    ['text' => 'Un libro deve essere un\'ascia per il mare ghiacciato che è dentro di noi.', 'author' => 'Franz Kafka'],
    ['text' => 'Leggere è sognare per mano di un altro.', 'author' => 'Fernando Pessoa'],
    ['text' => 'I libri sono la più silenziosa e costante delle amiche.', 'author' => 'Charles W. Eliot'],
    ['text' => 'Chi non legge, a 70 anni avrà vissuto una sola vita. Chi legge avrà vissuto 5000 anni.', 'author' => 'Umberto Eco']
];
$randomQuote = $quotes[array_rand($quotes)];
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BookSwap | Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,700;1,400&family=Cormorant+Garamond:ital,wght@0,300;0,600;1,400&family=DM+Sans:wght@300;400;600&family=Lato:ital,wght@0,300;0,400;0,600;1,300&display=swap" rel="stylesheet">
    <style>
        :root {
            --library-sepia: #f4f1ea;
            --library-dark: #2c2c2c;
            --library-accent: #8b5e3c;
        }

        @keyframes scrollBackground {
            from { background-position: 0 0; }
            to   { background-position: -2000px 0; }
        }

        body {
            font-family: 'Lato', sans-serif;
            background: linear-gradient(rgba(0,0,0,0.5), rgba(0,0,0,0.5)),
                url('https://images.unsplash.com/photo-1507842217343-583bb7270b66?auto=format&fit=crop&q=80&w=3000');
            background-size: auto 100%;
            background-repeat: repeat-x;
            height: 100vh;
            margin: 0;
            overflow: hidden;
            animation: scrollBackground 80s linear infinite;
        }

        .main-wrapper {
            height: 100vh;
            display: flex;
            align-items: center;
        }

        .login-section {
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .quote-section {
            background: rgba(0,0,0,0.4);
            height: 100vh;
            display: flex;
            flex-direction: column;
            justify-content: center;
            padding: 3rem;
            backdrop-filter: blur(8px);
            border-left: 1px solid rgba(255,255,255,0.1);
        }

        .card {
            border: none;
            border-radius: 20px;
            background-color: rgba(244, 241, 234, 0.97);
            box-shadow: 0 20px 50px rgba(0,0,0,0.5);
            width: 100%;
            max-width: 400px;
        }

        /* Titolo BookSwap nella card */
        header h2 {
            font-family: 'Cormorant Garamond', serif;
            font-weight: 300;
            font-size: 2.6rem;
            letter-spacing: 2px;
            color: var(--library-dark);
        }

        header p {
            font-family: 'Lato', sans-serif;
            font-size: 0.85rem;
        }

        /* Form inputs */
        .form-control {
            font-family: 'Lato', sans-serif;
            font-size: 0.9rem;
            border-radius: 8px;
            border: 1px solid #ddd;
        }

        .form-control:focus {
            border-color: var(--library-accent);
            box-shadow: 0 0 0 3px rgba(139,94,60,0.15);
        }

        .form-control::placeholder {
            font-family: 'DM Sans', sans-serif;
            font-size: 0.75rem;
            letter-spacing: 1px;
            text-transform: uppercase;
            color: #bbb;
        }

        .form-label {
            font-family: 'DM Sans', sans-serif;
            font-size: 0.65rem;
            letter-spacing: 2px;
            text-transform: uppercase;
            color: #888;
        }

        /* Bottone submit */
        .btn-success {
            background-color: var(--library-accent) !important;
            border-color: var(--library-accent) !important;
            font-family: 'DM Sans', sans-serif !important;
            font-size: 0.72rem !important;
            font-weight: 600 !important;
            letter-spacing: 3px !important;
            text-transform: uppercase !important;
            border-radius: 8px !important;
            padding: 12px !important;
            transition: background 0.2s !important;
        }

        .btn-success:hover {
            background-color: #6e4a2e !important;
            border-color: #6e4a2e !important;
        }

        /* Link registrazione / login */
        .auth-link {
            font-family: 'Lato', sans-serif;
            font-size: 0.85rem;
        }

        /* Frasi filosofiche — NON MODIFICARE */
        .quote-text {
            font-family: 'Playfair Display', serif;
            font-size: 2rem;
            font-style: italic;
            color: white;
            line-height: 1.4;
            margin-bottom: 1.5rem;
        }

        .quote-author {
            font-family: 'Lato', sans-serif;
            color: var(--library-sepia);
            font-weight: 300;
            letter-spacing: 2px;
            text-transform: uppercase;
            font-size: 0.78rem;
        }

        .logout-btn {
            position: absolute;
            top: 20px;
            left: 20px;
            z-index: 100;
        }
    </style>
</head>
<body>

    <?php if(isset($_SESSION['id_utente'])): ?>
        <div class="logout-btn">
            <a href="index.php?page=login&action=logout" class="btn btn-danger btn-sm">Esci</a>
        </div>
    <?php endif; ?>

    <div class="container-fluid p-0">
        <div class="row g-0 main-wrapper">

            <!-- COLONNA LOGIN -->
            <div class="col-lg-8 login-section">
                <div class="card p-5">

                    <header class="text-center mb-4">
                        <h2>BookSwap</h2>
                        <p class="text-muted">Bentornato nel tuo rifugio letterario</p>
                    </header>

                    <main>
                        <?php if(!empty($_SESSION['error'])): ?>
                            <div class="alert alert-danger border-0 small text-center mb-3">
                                <?= $_SESSION['error']; unset($_SESSION['error']); ?>
                            </div>
                        <?php endif; ?>

                        <?php if(!empty($_SESSION['success'])): ?>
                            <div class="alert alert-success border-0 small text-center mb-3">
                                <?= $_SESSION['success']; unset($_SESSION['success']); ?>
                            </div>
                        <?php endif; ?>

                        <?php if(!empty($_SESSION['info'])): ?>
                            <div class="alert alert-info border-0 small text-center mb-3">
                                <?= $_SESSION['info']; unset($_SESSION['info']); ?>
                            </div>
                        <?php endif; ?>

                        <?php if(!empty($view)) include $view; ?>

                        <?php if(isset($_SESSION['id_utente'])): ?>
                            <div class="d-grid mt-3">
                                <a href="index.php?page=login&action=logout" class="btn btn-danger">LOGOUT</a>
                            </div>
                        <?php endif; ?>

                        <div class="border-top pt-3 mt-3 text-center auth-link">
                            <?php if(!isset($_GET['registration_status'])): ?>
                                <?php if(isset($_GET['action']) && $_GET['action'] == 'registration'): ?>
                                    <a href="index.php?page=login&action=login" class="text-decoration-none text-secondary">
                                        Hai già un account? <span style="color:var(--library-accent)" class="fw-bold">Accedi qui</span>
                                    </a>
                                <?php elseif(!isset($_GET['action']) || $_GET['action'] == 'login'): ?>
                                    <a href="index.php?page=login&action=registration" class="text-decoration-none text-secondary">
                                        Non hai un account? <span style="color:var(--library-accent)" class="fw-bold">Registrati ora</span>
                                    </a>
                                <?php endif; ?>
                            <?php endif; ?>
                        </div>
                    </main>

                </div>
            </div>

            <!-- COLONNA QUOTE -->
            <div class="col-lg-4 d-none d-lg-flex quote-section">
                <div class="quote-content">
                    <p class="quote-text">"<?= htmlspecialchars($randomQuote['text']); ?>"</p>
                    <p class="quote-author">— <?= htmlspecialchars($randomQuote['author']); ?></p>
                </div>
            </div>

        </div>
    </div>

</body>
</html>