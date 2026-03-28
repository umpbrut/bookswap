<?php defined('APP') or die('Accesso Negato') ?>
<form action="index.php?page=products&action=destroy" method="post">
    <div class="mb-3">
        <label class="form-label">Seleziona elemento da eliminare:</label>
        <select name="name" class="form-select">
            <?php
                $names=array_column($table, 'name');
                foreach($names as $name){ echo "<option>$name</option>"; }
            ?>
        </select>
    </div>
    <input type="submit" value="SUBMIT" class="btn btn-danger">
</form>