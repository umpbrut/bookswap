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
        $id_utente=$_SESSION['id_utente'];
        $id_annuncio=$_GET['id_annuncio'];

        $param=[$id_utente,$id_annuncio];
        $this->model->insertRecord($param);

        header('location:index.php');
        exit;
    }
}