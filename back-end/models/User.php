<?php

class User {
    private $conn;

    public function __construct($dbconn) {
        $this->conn = $dbconn;
    }

    public function login($username) {
        $sql = "SELECT * FROM users WHERE username = :username";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':username', $username);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        return $user;
    }

    public function register($username, $password):bool {
        $sql = "INSERT INTO users (username, password) VALUES (:username, :password)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(":username", $username, PDO::PARAM_STR);
        $stmt->bindParam(":password", $password, PDO::PARAM_STR);
        return $stmt->execute();
    }
}