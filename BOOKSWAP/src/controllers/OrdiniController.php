<?php
defined('APP') or die('Accesso Negato');

require_once 'models/OrdiniModel.php';

// Stessa costante definita in AnnunciController; la definiamo solo se non esiste già.
// IMG_DIR serve per cancellare le immagini caricate quando l'ordine viene concluso.
if (!defined('IMG_DIR')) {
    define('IMG_DIR', '/var/www/html/govoni/images/');
}

// Controller che gestisce gli ordini: acquisti, vendite, consegne e ripristini.
class OrdiniController
{
    private $model;
    private $page;

    public function __construct()
    {
        $this->model = new OrdiniModel();
        $this->page = 'ordini';
    }

    // Protegge tutte le azioni riservate agli utenti autenticati.
    private function proteggiPagina()
    {
        if (!isset($_SESSION['id_utente'])) {
            header('Location: index.php?page=login&action=login');
            exit;
        }
    }

    // Index: mostra la pagina ordini dell'utente, con due tab separati:
    // - ordinati: gli acquisti effettuati dall'utente
    // - venduti: le vendite concluse dall'utente
    public function index()
    {
        $this->proteggiPagina();
        $id = $_SESSION['id_utente'];

        $ordinati = $this->model->selectOrdinati([$id]);
        $venduti = $this->model->selectVenduti([$id]);

        include 'views/template.php';
    }

    // Ordina un annuncio: registra l'acquisto e segna l'annuncio come riservato.
    public function ordina()
    {
        // 1. Protezione: l'utente deve essere autenticato.
        $this->proteggiPagina();

        $id_annuncio = $_GET['id_annuncio'] ?? 0;
        $id_compratore = $_SESSION['id_utente'];

        // 2. Chiamata al model per inserire l'ordine nel database.
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

    // Consegna: chiude l'ordine, libera l'annuncio e rimuove le immagini fisiche dal server.
    public function consegna()
    {
        $this->proteggiPagina();

        $id_annuncio = (int) ($_GET['id_annuncio'] ?? 0);
        $id_utente = $_SESSION['id_utente'];

        $links = $this->model->concludiOrdine([$id_annuncio, $id_utente]);

        // Elimina i file fisici associati all'annuncio venduto.
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

    // Ripristina un ordine annullato: rende nuovamente disponibile l'annuncio.
    public function ripristina()
    {
        $this->proteggiPagina();

        $id_annuncio = (int) ($_GET['id_annuncio'] ?? 0);
        $id_utente = $_SESSION['id_utente'];

        $this->model->ripristinaOrdine([$id_annuncio, $id_utente]);

        header('Location: index.php?page=ordini&action=index&tab=venduti');
        exit;
    }
}
