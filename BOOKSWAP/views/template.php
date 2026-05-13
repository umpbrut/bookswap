<?php defined('APP') or die('Accesso Negato') ?>
<!DOCTYPE html>
<html lang="it">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Biblioteca</title>

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

/* ================= HERO ================= */

.hero {
    display: flex;
    padding: 30px 5%;
    background: #d9c2a3;
    gap: 20px;
}

.hero-box {
    width: 30%;
    background: white;
    padding: 15px;
}

/* ================= CATALOGO ================= */

.catalogo {
    padding: 50px 8%; /* Più spazio sopra e ai lati del catalogo */
}

.grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
    gap: 30px; /* Aumentato lo spazio tra le card */
}

.card {
    background: #ffffff;
    border: 1px solid #ddd;
    padding: 10px; /* Più spazio interno alla card */
    text-align: center;
    position: relative;
    cursor: pointer;
    border-radius: 8px; /* Arrotonda leggermente per un look più moderno */
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
    color: #888; /* Grigio più chiaro rispetto al nero del titolo */
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.bottom {
    display: flex;
    margin-top: 15px;
    padding-top: 10px;
    border-top: 1px solid #eee; /* Una riga sottile per separare il prezzo */
}

.bottom-section {
    flex: 1;
    display: flex;
    justify-content: center;
    align-items: center;
}

.price {
    font-weight: bold;
    color: #333; /* Prezzo scuro, meno aggressivo del rosso */
    font-size: 15px;
}

/* Il cuore rimane rosso solo al passaggio o se attivo */
.like {
    cursor: pointer;
    font-size: 18px;
    color: #ccc; /* Cuore spento di base */
    transition: 0.3s;
}

.like:hover {
    color: #de1f26; /* Diventa rosso solo quando ci passi sopra */
    transform: scale(1.2);
}
</style>
</head>

<body>

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

        <div class="search-wrapper" style="position:relative;">
            <i class="fas fa-search"></i>
            <input type="text" class="search-bar-input" placeholder="Cerca..."
                onkeyup="showResult(this.value)">
            <div id="livesearch" style="position: absolute; top: 110%; right: 0; background: #faf7f0; border-radius: 10px; min-width: 280px; z-index: 9999; overflow: hidden;"></div>
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

<section class="hero">

    <div class="hero-box">
    <p>"Un libro è una porta su un altro mondo."</p>
    
    <?php if(isset($_SESSION['id_utente'])): ?>
        <button onclick="location.href='index.php?page=annunci&action=create'"style="background: white; color: #333; padding: 10px 20px; border: 1px solid #ccc; border-radius: 4px; cursor: pointer; font-family: Georgia, serif;">
            Vendi subito
        </button>
    <?php else: ?>
        <button onclick="location.href='index.php?page=login&action=login'"style="background: white; color: #333; padding: 10px 20px; border: 1px solid #ccc; border-radius: 4px; cursor: pointer; font-family: Georgia, serif;">
            Accedi per vendere
        </button>
    <?php endif; ?>
</div>

</section>

<section class="catalogo">

    <h2>LIBRI DISPONIBILI</h2>

    <div class="grid">

        <?php if (!empty($table)): ?>
            <?php foreach ($table as $annuncio): ?>

            <div class="card" onclick="location.href='about:blank'">
                <img src="views/IMG/libro_1.jpeg" style="width:100%; height:180px; object-fit:cover; border-radius:4px;">
                <h4><?= htmlspecialchars($annuncio['titolo']) ?></h4>

                <div class="author">
                    - <?= htmlspecialchars($annuncio['autore'] ?? 'autore sconosciuto') ?> -
                </div>

                <div class="bottom">
                    <div class="bottom-section">
                        <div class="price">
                            <?= number_format($annuncio['prezzo'], 2, ',', '.') ?> €
                        </div>
                    </div>

                    <div class="bottom-section">
                        <div class="like" onclick="event.stopPropagation(); addFav(this)">♡</div>
                    </div>
                </div>
            </div>

            <?php endforeach; ?>
        <?php endif; ?>

    </div>
</section>

<script>
let favorites = [];

function addFav(el) {
    const bookTitle = el.closest('.card').querySelector('h4').textContent;
    const index = favorites.indexOf(bookTitle);
    
    if (index > -1) {
        // Libro è nei preferiti, lo togliamo
        favorites.splice(index, 1);
        el.innerHTML = "♡";
    } else {
        // Libro non è nei preferiti, lo aggiungiamo
        favorites.push(bookTitle);
        el.innerHTML = "♥";
    }
    
    // Aggiorna il badge
    const badge = document.getElementById('favBadge');
    if (favorites.length > 0) {
        badge.style.display = 'inline-block';
        badge.textContent = favorites.length;
    } else {
        badge.style.display = 'none';
    }
}

function showResult(str) {
    if (str.length == 0) {
        document.getElementById("livesearch").innerHTML = "";
        document.getElementById("livesearch").style.border = "0px";
        return;
    }
    var xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            document.getElementById("livesearch").innerHTML = this.responseText;
            document.getElementById("livesearch").style.border = "1px solid #ddd";
        }
    }
    xmlhttp.open("GET", "livesearch.php?q=" + str, true);
    xmlhttp.send();
}
</script>
</body>
</html>