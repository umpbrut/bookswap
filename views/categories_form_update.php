<?php
defined('APP') or die('Accesso Negato')
?>
<form action="index.php?page=categories&action=edit" method="post" class="row g-3">
    <div class="col-12">
        <label class="form-label fw-bold text-primary">Seleziona ID Categoria da modificare</label>
        <select name="id" class="form-select border-primary">
            <?php
                $ids=array_column($table, 'category_id');
                foreach($ids as $id){
                    echo "<option>$id</option>";
                }
            ?>
        </select>
    </div>

    <div class="col-12">
        <label class="form-label fw-bold">Nuovo Nome Categoria</label>
        <input type="text" name="name" class="form-control" placeholder="Inserisci il nuovo nome" required>
    </div>

    <div class="col-12">
        <label class="form-label fw-bold">Nuova Descrizione</label>
        <textarea name="description" class="form-control" rows="3" placeholder="Aggiorna la descrizione..." required></textarea>
    </div>

    <div class="col-12 d-grid mt-3">
        <input type="submit" value="AGGIORNA CATEGORIA" class="btn btn-warning btn-lg">
    </div>
</form>