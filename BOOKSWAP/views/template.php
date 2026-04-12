<?php defined('APP') or die('Accesso Negato') ?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        #ricerca-libri {
            width: 100%;
            min-width: 400px;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }

        #lista-titoli option {
            padding: 10px;
        }
    </style>
    <title>ISIT BOOKS</title>
</head>
<body>
    <div>
        <header>
            <h1>Gestione Applicativo</h1>
            <div style="display: flex; justify-content: space-between; align-items: center;">
                <a href="index.php?page=annunci&action=index">ANNUNCI</a>
                <?php if(isset($_SESSION['id_utente'])): ?>
                    <a href="index.php?page=login&action=logout">LOGOUT</a>
                <?php endif; ?>
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
            <p>ISIT BOOKS - Gestione Applicativo © 2026</p>
        </footer>
    </div>
    <script> 
        const inputRicerca = document.getElementById('ricerca-libri');
        const hiddenInput = document.getElementById('id_libro_hidden');
        const listaTitoli = document.getElementById('lista-titoli');

        if (inputRicerca && hiddenInput) {
            const aggiornaIdLibro = function() {
                const inputValue = inputRicerca.value;
                const options = listaTitoli.querySelectorAll('option');
                
                hiddenInput.value = ""; 

                for (let i = 0; i < options.length; i++) {
                    if (options[i].value.toLowerCase() === inputValue.toLowerCase()) {
                        hiddenInput.value = options[i].getAttribute('data-id');
                        break; 
                    }
                }
            };

            inputRicerca.addEventListener('input', aggiornaIdLibro);
            inputRicerca.addEventListener('change', aggiornaIdLibro);
        }
    </script>
</body>
</html>