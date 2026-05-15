<?php
// Entry point dell'applicazione.
// - Avvia la sessione, determina controller/azione dalle querystring
// - Protegge le pagine riservate redirigendo al login se necessario
// - Carica dinamicamente il controller e invoca l'azione richiesta

session_start();
define('APP', true);

// Route minimal: ?page=annunci&action=index
$page = $_GET['page'] ?? 'annunci';
$action = $_GET['action'] ?? 'index';

// Pagine pubbliche che non richiedono login
$pagine_pubbliche = ['login', 'annunci'];

// Se la pagina non è pubblica e non c'è sessione, rimanda al login
if (!in_array($page, $pagine_pubbliche) && !isset($_SESSION['id_utente'])) {
    header('Location: index.php?page=login&action=login');
    exit;
}

// Convenzione: page -> Controller (es. 'annunci' -> 'AnnunciController')
$filename = ucfirst($page) . 'Controller';

if (file_exists("controllers/$filename.php")) {
    require_once "controllers/$filename.php";
    $controller = new $filename();

    // Se il metodo esiste nel controller, lo invochiamo; altrimenti mostriamo errore.
    if (method_exists($controller, $action)) {
        $controller->$action();
    } else {
        echo "Azione non trovata.";
    }
} else {
    echo "Pagina non trovata.";
}