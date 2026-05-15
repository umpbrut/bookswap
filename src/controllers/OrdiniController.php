<?php
defined('APP') or die('Accesso Negato');

require_once 'models/OrdiniModel.php';

// Stessa costante definita in AnnunciController; la definiamo solo se non esiste già
if (!defined('IMG_DIR')) {
    define('IMG_DIR', '/var/www/html/govoni/images/');
}

class OrdiniController {
    private $model;
    private $page;

    public function __construct() {
        $this->model = new OrdiniModel();
        $this->page  = 'ordini';
    }

    private function proteggiPagina() {
        if (!isset($_SESSION['id_utente'])) {
            header('Location: index.php?page=login&action=login');
            exit;
        }
    }

    // index: mostra la pagina con i due tab (ordinati / venduti)
    public function index() {
        $this->proteggiPagina();
        $id = $_SESSION['id_utente'];

        $ordinati = $this->model->selectOrdinati([$id]);
        $venduti  = $this->model->selectVenduti([$id]);

        include 'views/template.php';
    }

    public function ordina() {
    // 1. Protezione: devi essere loggato
        $this->proteggiPagina();

        $id_annuncio = $_GET['id_annuncio'] ?? 0;
        $id_compratore = $_SESSION['id_utente'];

        // 2. Chiamata al model (la funzione la creiamo al punto 2)
        $successo = $this->model->registraOrdine($id_annuncio, $id_compratore);

        if ($successo) {
            // Reindirizza alla pagina ordini per vedere l'acquisto fatto
            header('Location: index.php?page=ordini&action=index&tab=ordinati');
        } else {
            // Gestione errore (es. annuncio già venduto o è il tuo stesso annuncio)
            header('Location: index.php?page=annunci&action=index&error=cannot_order');
        }
        exit;
    }

    // consegna: segna l'ordine come concluso ed elimina le immagini fisiche
    public function consegna() {
        $this->proteggiPagina();

        $id_annuncio = (int) ($_GET['id_annuncio'] ?? 0);
        $id_utente   = $_SESSION['id_utente'];

        $links = $this->model->concludiOrdine([$id_annuncio, $id_utente]);

        // Elimina i file fisici dal server
        foreach ($links as $link) {
            $nomeFile = basename($link);
            $percorso = IMG_DIR . $nomeFile;
            if (file_exists($percorso)) {
                unlink($percorso);
            }
        }

        header('Location: index.php?page=ordini&action=index&tab=venduti');
        exit;
    }

    // ripristina: annulla l'ordine, rimette l'annuncio disponibile
    public function ripristina() {
        $this->proteggiPagina();

        $id_annuncio = (int) ($_GET['id_annuncio'] ?? 0);
        $id_utente   = $_SESSION['id_utente'];

        $this->model->ripristinaOrdine([$id_annuncio, $id_utente]);

        header('Location: index.php?page=ordini&action=index&tab=venduti');
        exit;
    }
}
