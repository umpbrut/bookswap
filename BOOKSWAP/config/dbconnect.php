<?php
defined('APP') or die('Accesso negato');
require_once 'dbconfig.php';

class DB{
    public static function connect(){
        try{
            $pdo = new PDO("mysql:host=" . HOST . ";dbname=" . DBNAME . ";charset=" . CHARSET,
                            USERNAME, PASSWORD, 
                            [PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION]
            );
            return $pdo;
            } catch(PDOException $e){
            echo $e->getMessage();
            }
    }
}




