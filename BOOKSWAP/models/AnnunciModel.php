<?php
defined('APP') or die('Accesso Negato');
require_once 'config/dbconnect.php';

class AnnunciModel{
    public function selectAll(array $param=[]) : array{
        $pdo = DB::connect();
        $dql = "SELECT * FROM Annunci
                JOIN Libri using(id_libro)";

        $stm = $pdo->prepare($dql);
        $stm->execute($param);
        return $stm->fetchAll(PDO::FETCH_ASSOC);
    }

    public function selectAnnunciByUtente(array $param=[]) : array{
        $pdo = DB::connect();
        $id=$_SESSION['id_utente'];
        $dql = "SELECT * FROM Annunci WHERE id_creatore = $id";

        $stm = $pdo->prepare($dql);
        $stm->execute($param);
        return $stm->fetchAll(PDO::FETCH_ASSOC);
    }

    public function selectAnnuncio(array $param=[]) : array{
        $pdo = DB::connect();
        $dql = "SELECT * FROM Annunci
                JOIN Libri using(id_libro)
                WHERE id_annuncio = ? and id_libro = ?";

        $stm = $pdo->prepare($dql);
        $stm->execute($param);
        return $stm->fetchAll(PDO::FETCH_ASSOC);
    }

    public function selectTitoli(array $param=[]) : array{
        $pdo = DB::connect();
        $dql = "SELECT id_libro,titolo FROM Libri";
        $stm = $pdo->prepare($dql);
        $stm->execute($param);
        return $stm->fetchAll(PDO::FETCH_ASSOC);
    }

    public function insertRecord(array $param) : bool{
        $pdo = DB::connect();
        $dml="INSERT INTO Annunci(`prezzo`,`data`,`ora`,`luogo`,`id_creatore`,`id_libro`,`condizioni`)
        VALUES(?,?,?,?,?,?,?)";
        $stm = $pdo->prepare($dml);
        $stm->execute($param);
        return $stm->rowCount() !== 0;
    }

    public function deleteRecord(array $param) : bool{
        $pdo = DB::connect();
        $dml="DELETE FROM Annunci WHERE id_annuncio = ?";

        $stm = $pdo->prepare($dml);
        $stm->execute($param);

        return $stm->rowCount() !== 0;
    }

    public function updateRecord(array $param) : bool{
        $pdo = DB::connect();
        $dml="UPDATE Annunci
        SET prezzo = ?, ora = ?, luogo = ?, condizioni = ?, id_libro = ?, stato = ?
        WHERE id_annuncio = ?";

        $stm = $pdo->prepare($dml);
        $stm->execute($param);

        return $stm->rowCount() !== 0;
    }
}