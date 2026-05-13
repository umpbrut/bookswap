<?php defined('APP') or die('Accesso Negato') ?>
<!DOCTYPE html>
<html lang="it">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>BookSwap | I Miei Libri</title>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

<style>
body {
    margin: 0;
    font-family: Georgia, serif;
    background: #f3e7d3;
}

/* ================= NAVBAR ================= */

.navbar-custom {
    background: #faf7f0;
    padding: 12px 5%;
    border-bottom: 1px solid #e5decb;
    display: flex;
    justify-content: space-between;
    align-items: center;
    position: sticky;
    top: 0;
    z-index: 1000;
}

.nav-center {
    position: absolute;
    left: 50%;
    transform: translateX(-50%);
    font-weight: bold;
}

.nav-center a {
    text-decoration: none;
    color: inherit;
}

/* ================= MENU ================= */

.nav-left,
.account-wrapper {
    position: relative;
}

.dropdown-custom,
.account-dropdown {
    position: absolute;
    top: 100%;
    background: #faf7f0;
    border-radius: 12px;
    border: 1px solid #ddd;
    min-width: 180px;
    padding: 8px 0;
    opacity: 0;
    transform: translateY(-10px);
    pointer-events: none;
    transition: all 0.5s ease;
}

.nav-left:hover .dropdown-custom,
.account-wrapper:hover .account-dropdown {
    opacity: 1;
    transform: translateY(0);
    pointer-events: auto;
}

.dropdown-custom a,
.account-dropdown a {
    display: block;
    padding: 10px 15px;
    text-decoration: none;
    color: #333;
    font-size: 13px;
}

.dropdown-custom a:hover,
.account-dropdown a:hover {
    background: #f3e7d3;
}

/* ================= SEARCH ================= */

.search-wrapper {
    display: flex;
    align-items: center;
    flex-direction: row-reverse;
}

.search-bar-input {
    width: 0;
    opacity: 0;
    border: 1px solid #ddd;
    border-radius: 20px;
    padding: 5px 0;
    transition: 0.4s ease;
    outline: none;
}

.search-wrapper:hover .search-bar-input {
    width: 170px;
    opacity: 1;
    padding: 5px 12px;
}

/* ================= ICONS ================= */

.nav-right {
    display: flex;
    align-items: center;
    gap: 18px;
}

.nav-icon-btn {
    position: relative;
    cursor: pointer;
    font-size: 20px;
}

.nav-icon-btn i {
    transition: 0.3s;
}

.nav-icon-btn i:hover {
    color: #de1f26;
}

.fav-badge {
    position: absolute;
    top: -6px;
    right: -10px;
    background: #de1f26;
    color: white;
    font-size: 10px;
    padding: 2px 6px;
    border-radius: 10px;
    display: none;
}

.account-circle {
    width: 36px;
    height: 36px;
    border: 1px solid #000;
    border-radius: 50%;
    display: flex;
    justify-content: center;
    align-items: center;
    cursor: pointer;
}

/* ================= LAYOUT PRINCIPALE ================= */

.page-body {
    display: flex;
    min-height: calc(100vh - 57px);
}

/* ================= SIDEBAR VENDI LIBRO ================= */

.sidebar-vendi {
    width: 280px;
    min-width: 280px;
    background: #d9c2a3;
    padding: 30px 20px;
    display: flex;
    flex-direction: column;
    flex-direction: column;
    gap: 20px;
    border-right: 1px solid #c9ae8a;
}

.sidebar-vendi .hero-box {
    background: white;
    padding: 20px;
    border-radius: 8px;
}

.sidebar-vendi .hero-box p {
    font-style: italic;
    color: #555;
    margin-bottom: 15px;
    font-size: 14px;
    line-height: 1.5;
}

.sidebar-vendi .hero-box button {
    width: 100%;
    background: #de1f26;
    color: white;
    padding: 10px 20px;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    font-family: Georgia, serif;
    font-size: 14px;
    transition: background 0.3s ease;
}

.sidebar-vendi .hero-box button:hover {
    background: #b81820;
}

.sidebar-info {
    background: white;
    padding: 20px;
    border-radius: 8px;
    font-size: 13px;
    color: #666;
    line-height: 1.6;
}

.sidebar-info strong {
    display: block;
    color: #2c2c2c;
    margin-bottom: 8px;
    font-size: 14px;
}

/* ================= CONTENUTO PRINCIPALE ================= */

.main-content {
    flex: 1;
    padding: 50px 5%;
}

.main-content h2 {
    margin-bottom: 30px;
}

/* ================= GRID LIBRI ================= */

.grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
    gap: 30px;
}

.card {
    background: #ffffff;
    border: 1px solid #ddd;
    padding: 15px;
    text-align: center;
    position: relative;
    cursor: default;
    border-radius: 8px;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.card:hover {
    transform: translateY(-5px);
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
}

.card h4 {
    font-size: 14px;
    margin: 5px 0;
    display: -webkit-box;
    -webkit-line-clamp: 3;
    -webkit-box-orient: vertical;
    overflow: hidden;
    text-overflow: ellipsis;
    word-break: break-word;
    overflow-wrap: break-word;
    min-height: 3.5em;
    max-height: 3.5em;
    transition: max-height 0.3s ease-in-out, color 0.2s;
}

.card:hover h4 {
    -webkit-line-clamp: unset;
    max-height: 200px;
    overflow: visible;
    color: #000;
}

.author {
    font-size: 11px;
    color: #888;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.bottom {
    display: flex;
    margin-top: 15px;
    padding-top: 10px;
    border-top: 1px solid #eee;
}

.bottom-section {
    flex: 1;
    display: flex;
    justify-content: center;
    align-items: center;
}

.price {
    font-weight: bold;
    color: #333;
    font-size: 15px;
}

.card-actions {
    display: flex;
    justify-content: center;
    gap: 10px;
    margin-top: 10px;
    padding-top: 10px;
    border-top: 1px solid #eee;
}

.btn-elimina, .btn-modifica {
    font-size: 12px;
    padding: 5px 10px;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    text-decoration: none;
    font-family: Georgia, serif;
    transition: background 0.2s ease;
}

.btn-elimina {
    background: #fde8e8;
    color: #de1f26;
}

.btn-elimina:hover {
    background: #de1f26;
    color: white;
}

.btn-modifica {
    background: #e8f0fe;
    color: #3a6fd8;
}

.btn-modifica:hover {
    background: #3a6fd8;
    color: white;
}

/* ================= STATO ANNUNCIO ================= */

.stato-badge {
    display: inline-block;
    font-size: 10px;
    padding: 2px 8px;
    border-radius: 10px;
    margin-bottom: 6px;
    font-family: sans-serif;
}

.stato-disponibile {
    background: #d4edda;
    color: #155724;
}

.stato-non-disponibile {
    background: #f8d7da;
    color: #721c24;
}
</style>
</head>

<body>

<!-- ================= NAVBAR ================= -->
<nav class="navbar-custom">

    <div class="nav-left">
        <div class="menu-icon"><i class="fas fa-bars"></i></div>

        <div class="dropdown-custom">
            <a href="index.php?page=annunci">Esplora Libri</a>
            <?php if(isset($_SESSION['id_utente'])): ?>
            <a href="index.php?page=annunci&action=personal">I Miei Libri</a>
            <a href="index.php?page=annunci&action=create">Vendi Libro</a>
            <?php endif; ?>
        </div>
    </div>

    <div class="nav-center">
        <a href="index.php?page=annunci">BookSwap</a>
    </div>

    <div class="nav-right">

        <div class="search-wrapper">
            <i class="fas fa-search"></i>
            <input type="text" class="search-bar-input" placeholder="Cerca...">
        </div>

        <div class="nav-icon-btn">
            <i class="far fa-heart"></i>
            <span class="fav-badge" id="favBadge">0</span>
        </div>

        <div class="account-wrapper">
            <div class="account-circle">
                <i class="far fa-user"></i>
            </div>

            <div class="account-dropdown">
                <?php if(isset($_SESSION['id_utente'])): ?>
                <a href="index.php?page=personal&action=index">Il mio account</a>
                <a href="index.php?page=login&action=logout">Logout</a>
                <?php else: ?>
                <a href="index.php?page=login&action=login">Accedi</a>
                <a href="index.php?page=login&action=registration">Registrati</a>
                <?php endif; ?>
            </div>
        </div>

    </div>
</nav>

<!-- ================= BODY: SIDEBAR + CONTENUTO ================= -->
<div class="page-body">

    <!-- SIDEBAR SINISTRA: sempre visibile -->
    <aside class="sidebar-vendi">
        <div class="hero-box">
            <p>"Ogni libro che vendi porta la sua storia a chi saprà amarla."</p>
            <button onclick="location.href='index.php?page=annunci&action=create'"style="background: white; color: #333; padding: 10px 20px; border: 1px solid #ccc; border-radius: 4px; cursor: pointer; font-family: Georgia, serif;">
                + Vendi un libro
            </button>
        </div>

        <div class="sidebar-info">
            <strong>📦 Come funziona</strong>
            Pubblica il tuo annuncio, scegli prezzo e come spedire il libro. Gli acquirenti ti contatteranno direttamente.
        </div>
    </aside>

    <!-- CONTENUTO PRINCIPALE -->
    <main class="main-content">

        <h2>I MIEI LIBRI</h2>

        <?php if (!empty($table)): ?>

            <div class="grid">
                <?php foreach ($table as $annuncio): ?>

                <div class="card">
                    <img src="views/IMG/AUTORI.jpg" style="width:100%; height:160px; object-fit:cover; border-radius:4px;">

                    <?php
                        $stato = $annuncio['stato'] ?? 'Disponibile';
                        $statoClass = ($stato === 'Disponibile') ? 'stato-disponibile' : 'stato-non-disponibile';
                    ?>
                    <span class="stato-badge <?= $statoClass ?>">
                        <?= htmlspecialchars($stato) ?>
                    </span>

                    <h4><?= htmlspecialchars($annuncio['titolo'] ?? 'Titolo non disponibile') ?></h4>

                    <div class="author">
                        <?= htmlspecialchars($annuncio['autore'] ?? 'autore sconosciuto') ?>
                    </div>

                    <div class="bottom">
                        <div class="bottom-section">
                            <div class="price">
                                <?= number_format($annuncio['prezzo'], 2, ',', '.') ?> €
                            </div>
                        </div>
                        <div class="bottom-section" style="font-size:12px; color:#888;">
                            <?= htmlspecialchars($annuncio['luogo'] ?? '') ?>
                        </div>
                    </div>

                    <div class="card-actions">
                        <a href="index.php?page=annunci&action=update&id_annuncio=<?= $annuncio['id_annuncio'] ?>&id_libro=<?= $annuncio['id_libro'] ?>"
                           class="btn-modifica">✏️ Modifica</a>
                        <a href="index.php?page=annunci&action=destroy&id_annuncio=<?= $annuncio['id_annuncio'] ?>"
                           class="btn-elimina"
                           onclick="return confirm('Sei sicuro di voler eliminare questo annuncio?')">🗑️ Elimina</a>
                    </div>
                </div>

                <?php endforeach; ?>
            </div>

        <?php endif; ?>

    </main>

</div>

</body>
</html>