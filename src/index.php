<?php
session_start();
define('APP', true);

$page = $_GET['page'] ?? 'annunci';
$action = $_GET['action'] ?? 'index';

$pagine_pubbliche = ['login', 'annunci'];

if (!in_array($page, $pagine_pubbliche) && !isset($_SESSION['id_utente'])) {
    header('Location: index.php?page=login&action=login');
    exit;
}

$filename = ucfirst($page) . 'Controller';

if (file_exists("controllers/$filename.php")) {
    require_once "controllers/$filename.php";
    $controller = new $filename();

    if (method_exists($controller, $action)) {
        $controller->$action();
    } else {
        echo "Azione non trovata.";
    }
} else {
    echo "Pagina non trovata.";
}