<?php
session_start();
define('APP', true);

// Recupero pagina e azione
$page = $_GET['page'] ?? 'annunci';
$action = $_GET['action'] ?? 'index';

<<<<<<<< HEAD:BOOKSWAP/src/index.php
$pagine_pubbliche = ['login', 'annunci'];

if (!in_array($page, $pagine_pubbliche) && !isset($_SESSION['id_utente'])) {
    header('Location: index.php?page=login&action=login');
========
// Pagine accessibili senza login
$pagine_pubbliche = ['login', 'annunci'];

if (!in_array($page, $pagine_pubbliche) && !isset($_SESSION['id_utente'])) {
    header("Location: index.php?page=login&action=login");
>>>>>>>> 623e581bd84429521189d0216fdd07e0b50a79bc:BOOKSWAP/index.php
    exit;
}

$filename = ucfirst($page) . 'Controller';

if (file_exists("controllers/$filename.php")) {
    require_once "controllers/$filename.php";
    $controller = new $filename();

    if (method_exists($controller, $action)) {
        $controller->$action();
    } else {
        echo "Azione '$action' non trovata.";
    }
} else {
    echo "Controller '$filename' non trovato.";
}