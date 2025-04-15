<?php

$host = $_ENV['DB_HOST'];
$port = $_ENV['DB_PORT'];
$db   = $_ENV['DB_DATABASE'];
$user = $_ENV['DB_USERNAME'];
$pass = $_ENV['DB_PASSWORD'];


$dsn = "pgsql:host=$host;port=$port;dbname=$db";

try {
    $conn = new PDO($dsn, $user, $pass);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}