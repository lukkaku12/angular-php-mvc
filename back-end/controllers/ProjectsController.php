<?php

require_once './core/db.php';
require_once './models/Project.php';
require_once './common/jwt.php';

class ProjectsController
{
    private $project;
    private $jwtAuth;

    public function __construct()
    {
        global $conn;
        $this->project = new Project($conn);
        $this->jwtAuth = new JwtAuth();
    }

    private function validateJWT()
    {
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

    // 🟢 Crear proyecto (POST)
    public function createProject()
    {
        // Validar JWT
        $this->validateJWT();

        $data = json_decode(file_get_contents("php://input"), true);

        $title = $data['titulo'];
        $description = $data['descripcion'];
        $startDate = $data['fecha_inicio'];
        $endDate = $data['fecha_entrega'];
        $status = $data['estado'];
        $userId = $data['user_id'];

        $result = $this->project->create($title, $description, $startDate, $endDate, $status, $userId);

        echo json_encode(['success' => $result]);
    }

    // 📋 Ver todos los proyectos de un usuario (GET)
    public function getUserProjects()
    {
        // Validar JWT
        $this->validateJWT();


        $userId = $_GET['id'];

        $projects = $this->project->getByUser($userId);
        echo json_encode($projects);
    }

    // 🔍 Ver detalle de un proyecto (GET /id)
    public function getProject()
    {
        // Validar JWT
        $this->validateJWT();

        $projectId = $_GET['id'];


        $project = $this->project->getById($projectId);
        echo json_encode($project);
    }

    // ✏️ Editar proyecto (PUT)
    public function updateProject()
    {
        // Validar JWT
        $this->validateJWT();

        $data = json_decode(file_get_contents("php://input"), true);

        $projectId = $_GET['id'];

        $title = $data['titulo'];
        $description = $data['descripcion'];
        $startDate = $data['fecha_inicio'];
        $endDate = $data['fecha_entrega'];
        $status = $data['estado'];

        $result = $this->project->update($projectId, $title, $description, $startDate, $endDate, $status);

        echo json_encode(['success' => $result]);
    }

    // 🗑️ Eliminar proyecto (DELETE)
    public function deleteProject()
    {
        // Validar JWT
        $this->validateJWT();

        $projectId = $_GET['id'] ?? null;

        if (!$projectId) {
            http_response_code(400);
            echo json_encode(['error' => 'ID de proyecto no proporcionado']);
            return;
        }

        $result = $this->project->delete($projectId);
        echo json_encode(['success' => $result]);
    }
}