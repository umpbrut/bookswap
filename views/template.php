<?php defined('APP') or die('Accesso Negato') ?>
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
            <div>
                <a href="index.php?page=annunci&action=index">ANNUNCI</a>
            </div>
        </header>

        <main>
            <nav>
                <?php
                    echo "<a href='index.php?page=$this->page&action=index'>HOME</a> | ";
                    echo "<a href='index.php?page=$this->page&action=create'>CREATE ANNUNCIO</a> | ";
                ?>
            </nav>

            <section>
                <?php include 'table.php'; ?>
            </section>

            <section>
                <?php if(!empty($view)) include $view; ?>
            </section>
        </main>

        <footer>
            <hr>
            FOOTER &copy; 2024
        </footer>
    </div>
</body>
</html>