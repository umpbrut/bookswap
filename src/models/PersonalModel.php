<?php
defined('APP') or die('Accesso Negato');
require_once 'config/dbconnect.php';

// Modello per gestire i dati del profilo personale dell'utente.
class PersonalModel
{
    private $pdo;

    public function __construct()
    {
        $this->pdo = DB::connect();
    }

    // Recupera i dati dell'utente per la pagina profilo personale.
    public function selectUtente(int $id): ?array
    {
        $dql = "SELECT nome, cognome, email, num_tel FROM Utenti WHERE id_utente = ?";
        $stm = $this->pdo->prepare($dql);
        $stm->execute([$id]);
        $result = $stm->fetch(PDO::FETCH_ASSOC);
        return $result ?: null;
    }

    // Aggiorna nome, cognome, email e telefono dell'utente.
    public function updateUtente(array $param): bool
    {
        $dml = "UPDATE Utenti SET nome = ?, cognome = ?, email = ?, num_tel = ? WHERE id_utente = ?";
        $stm = $this->pdo->prepare($dml);
        $stm->execute($param);
        return $stm->rowCount() !== 0;
    }

    // Aggiorna la password dell'utente con hashing sicuro.
    public function updatePassword(string $password, int $id): bool
    {
        $dml = "UPDATE Utenti SET password = ? WHERE id_utente = ?";
        $stm = $this->pdo->prepare($dml);
        $stm->execute([password_hash($password, PASSWORD_DEFAULT), $id]);
        return $stm->rowCount() !== 0;
    }
}