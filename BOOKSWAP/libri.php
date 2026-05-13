<?php
define("APP", 1);
require_once "config/dbconnect.php";

$db = DB::connect();

if(isset($_GET['get_libri'])){
    $testo = $_GET['testo'] ?? '';
    $sql = "SELECT id_libro, titolo FROM Libri WHERE titolo LIKE concat('%', ?, '%') LIMIT 10";
    $stm = $db->prepare($sql);
    $stm->execute([$testo]);
    $list = $stm->fetchAll(PDO::FETCH_ASSOC);
    
    header("Content-type: application/json");
    echo json_encode($list);
    exit;
}