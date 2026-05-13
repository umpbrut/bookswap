<?php
defined('APP') or die('Accesso Negato');
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BookSwap | Il Mio Account</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,300;0,400;0,600;1,300;1,400&family=DM+Sans:wght@300;400;500;600&family=Lato:ital,wght@0,300;0,400;0,700;1,300;1,400&display=swap" rel="stylesheet">
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
            min-height: 100vh;
            margin: 0;
            overflow-x: hidden;
            animation: scrollBackground 80s linear infinite;
        }

        .card {
            border: none;
            border-radius: 20px;
            background-color: rgba(244, 241, 234, 0.97);
            box-shadow: 0 20px 50px rgba(0,0,0,0.5);
            width: 100%;
            max-width: 480px;
        }

        .card-title {
            font-family: 'Cormorant Garamond', serif;
            font-weight: 300;
            font-size: 2.2rem;
            color: var(--library-dark);
            letter-spacing: 1px;
        }

        .card-subtitle {
            font-family: 'Lato', sans-serif;
            font-size: 0.85rem;
        }

        /* Tab switcher */
        .tab-switcher {
            display: flex;
            border-bottom: 2px solid #ddd;
            margin-bottom: 20px;
        }

        .tab-btn {
            flex: 1;
            background: none;
            border: none;
            padding: 10px;
            font-family: 'DM Sans', sans-serif;
            font-size: 0.68rem;
            letter-spacing: 2px;
            text-transform: uppercase;
            cursor: pointer;
            color: #999;
            border-bottom: 2px solid transparent;
            margin-bottom: -2px;
            transition: all 0.2s;
        }

        .tab-btn.active {
            color: var(--library-accent);
            border-bottom-color: var(--library-accent);
            font-weight: 600;
        }

        .tab-content { display: none; }
        .tab-content.active { display: block; }

        /* Campi visualizzazione */
        .field-label {
            font-family: 'DM Sans', sans-serif;
            font-size: 0.62rem;
            text-transform: uppercase;
            letter-spacing: 2px;
            color: #999;
            margin-bottom: 4px;
            margin-top: 14px;
        }

        .data-field {
            background: white;
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 10px 14px;
            font-family: 'Lato', sans-serif;
            font-size: 0.9rem;
            color: #333;
            width: 100%;
        }

        /* Form modifica */
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
            font-size: 0.78rem;
            letter-spacing: 1px;
            text-transform: uppercase;
            color: #bbb;
        }

        .btn-accent {
            background-color: var(--library-accent) !important;
            border-color: var(--library-accent) !important;
            color: white !important;
            font-family: 'DM Sans', sans-serif;
            font-size: 0.72rem;
            font-weight: 600;
            letter-spacing: 3px;
            text-transform: uppercase;
            border-radius: 8px;
            padding: 12px;
            transition: background 0.2s !important;
        }

        .btn-accent:hover {
            background-color: #6e4a2e !important;
            border-color: #6e4a2e !important;
        }

        .back-btn {
            position: absolute;
            top: 20px;
            left: 20px;
            z-index: 100;
        }

        .logout-link {
            font-family: 'Lato', sans-serif;
            font-size: 0.85rem;
        }
    </style>
</head>
<body>

    <div class="back-btn">
        <a href="index.php?page=annunci&action=index" class="btn btn-sm btn-light">← Torna ai libri</a>
    </div>

    <div class="min-vh-100 d-flex align-items-center justify-content-center py-5">
        <div class="card p-4 p-md-5">

            <div class="text-center mb-4">
                <h2 class="card-title">Il mio account</h2>
                <p class="card-subtitle text-muted">
                    Benvenuto, <?= htmlspecialchars($_SESSION['nome'] ?? 'Utente') ?>!
                </p>
            </div>

            <!-- Messaggi sessione -->
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

            <!-- Tab switcher -->
            <div class="tab-switcher">
                <button class="tab-btn active" onclick="switchTab('visualizza', this)">I miei dati</button>
                <button class="tab-btn"        onclick="switchTab('modifica', this)">Modifica dati</button>
            </div>

            <!-- TAB: VISUALIZZA -->
            <div class="tab-content active" id="tab-visualizza">
                <?php if(isset($utente)): ?>
                    <div class="field-label">Nome</div>
                    <div class="data-field"><?= htmlspecialchars($utente['nome']) ?></div>

                    <div class="field-label">Cognome</div>
                    <div class="data-field"><?= htmlspecialchars($utente['cognome']) ?></div>

                    <div class="field-label">Email</div>
                    <div class="data-field"><?= htmlspecialchars($utente['email']) ?></div>

                    <div class="field-label">Numero di telefono</div>
                    <div class="data-field"><?= htmlspecialchars($utente['num_tel']) ?></div>
                <?php endif; ?>
            </div>

            <!-- TAB: MODIFICA -->
            <div class="tab-content" id="tab-modifica">
                <form action="index.php?page=personal&action=update" method="post" class="row g-3">

                    <div class="col-6">
                        <input type="text" name="nome" class="form-control"
                               placeholder="Nome"
                               value="<?= htmlspecialchars($utente['nome'] ?? '') ?>" required>
                    </div>
                    <div class="col-6">
                        <input type="text" name="cognome" class="form-control"
                               placeholder="Cognome"
                               value="<?= htmlspecialchars($utente['cognome'] ?? '') ?>" required>
                    </div>
                    <div class="col-12">
                        <input type="text" name="num_tel" class="form-control"
                               placeholder="Numero di telefono"
                               value="<?= htmlspecialchars($utente['num_tel'] ?? '') ?>" required>
                    </div>
                    <div class="col-12">
                        <input type="email" name="email" class="form-control"
                               placeholder="Email"
                               value="<?= htmlspecialchars($utente['email'] ?? '') ?>" required>
                    </div>

                    <div class="col-12">
                        <hr class="my-1">
                        <p class="text-muted mb-2" style="font-size:0.78rem;">Lascia vuoto per non cambiare la password</p>
                        <input type="password" name="password" class="form-control" placeholder="Nuova password">
                    </div>

                    <div class="col-12 d-grid mt-2">
                        <input type="submit" value="Salva modifiche" class="btn btn-accent">
                    </div>

                </form>
            </div>

            <!-- Logout -->
            <div class="border-top pt-3 mt-3 text-center logout-link">
                <a href="index.php?page=login&action=logout" class="text-decoration-none text-secondary">
                    Vuoi uscire? <span style="color:var(--library-accent)" class="fw-bold">Logout</span>
                </a>
            </div>

        </div>
    </div>

    <script>
        function switchTab(tab, btn) {
            document.querySelectorAll('.tab-content').forEach(t => t.classList.remove('active'));
            document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
            document.getElementById('tab-' + tab).classList.add('active');
            btn.classList.add('active');
        }
    </script>

</body>
</html>