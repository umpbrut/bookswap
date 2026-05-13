<?php
defined('APP') or die('Accesso Negato');
require_once 'config/dbconnect.php';

class LoginModel{
<<<<<<<< HEAD:BOOKSWAP/src/models/LoginModel.php
    private $pdo;

    public function __construct(){
        $this->pdo=DB::connect();
    }

    public function selectEmailPassword($param) : array|bool{
        $dql = "SELECT `email`, `password`, `nome`, `id_utente` FROM Utenti
        WHERE email=?";

        $stm = $this->pdo->prepare($dql);
========
    public function selectEmailPassword($param) : ?array { 
        $pdo = DB::connect();
        $dql = "SELECT `email`, `password`, `nome`, `id_utente` FROM Utenti WHERE email=?";
        $stm = $pdo->prepare($dql);
>>>>>>>> 623e581bd84429521189d0216fdd07e0b50a79bc:BOOKSWAP/models/LoginModel.php
        $stm->execute([$param]);
        $result = $stm->fetch(PDO::FETCH_ASSOC);
        return $result ?: null; 
    }

    public function selectEmail() : array{
        $dql = "SELECT `email` FROM Utenti;";
<<<<<<<< HEAD:BOOKSWAP/src/models/LoginModel.php

        $stm = $this->pdo->prepare($dql);
========
        $stm = $pdo->prepare($dql);
>>>>>>>> 623e581bd84429521189d0216fdd07e0b50a79bc:BOOKSWAP/models/LoginModel.php
        $stm->execute();
        return $stm->fetchAll(PDO::FETCH_COLUMN);
    }

<<<<<<<< HEAD:BOOKSWAP/src/models/LoginModel.php
    public function insertRecord(array $param) : bool{
        $dml="INSERT INTO Utenti(`nome`,`cognome`,`email`,`password`,`num_tel`)
        VALUES(?,?,?,?,?)";

        $stm = $this->pdo->prepare($dml);
        $stm->execute($param);

        return $stm->rowCount() !== 0;
========
    public function insertRecord(array $param) : bool {
    $pdo = DB::connect();
    $dml = "INSERT INTO Utenti (`nome`, `cognome`, `email`, `password`, `num_tel`)
            VALUES (?, ?, ?, ?, ?)";
    $stm = $pdo->prepare($dml);
    $stm->execute($param);

    return $stm->rowCount() !== 0;
>>>>>>>> 623e581bd84429521189d0216fdd07e0b50a79bc:BOOKSWAP/models/LoginModel.php
    }
}