<?php
defined('APP') or die('Accesso Negato');
require_once 'models/PersonalModel.php';

// Controller per gestire il profilo personale dell'utente e la modifica delle credenziali.
class PersonalController
{

    private $model;
    private $page;

    public function __construct()
    {
        $this->model = new PersonalModel();
        $this->page = 'personal';
    }

    // Mostra la pagina con i dati personali dell'utente.
    public function index()
    {
        if (!isset($_SESSION['id_utente'])) {
            include 'views/access_denied.php';
            return;
        }

        $utente = $this->model->selectUtente($_SESSION['id_utente']);
        include 'views/credenziali.php';
    }

    // Aggiorna le informazioni dell'utente loggato.
    public function update()
    {
        if (!isset($_SESSION['id_utente'])) {
            include 'views/access_denied.php';
            return;
        }

        $nome = trim($_POST['nome'] ?? '');
        $cognome = trim($_POST['cognome'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $num_tel = trim($_POST['num_tel'] ?? '');
        $password = $_POST['password'] ?? '';
        $id = $_SESSION['id_utente'];

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $_SESSION['error'] = "Formato email non valido ❌";
            header("Location: index.php?page=personal&action=index");
            exit;
        }

        $success = $this->model->updateUtente([$nome, $cognome, $email, $num_tel, $id]);

        // Aggiorna la password solo se è stata compilata nel form.
        if (!empty($password)) {
            $this->model->updatePassword($password, $id);
        }

        // Aggiorna il nome in sessione
        $_SESSION['nome'] = $nome;

        // Feedback per l'utente sulla riuscita dell'aggiornamento.
        if ($success) {
            $_SESSION['success'] = "Dati aggiornati con successo! ✅";
        } else {
            $_SESSION['success'] = "Nessuna modifica rilevata.";
        }

        header("Location: index.php?page=personal&action=index");
        exit;
    }
}