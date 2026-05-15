<?php
// Endpoint AJAX/JSON utilizzati dal frontend per:
// - ottenere la lista dei libri (`?get_libri=1&testo=...`)
// - cercare annunci legati a un testo (`?cerca_annunci=1&testo=...`)
// Risponde con JSON e usa la funzione sendResponse() per formattare l'output.

define("APP", 1);
require("config/dbconnect.php");

if (empty($_GET)) {
  sendResponse("No op specified", 400);
  die("");
}

$db = DB::connect();

// Restituisce i libri (opzionalmente filtrati per testo nel titolo)
if (isset($_GET['get_libri'])) {
  $sql = "SELECT * FROM Libri";
  $param = [];
  if (isset($_GET['testo'])) {
    $sql .= " WHERE titolo LIKE concat('%',?,'%')";
    $param = [$_GET['testo']];
  }

  $stm = $db->prepare($sql);
  $stm->execute($param);
  $list = $stm->fetchAll(PDO::FETCH_ASSOC);
  sendResponse($list);
}

// Ricerca semplificata di annunci via titolo libro
if (isset($_GET['cerca_annunci'])) {
  $sql = "SELECT * FROM Annunci JOIN Libri USING(id_libro)";
  $param = [];
  if (isset($_GET['testo'])) {
    $sql .= " WHERE titolo LIKE concat('%',?,'%')";
    $param = [$_GET['testo']];
  }

  $stm = $db->prepare($sql);
  $stm->execute($param);
  $list = $stm->fetchAll(PDO::FETCH_ASSOC);
  sendResponse($list);
}

// Helper per inviare risposte HTTP con il giusto Content-Type.
function sendResponse($message, $responseCode = 200, $type = "json")
{
  switch ($type) {
    case 'json':
      $contentType = "application/json";
      $content = json_encode($message);
      break;

    case 'html':
      $contentType = "text/html";
      $content = $message;
      break;

    case 'txt':
    default:
      $contentType = "text/plain";
      $content = $message;
      break;
  }
  header("Content-type: {$contentType}; charset=UTF-8");
  http_response_code($responseCode);
  echo $content;
}