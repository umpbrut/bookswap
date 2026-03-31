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
                // if(password_verify($password, $dati['password'])){
                if($password == $dati['password']){ //solo perchè non ho messo la password hashata
                    $_SESSION['email']=$email;
                    $_SESSION['nome']=$dati['nome'];
                    $_SESSION['id_utente']=$dati['id_utente'];
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
        $nome = trim($_POST['nome']);
        $cognome = trim($_POST['cognome']);
        $email =trim($_POST['email']);
        $password=trim($_POST['password']);
        $num_tel=trim($_POST['num_tel']);

        if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
            $_SESSION['error'] = "Formato email non valido";
        }
        else{
            $param=[$nome,$cognome,$email,$password,$num_tel,password_hash($password,PASSWORD_DEFAULT)];
            $this->model->insertRecord($param);
            $_SESSION['error'] = "Registrazione avvenuta ✅";
        }

        header("location:index.php");
        exit;
    }
}