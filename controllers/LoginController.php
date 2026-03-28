<?php
defined('APP') or die('Accesso Negato');

require_once 'models/LoginModel.php';

class LoginController{
    private $model;
    private $page;

    public function __construct(){
        $this->model = new LoginModel();
        $this->page='login';
    }

    public function login(){
        $view='views/login_form.php'; 
        include 'views/login_template.php';
    }

    public function check(){
        $email = $_POST['email'];
        $password = $_POST['password'];
        $param=['email'];

        $emails = $this->model->selectEmail();
        if(in_array($email,$emails)){
            $dati=$this->model->selectEmailPassword($email);
                if(password_verify($password, $dati['password'])){
                    $_SESSION['email']=$email;
                    $_SESSION['name']=$dati['name'];
                    $_SESSION['customer_id']=$dati['customer_id'];
                }
        }
        header('location:index.php');
        exit;
    }

    public function registration(){
        $view='views/login_registration_form.php'; 
        include 'views/login_template.php';
    }

    public function store(){
        $name = trim($_POST['name']);
        $surname = trim($_POST['surname']);
        $dob =trim($_POST['dob']);
        $gender =trim($_POST['gender']);
        $email =trim($_POST['email']);
        $address=trim($_POST['address']);
        $password=trim($_POST['password']);

        if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
            $_SESSION['error'] = "Formato email non valido";
        }
        else{
            $param=[$name,$surname,$dob,$gender,$email,$address,password_hash($password,PASSWORD_DEFAULT)];
            $this->model->insertRecord($param);
            $_SESSION['error'] = "Registrazione avvenuta ✅";
        }

        header("location:index.php");
        exit;
    }
}