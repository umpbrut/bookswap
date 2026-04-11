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

    public function index() {
        $table = $this->model->selectAll();
        include 'views/template.php';
    }

    public function create() {
        $libri = $this->model->selectTitoli();
        $view = 'views/annunci_create_form.php';
        include 'views/template.php';
    }

    public function store() {
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
}