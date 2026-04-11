<form action="index.php?page=annunci&action=store" method="post">
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