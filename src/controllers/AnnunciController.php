<?php
defined('APP') or die('Accesso Negato');

require_once 'config/imgconfig.php';
require_once 'models/AnnunciModel.php';

class AnnunciController{
    private $model;
    private $page;

    public function __construct(){
        $this->model = new AnnunciModel();
        $this->page='annunci';
    }
    
    private function proteggiPagina() {
        if (!isset($_SESSION['id_utente'])) {
            header('location: index.php?page=login&action=login');
            exit;
        }
    }

    public function index() {
        $materie = $this->model->selectMaterie();

        $filtri = array_filter([
            'id_materia' => $_GET['id_materia'] ?? '',
            'condizioni' => $_GET['condizioni'] ?? '',
            'prezzo_max' => $_GET['prezzo_max'] ?? '',
        ]);

        if (!empty($filtri)) {
            $table = $this->model->selectByFiltri($filtri);
        }
        else{
            $table = $this->model->selectAll();
        }

        include 'views/template.php';
    }

    public function personal(){
        $this->proteggiPagina();
        $table = $this->model->selectAnnunciByUtente();
        include 'views/template.php';
    }

    public function create(){
        $this->proteggiPagina();
        $view='views/annunci_create_form.php';
        include 'views/template.php';
    }

    public function store(){
        // ── Dati annuncio (logica originale invariata) ──
        $prezzo_vendita = trim($_POST['prezzo_vendita']);
        $data           = trim($_POST['data']);
        $ora            = trim($_POST['ora']);
        $luogo          = trim($_POST['luogo']);
        $id_creatore    = trim($_SESSION['id_utente']);
        $condizioni     = trim($_POST['condizioni']);
        $id_libro       = trim($_POST['id_libro']);

        $param = [$prezzo_vendita, $data, $ora, $luogo, $id_creatore, $id_libro, $condizioni];
        $id_annuncio = $this->model->insertRecord($param); // ora ritorna l'id

        // ── Upload immagini via FTP ──
        if ($id_annuncio && isset($_FILES['foto']) && !empty($_FILES['foto']['name'][0])) {
            $ftp = ftp_connect(FTP_HOST);

            if ($ftp && ftp_login($ftp, FTP_USER, FTP_PASS)) {
                ftp_pasv($ftp, true); // modalità passiva (funziona dietro NAT/firewall)

                $files    = $_FILES['foto'];
                $count    = count($files['name']);
                $caricati = 0;

                for ($i = 0; $i < $count && $caricati < 3; $i++) {
                    if ($files['error'][$i] !== UPLOAD_ERR_OK) continue;

                    $tmp      = $files['tmp_name'][$i];
                    $ext      = strtolower(pathinfo($files['name'][$i], PATHINFO_EXTENSION));
                    // Nome univoco: id_annuncio + timestamp + indice
                    $nomeFile = 'annuncio_' . $id_annuncio . '_' . time() . '_' . $i . '.' . $ext;
                    $percorsoFtp = FTP_DIR . $nomeFile;

                    if (ftp_put($ftp, $percorsoFtp, $tmp, FTP_BINARY)) {
                        $link = IMG_URL . $nomeFile;
                        $this->model->insertImmagine([$link, $id_annuncio]);
                        $caricati++;
                    }
                }

                ftp_close($ftp);
            }
        }

        header('location:index.php');
        exit;
    }

    public function destroy(){
        $id=$_GET['id_annuncio'];

        $param=[$id];
        $this->model->deleteRecord($param);

        header("location:index.php?page=annunci&action=personal");
        exit;
    }

    public function update(){
        $libri=$this->model->selectTitoli();
        $id_annuncio = $_GET['id_annuncio'];
        $id_libro=$_GET['id_libro'];
        $param=[$id_annuncio,$id_libro];
        $table=$this->model->selectAnnuncio($param);
        $view='views/annunci_update_form.php';
        include 'views/template.php';
    }

    public function edit(){
        $prezzo_vendita = trim($_POST['prezzo_vendita']);
        $ora            = trim($_POST['ora']);
        $luogo          = trim($_POST['luogo']);
        $condizioni     = trim($_POST['condizioni']);
        $id_libro       = trim($_POST['id_libro']);
        $stato          = trim($_POST['stato']);
        $id_annuncio    = trim($_POST['id_annuncio']);

        $param=[$prezzo_vendita, $ora, $luogo, $condizioni, $id_libro, $stato, $id_annuncio];
        $this->model->updateRecord($param);

        header('location:index.php');
        exit;
    }
}