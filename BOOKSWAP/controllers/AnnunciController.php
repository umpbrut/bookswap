<?php
defined('APP') or die('Accesso Negato');
require_once 'models/AnnunciModel.php';

class AnnunciController {
    private $model;
    private $page;

    public function __construct() {
        $this->model = new AnnunciModel();
        $this->page = 'annunci';
    }

    private function proteggiPagina() {
        if (!isset($_SESSION['id_utente'])) {
            include 'views/access_denied.php';
            exit;
        }
    }

    public function index() {
        $table = $this->model->selectAll();
        include 'views/template.php';
    }

    public function create() {
        $this->proteggiPagina();
        $libri = $this->model->selectTitoli();
        $view = 'views/annunci_create_form.php';
        include 'views/template.php';
    }

    public function store() {
        $this->proteggiPagina();
        
        $prezzo      = trim($_POST['prezzo'] ?? '');
        $data        = trim($_POST['data'] ?? '');
        $ora         = trim($_POST['ora'] ?? '');
        $luogo       = trim($_POST['luogo'] ?? '');
        $condizioni  = trim($_POST['condizioni'] ?? '');
        $id_libro    = trim($_POST['id_libro'] ?? '');
        $id_creatore = $_SESSION['id_utente'] ?? null;

        if (empty($id_libro)) {
            $_SESSION['error'] = "Errore: devi selezionare un libro valido dalla lista suggerita ❌";
            header('Location: index.php?page=annunci&action=create');
            exit;
        }

        if (!$id_creatore) {
            $_SESSION['error'] = "Sessione scaduta o utente non loggato ❌";
            header('Location: index.php?page=login');
            exit;
        }

        $param = [
            $prezzo, 
            $data, 
            $ora, 
            $luogo, 
            $id_creatore, 
            $id_libro, 
            $condizioni
        ];

        $success = $this->model->insertRecord($param);

        if ($success) {
            $_SESSION['success'] = "Annuncio pubblicato con successo! ✅";
        } else {
            $_SESSION['error'] = "Si è verificato un errore durante la pubblicazione ❌";
        }

        header('Location: index.php?page=annunci&action=index');
        exit;
    }

    public function personal() {
        $this->proteggiPagina();
        $table = $this->model->selectAnnunciByUtente();
        include 'views/libri_disponibili.php';
    }

    public function destroy() {
        $this->proteggiPagina();
        
        $id = $_GET['id_annuncio'] ?? null;
        
        if (!$id) {
            $_SESSION['error'] = "ID annuncio non valido ❌";
            header('Location: index.php?page=annunci&action=personal');
            exit;
        }

        $param = [$id];
        $success = $this->model->deleteRecord($param);

        if ($success) {
            $_SESSION['success'] = "Annuncio eliminato con successo! ✅";
        } else {
            $_SESSION['error'] = "Errore durante l'eliminazione dell'annuncio ❌";
        }

        header('Location: index.php?page=annunci&action=personal');
        exit;
    }

    public function update() {
        $this->proteggiPagina();
        
        $id_annuncio = $_GET['id_annuncio'] ?? null;
        $id_libro = $_GET['id_libro'] ?? null;

        if (!$id_annuncio || !$id_libro) {
            $_SESSION['error'] = "Parametri non validi ❌";
            header('Location: index.php?page=annunci&action=personal');
            exit;
        }

        $libri = $this->model->selectTitoli();
        $param = [$id_annuncio, $id_libro];
        $table = $this->model->selectAnnuncio($param);
        $view = 'views/annunci_update_form.php';
        include 'views/template.php';
    }

    public function edit() {
        $this->proteggiPagina();
        
        $prezzo      = trim($_POST['prezzo'] ?? '');
        $ora         = trim($_POST['ora'] ?? '');
        $luogo       = trim($_POST['luogo'] ?? '');
        $condizioni  = trim($_POST['condizioni'] ?? '');
        $id_libro    = trim($_POST['id_libro'] ?? '');
        $stato       = trim($_POST['stato'] ?? '');
        $id_annuncio = trim($_POST['id_annuncio'] ?? '');

        if (!$id_annuncio) {
            $_SESSION['error'] = "Errore: ID annuncio non valido ❌";
            header('Location: index.php?page=annunci&action=personal');
            exit;
        }

        $param = [$prezzo, $ora, $luogo, $condizioni, $id_libro, $stato, $id_annuncio];
        $success = $this->model->updateRecord($param);

        if ($success) {
            $_SESSION['success'] = "Annuncio aggiornato con successo! ✅";
        } else {
            $_SESSION['error'] = "Errore durante l'aggiornamento dell'annuncio ❌";
        }

        header('Location: index.php?page=annunci&action=personal');
        exit;
    }
}