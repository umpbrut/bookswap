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

        .dropdown-custom {
            position: absolute;
            top: 100%; /* Appare esattamente sotto l'input */
            left: 0;
            right: 0;
            z-index: 1000;
            background-color: white;
            border: 1px solid #ddd;
            border-top: none;
            max-height: 200px;
            overflow-y: auto;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            display: none; /* Nascosto di default */
            border-radius: 0 0 8px 8px;
        }

        /* Singolo suggerimento */
        .dropdown-item {
            padding: 10px 15px;
            cursor: pointer;
            transition: background 0.2s;
            border-bottom: 1px solid #f0f0f0;
        }

        .dropdown-item:last-child {
            border-bottom: none;
        }

        /* Effetto al passaggio del mouse */
        .dropdown-item:hover {
            background-color: #f8f9fa;
            color: #007bff;
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
    <script>
        function get_libri_api() {
            const input = document.getElementById('ricerca-libri');
            const container = document.getElementById('risultati-ricerca');
            const hiddenInput = document.getElementById('id_libro_hidden');
            
            if (!input || !container) return;

            let query = input.value.trim();

            // Se l'utente cancella, puliamo l'ID e nascondiamo tutto
            if (query.length < 2) {
                container.style.display = "none";
                container.innerHTML = "";
                if(hiddenInput) hiddenInput.value = ""; 
                return;
            }

            // Nota l'aggiunta di get_libri=1 per matchare il tuo IF nel PHP
            fetch("api/get_libri.php?get_libri=1&testo=" + encodeURIComponent(query))
                .then(res => res.json())
                .then(data => {
                    container.innerHTML = ""; 
                    
                    if (data && data.length > 0) {
                        container.style.display = "block"; 
                        
                        data.forEach(libro => {
                            let item = document.createElement('div');
                            item.classList.add('dropdown-item');
                            // Usiamo i nomi colonne del tuo database (titolo)
                            item.innerHTML = `<strong>${libro.titolo}</strong>`;
                            
                            item.onclick = function() {
                                input.value = libro.titolo; // Scrive il titolo nell'input
                                hiddenInput.value = libro.id_libro; // Salva l'ID nell'hidden
                                container.style.display = "none"; // Chiude la tendina
                                console.log("Selezionato ID:", hiddenInput.value); // Debug
                            };
                            
                            container.appendChild(item);
                        });
                    } else {
                        container.style.display = "none";
                    }
                })
                .catch(err => {
                    console.error("Errore Fetch:", err);
                    container.style.display = "none";
                });
        }

    // Chiudi il menu se l'utente clicca fuori dall'area di ricerca
    document.addEventListener('click', function(e) {
        if (!document.getElementById('ricerca-libri').contains(e.target)) {
            document.getElementById('risultati-ricerca').style.display = "none";
        }
    });
    </script>
</body>
</html>