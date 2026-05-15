<?php
defined('APP') or die('Accesso Negato');

require_once 'models/PreferitiModel.php';

// Controller che gestisce i preferiti dell'utente e la logica toggle per aggiungere/rimuovere annunci.
class PreferitiController
{
    private $model;
    private $page;

    public function __construct()
    {
        $this->model = new PreferitiModel();
        $this->page = 'preferiti';
    }

    // Controlla che l'utente sia autenticato prima di accedere ai preferiti.
    private function proteggiPagina()
    {
        if (!isset($_SESSION['id_utente'])) {
            header('location: index.php?page=login&action=login');
            exit;
        }
    }
    // Mostra la lista dei preferiti dell'utente.
    public function index()
    {
        $this->proteggiPagina();
        $param = [$_SESSION['id_utente']];
        $table = $this->model->selectAll($param);
        include 'views/template.php';
    }

    // Aggiunge o rimuove un annuncio dai preferiti (toggle).
    public function store()
    {
        $this->proteggiPagina();

        $id_utente = $_SESSION['id_utente'];
        $id_annuncio = $_GET['id_annuncio'] ?? null;

        if (!$id_annuncio) {
            header('location:index.php');
            exit;
        }

        $param = [$id_utente, $id_annuncio];

        // Logica toggle: se l'annuncio è già nei preferiti, lo rimuove;
        // altrimenti lo aggiunge.
        if ($this->model->exists($id_utente, $id_annuncio)) {
            // Se esiste già, lo rimuoviamo.
            $this->model->deleteRecord($param);
            header("location: index.php?page=preferiti");
            exit;
        } else {
            // Se non esiste, lo inseriamo nei preferiti.
            $this->model->insertRecord($param);
            header("location: index.php?page=annunci");
            exit;
        }
    }
}