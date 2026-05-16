<?php
defined('APP') or die('Accesso Negato');

require_once 'config/dbconnect.php';

// Modello per gestire gli annunci: query per visualizzare, filtrare, creare,
// aggiornare, eliminare annunci e salvare le immagini associate.
class AnnunciModel
{
    // $pdo: connessione al database ottenuta tramite DB::connect().
    private $pdo;

    public function __construct()
    {
        $this->pdo = DB::connect();
    }

    // Restituisce tutti gli annunci con stato "Disponibile", includendo i dati del libro,
    // della materia e le immagini aggregate in una stringa separata da virgole.
    public function selectAll(array $param = []): array
    {
        // GROUP_CONCAT aggrega i link delle immagini di ogni annuncio in un unico campo.
        // DISTINCT evita duplicati causati dalla JOIN multipla su Libri_Associazioni.
        // ORDER BY id_immagine garantisce sempre lo stesso ordine di inserimento.
        $dql = "SELECT Annunci.*, Libri.*, Materie.nome as materia,
                GROUP_CONCAT(DISTINCT Immagini.link ORDER BY Immagini.id_immagine) as immagini
                FROM Annunci
                JOIN Libri USING(id_libro)
                JOIN Libri_Associazioni ON Libri_Associazioni.id_libro = Libri.id_libro
                JOIN Materie ON Materie.id_materia = Libri_Associazioni.id_materia
                LEFT JOIN Immagini USING(id_annuncio)
                WHERE Annunci.stato LIKE 'Disponibile'
                GROUP BY id_annuncio";

        $stm = $this->pdo->prepare($dql);
        $stm->execute($param);
        return $stm->fetchAll(PDO::FETCH_ASSOC);
    }

    // Restituisce tutti gli annunci creati dall'utente attualmente loggato (id in sessione).
    // Struttura identica a selectAll ma filtrata per id_creatore.
    public function selectAnnunciByUtente(array $param = []): array
    {
        $id  = $_SESSION['id_utente'];
        // GROUP BY è essenziale per non duplicare le righe quando un annuncio ha più immagini.
        $dql = "SELECT Annunci.*, Libri.*, Materie.nome as materia,
                GROUP_CONCAT(DISTINCT Immagini.link ORDER BY Immagini.id_immagine) as immagini
                FROM Annunci
                JOIN Libri USING(id_libro)
                JOIN Libri_Associazioni ON Libri_Associazioni.id_libro = Libri.id_libro
                JOIN Materie ON Materie.id_materia = Libri_Associazioni.id_materia
                LEFT JOIN Immagini USING(id_annuncio)
                WHERE id_creatore = $id
                GROUP BY id_annuncio";

        $stm = $this->pdo->prepare($dql);
        $stm->execute($param);
        return $stm->fetchAll(PDO::FETCH_ASSOC);
    }

    // Restituisce i dati completi di un singolo annuncio identificato da id_annuncio e id_libro.
    // Usato dal controller per precaricare il form di modifica.
    public function selectAnnuncio(array $param = []): array
    {
        $dql = "SELECT Annunci.*, Libri.*, Materie.nome as materia
                FROM Annunci
                JOIN Libri USING(id_libro)
                JOIN Libri_Associazioni ON Libri_Associazioni.id_libro = Libri.id_libro
                JOIN Materie ON Materie.id_materia = Libri_Associazioni.id_materia
                WHERE id_annuncio = ? AND Annunci.id_libro = ?";

        $stm = $this->pdo->prepare($dql);
        $stm->execute($param);
        return $stm->fetchAll(PDO::FETCH_ASSOC);
    }

    // Restituisce l'elenco semplice dei libri (solo ID e titolo).
    // Usato in passato da un <select> statico nel form; ora la ricerca avviene via AJAX.
    public function selectTitoli(array $param = []): array
    {
        $dql = "SELECT id_libro, titolo FROM Libri";
        $stm = $this->pdo->prepare($dql);
        $stm->execute($param);
        return $stm->fetchAll(PDO::FETCH_ASSOC);
    }

    // Inserisce un nuovo annuncio e ritorna l'ID generato automaticamente dal DB.
    // L'ID è necessario per collegare le immagini all'annuncio appena creato.
    // Ritorna false se l'inserimento non ha prodotto righe (errore).
    public function insertRecord(array $param): int|false
    {
        $dml = "INSERT INTO Annunci(`prezzo_vendita`,`data`,`ora`,`luogo`,`id_creatore`,`id_libro`,`condizioni`)
                VALUES(?,?,?,?,?,?,?)";

        $stm = $this->pdo->prepare($dml);
        $stm->execute($param);

        if ($stm->rowCount() === 0)
            return false;
        // lastInsertId() restituisce l'ID dell'ultima riga inserita con AUTO_INCREMENT.
        return (int) $this->pdo->lastInsertId();
    }

    // Salva nella tabella Immagini il link pubblico di un'immagine associata a un annuncio.
    // Viene chiamato una volta per ogni foto caricata (max 3 per annuncio).
    public function insertImmagine(array $param): bool
    {
        $dml = "INSERT INTO Immagini(`link`, `id_annuncio`) VALUES(?, ?)";
        $stm = $this->pdo->prepare($dml);
        $stm->execute($param);
        return $stm->rowCount() !== 0;
    }

    // Elimina definitivamente un annuncio dal database tramite il suo ID.
    // Ritorna true se almeno una riga è stata cancellata.
    public function deleteRecord(array $param): bool
    {
        $dml = "DELETE FROM Annunci WHERE id_annuncio = ?";
        $stm = $this->pdo->prepare($dml);
        $stm->execute($param);
        return $stm->rowCount() !== 0;
    }

    // Aggiorna i dati modificabili di un annuncio esistente.
    // NOTA (modifica n.1): la colonna "stato" non è più inclusa nell'UPDATE.
    // Lo stato viene gestito esclusivamente dalle azioni "consegna" e "ripristina"
    // in OrdiniController, per evitare che l'utente possa manipolarlo liberamente.
    public function updateRecord(array $param): bool
    {
        // Parametri attesi: [prezzo_vendita, data, ora, luogo, condizioni, id_libro, id_annuncio]
        $dml = "UPDATE Annunci
                SET prezzo_vendita = ?, data = ?, ora = ?, luogo = ?, condizioni = ?, id_libro = ?
                WHERE id_annuncio = ?";

        $stm = $this->pdo->prepare($dml);
        $stm->execute($param);
        return $stm->rowCount() !== 0;
    }

    // Restituisce gli annunci filtrati secondo i parametri scelti dall'utente nella barra filtri.
    // Usa LIKE con '%' per far funzionare i filtri opzionali: se il valore è '%' corrisponde a tutto.
    public function selectByFiltri(array $param): array
    {
        // JOIN a Classi necessaria per filtrare per anno scolastico.
        $dql = "SELECT Annunci.*, Libri.*, Materie.nome as materia,
                GROUP_CONCAT(DISTINCT Immagini.link ORDER BY Immagini.id_immagine) as immagini
                FROM Annunci
                JOIN Libri USING(id_libro)
                JOIN Libri_Associazioni ON Libri_Associazioni.id_libro = Libri.id_libro
                JOIN Materie ON Materie.id_materia = Libri_Associazioni.id_materia
                JOIN Classi ON Classi.id_classe = Libri_Associazioni.id_classe
                LEFT JOIN Immagini USING(id_annuncio)
                WHERE Materie.id_materia LIKE ?
                AND Classi.anno LIKE ?
                AND Annunci.condizioni LIKE ?
                AND Annunci.prezzo_vendita <= ?
                GROUP BY id_annuncio";

        // Se il filtro è vuoto usiamo '%' per "tutti i valori"; se numerico usiamo un valore alto.
        $id_materia = !empty($param['id_materia']) ? $param['id_materia'] : '%';
        $classe     = !empty($param['classe'])     ? $param['classe']     : '%';
        $condizioni = !empty($param['condizioni']) ? $param['condizioni'] : '%';
        $prezzo_max = !empty($param['prezzo_max']) ? $param['prezzo_max'] : 999999;

        $stm = $this->pdo->prepare($dql);
        $stm->execute([$id_materia, $classe, $condizioni, $prezzo_max]);
        return $stm->fetchAll(PDO::FETCH_ASSOC);
    }

    // Restituisce tutte le materie presenti nel DB per popolare il dropdown filtri.
    public function selectMaterie(): array
    {
        $stm = $this->pdo->prepare("SELECT id_materia, nome FROM Materie");
        $stm->execute();
        return $stm->fetchAll(PDO::FETCH_ASSOC);
    }
}
