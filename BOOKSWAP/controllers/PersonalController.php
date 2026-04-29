<?php
defined('APP') or die('Accesso Negato');
require_once 'models/PersonalModel.php';

class PersonalController {

    private $model;

    public function __construct() {
        $this->model = new PersonalModel();
    }

    public function index() {
        if (!isset($_SESSION['id_utente'])) {
            include 'views/access_denied.php';
            return;
        }

        $utente = $this->model->selectUtente($_SESSION['id_utente']);
        include 'views/credenziali.php';
    }

    public function update() {
        if (!isset($_SESSION['id_utente'])) {
            include 'views/access_denied.php';
            return;
        }

        $nome     = trim($_POST['nome'] ?? '');
        $cognome  = trim($_POST['cognome'] ?? '');
        $email    = trim($_POST['email'] ?? '');
        $num_tel  = trim($_POST['num_tel'] ?? '');
        $password = $_POST['password'] ?? '';
        $id       = $_SESSION['id_utente'];

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $_SESSION['error'] = "Formato email non valido ❌";
            header("Location: index.php?page=personal&action=index");
            exit;
        }

        $success = $this->model->updateUtente([$nome, $cognome, $email, $num_tel, $id]);

        // Aggiorna la password solo se è stata compilata
        if (!empty($password)) {
            $this->model->updatePassword($password, $id);
        }

        // Aggiorna il nome in sessione
        $_SESSION['nome'] = $nome;

        if ($success) {
            $_SESSION['success'] = "Dati aggiornati con successo! ✅";
        } else {
            $_SESSION['success'] = "Nessuna modifica rilevata.";
        }

        header("Location: index.php?page=personal&action=index");
        exit;
    }
}