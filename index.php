<?php
session_start();
define('APP',true);

if(isset($_SESSION['customer_id'])){
    $page=$_GET['page'] ?? 'categories';
    $action=$_GET['action'] ?? 'index';
    $filename=ucfirst($page).'Controller';
}
else{
    $page='login';
    $action=$_GET['action'] ?? 'login';
    $filename=ucfirst($page).'Controller';
}

    require_once "controllers/$filename.php";
    $controller = new $filename();

    if(method_exists($controller,$action)){
        $controller->$action();
    }