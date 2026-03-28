<?php
defined('APP') or die('Accesso Negato');

require_once 'models/ProductsModel.php';

class ProductsController{
    private $model;
    private $page;

    public function __construct(){
        $this->model = new ProductsModel();
        $this->page='products';
    }

    public function index(){
        $table = $this->model->selectAll();
        include 'views/template.php';
    }

    public function create(){
        $table=$this->model->selectAll();
        $categories=$this->model->selectCategories();
        $view='views/products_form_create.php';
        include 'views/template.php';
    }

    public function store(){
        $name = $_POST['name'];
        $brand = $_POST['brand'];
        $amount = (int) $_POST['amount'];
        $price = (float) $_POST['price'];
        $category_id = (int) $_POST['category_id'];

        $param=[$name,$brand,$amount,$price,$category_id];
        $this->model->insertRecord($param);

        header("location:index.php?page=$this->page&action=index");
        exit;
    }

    public function delete(){
        $table=$this->model->selectAll();
        $view='views/products_form_delete.php';
        include 'views/template.php';
    }

    public function destroy(){
        $name=$_POST['name'];

        $param=[$name];
        $this->model->deleteRecord($param);

        header("location:index.php?page=$this->page&action=index");
        exit;
    }

    public function update(){
        $table=$this->model->selectAll();
        $categories=$this->model->selectCategories();
        $view='views/products_form_update.php'; //nome del file form 
        include 'views/template.php';
    }

    public function edit(){
        $id=$_POST['id'];
        $name=$_POST['name'];
        $brand=$_POST['brand'];
        $amount=$_POST['amount'];
        $price=$_POST['price'];
        $category_id=$_POST['category_id'];

        $param=[$name,$brand,$amount,$price,$category_id,$id];
        $this->model->updateRecord($param);

        header('location:index.php');
        exit;
    }
}