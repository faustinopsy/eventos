<?php
namespace Backend\Api\Repositories;

use Backend\Api\Database\Database;
use Backend\Api\Models\Evento;
use PDO;

class EventoRepository {
    private $conn;

    public function __construct() {
        $this->conn = Database::getInstance();
        $this->criarTabelaSeNaoExistir();
    }

    private function criarTabelaSeNaoExistir() {
        $query = "
            CREATE TABLE IF NOT EXISTS eventos (
                evento_id INTEGER PRIMARY KEY AUTOINCREMENT,
                titulo TEXT NOT NULL,
                descricao TEXT,
                datainicial TEXT NOT NULL,
                datafinal TEXT,
                recorrencia TEXT,
                nome TEXT NOT NULL
            )
        ";
        $this->conn->exec($query);
    }

    public function obterTodosEventos() {
        $query = "SELECT * FROM eventos";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function criarEvento(Evento $evento) {
        $query = "INSERT INTO eventos (titulo, descricao, datainicial, datafinal, recorrencia, nome) VALUES (:titulo, :descricao, :datainicial, :datafinal, :recorrencia, :nome)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":titulo", $evento->getTitulo());
        $stmt->bindParam(":descricao", $evento->getDescricao());
        $stmt->bindParam(":datainicial", $evento->getDataInicial());
        $stmt->bindParam(":datafinal", $evento->getDataFinal());
        $stmt->bindParam(":recorrencia", $evento->getRecorrencia());
        $stmt->bindParam(":nome", $evento->getNome());
        return $stmt->execute();
    }

    public function obterEventoPorId($eventoId) {
        $query = "SELECT * FROM eventos WHERE evento_id = :evento_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":evento_id", $eventoId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function atualizarEvento(Evento $evento) {
        $query = "UPDATE eventos SET titulo = :titulo, descricao = :descricao, datainicial = :datainicial, datafinal = :datafinal, recorrencia = :recorrencia, nome = :nome WHERE evento_id = :evento_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":titulo", $evento->getTitulo());
        $stmt->bindParam(":descricao", $evento->getDescricao());
        $stmt->bindParam(":datainicial", $evento->getDataInicial());
        $stmt->bindParam(":datafinal", $evento->getDataFinal());
        $stmt->bindParam(":recorrencia", $evento->getRecorrencia());
        $stmt->bindParam(":nome", $evento->getNome());
        $stmt->bindParam(":evento_id", $evento->getEventoId(), PDO::PARAM_INT);
        return $stmt->execute();
    }

    public function excluirEvento($eventoId) {
        $query = "DELETE FROM eventos WHERE evento_id = :evento_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":evento_id", $eventoId, PDO::PARAM_INT);
        return $stmt->execute();
    }
}
