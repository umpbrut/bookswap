<?php
defined('APP') or die('Accesso Negato');

require_once 'config/dbconnect.php';

class PreferitiModel{
    private $pdo;

    public function __construct(){
        $this->pdo=DB::connect();
    }

    public function selectAll(array $param=[]) : array{
        $dql = "SELECT Annunci.*, Libri.*, Materie.nome as materia,
                       GROUP_CONCAT(Immagini.link) as immagini
                FROM Preferiti
                JOIN Annunci USING(id_annuncio)
                JOIN Libri USING(id_libro)
                JOIN Materie USING(id_materia)
                LEFT JOIN Immagini USING(id_annuncio)
                WHERE Preferiti.id_utente = ?
                GROUP BY Annunci.id_annuncio";

        $stm = $this->pdo->prepare($dql);
        $stm->execute($param);
        return $stm->fetchAll(PDO::FETCH_ASSOC);
    }

    public function insertRecord(array $param) : bool{
        $dml="INSERT INTO Preferiti(`id_utente`,`id_annuncio`)
        VALUES(?,?)";

        $stm = $this->pdo->prepare($dml);
        $stm->execute($param);

        return $stm->rowCount() !== 0;
    }

    public function exists($id_utente, $id_annuncio) : bool {
        $sql = "SELECT 1 FROM Preferiti WHERE id_utente = ? AND id_annuncio = ?";
        $stm = $this->pdo->prepare($sql);
        $stm->execute([$id_utente, $id_annuncio]);
        return (bool)$stm->fetch();
    }

    public function deleteRecord(array $param) : bool {
        $dml = "DELETE FROM Preferiti WHERE id_utente = ? AND id_annuncio = ?";
        $stm = $this->pdo->prepare($dml);
        $stm->execute($param);
        return $stm->rowCount() !== 0;
    }
}