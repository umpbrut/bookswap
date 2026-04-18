<!-- <?php defined('APP') or die('Accesso Negato') ?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ISIT BOOKS</title>
</head>
<body>
    <div>
        <header>
            <h1>Gestione Applicativo</h1>
        </header>

        <main>
            <nav>
                <?php
                    echo "<a href='index.php?page=$this->page&action=index'>ANNUNCI</a> | ";
                    echo "<a href='index.php?page=$this->page&action=create'>CREATE ANNUNCIO</a> | ";
                    echo "<a href='index.php?page=$this->page&action=personal'>MIEI ANNUNCI</a> | ";
                ?>
            </nav>

            <section>
                <?php //include 'table.php';
                $action = $_GET['action'] ?? 'index';
                    if ($action == 'personal'){
                        include 'table_personal.php';
                    } else {
                        include 'table.php';
                    }       
                ?>
            </section>

            <section>
                <?php if(!empty($view)) include $view; ?>
            </section>
        </main>

        <footer>
            <hr>
            FOOTER &copy; 2026
        </footer>
    </div>

</body>
</html> -->

<?php defined('APP') or die('Accesso Negato') ?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BookSwap | Esplora</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=Inter:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        :root {
            --library-sepia: #f4f1ea;
            --library-dark: #2c2c2c;
            --library-accent: #8b5e3c;
            --library-soft: #e8e4d9;
        }

        body { 
            font-family: 'Inter', sans-serif; 
            background-color: var(--library-sepia); 
            color: var(--library-dark);
            margin: 0;
        }

        /* Navbar elegante */
        .navbar-custom {
            background-color: var(--library-dark);
            padding: 1rem 2rem;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }
        .navbar-brand { font-family: 'Playfair Display', serif; color: white !important; font-size: 1.8rem; }
        .nav-link { color: rgba(255,255,255,0.8) !important; font-weight: 500; transition: 0.3s; }
        .nav-link:hover { color: var(--library-accent) !important; }

        /* Sidebar Filtri */
        .sidebar-filters {
            background-color: var(--library-soft);
            border-radius: 15px;
            padding: 2rem;
            position: sticky;
            top: 20px;
            height: fit-content;
            border: 1px solid rgba(0,0,0,0.05);
        }

        .filter-title {
            font-family: 'Playfair Display', serif;
            font-size: 1.4rem;
            margin-bottom: 1.5rem;
            border-bottom: 2px solid var(--library-accent);
            display: inline-block;
        }

        /* Footer */
        footer {
            background-color: var(--library-dark);
            color: white;
            padding: 2rem 0;
            margin-top: 4rem;
            text-align: center;
        }
    </style>
</head>
<body>

    <nav class="navbar navbar-expand-lg navbar-custom mb-5">
        <div class="container-fluid">
            <a class="navbar-brand" href="index.php">BookSwap</a>
            <div class="navbar-nav ms-auto">
                <a class="nav-link" href="index.php?page=<?= $this->page ?>&action=index">Catalogo</a>
                <a class="nav-link" href="index.php?page=<?= $this->page ?>&action=create">Pubblica</a>
                <a class="nav-link" href="index.php?page=<?= $this->page ?>&action=personal">I miei Annunci</a>
                <?php if(isset($_SESSION['id_utente'])): ?>
                    <a class="nav-link text-danger" href="index.php?page=login&action=logout">Logout</a>
                <?php else: ?>
                    <a class="nav-link" href="index.php?page=login">Accedi</a>
                <?php endif; ?>
            </div>
        </div>
    </nav>

    <div class="container">
        <div class="row g-4">
            <aside class="col-lg-3">
                <div class="sidebar-filters shadow-sm">
                    <h3 class="filter-title">Filtra Ricerca</h3>
                    <form action="index.php" method="GET">
                        <input type="hidden" name="page" value="annunci">
                        <div class="mb-3">
                            <label class="form-label small fw-bold">Cerca Titolo</label>
                            <input type="text" name="search" class="form-control form-control-sm" placeholder="Es: Il nome della rosa">
                        </div>
                        <div class="mb-3">
                            <label class="form-label small fw-bold">Prezzo Max</label>
                            <input type="range" class="form-range" min="0" max="100" step="5">
                        </div>
                        <div class="mb-3">
                            <label class="form-label small fw-bold">Condizioni</label>
                            <select class="form-select form-select-sm">
                                <option value="">Tutte</option>
                                <option value="nuovo">Nuovo</option>
                                <option value="ottimo">Ottimo</option>
                                <option value="usato">Usato</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-sm w-100 text-white" style="background-color: var(--library-accent)">Applica</button>
                    </form>
                </div>
            </aside>

            <main class="col-lg-9">
                <?php if(!empty($view)): ?>
                    <div class="card shadow-sm p-4 mb-4 border-0" style="background-color: white; border-radius: 15px;">
                        <?php include $view; ?>
                    </div>
                <?php endif; ?>

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
            </main>
        </div>
    </div>

    <footer>
        <div class="container">
            <p class="mb-0">&copy; 2026 BookSwap - Dove i libri trovano nuova vita.</p>
        </div>
    </footer>

</body>
</html>