<?php
defined('APP') or die('Accesso Negato');
require_once 'models/LoginModel.php';

class LoginController {
    private $model;
    private $page;

    public function __construct() {
        $this->model = new LoginModel();
        $this->page = 'login';
    }

    public function login() {
        $page = $this->page; 
        $view = 'views/login_form.php'; 
        include 'views/login_template.php';
    }

    public function check() {
        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';
        $dati = $this->model->selectEmailPassword($email);
        
        if($dati && password_verify($password, $dati['password'])) {
            $_SESSION['id_utente'] = $dati['id_utente'];
            $_SESSION['nome'] = $dati['nome'];
            
            header('Location: index.php?page=annunci&action=index'); 
            exit;
        } else {
            header('Location: index.php?page=login');
            exit;
        }
    }

    public function logout() {
        session_destroy();
        header("Location: index.php?page=login");
        exit;
    }

    public function registration() {
        $page = $this->page;
        $view = 'views/login_registration_form.php'; 
        include 'views/login_template.php';
    }

    public function store() {
        $nome = trim($_POST['nome']);
        $cognome = trim($_POST['cognome']);
        $email = trim($_POST['email']);
        $password = trim($_POST['password']);
        $num_tel = trim($_POST['num_tel']);
        if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $_SESSION['error'] = "Formato email non valido ❌";
            header("Location: index.php?page=login&action=registration");
            exit;
        }

        $utenteEsistente = $this->model->selectEmailPassword($email);

        if ($utenteEsistente) {
            $_SESSION['info'] = "L'email associata ha già un account. Accedi qui sotto. ℹ️";
            header("Location: index.php?page=login&registration_status=exists");
            exit;
        }

        $param = [$nome, $cognome, $email, password_hash($password, PASSWORD_DEFAULT), $num_tel];
        $success = $this->model->insertRecord($param);
        
        if ($success) {
            $_SESSION['success'] = "Registrazione avvenuta con successo! ✅";
            header("Location: index.php?page=login&registration_status=success");
        } else {
            $_SESSION['error'] = "Errore durante la registrazione. Riprova. ❌";
            header("Location: index.php?page=login&action=registration");
        }
        exit;
    }
}
