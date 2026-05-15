<?php defined('APP') or die('Accesso Negato') ?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BookSwap | ISIT Books</title>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,300;0,400;0,600;1,300&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">
    <style>
        :root {
            --bg:     #faf8f5;
            --bg2:    #f3f0ea;
            --bg3:    #ebe6dc;
            --gold:   #9c6b3c;
            --gold2:  #b8844f;
            --ink:    #2a1f14;
            --muted:  #7a6a58;
            --border: rgba(156,107,60,0.18);
            --shadow: 0 2px 20px rgba(60,40,20,0.08);
        }
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        html { scroll-behavior: smooth; }
        body { font-family: 'DM Sans', sans-serif; background: var(--bg); color: var(--ink); }

        /* HEADER */
        header {
            position: fixed; top: 0; left: 0; width: 100%; z-index: 900;
            padding: 18px 40px;
            display: flex; align-items: center; justify-content: space-between;
            background: rgba(250,248,245,0.93);
            backdrop-filter: blur(16px);
            border-bottom: 0.5px solid var(--border);
        }
        .logo {
            font-family: 'Cormorant Garamond', serif;
            font-size: 1.5rem; font-weight: 600;
            letter-spacing: 4px; text-transform: uppercase;
            color: var(--gold); text-decoration: none;
        }
        nav { display: flex; gap: 28px; align-items: center; }
        nav a {
            color: var(--muted); text-decoration: none;
            font-size: 0.7rem; letter-spacing: 2px; text-transform: uppercase;
            transition: color 0.3s; padding: 6px 0;
            border-bottom: 1.5px solid transparent;
        }
        nav a:hover, nav a.active { color: var(--gold); border-bottom-color: var(--gold); }
        .nav-btn {
            padding: 8px 20px !important;
            background: var(--gold) !important; color: white !important;
            border-radius: 3px; border-bottom: none !important;
        }
        .nav-btn:hover { background: var(--gold2) !important; }

        /* HERO */
        .hero {
            height: 100vh; min-height: 600px;
            position: relative; overflow: hidden;
            display: flex; align-items: center; justify-content: center;
            text-align: center; padding: 0 24px;
        }
        .hero-bg {
            position: absolute; inset: 0;
            background:
                linear-gradient(to bottom, rgba(250,248,245,0.25) 0%, rgba(250,248,245,0.65) 65%, var(--bg) 100%),
                url('https://images.unsplash.com/photo-1507842217343-583bb7270b66?auto=format&fit=crop&q=80&w=2400')
                center/cover no-repeat;
            animation: zoom 20s ease-in-out infinite alternate;
        }
        @keyframes zoom { from { transform: scale(1.04); } to { transform: scale(1.10); } }
        .hero-content { position: relative; z-index: 2; max-width: 740px; }
        .hero-label { font-size: 0.65rem; letter-spacing: 4px; text-transform: uppercase; color: var(--gold); margin-bottom: 28px; }
        .hero-title {
            font-family: 'Cormorant Garamond', serif;
            font-size: clamp(2.4rem, 5.5vw, 4.2rem);
            font-weight: 300; font-style: italic; line-height: 1.22; color: var(--ink);
        }
        .hero-title em { color: var(--gold); }
        .hero-attr { margin-top: 16px; font-size: 0.72rem; letter-spacing: 3px; text-transform: uppercase; color: var(--muted); }
        .hero-cta {
            display: inline-block; margin-top: 48px; padding: 14px 44px;
            background: var(--gold); color: white; text-decoration: none;
            font-size: 0.7rem; letter-spacing: 4px; text-transform: uppercase;
            border-radius: 3px; transition: background 0.3s;
        }
        .hero-cta:hover { background: var(--gold2); }

        /* LAYOUT */
        main { padding-top: 80px; }
        section { max-width: 1200px; margin: 0 auto; padding: 60px 40px; }
        .eyebrow { font-size: 0.62rem; letter-spacing: 4px; text-transform: uppercase; color: var(--gold); margin-bottom: 8px; }
        h2.section-title {
            font-family: 'Cormorant Garamond', serif;
            font-size: clamp(1.8rem, 3.5vw, 2.8rem);
            font-weight: 300; margin-bottom: 32px;
        }

        /* FOOTER */
        footer {
            border-top: 1px solid var(--border); padding: 24px 40px;
            display: flex; justify-content: space-between; align-items: center;
            font-size: 0.7rem; color: var(--muted); background: white;
        }

        /* DROPDOWN CONTAINER */
        #menu-toggle {
            display: none;
        }

        /* Titolo della tendina (ora è una Label) */
        .dropdown-trigger {
            cursor: pointer;
            color: var(--muted);
            font-size: 0.7rem;
            letter-spacing: 2px;
            text-transform: uppercase;
            padding: 6px 0;
            transition: color 0.3s;
            user-select: none; /* Impedisce di evidenziare il testo al click */
        }

        /* La tendina: nascosta di default */
        .dropdown-content {
            display: none; 
            position: absolute;
            right: 0;
            top: 100%;
            background-color: white;
            min-width: 180px;
            box-shadow: var(--shadow);
            border: 0.5px solid var(--border);
            border-radius: 4px;
            z-index: 1000;
            padding: 10px 0;
            margin-top: 10px;
        }

        /* LOGICA DI APERTURA: Se la checkbox è selezionata, mostra il fratello .dropdown-content */
        #menu-toggle:checked ~ .dropdown-content {
            display: block;
        }

        /* Cambia colore al testo quando è aperto */
        #menu-toggle:checked ~ .dropdown-trigger {
            color: var(--gold);
        }

        /* Link interni alla tendina */
        .dropdown-content a {
            color: var(--muted);
            padding: 12px 20px;
            text-decoration: none;
            display: block;
            font-size: 0.65rem;
            border-bottom: none !important;
            text-align: left;
        }

        .dropdown-content a:hover {
            background-color: var(--bg2);
            color: var(--gold);
        }

        /* Freccetta */
        .dropdown-trigger::after {
            content: ' ▾';
            font-size: 0.6rem;
            vertical-align: middle;
        }
    </style>
</head>
<body>

<?php
$action = $_GET['action'] ?? 'index';
$page   = $_GET['page']   ?? 'annunci';
?>

<header>
    <a href="index.php?page=annunci" class="logo">BookSwap</a>
    <nav>
        <a href="index.php?page=annunci"
           <?= ($page === 'annunci' && $action === 'index') ? 'class="active"' : '' ?>>
            Annunci
        </a>

        <a href="index.php?page=annunci&action=create" class="nav-btn">+ Pubblica</a>

        <div class="dropdown">
            <input type="checkbox" id="menu-toggle">
            
            <label for="menu-toggle" class="dropdown-trigger">Area Personale</label>

            <div class="dropdown-content">
                <a href="index.php?page=annunci&action=personal">I miei annunci</a>
                <a href="index.php?page=preferiti&action=index">Preferiti</a>
                <a href="index.php?page=ordini&action=index">I miei ordini</a>
                
                <hr style="border: 0; border-top: 1px solid var(--border); margin: 5px 0;">
                
                <?php if(isset($_SESSION['id_utente'])):?>
                    <a href="index.php?page=personal&action=index">Il mio Profilo</a>
                    <a href="index.php?page=login&action=logout" style="color: #8B5E3C;">Logout</a>
                <?php else:?>
                    <a href="index.php?page=login&action=login">Login / Registrati</a>
                <?php endif;?>
            </div>
        </div>
        
        
    </nav>
</header>

<?php if ($page === 'annunci' && $action === 'index'): ?>
<!-- Hero: solo nella pagina principale degli annunci -->
<div class="hero">
    <div class="hero-bg"></div>
    <div class="hero-content">
        <p class="hero-label">BookSwap — ISIT Books</p>
        <h1 class="hero-title">"Un libro è un giardino<br>che puoi custodire <em>in tasca</em>."</h1>
        <p class="hero-attr">— Francis Bacon</p>
        <a href="#annunci" class="hero-cta">Esplora gli Annunci</a>
    </div>
</div>
<?php endif; ?>

<main>

    <?php if ($page === 'annunci' && $action === 'index'): ?>
        <div id="annunci"></div>
        <section>
            <p class="eyebrow">Libri disponibili</p>
            <h2 class="section-title">Annunci recenti</h2>
            <?php include 'table.php'; ?>
        </section>

    <?php elseif ($page === 'annunci' && $action === 'personal'): ?>
        <section>
            <p class="eyebrow">Area personale</p>
            <h2 class="section-title">I miei annunci</h2>
            <?php include 'table_personal.php'; ?>
        </section>

    <?php elseif ($page === 'preferiti' && $action === 'index'): ?>
        <section>
            <p class="eyebrow">La tua libreria</p>
            <h2 class="section-title">I miei preferiti</h2>
            <?php include 'table_preferiti.php'; ?>
        </section>

    <?php elseif ($page === 'ordini' && $action === 'index'): ?>
        <section>
            <p class="eyebrow">Area personale</p>
            <h2 class="section-title">I miei ordini</h2>
            <?php include 'table_ordini.php'; ?>
        </section>

    <?php endif; ?>

    <?php if (!empty($view)) include $view; ?>

</main>

<footer>
    <span>© 2026 BookSwap — ISIT Books</span>
    <span>Progetto scolastico</span>
</footer>

</body>
</html>
