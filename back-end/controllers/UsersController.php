<?php

require_once './core/db.php';
require_once './models/User.php';
require_once './common/jwt.php';

class UsersController
{
    private $User;
    private $JwtAuth;
    public function __construct()
    {
        global $conn;
        $this->User = new User($conn);
        $this->JwtAuth = new JwtAuth();
    }

    public function register()
    {
        $data = json_decode(file_get_contents("php://input"), true);

        if (!isset($data['username']) || !isset($data['password'])) {
            echo json_encode([
                'status' => 'error',
                'message' => 'Faltan campos obligatorios (username o password)'
            ]);
            return;
        }

        $username = $data['username'];
        $password = $data['password'];

        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

        $response = $this->User->register($username, $hashedPassword);

        if ($response) {
            echo json_encode([
                'status' => 'success',
                'message' => 'User saved successfully, please login'
            ]);
        } else {
            echo json_encode([
                'status' => 'error',
                'message' => 'Error saving user'
            ]);
        }
    }

    public function login(){

        $data = json_decode(file_get_contents("php://input"), true);

        if (!isset($data['username']) || !isset($data['password'])) {
            echo json_encode([
                'status' => 'error',
                'message' => 'Faltan campos obligatorios (username o password)'
            ]);
            return;
        }

        $username = $data['username'];
        $password = $data['password'];

        $userFromQuery = $this->User->login($username);

        if ($userFromQuery && password_verify($password, $userFromQuery['password'])) {
            $token = $this->JwtAuth->encode([
                'user_id' => $userFromQuery['id'],
                'username' => $userFromQuery['username'],
                'exp' => time() + 3600,
            ]);

            echo json_encode([
                'status' => 'success',
                'response' => $token,
                'user_id' => $userFromQuery['id']
            ]);
        } else {
            echo json_encode([
                'status' => 'error',
                'message' => 'Credenciales incorrectas'
            ]);
        }
    }
}