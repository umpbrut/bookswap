<?php
defined('APP') or die('Accesso Negato')
?>
<form action="index.php?page=categories&action=store" method="post" class="row g-3">
    <div class="col-12">
        <label class="form-label fw-bold">Nome Categoria</label>
        <input type="text" name="name" class="form-control" placeholder="Inserisci il nome della categoria" required>
    </div>

    <div class="col-12">
        <label class="form-label fw-bold">Descrizione</label>
        <textarea name="description" class="form-control" rows="3" placeholder="Aggiungi una breve descrizione..." required></textarea>
    </div>

    <div class="col-12 d-grid mt-3">
        <input type="submit" value="CREA CATEGORIA" class="btn btn-success btn-lg">
    </div>
</form>