<?php

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