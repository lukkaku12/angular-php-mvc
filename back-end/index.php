<?php

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS, DELETE, PUT");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

require_once './core/db.php';

session_start();


$c = "";
$m = "";

if (isset($_REQUEST["c"]) and isset($_REQUEST["m"])) {
    $c = $_REQUEST["c"] . "Controller";
    $m = $_REQUEST["m"];

    if (file_exists("controllers/$c.php")) {
        require "controllers/$c.php";
        $c = new $c;
        call_user_func([$c, $m]);
    } else {
        print "el archivo $c no existe";
    }
    
}