<?php
defined('APP') or die('Accesso Negato');

require_once 'config/dbconnect.php';

class AnnunciModel{
    private $pdo;

    public function __construct(){
        $this->pdo=DB::connect();
    }

    public function selectAll(array $param=[]) : array{
        $dql = "SELECT * FROM Annunci
                JOIN Libri using(id_libro)";

        $stm = $this->pdo->prepare($dql);
        $stm->execute($param);
        return $stm->fetchAll(PDO::FETCH_ASSOC);
    }

    public function selectAnnunciByUtente(array $param=[]) : array{
        $id=$_SESSION['id_utente'];
        $dql = "SELECT * FROM Annunci
        JOIN Libri USING(id_libro)
        WHERE id_creatore = $id";

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

    public function insertRecord(array $param) : bool{
        $dml="INSERT INTO Annunci(`prezzo`,`data`,`ora`,`luogo`,`id_creatore`,`id_libro`,`condizioni`)
        VALUES(?,?,?,?,?,?,?)";

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
        SET prezzo = ?, ora = ?, luogo = ?, condizioni = ?, id_libro = ?, stato = ?
        WHERE id_annuncio = ?";

        $stm = $this->pdo->prepare($dml);
        $stm->execute($param);

        return $stm->rowCount() !== 0;
    }
}