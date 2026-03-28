<?php
defined('APP') or die('Accesso Negato')
?>
<form action="index.php?page=products&action=store" method="post" class="row g-3">
    <div class="col-md-6">
        <label class="form-label font-weight-bold">Nome Prodotto</label>
        <input type="text" name="name" class="form-control" placeholder="es. iPhone 15" required>
    </div>

    <div class="col-md-6">
        <label class="form-label font-weight-bold">Marca</label>
        <input type="text" name="brand" class="form-control" placeholder="es. Apple" required>
    </div>

    <div class="col-md-6">
        <label class="form-label font-weight-bold">Quantità</label>
        <input type="number" name="amount" class="form-control" placeholder="0" required>
    </div>

    <div class="col-md-6">
        <label class="form-label font-weight-bold">Prezzo (€)</label>
        <input type="number" name="price" step="0.01" class="form-control" placeholder="0.00" required>
    </div>

    <div class="col-12">
        <label class="form-label font-weight-bold">Categoria</label>
        <select name="category_id" class="form-select">
            <?php foreach($categories as $category) : ?>
                <option value="<?= $category['category_id'] ?>"> <?= $category['name'] ?></option>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="col-12 mt-4 d-grid">
        <input type="submit" value="AGGIUNGI PRODOTTO" class="btn btn-success btn-lg">
    </div>
</form>