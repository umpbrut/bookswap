<?php
defined('APP') or die('Accesso Negato');

require_once 'config/dbconnect.php';

class AnnunciModel{
    //LETTURA
    public function selectAll(array $param=[]) : array{ //se lo passo param prende il valore che ho passato, altrimenti è vuoto
        $pdo = DB::connect();
        $dql = "SELECT * FROM Annunci";
        //------------------------------------
        // $param=[];
        $stm = $pdo->prepare($dql);
        $stm->execute($param);
        //------------------------------------
        return $stm->fetchAll(PDO::FETCH_ASSOC);
    }
}