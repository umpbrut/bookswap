<?php defined('APP') or die('Accesso Negato') ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DB manager</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light text-dark">
    <div class="container py-5">
        <header class="pb-3 mb-4 border-bottom d-flex justify-content-between align-items-center">
            <h1 class="h2">Gestione Applicativo</h1>
            <div>
                <a href="index.php?page=products&action=index" class="btn btn-primary">PRODUCTS</a>
                <a href="index.php?page=categories&action=index" class="btn btn-primary">CATEGORIES</a>
            </div>
        </header>

        <main>
            <nav class="mb-4">
                <div class="btn-group w-100" role="group">
                    <?php
                        echo "<a href='index.php?page=$this->page&action=index' class='btn btn-outline-dark'>HOME</a>";
                        echo "<a href='index.php?page=$this->page&action=create' class='btn btn-outline-dark'>CREATE</a>";
                        echo "<a href='index.php?page=$this->page&action=update' class='btn btn-outline-dark'>UPDATE</a>";
                        echo "<a href='index.php?page=$this->page&action=delete' class='btn btn-outline-dark'>DELETE</a>";
                    ?>
                </div>
            </nav>

            <section class="card shadow-sm p-4 mb-4">
                <?php include 'table.php'; ?>
            </section>

            <section class="card shadow-sm p-4">
                <?php if(!empty($view)) include $view; ?>
            </section>
        </main>

        <footer class="pt-3 mt-4 text-muted border-top text-center">
            FOOTER &copy; 2024
        </footer>
    </div>
</body>
</html>