<?php defined('APP') or die('Accesso Negato') ?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        ul{
            display: none; 
            position: absolute; 
            top: 100%; 
            left: 0; 
            right: 0; 
            z-index: 1000; 
            background: white; 
            border: 1px solid #ccc; 
            list-style: none; 
            margin: 0; 
            padding: 0; 
            max-height: 200px; 
            overflow-y: auto;
        }
    </style>
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
</html>