<?php
defined('APP') or die('Accesso Negato');

require_once 'models/CategoriesModel.php';

class CategoriesController{
    private $model;
    private $page;

    public function __construct(){
        $this->model = new CategoriesModel();
        $this->page='categories';
    }

    public function index(){
        $table = $this->model->selectAll();
        include 'views/template.php';
    }

    public function create(){
        $table=$this->model->selectAll();
        $view='views/categories_form_create.php'; //nome del file form 
        include 'views/template.php';
    }

    public function store(){

        $name = trim($_POST['name']);
        $description = trim($_POST['description']);
 
        $param=[$name,$description];
        $this->model->insertRecord($param);

        header('location:index.php');
        exit;
    }

    public function delete(){
        $table=$this->model->selectAll();
        $view='views/categories_form_delete.php'; //nome del file form 
        include 'views/template.php';
    }

    public function destroy(){
        $name=trim($_POST['name']);

        $this->model->deleteRecord($name);

        header('location:index.php');
        exit;
    }

    public function update(){
        $table=$this->model->selectAll();
        $view='views/categories_form_update.php'; //nome del file form 
        include 'views/template.php';
    }

    public function edit(){
        $id=trim($_POST['id']);
        $name=trim($_POST['name']);
        $description=trim($_POST['description']);

        $param=[$name,$description,$id];
        $this->model->updateRecord($param);

        header('location:index.php');
        exit;
    }
}