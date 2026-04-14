<form action="index.php?page=annunci&action=edit" method="post">
    
    <input type="hidden" name="id_annuncio" value="<?php echo $annuncio['id_annuncio']; ?>">

    <label for="ricerca-libri">Cerca un libro:</label>
    <input type="text" id="ricerca-libri" list="lista-titoli" placeholder="Inizia a scrivere il titolo..." autocomplete="off">

    <input type="hidden" name="id_libro" id="id_libro_hidden">

    <datalist id="lista-titoli">
        <?php
            foreach($libri as $libro){
                $id=$libro['id_libro'];
                $titolo=$libro['titolo'];
                echo "<option data-id='$id' value='$titolo'>";
            }
        ?>
    </datalist>

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
</form>