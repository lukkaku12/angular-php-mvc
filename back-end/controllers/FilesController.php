<?php

require_once 'common/jwt.php';
require_once 'models/File.php';
require_once './common/jwt.php';

class FilesController {
    private $file;
    private $jwtAuth;

    public function __construct() {
        // conn es un acoplamiento invisible para poder instanciar file
        global $conn;
        $this->file = new File($conn);
         // no seria ideal para futura programacion si se crea mas clases. idealmente usar DI (Dependency Inyection)
        $this->jwtAuth = new JwtAuth();
    }

    private function validateJWT() {
        $headers = apache_request_headers();
        if (!isset($headers['Authorization'])) {
            http_response_code(401);
            echo json_encode(['message' => 'Authorization header is missing']);
            exit();
        }

        $jwt = str_replace("Bearer ", "", $headers['Authorization']);

        if (empty($jwt)) {
            http_response_code(401);
            echo json_encode(['message' => 'Token is missing']);
            exit();
        }

        $decoded = $this->jwtAuth->verify($jwt);

        if (!$decoded) {
            http_response_code(401);
            echo json_encode(['message' => 'Invalid token']);
            exit();
        }

        return $decoded;
    }

    // Subir archivos
    public function upload() {
        // Opcional: evitar que se impriman errores/warnings en la respuesta JSON
        error_reporting(0);
        ini_set('display_errors', 0);
    
        // Encabezado correcto para JSON
        header('Content-Type: application/json');
    
        // Validar JWT (ajustá según tu implementación)
        $this->validateJWT();
    
        // Obtener ID del proyecto
        $proyectoId = $_POST['proyectoId'] ?? null;
    
        if (!$proyectoId || !isset($_FILES['archivos'])) {
            http_response_code(400);
            echo json_encode(['message' => 'Faltan datos']);
            return;
        }
    
        // Crear directorio si no existe
        $uploadDir = 'uploads/proyecto_' . $proyectoId . '/';
        if (!file_exists($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }
    
        // Tipos permitidos
        $allowed = [
            'application/pdf',
            'image/jpeg',
            'image/png',
            'application/msword',
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document'
        ];
    
        $archivos = $_FILES['archivos'];
    
        // Asegurar formato de arreglo si solo se sube un archivo
        if (!is_array($archivos['name'])) {
            $archivos = [
                'name' => [$archivos['name']],
                'type' => [$archivos['type']],
                'tmp_name' => [$archivos['tmp_name']],
                'error' => [$archivos['error']],
                'size' => [$archivos['size']],
            ];
        }
    
        foreach ($archivos['tmp_name'] as $key => $tmpName) {
            // Validar que el archivo exista
            if (!is_uploaded_file($tmpName)) {
                continue; // o lanzar error si preferís
            }
    
            $name = basename($archivos['name'][$key]);
            $fileType = mime_content_type($tmpName);
    
            if (!in_array($fileType, $allowed)) {
                http_response_code(400);
                echo json_encode(["error" => "Archivo no permitido: $fileType"]);
                return;
            }
    
            // Mover el archivo al destino
            move_uploaded_file($tmpName, $uploadDir . $name);
        }
    
        echo json_encode(['message' => 'Archivos subidos correctamente']);
    }

    // Listar archivos
    public function list() {
        $this->validateJWT();

        $proyectoId = $_GET['proyectoId'] ?? null;
        if (!$proyectoId) {
            http_response_code(400);
            echo json_encode(['message' => 'Falta el ID del proyecto']);
            return;
        }

        $folder = 'uploads/proyecto_' . $proyectoId;
        $files = [];

        if (file_exists($folder)) {
            foreach (scandir($folder) as $file) {
                if (!in_array($file, ['.', '..'])) {
                    $files[] = $file;
                }
            }
        }

        echo json_encode($files);
    }

    // Descargar archivo
    public function download() {
        $this->validateJWT();

        $proyectoId = $_GET['proyectoId'] ?? null;
        $filename = $_GET['filename'] ?? null;

        if (!$proyectoId || !$filename) {
            http_response_code(400);
            echo json_encode(['message' => 'Datos faltantes']);
            return;
        }

        $filePath = 'uploads/proyecto_' . $proyectoId . '/' . $filename;

        if (!file_exists($filePath)) {
            http_response_code(404);
            echo json_encode(['message' => 'Archivo no encontrado']);
            return;
        }

        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header("Content-Disposition: attachment; filename=\"$filename\"");
        readfile($filePath);
        exit;
    }

    // Eliminar archivo
    public function delete() {
        $this->validateJWT();

        $json = json_decode(file_get_contents("php://input"), true);
        $proyectoId = $json['proyectoId'] ?? null;
        $filename = $json['filename'] ?? null;

        if (!$proyectoId || !$filename) {
            http_response_code(400);
            echo json_encode(['message' => 'Datos faltantes']);
            return;
        }

        $filePath = 'uploads/proyecto_' . $proyectoId . '/' . $filename;

        if (!file_exists($filePath)) {
            http_response_code(404);
            echo json_encode(['message' => 'Archivo no encontrado']);
            return;
        }

        unlink($filePath);
        echo json_encode(['message' => 'Archivo eliminado']);
    }
}