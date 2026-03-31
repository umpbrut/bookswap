<?php
defined('APP') or die('ACCESSO NEGATO');
?>
<form action="index.php?page=annunci&action=store" method="post">

<label for="Cerca un titolo"></label>
<input type="text" id="ricerca-libri" name="libro" list="lista-titoli" placeholder="Scrivi il titolo...">

<datalist>
  <?php
  foreach ($libri as $libro){
    $id = $libro['id'];
    $titolo = $libro['titolo'];
    echo"<option data-id='$id' value = '$titolo'"; 
  }
  ?>
</datalist>

</form>