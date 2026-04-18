<form action="index.php?page=annunci&action=store" method="post">

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
    //carica solamente i libri quando si inizia a scrivere perchè senno li caricherebbe ogni volta
</script>
  
</div>

<br>

<label for="prezzo">Prezzo (€):</label>
    <input type="number" step="0.01" name="prezzo" id="prezzo" required>

    <br><br>

    <label for="data">Data:</label>
    <input type="date" name="data" id="data" value="<?php echo date('Y-m-d'); ?>" required>

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

    <button type="submit">Pubblica Annuncio</button>

</form>