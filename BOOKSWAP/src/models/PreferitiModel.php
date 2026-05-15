<?php
defined('APP') or die('Accesso Negato');

require_once 'config/dbconnect.php';

// Modello che gestisce i preferiti: lettura, inserimento, verifica ed eliminazione.
class PreferitiModel
{
    private $pdo;

    public function __construct()
    {
        $this->pdo = DB::connect();
    }

    // Restituisce gli annunci salvati nei preferiti dall'utente autenticato.
    public function selectAll(array $param = []): array
    {
        // Passiamo per Libri_Associazioni per recuperare la materia del libro
        // DISTINCT evita duplicati, ORDER BY id_immagine mantiene l'ordine di inserimento
        $dql = "SELECT Annunci.*, Libri.*, Materie.nome as materia,
                       GROUP_CONCAT(DISTINCT Immagini.link ORDER BY Immagini.id_immagine) as immagini
                FROM Preferiti
                JOIN Annunci USING(id_annuncio)
                JOIN Libri USING(id_libro)
                JOIN Libri_Associazioni ON Libri_Associazioni.id_libro = Libri.id_libro
                JOIN Materie ON Materie.id_materia = Libri_Associazioni.id_materia
                LEFT JOIN Immagini USING(id_annuncio)
                WHERE Preferiti.id_utente = ?
                GROUP BY Annunci.id_annuncio";

        $stm = $this->pdo->prepare($dql);
        $stm->execute($param);
        return $stm->fetchAll(PDO::FETCH_ASSOC);
    }

    // Inserisce un annuncio nei preferiti dell'utente.
    public function insertRecord(array $param): bool
    {
        $dml = "INSERT INTO Preferiti(`id_utente`,`id_annuncio`)
        VALUES(?,?)";

        $stm = $this->pdo->prepare($dml);
        $stm->execute($param);

        return $stm->rowCount() !== 0;
    }

    // Controlla se l'annuncio è già presente nei preferiti dell'utente.
    public function exists($id_utente, $id_annuncio): bool
    {
        $sql = "SELECT 1 FROM Preferiti WHERE id_utente = ? AND id_annuncio = ?";
        $stm = $this->pdo->prepare($sql);
        $stm->execute([$id_utente, $id_annuncio]);
        return (bool) $stm->fetch();
    }

    // Elimina l'annuncio dai preferiti dell'utente.
    public function deleteRecord(array $param): bool
    {
        $dml = "DELETE FROM Preferiti WHERE id_utente = ? AND id_annuncio = ?";
        $stm = $this->pdo->prepare($dml);
        $stm->execute($param);
        return $stm->rowCount() !== 0;
    }
}
