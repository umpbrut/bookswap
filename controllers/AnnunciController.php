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

   
}