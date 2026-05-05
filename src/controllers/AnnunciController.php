<?php
defined('APP') or die('Accesso Negato');

require_once 'models/AnnunciModel.php';

class AnnunciController{
    private $model;
    private $page;

    public function __construct(){
        $this->model = new AnnunciModel();
        $this->page='annunci';
    }
    
    private function proteggiPagina() {
        if (!isset($_SESSION['id_utente'])) {
            header('location: index.php?page=login&action=login');
            exit;
        }
    }
    public function index(){
        $table = $this->model->selectAll();
        include 'views/template.php';
    }

    public function personal(){
        $this->proteggiPagina();
        $table = $this->model->selectAnnunciByUtente();
        include 'views/template.php';
    }

    public function create(){
        $this->proteggiPagina();
        $view='views/annunci_create_form.php';
        include 'views/template.php';
    }

    public function store(){
        $prezzo_vendita = trim($_POST['prezzo_vendita']);
        $data = trim($_POST['data']);
        $ora = trim($_POST['ora']);
        $luogo = trim($_POST['luogo']);
        $id_creatore = trim($_SESSION['id_utente']);
        $condizioni = trim($_POST['condizioni']);
        $id_libro = trim($_POST['id_libro']);

        $param=[$prezzo_vendita, $data, $ora, $luogo, $id_creatore, $id_libro, $condizioni];
        $this->model->insertRecord($param);

        header('location:index.php');
        exit;
    }

    public function destroy(){
        $id=$_GET['id_annuncio'];

        $param=[$id];
        $this->model->deleteRecord($param);

        header("location:index.php?page=annunci&action=personal");
        exit;
    }

    public function update(){
        $libri=$this->model->selectTitoli();
        $id_annuncio = $_GET['id_annuncio'];
        $id_libro=$_GET['id_libro'];
        $param=[$id_annuncio,$id_libro];
        $table=$this->model->selectAnnuncio($param);
        $view='views/annunci_update_form.php';
        include 'views/template.php';
    }

    public function edit(){
        $prezzo_vendita = trim($_POST['prezzo_vendita']);
        $ora = trim($_POST['ora']);
        $luogo = trim($_POST['luogo']);
        $condizioni = trim($_POST['condizioni']);
        $id_libro = trim($_POST['id_libro']);
        $stato = trim($_POST['stato']);
        $id_annuncio = trim($_POST['id_annuncio']);

        $param=[$prezzo_vendita, $ora, $luogo, $condizioni, $id_libro, $stato, $id_annuncio];
        $this->model->updateRecord($param);

        header('location:index.php');
        exit;
    }
}