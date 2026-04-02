<form action="index.php?page=annunci&action=store" method="post">

<label for="Cerca un titolo"></label>
<input type="text" id="ricerca-libri" name="libro" list="lista-titoli" placeholder="Scrivi il titolo...">

<datalist id="lista-titoli">
  <?php
    foreach($libri as $libro){
        $id=$libro['id-libro'];
        $titolo=$libro['titolo'];
        echo "<option value='$titolo'>$id</option>";
    }
  ?>
</datalist>

</form>