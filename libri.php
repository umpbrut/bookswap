<?php
define("APP",1);
require("config/dbconnect.php");

if(empty($_GET)){
    sendResponse("No op specified",400);
    die("");
}

$db=DB::connect();

if(isset($_GET['get_libri'])){
  $sql = "SELECT * FROM Libri";
  $param=[];
  if(isset($_GET['testo'])){
    $sql .= " WHERE titolo LIKE concat('%',?,'%')";
    $param=[$_GET['testo']];
  }

  $stm = $db->prepare($sql);
  $stm->execute($param);
  $list = $stm->fetchAll(PDO::FETCH_ASSOC);
  sendResponse($list);
}

function sendResponse($message, $responseCode=200, $type="json"){
    switch($type){
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