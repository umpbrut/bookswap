<?php
defined('APP') or die('Accesso Negato');

require_once 'config/dbconnect.php';

class LoginModel{
    private $pdo;

    public function __construct(){
        $this->pdo=DB::connect();
    }

    public function selectEmailPassword($param) : array|bool{
        $dql = "SELECT `email`, `password`, `nome`, `id_utente` FROM Utenti
        WHERE email=?";

        $stm = $this->pdo->prepare($dql);
        $stm->execute([$param]);

        return $stm->fetch(PDO::FETCH_ASSOC);
    }

    public function selectEmail() : array{
        $dql = "SELECT `email` FROM Utenti;";

        $stm = $this->pdo->prepare($dql);
        $stm->execute();

        return $stm->fetchAll(PDO::FETCH_COLUMN);
    }

    public function insertRecord(array $param) : bool{
        $dml="INSERT INTO Utenti(`nome`,`cognome`,`email`,`password`,`num_tel`)
        VALUES(?,?,?,?,?)";

        $stm = $this->pdo->prepare($dml);
        $stm->execute($param);

        return $stm->rowCount() !== 0;
    }
}