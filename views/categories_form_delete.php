<?php
defined('APP') or die('Accesso Negato')
?>
<form action="index.php?page=categories&action=destroy" method="post" class="card border-danger mt-3">
    <div class="card-body">
        <h5 class="card-title text-danger mb-3">Elimina Categoria</h5>
        <div class="mb-3">
            <label class="form-label">Seleziona il nome della categoria da rimuovere:</label>
            <select name="name" class="form-select">
                <?php
                    $names=array_column($table, 'name');
                    foreach($names as $name){
                        echo "<option>$name</option>";
                    }
                ?>
            </select>
        </div>
        <div class="d-grid">
            <input type="submit" value="ELIMINA DEFINITIVAMENTE" class="btn btn-danger">
        </div>
    </div>
</form>