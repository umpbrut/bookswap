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

    public function index(){
        $table = $this->model->selectAll();
        include 'views/template.php';
    }

    public function create(){
        $libri=$this->model->selectTitoli();
        $view='views/annunci_create_form.php';
        include 'views/template.php';
    }

    public function store(){
        $prezzo = trim($_POST['prezzo']);
        $data = trim($_POST['data']);
        $ora = trim($_POST['ora']);
        $luogo = trim($_POST['luogo']);
        $id_creatore = trim($_SESSION['id_utente']);
        $condizioni = trim($_POST['condizioni']);
        $id_libro = trim($_POST['id_libro']);

        $param=[$prezzo, $data, $ora, $luogo, $id_creatore, $id_libro, $condizioni];
        $this->model->insertRecord($param);

        header('location:index.php');
        exit;
    }
}