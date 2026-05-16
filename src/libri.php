<?php
// Endpoint AJAX/JSON utilizzati dal frontend per:
// - cercare libri per titolo  (?get_libri&testo=...)
// - cercare libri per ISBN    (?get_libri_isbn&testo=...)   ← modifica n.4
// - cercare annunci per testo (?cerca_annunci&testo=...)
// Tutte le risposte sono in formato JSON grazie alla funzione sendResponse().

define("APP", 1);
require("config/dbconnect.php");

// Se non viene passato alcun parametro GET, risponde con errore e termina.
if (empty($_GET)) {
    sendResponse("No op specified", 400);
    die("");
}

// Ottiene la connessione PDO al database tramite la classe DB definita in dbconnect.php.
$db = DB::connect();

// ── Ricerca libri per TITOLO ──
// Restituisce tutti i libri, opzionalmente filtrati per titolo con LIKE '%testo%'.
// Usato dall'autocomplete del campo "Titolo" nel form di inserimento e modifica.
if (isset($_GET['get_libri'])) {
    $sql   = "SELECT * FROM Libri";
    $param = [];
    if (isset($_GET['testo'])) {
        // concat('%',?,'%') è il modo sicuro per LIKE con parametri in PDO.
        $sql  .= " WHERE titolo LIKE concat('%',?,'%')";
        $param = [$_GET['testo']];
    }
    $stm = $db->prepare($sql);
    $stm->execute($param);
    $list = $stm->fetchAll(PDO::FETCH_ASSOC);
    sendResponse($list);
}

// ── Ricerca libri per ISBN (modifica n.4) ──
// Restituisce i libri il cui ISBN contiene il testo cercato.
// Usato dall'autocomplete del campo "ISBN" nel form di inserimento:
// l'utente può digitare l'ISBN e selezionare il libro corrispondente,
// che poi popola automaticamente il campo titolo e l'id_libro nascosto.
if (isset($_GET['get_libri_isbn'])) {
    $sql   = "SELECT * FROM Libri";
    $param = [];
    if (isset($_GET['testo'])) {
        // La ricerca è su ISBN con LIKE, quindi trova anche ISBN parziali.
        $sql  .= " WHERE ISBN LIKE concat('%',?,'%')";
        $param = [$_GET['testo']];
    }
    $stm = $db->prepare($sql);
    $stm->execute($param);
    $list = $stm->fetchAll(PDO::FETCH_ASSOC);
    sendResponse($list);
}

// ── Ricerca annunci per titolo ──
// Restituisce gli annunci uniti ai dati del libro (JOIN su id_libro).
// Filtra per titolo se viene passato il parametro "testo".
// Usato dalla ricerca pubblica degli annunci disponibili.
if (isset($_GET['cerca_annunci'])) {
    $sql   = "SELECT * FROM Annunci JOIN Libri USING(id_libro)";
    $param = [];
    if (isset($_GET['testo'])) {
        $sql  .= " WHERE titolo LIKE concat('%',?,'%')";
        $param = [$_GET['testo']];
    }
    $stm = $db->prepare($sql);
    $stm->execute($param);
    $list = $stm->fetchAll(PDO::FETCH_ASSOC);
    sendResponse($list);
}

// ── Helper sendResponse() ──
// Imposta l'header Content-Type corretto in base al tipo richiesto,
// serializza il contenuto (JSON per array/oggetti) e lo invia al client.
// $responseCode: codice HTTP (default 200 = OK, 400 = Bad Request, ecc.)
function sendResponse($message, $responseCode = 200, $type = "json")
{
    switch ($type) {
        case 'json':
            // json_encode converte array PHP in stringa JSON.
            $contentType = "application/json";
            $content     = json_encode($message);
            break;
        case 'html':
            $contentType = "text/html";
            $content     = $message;
            break;
        case 'txt':
        default:
            $contentType = "text/plain";
            $content     = $message;
            break;
    }
    // Invia l'header del tipo di contenuto con charset UTF-8 per supportare caratteri accentati.
    header("Content-type: {$contentType}; charset=UTF-8");
    http_response_code($responseCode);
    echo $content;
}
