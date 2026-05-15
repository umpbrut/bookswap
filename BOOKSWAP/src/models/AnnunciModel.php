<?php
defined('APP') or die('Accesso Negato');

require_once 'config/dbconnect.php';

// Modello per gestire gli annunci: query per visualizzare, filtrare, creare,
// aggiornare, eliminare annunci e salvare immagini associate.
class AnnunciModel
{
    private $pdo;

    public function __construct()
    {
        $this->pdo = DB::connect();
    }

    // Restituisce tutti gli annunci disponibili, aggregando eventuali immagini
    // collegate e includendo i dati del libro e della materia.
    public function selectAll(array $param = []): array
    {
        // Passiamo per Libri_Associazioni (tabella ponte) per ottenere materia, corso e classe
        // DISTINCT evita duplicati causati da più righe in Libri_Associazioni
        // ORDER BY id_immagine mantiene sempre lo stesso ordine di inserimento
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

    public function selectAnnunciByUtente(array $param = []): array
    {
        $id = $_SESSION['id_utente'];
        // Stessa struttura di selectAll, filtrata per utente creatore
        $dql = "SELECT Annunci.*, Libri.*, Materie.nome as materia,
                GROUP_CONCAT(DISTINCT Immagini.link ORDER BY Immagini.id_immagine) as immagini
                FROM Annunci
                JOIN Libri USING(id_libro)
                JOIN Libri_Associazioni ON Libri_Associazioni.id_libro = Libri.id_libro
                JOIN Materie ON Materie.id_materia = Libri_Associazioni.id_materia
                LEFT JOIN Immagini USING(id_annuncio)
                WHERE id_creatore = $id
                GROUP BY id_annuncio"; // GROUP BY è vitale per non duplicare le righe

        $stm = $this->pdo->prepare($dql);
        $stm->execute($param);
        return $stm->fetchAll(PDO::FETCH_ASSOC);
    }

    public function selectAnnuncio(array $param = []): array
    {
        // Aggiungiamo la JOIN a Libri_Associazioni per recuperare anche materia/corso/classe
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

    // Restituisce l'elenco dei libri disponibili per il form di creazione/modifica.
    public function selectTitoli(array $param = []): array
    {
        $dql = "SELECT id_libro,titolo FROM Libri";

        $stm = $this->pdo->prepare($dql);
        $stm->execute($param);
        return $stm->fetchAll(PDO::FETCH_ASSOC);
    }

    // Modificato: ora ritorna l'id dell'annuncio appena inserito
    // Inserisce un nuovo annuncio e ritorna l'ID generato (utile per collegare le immagini).
    public function insertRecord(array $param): int|false
    {
        $dml = "INSERT INTO Annunci(`prezzo_vendita`,`data`,`ora`,`luogo`,`id_creatore`,`id_libro`,`condizioni`)
        VALUES(?,?,?,?,?,?,?)";

        $stm = $this->pdo->prepare($dml);
        $stm->execute($param);

        if ($stm->rowCount() === 0)
            return false;
        return (int) $this->pdo->lastInsertId();
    }

    // Salva il link di un'immagine associata a un annuncio.
    public function insertImmagine(array $param): bool
    {
        $dml = "INSERT INTO Immagini(`link`, `id_annuncio`) VALUES(?, ?)";
        $stm = $this->pdo->prepare($dml);
        $stm->execute($param);
        return $stm->rowCount() !== 0;
    }

    // Elimina un annuncio dal database.
    public function deleteRecord(array $param): bool
    {
        $dml = "DELETE FROM Annunci WHERE id_annuncio = ?";

        $stm = $this->pdo->prepare($dml);
        $stm->execute($param);

        return $stm->rowCount() !== 0;
    }

    // Aggiorna i dati principali di un annuncio esistente.
    public function updateRecord(array $param): bool
    {
        $dml = "UPDATE Annunci
        SET prezzo_vendita = ?, data = ?, ora = ?, luogo = ?, condizioni = ?, id_libro = ?, stato = ?
        WHERE id_annuncio = ?";

        $stm = $this->pdo->prepare($dml);
        $stm->execute($param);

        return $stm->rowCount() !== 0;
    }

    // Restituisce gli annunci filtrati secondo i parametri scelti dall'utente.
    // Usa LIKE per gestire anche i filtri vuoti (tutti) e confronta il prezzo massimo.
    public function selectByFiltri(array $param): array
    {
        // Base della query: JOIN a Libri_Associazioni per accedere a materia e classe
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
                GROUP BY id_annuncio"; // Fondamentale per non avere duplicati

        // Prepariamo i valori (se il filtro è vuoto usiamo % per "tutti")
        $id_materia = !empty($param['id_materia']) ? $param['id_materia'] : '%';
        $classe = !empty($param['classe']) ? $param['classe'] : '%';
        $condizioni = !empty($param['condizioni']) ? $param['condizioni'] : '%';
        $prezzo_max = !empty($param['prezzo_max']) ? $param['prezzo_max'] : 999999;

        $stm = $this->pdo->prepare($dql);
        $stm->execute([$id_materia, $classe, $condizioni, $prezzo_max]);
        return $stm->fetchAll(PDO::FETCH_ASSOC);
    }

    // Restituisce la lista delle materie per il filtro degli annunci.
    public function selectMaterie(): array
    {
        $stm = $this->pdo->prepare("SELECT id_materia, nome FROM Materie");
        $stm->execute();
        return $stm->fetchAll(PDO::FETCH_ASSOC);
    }
}
