<?php
defined('APP') or die('Accesso Negato');

require_once 'config/dbconnect.php';

class LoginModel{
    public function selectEmailPassword($param) : ?array { 
        $pdo = DB::connect();
        $dql = "SELECT `email`, `password`, `nome`, `id_utente` FROM Utenti WHERE email=?";
        $stm = $pdo->prepare($dql);
        $stm->execute([$param]);
        $result = $stm->fetch(PDO::FETCH_ASSOC);
        return $result ?: null; 
    }

    public function selectEmail() : array{
        $pdo = DB::connect();
        $dql = "SELECT `email` FROM Utenti;";
        $stm = $pdo->prepare($dql);
        $stm->execute();
        return $stm->fetchAll(PDO::FETCH_COLUMN);
    }

    public function insertRecord(array $param) : bool {
    $pdo = DB::connect();
    $dml = "INSERT INTO Utenti (`nome`, `cognome`, `email`, `password`, `num_tel`)
            VALUES (?, ?, ?, ?, ?)";
    $stm = $pdo->prepare($dml);
    $stm->execute($param);

    return $stm->rowCount() !== 0;
    }
}