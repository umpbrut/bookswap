<?php 
defined('APP') or die('Accesso Negato') ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ISIT BOOKS LOGIN</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container d-flex flex-column align-items-center justify-content-center vh-100">
        <div class="card shadow-sm p-4" style="width: 100%; max-width: 400px;">
            <header class="text-center mb-4">
                <h1 class="h3 font-weight-normal">LOGIN</h1>
            </header>
            <main>
                <section class="mb-3">
                    <?php if(!empty($_SESSION['error'])): ?>
                        <div class="alert alert-danger text-center"><?= $_SESSION['error'] ?></div>
                    <?php endif; ?>
                </section>
                <section class="mb-3">
                    <?php if(!empty($view)) include $view; ?>
                </section>
                <div class="d-grid gap-2">
                    <a href="index.php?page=login&action=registration" class="btn btn-outline-secondary">REGISTRATION</a>
                </div>
            </main>
        </div>
        <footer class="mt-4 text-muted small">
            <div>FOOTER &copy; 2024</div>
        </footer>
    </div>
</body>
</html>