<?php
defined('APP') or die('Accesso Negato');

require_once 'config/dbconnect.php';

// Modello per gestire i dati di login e registrazione degli utenti.
class LoginModel
{
    private $pdo;

    public function __construct()
    {
        $this->pdo = DB::connect();
    }

    // Recupera i dati dell'utente in base all'email.
    // Usato per controllare login e verificare password.
    public function selectEmailPassword($param): array|bool
    {
        $dql = "SELECT `email`, `password`, `nome`, `id_utente` FROM Utenti
        WHERE email=?";

        $stm = $this->pdo->prepare($dql);
        $stm->execute([$param]);

        return $stm->fetch(PDO::FETCH_ASSOC);
    }

    // Restituisce tutte le email registrate, utile per eventuali controlli lato server.
    public function selectEmail(): array
    {
        $dql = "SELECT `email` FROM Utenti;";

        $stm = $this->pdo->prepare($dql);
        $stm->execute();

        return $stm->fetchAll(PDO::FETCH_COLUMN);
    }

    // Inserisce un nuovo utente nel database.
    // La password viene hashata nel controller prima dell'inserimento.
    public function insertRecord(array $param): bool
    {
        $dml = "INSERT INTO Utenti(`nome`,`cognome`,`email`,`password`,`num_tel`)
        VALUES(?,?,?,?,?)";

        $stm = $this->pdo->prepare($dml);
        $stm->execute($param);

        return $stm->rowCount() !== 0;
    }
}