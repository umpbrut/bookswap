<!-- <form action="index.php?page=annunci&action=edit" method="post">
    
    <input type="hidden" name="id_annuncio" value="<?php echo $annuncio['id_annuncio']; ?>">

    <input type="hidden" name="id_libro" id="id_libro_hidden">

    <div class="search-container" style="position: relative; width: 300px;">
    
        <input type="text" id="search" list="lista_libri" oninput="get_libri()" placeholder="Inizia a scrivere..." autocomplete="off" 
            style="width: 100%; padding: 8px; box-sizing: border-box;">

        <datalist id="lista_libri"></datalist>

        <script>
            function get_libri() {
            let cerca = document.getElementById('search').value;
            let lista = document.getElementById('lista_libri');
            let hiddenInput = document.getElementById('id_libro_hidden'); // <--- NUOVO
            
            if (cerca == "") {
                hiddenInput.value = "";
                return;
            }

            fetch("libri.php?get_libri&testo=" + cerca)
            .then(res => res.json())
            .then(data => {
                lista.innerHTML = ""; // Svuota la lista precedente
                
                data.forEach(riga => {
                    // Creiamo l'elemento option programmando l'ID al suo interno
                    let option = document.createElement('option');
                    option.value = riga.titolo; 
                    option.setAttribute('data-id', riga.id_libro); // <--- NUOVO: salva l'ID qui
                    lista.appendChild(option);
                });

                // CONTROLLO SELEZIONE:
                // Ciclo le opzioni appena create: se il testo nell'input è uguale a una 
                // delle opzioni, allora l'utente ha "selezionato" quel libro.
                let opzioneTrovata = Array.from(lista.options).find(opt => opt.value === cerca);
                
                    if (opzioneTrovata) {
                        // Se lo trova, imposta l'ID nel campo hidden che verrà inviato al PHP
                        hiddenInput.value = opzioneTrovata.getAttribute('data-id');
                    } else {
                        // Se l'utente sta ancora scrivendo o cancella, svuota l'ID
                        hiddenInput.value = "";
                    }
                });
            }
        </script>
    </div>

    <br><br>

    <label for="prezzo">Prezzo (€):</label>
    <input type="number" step="0.01" name="prezzo" id="prezzo" required>

    <br><br>

    <label for="ora">Ora:</label>
    <input type="time" name="ora" id="ora" value="<?php echo date('H:i'); ?>" required>

    <br><br>

    <label for="luogo">Luogo:</label>
    <input type="text" name="luogo" id="luogo" placeholder="Es. Biblioteca" required>

    <br><br>

    <label for="condizioni">Condizioni del libro:</label>
    <select name="condizioni" id="condizioni">
        <option value="Nuovo">Nuovo</option>
        <option value="Ottime">Ottime</option>
        <option value="Buone">Buone</option>
        <option value="Usato">Usato/Rovinato</option>
    </select>

    <br><br>

    <label>Stato Annuncio:</label>
    <select name="stato">
        <option value="Disponibile">Disponibile</option>
        <option value="Non disponibile">Non Disponibile</option>
    </select>

    <br><br>
    <input type="hidden" name="id_annuncio" value="<?php echo $_GET['id_annuncio']?>">

    <button type="submit">Salva Modifiche</button>
</form> -->

<?php 
// Assicurati che $table[0] contenga i dati dell'annuncio da modificare
$annuncio = $table[0]; 
?>

<form action="index.php?page=annunci&action=edit" method="post">
    
    <input type="hidden" name="id_annuncio" value="<?php echo $annuncio['id_annuncio']; ?>">

    <input type="hidden" name="id_libro" id="id_libro_hidden" value="<?php echo $annuncio['id_libro']; ?>">

    <div class="search-container" style="position: relative; width: 300px;">
        <label>Libro:</label>
        <input type="text" id="search" list="lista_libri" oninput="get_libri()" 
               placeholder="Cerca un libro..." autocomplete="off" 
               style="width: 100%; padding: 8px; box-sizing: border-box;">
        
        <datalist id="lista_libri"></datalist>

        <script>
            function get_libri() {
                let cerca = document.getElementById('search').value;
                let lista = document.getElementById('lista_libri');
                let hiddenInput = document.getElementById('id_libro_hidden');
                
                if (cerca == "") {
                    hiddenInput.value = "";
                    return;
                }

                fetch("libri.php?get_libri&testo=" + cerca)
                .then(res => res.json())
                .then(data => {
                    lista.innerHTML = "";
                    data.forEach(riga => {
                        let option = document.createElement('option');
                        option.value = riga.titolo; 
                        // Verifica se il tuo libri.php restituisce 'id_libro' o 'id'
                        option.setAttribute('data-id', riga.id_libro); 
                        lista.appendChild(option);
                    });

                    let opzioneTrovata = Array.from(lista.options).find(opt => opt.value === cerca);
                    if (opzioneTrovata) {
                        hiddenInput.value = opzioneTrovata.getAttribute('data-id');
                    }
                    // NOTA: non resettare a "" qui nell'update se l'utente sta solo scrivendo, 
                    // o rischi di svuotare l'ID precedente mentre digita.
                });
            }
        </script>
    </div>

    <br>

    <label for="prezzo">Prezzo (€):</label>
    <input type="number" step="0.01" name="prezzo" id="prezzo" value="<?php echo $annuncio['prezzo']; ?>" required>

    <br><br>

    <label for="ora">Ora:</label>
    <input type="time" name="ora" id="ora" value="<?php echo $annuncio['ora']; ?>" required>

    <br><br>

    <label for="luogo">Luogo:</label>
    <input type="text" name="luogo" id="luogo" value="<?php echo $annuncio['luogo']; ?>" required>

    <br><br>

    <label for="condizioni">Condizioni del libro:</label>
    <select name="condizioni" id="condizioni">
        <option value="Nuovo" <?php echo ($annuncio['condizioni'] == 'Nuovo') ? 'selected' : ''; ?>>Nuovo</option>
        <option value="Ottime" <?php echo ($annuncio['condizioni'] == 'Ottime') ? 'selected' : ''; ?>>Ottime</option>
        <option value="Buone" <?php echo ($annuncio['condizioni'] == 'Buone') ? 'selected' : ''; ?>>Buone</option>
        <option value="Usato" <?php echo ($annuncio['condizioni'] == 'Usato') ? 'selected' : ''; ?>>Usato/Rovinato</option>
    </select>

    <br><br>

    <label>Stato Annuncio:</label>
    <select name="stato">
        <option value="Disponibile" <?php echo ($annuncio['stato'] == 'Disponibile') ? 'selected' : ''; ?>>Disponibile</option>
        <option value="Non disponibile" <?php echo ($annuncio['stato'] == 'Non disponibile') ? 'selected' : ''; ?>>Non Disponibile</option>
    </select>

    <br><br>
    
    <button type="submit">Salva Modifiche</button>
</form>