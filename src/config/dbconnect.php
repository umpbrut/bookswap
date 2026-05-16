<?php
// Simple PDO connection wrapper.
// - Include `dbconfig.php` which defines HOST, DBNAME, CHARSET, USERNAME, PASSWORD
// - Use `DB::connect()` to obtain a PDO instance with exceptions enabled

defined('APP') or die('Accesso Negato');

require_once 'dbconfig.php';

class DB{
    public static function connect(){
        try{
            // Crea e ritorna l'oggetto PDO configurato
            $pdo = new PDO(
                "mysql:host=" . HOST . ";dbname=" . DBNAME . ";charset=" . CHARSET,
                USERNAME, PASSWORD,
                [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
            );
            return $pdo;
        } catch(PDOException $e) {
            // In ambiente di sviluppo può essere utile loggare l'errore;
            // in produzione preferire un messaggio generico o logging sicuro.
            echo $e->getMessage();
        }
    }
}