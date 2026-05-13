<?php
define('APP', true);
require_once 'config/dbconnect.php';

$q = trim($_GET['q'] ?? '');

if (empty($q)) exit;

$pdo = DB::connect();

$sql = "SELECT a.id_annuncio, l.titolo, l.autore, a.prezzo, a.luogo
        FROM Annunci a
        JOIN Libri l USING(id_libro)
        WHERE l.titolo LIKE ? OR l.autore LIKE ?
        LIMIT 8";

$stm = $pdo->prepare($sql);
$like = '%' . $q . '%';
$stm->execute([$like, $like]);
$results = $stm->fetchAll(PDO::FETCH_ASSOC);

if (empty($results)) {
    echo "<div style='padding:14px 16px; font-size:13px; color:#999; font-family:Georgia,serif;'>Nessun risultato per \"" . htmlspecialchars($q) . "\"</div>";
    exit;
}

foreach ($results as $r) {
    echo "
    <a href='index.php?page=annunci&action=index' style='
        display:block;
        padding:10px 16px;
        text-decoration:none;
        color:#333;
        font-family:Georgia,serif;
        font-size:13px;
        border-bottom:1px solid #f0e8d8;
        transition: background 0.2s;
    ' onmouseover=\"this.style.background='#f3e7d3'\" onmouseout=\"this.style.background=''\">
        <strong>" . htmlspecialchars($r['titolo']) . "</strong><br>
        <span style='color:#888; font-size:11px;'>" . htmlspecialchars($r['autore'] ?? '') . " · " . number_format($r['prezzo'], 2, ',', '.') . " € · " . htmlspecialchars($r['luogo']) . "</span>
    </a>";
}
?>