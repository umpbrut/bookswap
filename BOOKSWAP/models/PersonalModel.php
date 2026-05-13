<?php
defined('APP') or die('Accesso Negato');
require_once 'config/dbconnect.php';

class PersonalModel {

    public function selectUtente(int $id): ?array {
        $pdo = DB::connect();
        $dql = "SELECT nome, cognome, email, num_tel FROM Utenti WHERE id_utente = ?";
        $stm = $pdo->prepare($dql);
        $stm->execute([$id]);
        $result = $stm->fetch(PDO::FETCH_ASSOC);
        return $result ?: null;
    }

    public function updateUtente(array $param): bool {
        $pdo = DB::connect();
        $dml = "UPDATE Utenti SET nome = ?, cognome = ?, email = ?, num_tel = ? WHERE id_utente = ?";
        $stm = $pdo->prepare($dml);
        $stm->execute($param);
        return $stm->rowCount() !== 0;
    }

    public function updatePassword(string $password, int $id): bool {
        $pdo = DB::connect();
        $dml = "UPDATE Utenti SET password = ? WHERE id_utente = ?";
        $stm = $pdo->prepare($dml);
        $stm->execute([password_hash($password, PASSWORD_DEFAULT), $id]);
        return $stm->rowCount() !== 0;
    }
}
