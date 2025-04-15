<?php

class Project {
    private $conn;

    public function __construct($dbConnection) {
        $this->conn = $dbConnection;
    }

    public function create($titulo, $descripcion, $fecha_inicio, $fecha_entrega, $estado, $user_id) {
        $sql = "INSERT INTO projects (titulo, descripcion, fecha_inicio, fecha_entrega, estado, user_id) 
                VALUES (:titulo, :descripcion, :fecha_inicio, :fecha_entrega, :estado, :user_id)";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            ':titulo' => $titulo,
            ':descripcion' => $descripcion,
            ':fecha_inicio' => $fecha_inicio,
            ':fecha_entrega' => $fecha_entrega,
            ':estado' => $estado,
            ':user_id' => $user_id
        ]);
    }

    public function getByUser($user_id) {
        $sql = "SELECT * FROM projects WHERE user_id = :user_id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':user_id' => $user_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById($project_id) {
        $sql = "SELECT * FROM projects WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':id' => $project_id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function update($id, $titulo, $descripcion, $fecha_inicio, $fecha_entrega, $estado) {
        $sql = "UPDATE projects 
                SET titulo = :titulo, descripcion = :descripcion, fecha_inicio = :fecha_inicio, 
                    fecha_entrega = :fecha_entrega, estado = :estado 
                WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            ':id' => $id,
            ':titulo' => $titulo,
            ':descripcion' => $descripcion,
            ':fecha_inicio' => $fecha_inicio,
            ':fecha_entrega' => $fecha_entrega,
            ':estado' => $estado
        ]);
    }

    public function delete($id) {
        $sql = "DELETE FROM projects WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }
}