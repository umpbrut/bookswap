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

    <label>Prezzo (€):</label>
    <input type="number" step="0.01" name="prezzo" value="<?php echo $annuncio['prezzo']; ?>" required>

    <br><br>

    <label>Ora:</label>
    <input type="time" name="ora" value="<?php echo $annuncio['ora']; ?>" required>

    <br><br>

    <label>Luogo:</label>
    <input type="text" name="luogo" value="<?php echo $annuncio['luogo']; ?>" required>

    <br><br>

    <label>Condizioni:</label>
    <select name="condizioni">
        <option value="Nuovo" <?php if($annuncio['condizioni'] == "Nuovo") echo "selected"; ?>>Nuovo</option>
        <option value="Ottime" <?php if($annuncio['condizioni'] == "Ottime") echo "selected"; ?>>Ottime</option>
        <option value="Buone" <?php if($annuncio['condizioni'] == "Buone") echo "selected"; ?>>Buone</option>
        <option value="Usato" <?php if($annuncio['condizioni'] == "Usato") echo "selected"; ?>>Usato</option>
    </select>

    <br><br>

    <label>Stato Annuncio:</label>
    <select name="stato">
        <option value="disponibile" <?php if($annuncio['stato'] == "disponibile") echo "selected"; ?>>Disponibile</option>
        <option value="non disponibile" <?php if($annuncio['stato'] == "non disponibile") echo "selected"; ?>>Non Disponibile</option>
    </select>

    <br><br>

    <button type="submit">Salva Modifiche</button>
</form>