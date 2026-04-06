<?php
defined('APP') or die('Accesso Negato');

require_once 'config/dbconnect.php';

class AnnunciModel{
    public function selectAll(array $param=[]) : array{
        $pdo = DB::connect();
        $dql = "SELECT * FROM Annunci";

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
}