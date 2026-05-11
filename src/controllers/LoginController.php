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
        $email = trim($_POST['email']) ?? '';
        $password = $_POST['password'] ?? '';

        if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $_SESSION['error'] = "Formato email non valido. Riprova. ❌";
            header('Location: index.php?page=login');
            exit;
        }

        $dati = $this->model->selectEmailPassword($email);
        
        if($dati && password_verify($password, $dati['password'])) {
            $_SESSION['id_utente'] = $dati['id_utente'];
            $_SESSION['nome'] = $dati['nome'];
            
            header('Location: index.php?page=annunci&action=index'); 
            exit;
        } else {
            $_SESSION['error'] = "Credenziali errate. Riprova. ❌";
            header('Location: index.php?page=login&action=login');
            exit;
        }
    }

    public function logout() {
        session_destroy();
        header("Location: index.php?page=annunci&action=index");
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

        // Estraiamo il dominio dall'email (tutto ciò che sta dopo la @)
        $dominio = substr(strrchr($email, "@"), 1);

        // Controlliamo se il dominio ha dei record MX (Mail Exchanger)
        if (!checkdnsrr($dominio, "MX")) {
            $_SESSION['error'] = "Email inesistente ❌";
            header("Location: index.php?page=login&action=registration");
            exit;
        }

        $utenteEsistente = $this->model->selectEmailPassword($email);

        if ($utenteEsistente) {
            $_SESSION['info'] = "L'email associata ha già un account. Accedi qui sotto. ℹ️";
            header("Location: index.php?page=login&action=registration");
            exit;
        }

        $param = [$nome, $cognome, $email, password_hash($password, PASSWORD_DEFAULT), $num_tel];
        $success = $this->model->insertRecord($param);
        
        if ($success) {
            $_SESSION['success'] = "Registrazione avvenuta con successo! ✅";
            header("Location: index.php?page=login&action=login");
        } else {
            $_SESSION['error'] = "Errore durante la registrazione. Riprova. ❌";
            header("Location: index.php?page=login&action=registration");
        }
        exit;
    }
}