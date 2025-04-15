<?php

require_once './core/db.php';
require_once './models/User.php';

class UsersController {
    private $User;
    private $JwtAuth;
    public function __construct() {
        global $conn;
        $this->User = new User($conn);
        $this->JwtAuth = new JwtAuth();
    }

    public function register() {
        $username = $_POST['username'];
        $password = $_POST['password'];
    
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
    
        $response = $this->User->register($username, $hashedPassword);
    
        if ($response) {
            echo json_encode(array(
                'status' => 'success',
                'message' => 'User saved successfully, please login'
            ));
        } else {
            echo json_encode(array(
                'status' => 'error',
                'message' => 'Error saving user'
            ));
        }
    }

    public function login() {
        $username = $_POST['username'];
        $password = $_POST['password'];

        $userFromQuery = $this->User->login($username);

        if ($userFromQuery && password_verify($password, $userFromQuery['password'])) {
            
            $token = $this->JwtAuth->encode([
                'user_id' => $userFromQuery['user_id'],
                'username' => $userFromQuery['username'],
                'exp' => time() + 3600, 
            ]);

            return json_encode([
                'status'=> 'success',
                'response'=> $token
            ]);

        } else {
            echo "Credenciales incorrectas";
        }
    }
}