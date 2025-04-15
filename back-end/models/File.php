<?php

class File {
    private $conn;
    private $uploadDir = './uploads/';

    public function __construct($db) {
        $this->conn = $db;
    }

    // 📌 Subir uno o varios archivos
    public function uploadFiles($projectId, $files) {
        $allowedTypes = ['application/pdf', 'image/jpeg', 'image/png', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'];
        $responses = [];

        foreach ($files['name'] as $key => $name) {
            $tmpName = $files['tmp_name'][$key];
            $type = $files['type'][$key];
            $size = $files['size'][$key];

            if (!in_array($type, $allowedTypes)) {
                $responses[] = ['name' => $name, 'status' => 'error', 'message' => 'Tipo de archivo no permitido'];
                continue;
            }

            $uniqueName = uniqid() . "_" . basename($name);
            $targetPath = $this->uploadDir . $uniqueName;

            if (move_uploaded_file($tmpName, $targetPath)) {
                $sql = "INSERT INTO files (project_id, filename, file_path, mime_type, size) VALUES (:project_id, :filename, :file_path, :mime_type, :size)";
                $stmt = $this->conn->prepare($sql);
                $stmt->execute([
                    ':project_id' => $projectId,
                    ':filename' => $name,
                    ':file_path' => $targetPath,
                    ':mime_type' => $type,
                    ':size' => $size
                ]);

                $responses[] = ['name' => $name, 'status' => 'success'];
            } else {
                $responses[] = ['name' => $name, 'status' => 'error', 'message' => 'Error al mover el archivo'];
            }
        }

        return $responses;
    }

    // 📋 Ver archivos de un proyecto
    public function getFilesByProject($projectId) {
        $sql = "SELECT * FROM files WHERE project_id = :project_id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':project_id' => $projectId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // 📥 Descargar archivo
    public function downloadFile($fileId) {
        $sql = "SELECT * FROM files WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':id' => $fileId]);
        $file = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($file && file_exists($file['file_path'])) {
            header('Content-Description: File Transfer');
            header('Content-Type: ' . $file['mime_type']);
            header('Content-Disposition: attachment; filename="' . $file['filename'] . '"');
            header('Content-Length: ' . filesize($file['file_path']));
            readfile($file['file_path']);
            exit;
        }

        return false;
    }

    // 🗑️ Eliminar archivo
    public function deleteFile($fileId) {
        $sql = "SELECT * FROM files WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':id' => $fileId]);
        $file = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($file) {
            if (file_exists($file['file_path'])) {
                unlink($file['file_path']);
            }

            $sql = "DELETE FROM files WHERE id = :id";
            $stmt = $this->conn->prepare($sql);
            return $stmt->execute([':id' => $fileId]);
        }

        return false;
    }
}