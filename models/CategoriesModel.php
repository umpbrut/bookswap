<?php
defined('APP') or die('Accesso Negato');

require_once 'config/dbconnect.php';

class CategoriesModel{
    //INSERIMENTO
    public function insertRecord(array $param) : bool{ //restituisce un booleano
        $pdo = DB::connect();
        $dml="INSERT INTO st_categories(`name`,`description`)
        VALUES(?,?)";
        //--------------------------------------------------------
        // $param = [$name,$description];
        $stm = $pdo->prepare($dml);
        $stm->execute($param);
        //--------------------------------------------------------
        return $stm->rowCount() !== 0; //ritorna true se è != da 0
         //restituisce quante righe sono state inserite,modificate...
    }

    //ELIMINAZIONE
    public function deleteRecord($nome) : bool{
        $pdo = DB::connect();
        $dml = "DELETE FROM st_categories WHERE `name` = ?";
        //-------------------------------------------------------
        $stm=$pdo->prepare($dml);
        $stm->execute([$nome]);
        //-------------------------------------------------------
        return $stm->rowCount() !==0;
    }

    //LETTURA
    public function selectAll(array $param=[]) : array{ //se lo passo param prende il valore che ho passato, altrimenti è vuoto
        $pdo = DB::connect();
        $dql = "SELECT * FROM st_categories";
        //------------------------------------
        // $param=[];
        $stm = $pdo->prepare($dql);
        $stm->execute($param);
        //------------------------------------
        return $stm->fetchAll(PDO::FETCH_ASSOC);
    }

    public function selectIds(array $param=[]) : array{ //se lo passo param prende il valore che ho passato, altrimenti è vuoto
        $pdo = DB::connect();
        $dql = "SELECT category_id FROM st_categories";
        //------------------------------------
        // $param=[];
        $stm = $pdo->prepare($dql);
        $stm->execute($param);
        //------------------------------------
        return $stm->fetch(PDO::FETCH_COLUMN);
    }

    public function updateRecord(array $param) : bool{
        $pdo = DB::connect();
        $dml="UPDATE st_categories
        SET name = ?, description = ?
        WHERE category_id = ?";

        $stm = $pdo->prepare($dml);
        $stm->execute($param);

        return $stm->rowCount() !== 0;
    }
}