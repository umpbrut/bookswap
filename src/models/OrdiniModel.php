<?php
defined('APP') or die('Accesso Negato');

require_once 'config/dbconnect.php';

class OrdiniModel {
    private $pdo;

    public function __construct() {
        $this->pdo = DB::connect();
    }

    // Annunci in cui l'utente è il compratore (ordinati)
    public function selectOrdinati(array $param) : array {
        $dql = "SELECT Annunci.*, Libri.titolo, Libri.autore
                FROM Annunci
                JOIN Libri USING(id_libro)
                WHERE Annunci.id_compratore = ?
                ORDER BY Annunci.id_annuncio DESC";

        $stm = $this->pdo->prepare($dql);
        $stm->execute($param);
        return $stm->fetchAll(PDO::FETCH_ASSOC);
    }

    public function registraOrdine($id_annuncio, $id_compratore) {
        // Aggiorniamo l'annuncio solo se è ancora 'disponibile' 
        // e se il compratore non è lo stesso che l'ha creato
        $dml = "UPDATE Annunci 
                SET stato = 'In attesa', id_compratore = ? 
                WHERE id_annuncio = ? 
                AND stato = 'Disponibile' 
                AND id_creatore != ?";
                
        $stm = $this->pdo->prepare($dml);
        $stm->execute([$id_compratore, $id_annuncio, $id_compratore]);
        
        return $stm->rowCount() > 0;
    }

    // Annunci in cui l'utente è il venditore e ha un compratore assegnato (venduti)
    public function selectVenduti(array $param) : array {
        $dql = "SELECT Annunci.*, Libri.titolo, Libri.autore,
                       Utenti.nome AS nome_compratore, Utenti.cognome AS cognome_compratore
                FROM Annunci
                JOIN Libri USING(id_libro)
                LEFT JOIN Utenti ON Utenti.id_utente = Annunci.id_compratore
                WHERE Annunci.id_creatore = ?
                  AND Annunci.id_compratore IS NOT NULL
                ORDER BY Annunci.id_annuncio DESC";

        $stm = $this->pdo->prepare($dql);
        $stm->execute($param);
        return $stm->fetchAll(PDO::FETCH_ASSOC);
    }

    // Segna come concluso e restituisce i link delle immagini da eliminare
    public function concludiOrdine(array $param) : array {
        // Recupera i link immagini prima di pulire
        $sel = "SELECT link FROM Immagini WHERE id_annuncio = ?";
        $stm = $this->pdo->prepare($sel);
        $stm->execute([$param[0]]);
        $links = $stm->fetchAll(PDO::FETCH_COLUMN);

        // Elimina le immagini dal DB
        $del = "DELETE FROM Immagini WHERE id_annuncio = ?";
        $stm = $this->pdo->prepare($del);
        $stm->execute([$param[0]]);

        // Aggiorna lo stato dell'annuncio
        $upd = "UPDATE Annunci SET stato = 'Concluso' WHERE id_annuncio = ? AND id_creatore = ?";
        $stm = $this->pdo->prepare($upd);
        $stm->execute($param);

        return $links;
    }

    // Ripristina l'annuncio: rimuove compratore e rimette disponibile
    public function ripristinaOrdine(array $param) : bool {
        $dml = "UPDATE Annunci
                SET stato = 'Disponibile', id_compratore = NULL
                WHERE id_annuncio = ? AND id_creatore = ?";

        $stm = $this->pdo->prepare($dml);
        $stm->execute($param);
        return $stm->rowCount() !== 0;
    }
}
