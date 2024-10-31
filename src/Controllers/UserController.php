<?php

namespace Backend\Api\Controllers;

use Backend\Api\Models\User;
use Backend\Api\Repositories\UserRepository;
use Backend\Api\Rotas\Router;

class UserController {
    private $userRepository;

    public function __construct() {
        $this->userRepository = new UserRepository();
    }
    #[Router('/users/nome/{nome:.+}', methods: ['GET'])]
    public function getUserByName($nome) {
        $user = $this->userRepository->getUserByName($nome);
        if ($user) {
            http_response_code(200);
            echo json_encode($user);
        } else {
            http_response_code(404);
            echo json_encode(['status' => false, 'message' => 'Usuário não encontrado']);
        }
    }

      
    #[Router('/users/data/{dataini}/{datafim}', methods: ['GET'])]
    public function getUsersByDateRange($dataini, $datafim) {
        // Verificar se as datas estão no formato YYYY-MM-DD
        if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $dataini) || !preg_match('/^\d{4}-\d{2}-\d{2}$/', $datafim)) {
            http_response_code(400);
            echo json_encode(['status' => false, 'message' => 'Formato de data inválido']);
            return;
        }
    
        http_response_code(200);
        echo json_encode(['dataInicial' => $dataini, 'dataFinal' => $datafim]);
    }
    
        
    #[Router('/users', methods: ['GET'])]
    public function getAllUsers() {
        $users = $this->userRepository->getAllUsers();
        http_response_code(200);
        echo json_encode($users);
    }
    #[Router('/users/{id}', methods: ['GET'])]
    public function getUserById($id) {
        $user = $this->userRepository->getUserById($id);
        if ($user) {
            http_response_code(200);
            echo json_encode($user);
        } else {
            http_response_code(404);
            echo json_encode(['status' => false, 'message' => 'Usuário não encontrado']);
        }
    }
    

    #[Router('/users', methods: ['POST'])]
    public function createUser($data) {
        if($this->userRepository->getUsuarioByEmail($data->email)){
            echo json_encode(['status' => false, 'message' => 'Usuário já existe']);
            exit;
        }
        $user = new User();
        $user->setNome($data->nome);
        $user->setEmail($data->email);
        $user->setSenha($data->senha);
        $createdUser = $this->userRepository->createUser($user);
       if($createdUser){
        http_response_code(201);
        echo json_encode(['status' => true, 'message' => 'Usuário criado']);
       }
    }
    #[Router('/login', methods: ['POST'])]
    public function login($data) {
        if (!isset($data->email, $data->senha)) {
            http_response_code(400);
            echo json_encode(["error" => "Email e senha são necessários para o login."]);
            return;
        }
        $usuario = $this->userRepository->getUsuarioByEmail($data->email);
        if ($usuario && password_verify($data->senha, $usuario['senha'])) {
            unset($usuario['senha']);
            http_response_code(200);
            echo json_encode(["message" => "Login bem-sucedido.",
             "usuario" => [$usuario]]);
        } else {
            http_response_code(401);
            echo json_encode(["error" => "Email ou senha inválidos."]);
        }
    }

    #[Router('/users/{id}', methods: ['PUT'])]
    public function updateUser($id, $data) {
        $userid = $this->userRepository->getUserById($id);
        if ($userid) {
            $user = new User();
            $user->setUsuarioId($id ?? $user->getUsuarioId());
            $user->setNome($data->nome ?? $user->getNome());
            $user->setEmail($data->email ?? $user->getEmail());
            $user->setSenha($data->senha ?? $user->getSenha());
            
            $updatedUser = $this->userRepository->updateUser($user);
            if($updatedUser){
                http_response_code(200);
                echo json_encode(['status' => true, 'message' => 'Usuário atualizado']);
            }
            
        } else {
            http_response_code(404);
            echo json_encode(['status' => false, 'message' => 'Usuário não encontrado']);
        }
    }
    #[Router('/users/{id}', methods: ['DELETE'])]
    public function deleteUser($id) {
        if ($this->userRepository->deleteUser($id)) {
            http_response_code(200);
            echo json_encode(['status' => true, 'message' => 'Usuário excluído']);
        } else {
            http_response_code(404);
            echo json_encode(['status' => false, 'message' => 'Usuário não encontrado']);
        }
    }
    
}
