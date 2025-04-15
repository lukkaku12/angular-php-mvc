<?php

// Cargar el autoload de Composer
require_once './vendor/autoload.php';

use Firebase\JWT\JWT;
use Firebase\JWT\Key;


class JwtAuth {
    private $secret_key;

    public function __construct() {
        $this->secret_key = 'supersecreto123';
    }

    public function verify($token) {
        try {
            $decoded = JWT::decode($token, new Key($this->secret_key, 'HS256'));
            return $decoded;
        } catch (Exception $e) {
            echo 'Error al decodificar el token: ' . $e->getMessage();
            return null;
        }
    }

    public function encode($payload) {
        try {
            $jwt = JWT::encode($payload, $this->secret_key, 'HS256');
            return $jwt;
        } catch (Exception $e) {
            echo 'Error al decodificar el token: ' . $e->getMessage();
        }

    }
    // Datos a incluir en el payload
    // $payload = [
    // 'user_id' => 123,
    // 'username' => 'juanito',
    // 'exp' => time() + 3600,    
    // ];
}



