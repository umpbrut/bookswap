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
            /* max-width: 600px; */
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
    <script> //script per far si che nel $post['id-libro'] ci sia effettivamente l'id e non il titolo
        const inputRicerca = document.getElementById('ricerca-libri');
        const hiddenInput = document.getElementById('id_libro_hidden');
        const listaTitoli = document.getElementById('lista-titoli');

        if (inputRicerca && hiddenInput) {
            // Funzione unica per aggiornare l'ID
            const aggiornaIdLibro = function() {
                const inputValue = inputRicerca.value;
                const options = listaTitoli.querySelectorAll('option');
                
                // Reset iniziale: se non trova corrispondenza, l'ID resta vuoto
                hiddenInput.value = ""; 

                for (let i = 0; i < options.length; i++) {
                    // Confronto tra il testo scritto e il value dell'opzione (case-insensitive)
                    if (options[i].value.toLowerCase() === inputValue.toLowerCase()) {
                        hiddenInput.value = options[i].getAttribute('data-id');
                        
                        // DEBUG: Decommenta la riga sotto per vedere l'ID nella console del browser (F12)
                        // console.log("ID Libro trovato e impostato: " + hiddenInput.value);
                        
                        break; 
                    }
                }
            };

            // 'input' serve mentre l'utente scrive
            inputRicerca.addEventListener('input', aggiornaIdLibro);
            
            // 'change' serve quando l'utente clicca su un suggerimento o preme invio
            inputRicerca.addEventListener('change', aggiornaIdLibro);
        }
    </script>
</body>
</html>