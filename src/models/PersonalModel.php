<?php
defined('APP') or die('Accesso Negato');
require_once 'config/dbconnect.php';

class PersonalModel {
    private $pdo;

    public function __construct(){
        $this->pdo=DB::connect();
    }

    public function selectUtente(int $id): ?array {
        $dql = "SELECT nome, cognome, email, num_tel FROM Utenti WHERE id_utente = ?";
        $stm = $this->pdo->prepare($dql);
        $stm->execute([$id]);
        $result = $stm->fetch(PDO::FETCH_ASSOC);
        return $result ?: null;
    }

    public function updateUtente(array $param): bool {
        $dml = "UPDATE Utenti SET nome = ?, cognome = ?, email = ?, num_tel = ? WHERE id_utente = ?";
        $stm = $this->pdo->prepare($dml);
        $stm->execute($param);
        return $stm->rowCount() !== 0;
    }

    public function updatePassword(string $password, int $id): bool {
        $dml = "UPDATE Utenti SET password = ? WHERE id_utente = ?";
        $stm = $this->pdo->prepare($dml);
        $stm->execute([password_hash($password, PASSWORD_DEFAULT), $id]);
        return $stm->rowCount() !== 0;
    }
}