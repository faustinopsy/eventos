<?php
namespace Backend\Api\Models;
class User {
    private $userid;
    private $nome;
    private $email;
    private $senha;

    public function getUsuarioId(){
        return $this->userid;
    }

    public function setUsuarioId($userid): self{
        $this->userid = $userid;

        return $this;
    }

    public function getNome(){
        return $this->nome;
    }

    public function setNome($nome): self{
        $this->nome = $nome;

        return $this;
    }

    public function getEmail(){
        return $this->email;
    }

    public function setEmail($email): self{
        $this->email = $email;

        return $this;
    }

    public function getSenha() {
        return $this->senha;
    }

    public function setSenha($senha): self {
        $this->senha = password_hash($senha, PASSWORD_DEFAULT);
        return $this;
    }
}