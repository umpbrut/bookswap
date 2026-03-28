<?php
defined('APP') or die('Accesso Negato');

require_once 'config/dbconnect.php';

class LoginModel{
    public function selectEmailPassword($param) : array{
        $pdo = DB::connect();
        $dql = "SELECT `email`, `password`, `name`, `customer_id` FROM st_customers
        WHERE email=?";

        $stm = $pdo->prepare($dql);
        $stm->execute([$param]);

        return $stm->fetch(PDO::FETCH_ASSOC);
    }

    public function selectEmail() : array{
        $pdo = DB::connect();
        $dql = "SELECT `email` FROM st_customers;";

        $stm = $pdo->prepare($dql);
        $stm->execute();

        return $stm->fetchAll(PDO::FETCH_COLUMN);
    }

    public function insertRecord(array $param) : bool{
        $pdo = DB::connect();
        $dml="INSERT INTO st_customers(`name`,`surname`,`dob`,`gender`,`email`,`address`,`password`)
        VALUES(?,?,?,?,?,?,?)";

        $stm = $pdo->prepare($dml);
        $stm->execute($param);

        return $stm->rowCount() !== 0;
    }
}