<?php
defined('APP') or die('Accesso Negato');

require_once 'config/dbconnect.php';

class PreferitiModel{
    public function selectAll(array $param=[]) : array{
        $pdo = DB::connect();
        $dql = "SELECT * FROM Preferiti
                JOIN Annunci using(id_annuncio)
                WHERE id_utente = ?";

        $stm = $pdo->prepare($dql);
        $stm->execute($param);
        return $stm->fetchAll(PDO::FETCH_ASSOC);
    }

    public function insertRecord(array $param) : bool{
        $pdo = DB::connect();
        $dml="INSERT INTO Preferiti(`id_utente`,`id_annuncio`)
        VALUES(?,?)";

        $stm = $pdo->prepare($dml);
        $stm->execute($param);

        return $stm->rowCount() !== 0;
    }
}