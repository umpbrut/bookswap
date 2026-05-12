<?php
defined('APP') or die('Accesso Negato');

require_once 'config/dbconnect.php';

class AnnunciModel{
    private $pdo;

    public function __construct(){
        $this->pdo=DB::connect();
    }

    // public function selectAll(array $param=[]) : array{
    //     $dql = "SELECT * FROM Annunci
    //             JOIN Libri using(id_libro)
    //             JOIN Immagini using(id_annuncio)";

    //     $stm = $this->pdo->prepare($dql);
    //     $stm->execute($param);
    //     return $stm->fetchAll(PDO::FETCH_ASSOC);
    // }

    public function selectAll(array $param=[]) : array{
        $dql = "SELECT Annunci.*, Libri.*, Materie.nome as materia, 
                GROUP_CONCAT(Immagini.link) as links
                FROM Annunci
                JOIN Libri USING(id_libro)
                JOIN Materie USING(id_materia)
                LEFT JOIN Immagini USING(id_annuncio)
                GROUP BY id_annuncio";

        $stm = $this->pdo->prepare($dql);
        $stm->execute($param);
        return $stm->fetchAll(PDO::FETCH_ASSOC);
    }

    // public function selectAnnunciByUtente(array $param=[]) : array{
    //     $id=$_SESSION['id_utente'];
    //     $dql = "SELECT * FROM Annunci
    //     JOIN Libri USING(id_libro)
    //     WHERE id_creatore = $id";

    //     $stm = $this->pdo->prepare($dql);
    //     $stm->execute($param);
    //     return $stm->fetchAll(PDO::FETCH_ASSOC);
    // }

    public function selectAnnunciByUtente(array $param=[]) : array{
        $id = $_SESSION['id_utente'];
        // Aggiungiamo la JOIN per le immagini e le materie
        $dql = "SELECT Annunci.*, Libri.*, Materie.nome as materia, 
                GROUP_CONCAT(Immagini.link) as links
                FROM Annunci
                JOIN Libri USING(id_libro)
                JOIN Materie USING(id_materia)
                LEFT JOIN Immagini USING(id_annuncio)
                WHERE id_creatore = $id
                GROUP BY id_annuncio"; // GROUP BY è vitale per non duplicare le righe

        $stm = $this->pdo->prepare($dql);
        $stm->execute($param);
        return $stm->fetchAll(PDO::FETCH_ASSOC);
    }

    public function selectAnnuncio(array $param=[]) : array{
        $dql = "SELECT * FROM Annunci
                JOIN Libri using(id_libro)
                WHERE id_annuncio = ? and id_libro = ?";

        $stm = $this->pdo->prepare($dql);
        $stm->execute($param);
        return $stm->fetchAll(PDO::FETCH_ASSOC);
    }

    public function selectTitoli(array $param=[]) : array{
        $dql = "SELECT id_libro,titolo FROM Libri";

        $stm = $this->pdo->prepare($dql);
        $stm->execute($param);
        return $stm->fetchAll(PDO::FETCH_ASSOC);
    }

    // Modificato: ora ritorna l'id dell'annuncio appena inserito
    public function insertRecord(array $param) : int|false {
        $dml="INSERT INTO Annunci(`prezzo_vendita`,`data`,`ora`,`luogo`,`id_creatore`,`id_libro`,`condizioni`)
        VALUES(?,?,?,?,?,?,?)";

        $stm = $this->pdo->prepare($dml);
        $stm->execute($param);

        if ($stm->rowCount() === 0) return false;
        return (int) $this->pdo->lastInsertId();
    }

    // Nuovo: salva il link dell'immagine collegato all'annuncio
    public function insertImmagine(array $param) : bool {
        $dml = "INSERT INTO Immagini(`link`, `id_annuncio`) VALUES(?, ?)";
        $stm = $this->pdo->prepare($dml);
        $stm->execute($param);
        return $stm->rowCount() !== 0;
    }

    public function deleteRecord(array $param) : bool{
        $dml="DELETE FROM Annunci WHERE id_annuncio = ?";

        $stm = $this->pdo->prepare($dml);
        $stm->execute($param);

        return $stm->rowCount() !== 0;
    }

    public function updateRecord(array $param) : bool{
        $dml="UPDATE Annunci
        SET prezzo_vendita = ?, ora = ?, luogo = ?, condizioni = ?, id_libro = ?, stato = ?
        WHERE id_annuncio = ?";

        $stm = $this->pdo->prepare($dml);
        $stm->execute($param);

        return $stm->rowCount() !== 0;
    }

    public function selectByFiltri(array $param) : array {
        // 1. Usiamo la stessa struttura di selectAll con GROUP_CONCAT e JOIN su Materie
        $dql = "SELECT Annunci.*, Libri.*, Materie.nome as materia, 
                GROUP_CONCAT(Immagini.link) as links
                FROM Annunci
                JOIN Libri USING(id_libro)
                JOIN Materie USING(id_materia)
                LEFT JOIN Immagini USING(id_annuncio)
                WHERE Materie.id_materia LIKE ?
                AND Annunci.condizioni LIKE ?
                AND Annunci.prezzo_vendita <= ?
                GROUP BY id_annuncio"; // Fondamentale per non avere duplicati

        // 2. Prepariamo i valori (gestendo i casi vuoti)
        $id_materia = !empty($param['id_materia']) ? $param['id_materia'] : '%';
        $condizioni = !empty($param['condizioni']) ? $param['condizioni'] : '%';
        $prezzo_max = !empty($param['prezzo_max']) ? $param['prezzo_max'] : 999999;

        $stm = $this->pdo->prepare($dql);
        $stm->execute([$id_materia, $condizioni, $prezzo_max]);
        return $stm->fetchAll(PDO::FETCH_ASSOC);
    }         

    public function selectMaterie() : array {
        $stm = $this->pdo->prepare("SELECT id_materia, nome FROM Materie");
        $stm->execute();
        return $stm->fetchAll(PDO::FETCH_ASSOC);
    }
}