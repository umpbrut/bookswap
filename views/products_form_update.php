<?php defined('APP') or die('Accesso Negato') ?>
<form action="index.php?page=products&action=edit" method="post" class="row g-3">
    <div class="col-12">
        <label class="form-label text-muted small">Select Product ID</label>
        <select name="id" class="form-select">
            <?php
                $ids=array_column($table, 'product_id');
                foreach($ids as $id){ echo "<option>$id</option>"; }
            ?>
        </select>
    </div>
    <div class="col-md-6"><input type="text" name="name" class="form-control" placeholder="Product name" required></div>
    <div class="col-md-6"><input type="text" name="brand" class="form-control" placeholder="Brand" required></div>
    <div class="col-md-6"><input type="number" name="amount" class="form-control" placeholder="Amount" required></div>
    <div class="col-md-6"><input type="number" name="price" step="0.01" class="form-control" placeholder="Price" required></div>
    <div class="col-12">
        <select name="category_id" class="form-select">
            <?php foreach($categories as $category) : ?>
                <option value="<?= $category['category_id'] ?>"> <?= $category['name'] ?></option>
            <?php endforeach; ?>
        </select>
    </div>
    <div class="col-12 d-grid"><input type="submit" value="SUBMIT" class="btn btn-warning"></div>
</form>