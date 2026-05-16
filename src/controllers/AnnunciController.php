<?php
defined('APP') or die('Accesso Negato');

require_once 'models/AnnunciModel.php';

// ── Percorsi immagini ─────────────────────────────────────────────────────────
// IMG_DIR è il percorso fisico sul server dove vengono salvati i file immagine.
// IMG_URL è l'URL pubblico corrispondente usato per mostrare le immagini nel browser.
define('IMG_DIR', '/var/www/html/govoni/images/');
define('IMG_URL', 'http://lab.isit100.fe.it:8092/govoni/images/');
// ─────────────────────────────────────────────────────────────────────────────

// Controller che gestisce tutte le azioni relative agli annunci:
// visualizzazione pubblica, creazione, modifica, eliminazione e upload immagini.
class AnnunciController
{
    // $model: istanza di AnnunciModel per tutte le operazioni sul database.
    private $model;
    // $page: nome della pagina corrente, usato dal template per l'inclusione delle view.
    private $page;

    public function __construct()
    {
        $this->model = new AnnunciModel();
        $this->page  = 'annunci';
    }

    // Verifica che l'utente sia autenticato; se no, lo rimanda alla pagina di login.
    // Viene chiamata all'inizio di ogni azione riservata.
    private function proteggiPagina()
    {
        if (!isset($_SESSION['id_utente'])) {
            header('location: index.php?page=login&action=login');
            exit;
        }
    }

    // Mostra la lista pubblica degli annunci disponibili.
    // Supporta filtri opzionali: materia, classe, condizioni e prezzo massimo.
    public function index()
    {
        // Carica l'elenco delle materie per il pannello filtri nella view.
        $materie = $this->model->selectMaterie();

        // array_filter rimuove i valori vuoti ('') dall'array filtri.
        $filtri = array_filter([
            'id_materia' => $_GET['id_materia'] ?? '',
            'classe'     => $_GET['classe']     ?? '',
            'condizioni' => $_GET['condizioni'] ?? '',
            'prezzo_max' => $_GET['prezzo_max'] ?? '',
        ]);

        // Se almeno un filtro è attivo, usa la query filtrata; altrimenti mostra tutto.
        if (!empty($filtri)) {
            $table = $this->model->selectByFiltri($filtri);
        } else {
            $table = $this->model->selectAll();
        }

        include 'views/template.php';
    }

    // Mostra gli annunci creati dall'utente autenticato (sezione "I miei annunci").
    public function personal()
    {
        $this->proteggiPagina();
        $table = $this->model->selectAnnunciByUtente();
        include 'views/template.php';
    }

    // Mostra il form di creazione di un nuovo annuncio.
    // Passa la variabile $view al template così sa quale sottoview includere.
    public function create()
    {
        $this->proteggiPagina();
        $view = 'views/annunci_create_form.php';
        include 'views/template.php';
    }

    // Elabora il form di creazione, salva l'annuncio e gestisce l'upload delle immagini.
    public function store()
    {
        // Legge e pulisce i dati inviati dal form tramite $_POST.
        $prezzo_vendita = trim($_POST['prezzo_vendita']);
        $data           = trim($_POST['data']);
        $ora            = trim($_POST['ora']);
        $luogo          = trim($_POST['luogo']);
        $id_creatore    = trim($_SESSION['id_utente']);
        $condizioni     = trim($_POST['condizioni']);
        $id_libro       = trim($_POST['id_libro']);

        // Validazione server-side del prezzo: deve essere un numero tra 0.01 e 200.
        // Questa controllo è duplicato rispetto al client (HTML required/min/max)
        // ma è necessario perché le validazioni lato client possono essere aggirate.
        if (!is_numeric($prezzo_vendita) || $prezzo_vendita <= 0 || $prezzo_vendita > 200) {
            header('location: index.php?page=annunci&action=create&errore=prezzo');
            exit;
        }

        // Inserisce l'annuncio nel DB e ottiene l'ID generato per collegare le immagini.
        $param       = [$prezzo_vendita, $data, $ora, $luogo, $id_creatore, $id_libro, $condizioni];
        $id_annuncio = $this->model->insertRecord($param);

        // Upload delle immagini: gestisce fino a 3 file per annuncio.
        if ($id_annuncio && isset($_FILES['foto']) && !empty($_FILES['foto']['name'][0])) {
            $files    = $_FILES['foto'];
            $count    = count($files['name']);
            $caricati = 0;

            // Cicla ogni file caricato; si ferma dopo 3 immagini salvate correttamente.
            for ($i = 0; $i < $count && $caricati < 3; $i++) {
                // Salta i file con errori di upload (es. dimensione superata, trasferimento interrotto).
                if ($files['error'][$i] !== UPLOAD_ERR_OK)
                    continue;

                $tmp  = $files['tmp_name'][$i];
                // pathinfo estrae l'estensione originale del file (es. "jpg", "png").
                $ext  = strtolower(pathinfo($files['name'][$i], PATHINFO_EXTENSION));

                // Genera un nome univoco combinando ID annuncio, timestamp e indice.
                $nomeFile    = 'annuncio_' . $id_annuncio . '_' . time() . '_' . $i . '.' . $ext;
                $destinazione = IMG_DIR . $nomeFile;

                // Sposta il file dalla cartella temporanea alla destinazione finale.
                if (move_uploaded_file($tmp, $destinazione)) {
                    $link = IMG_URL . $nomeFile;
                    // Salva il link pubblico dell'immagine nella tabella Immagini del DB.
                    $this->model->insertImmagine([$link, $id_annuncio]);
                    $caricati++;
                }
            }
        }

        // Torna alla homepage dopo la pubblicazione.
        header('location:index.php');
        exit;
    }

    // Elimina un annuncio specificato tramite parametro GET e torna alla lista personale.
    public function destroy()
    {
        $id    = $_GET['id_annuncio'];
        $param = [$id];
        $this->model->deleteRecord($param);

        header("location:index.php?page=annunci&action=personal");
        exit;
    }

    // Carica i dati di un annuncio esistente e mostra il form di modifica.
    public function update()
    {
        // Recupera i titoli disponibili (usato in passato da un <select>; ora gestito via AJAX).
        $libri       = $this->model->selectTitoli();
        $id_annuncio = $_GET['id_annuncio'];
        $id_libro    = $_GET['id_libro'];
        $param       = [$id_annuncio, $id_libro];
        $table       = $this->model->selectAnnuncio($param);
        $view        = 'views/annunci_update_form.php';
        include 'views/template.php';
    }

    // Salva le modifiche apportate a un annuncio esistente.
    // NOTA (modifica n.1): il campo "stato" non viene più inviato dal form di modifica.
    // Lo stato rimane invariato: viene gestito solo dalle azioni "consegna" e "ripristina"
    // nel controller OrdiniController, garantendo un flusso controllato.
    public function edit()
    {
        $prezzo_vendita = trim($_POST['prezzo_vendita']);
        $data           = trim($_POST['data']);
        $ora            = trim($_POST['ora']);
        $luogo          = trim($_POST['luogo']);
        $condizioni     = trim($_POST['condizioni']);
        $id_libro       = trim($_POST['id_libro']);
        $id_annuncio    = trim($_POST['id_annuncio']);

        // Il campo stato non arriva più dal form (modifica n.1).
        // Viene letto direttamente dalla query di aggiornamento senza sovrascriverlo.

        // Validazione server-side del prezzo anche in fase di modifica.
        if (!is_numeric($prezzo_vendita) || $prezzo_vendita <= 0 || $prezzo_vendita > 200) {
            header('location: index.php?page=annunci&action=personal');
            exit;
        }

        // La query updateRecord si aspetta: prezzo, data, ora, luogo, condizioni, id_libro, id_annuncio.
        // Lo stato viene mantenuto invariato nel DB (la colonna non viene toccata).
        $param = [$prezzo_vendita, $data, $ora, $luogo, $condizioni, $id_libro, $id_annuncio];
        $this->model->updateRecord($param);

        header('location:index.php?page=annunci&action=personal');
        exit;
    }
}
