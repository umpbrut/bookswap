<?php
defined('APP') or die('Accesso Negato');

require_once 'config/dbconnect.php';

class ProductsModel{
    public function selectAll(array $param=[]) : array{
        $pdo = DB::connect();
        $dql = "SELECT * FROM st_products";

        $stm = $pdo->prepare($dql);
        $stm->execute($param);

        return $stm->fetchAll(PDO::FETCH_ASSOC);
    }

    public function insertRecord(array $param) : bool{
        $pdo = DB::connect();
        $dml="INSERT INTO st_products(`name`,`brand`,`amount`,`price`,`category_id`)
        VALUES(?,?,?,?,?)";

        $stm = $pdo->prepare($dml);
        $stm->execute($param);

        return $stm->rowCount() !== 0;
    }

    public function selectCategories(array $param=[]) : array{ //se lo passo param prende il valore che ho passato, altrimenti è vuoto
        $pdo = DB::connect();
        $dql = "SELECT `name`,`category_id` FROM st_categories";

        $stm = $pdo->prepare($dql);
        $stm->execute($param);

        return $stm->fetchAll(PDO::FETCH_ASSOC);
    }

    public function deleteRecord(array $param) : bool{
        $pdo = DB::connect();
        $dml = "DELETE FROM st_products WHERE `name` = ?";

        $stm=$pdo->prepare($dml);
        $stm->execute($param);

        return $stm->rowCount() !==0;
    }

    public function updateRecord(array $param) : bool{
        $pdo = DB::connect();
        $dml="UPDATE st_products
        SET name = ?, brand = ?, amount = ?, price = ?, category_id = ?
        WHERE product_id = ?";

        $stm = $pdo->prepare($dml);
        $stm->execute($param);

        return $stm->rowCount() !== 0;
    }
}