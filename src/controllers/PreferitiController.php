<?php
defined('APP') or die('Accesso Negato');

require_once 'models/PreferitiModel.php';

class PreferitiController{
    private $model;
    private $page;

    public function __construct(){
        $this->model = new PreferitiModel();
        $this->page='preferiti';
    }
    
    private function proteggiPagina() {
        if (!isset($_SESSION['id_utente'])) {
            header('location: index.php?page=login&action=login');
            exit;
        }
    }
    public function index(){
        $this->proteggiPagina();
        $param=[$_SESSION['id_utente']];
        $table = $this->model->selectAll($param);
        include 'views/template.php';
    }

    public function store(){
        $this->proteggiPagina();
        
        $id_utente = $_SESSION['id_utente'];
        $id_annuncio = $_GET['id_annuncio'] ?? null;

        if (!$id_annuncio) {
            header('location:index.php');
            exit;
        }

        $param = [$id_utente, $id_annuncio];

        // LOGICA TOGGLE:
        if ($this->model->exists($id_utente, $id_annuncio)) {
            // Se esiste già, lo rimuoviamo
            $this->model->deleteRecord($param);
            header("location: index.php?page=preferiti");
            exit;
        } else {
            // Se non esiste, lo inseriamo
            $this->model->insertRecord($param);
            header("location: index.php?page=annunci");
            exit;
        }
    }
}