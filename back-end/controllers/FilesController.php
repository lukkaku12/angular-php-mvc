<?php

require_once 'common/jwt.php';
require_once 'models/File.php';

class FilesController {
    private $file;
    private $jwtAuth;

    public function __construct() {
        global $conn;
        $this->file = new File($conn);
        $this->jwtAuth = new JwtAuth();  // Crear instancia de JwtAuth
    }

    private function validateJWT() {
        // Obtener el token desde el encabezado Authorization
        $headers = apache_request_headers();
        if (!isset($headers['Authorization'])) {
            echo json_encode(['message' => 'Authorization header is missing']);
            exit();
        }

        $jwt = str_replace("Bearer ", "", $headers['Authorization']);

        if (empty($jwt)) {
            echo json_encode(['message' => 'Token is missing']);
            exit();
        }

        // Usar JwtAuth para validar el token
        $decoded = $this->jwtAuth->verify($jwt);

        if (!$decoded) {
            echo json_encode(['message' => 'Invalid token']);
            exit();
        }

        return $decoded;  // El token es válido, retornamos la decodificación
    }
}