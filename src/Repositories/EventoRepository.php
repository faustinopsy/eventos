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
                horarioinicial TEXT,
                horariofinal TEXT,
                recorrencia TEXT,
                nome TEXT NOT NULL,
                evento_base_id TEXT,
                cor TEXT
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
        $query = "INSERT INTO eventos (evento_base_id, titulo, descricao, datainicial, datafinal, horarioinicial, horariofinal, recorrencia, nome, cor)
                  VALUES (:evento_base_id, :titulo, :descricao, :datainicial, :datafinal, :horarioinicial, :horariofinal, :recorrencia, :nome, :cor)";
        $stmt = $this->conn->prepare($query);
    
        $eventoBaseId = $evento->getEventoBaseId();
        $titulo = $evento->getTitulo();
        $descricao = $evento->getDescricao();
        $datainicial = $evento->getDataInicial();
        $datafinal = $evento->getDataFinal();
        $horarioinicial = $evento->getHorarioInicial();
        $horariofinal = $evento->getHorarioFinal();
        $recorrencia = $evento->getRecorrencia();
        $nome = $evento->getNome();
        $cor = $evento->getCor();
    
        $stmt->bindParam(":evento_base_id", $eventoBaseId);
        $stmt->bindParam(":titulo", $titulo);
        $stmt->bindParam(":descricao", $descricao);
        $stmt->bindParam(":datainicial", $datainicial);
        $stmt->bindParam(":datafinal", $datafinal);
        $stmt->bindParam(":horarioinicial", $horarioinicial);
        $stmt->bindParam(":horariofinal", $horariofinal);
        $stmt->bindParam(":recorrencia", $recorrencia);
        $stmt->bindParam(":nome", $nome);
        $stmt->bindParam(":cor", $cor);
    
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
        $query = "UPDATE eventos SET titulo = :titulo, descricao = :descricao, datainicial = :datainicial, datafinal = :datafinal, horarioinicial = :horarioinicial, horariofinal = :horariofinal, recorrencia = :recorrencia, nome = :nome, cor = :cor WHERE evento_id = :evento_id";
        $stmt = $this->conn->prepare($query);

        $titulo = $evento->getTitulo();
        $descricao = $evento->getDescricao();
        $datainicial = $evento->getDataInicial();
        $datafinal = $evento->getDataFinal();
        $horarioinicial = $evento->getHorarioInicial();
        $horariofinal = $evento->getHorarioFinal();
        $recorrencia = $evento->getRecorrencia();
        $nome = $evento->getNome();
        $cor = $evento->getCor();
        $eventoId = $evento->getEventoId();

        $stmt->bindParam(":titulo", $titulo);
        $stmt->bindParam(":descricao", $descricao);
        $stmt->bindParam(":datainicial", $datainicial);
        $stmt->bindParam(":datafinal", $datafinal);
        $stmt->bindParam(":horarioinicial", $horarioinicial);
        $stmt->bindParam(":horariofinal", $horariofinal);
        $stmt->bindParam(":recorrencia", $recorrencia);
        $stmt->bindParam(":nome", $nome);
        $stmt->bindParam(":cor", $cor);
        $stmt->bindParam(":evento_id", $eventoId, PDO::PARAM_INT);
        
        return $stmt->execute();
    }

    public function obterEventoPorNome($nome) {
        $query = "SELECT * FROM eventos WHERE nome LIKE :nome";
        $stmt = $this->conn->prepare($query);
        $nome = $nome . '%';
        $stmt->bindParam(":nome", $nome, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function obterEventosPorIntervaloDeData($dataini, $datafim) {
        $query = "SELECT * FROM eventos WHERE datainicial >= :dataini AND datafinal <= :datafim";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":dataini", $dataini);
        $stmt->bindParam(":datafim", $datafim);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function excluirEventosPorID($id) {
        $query = "DELETE FROM eventos WHERE evento_base_id = :evento_base_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":evento_base_id", $id, PDO::PARAM_STR);
        return $stmt->execute();
    }

    public function excluirEventosPorNome($nome) {
        $query = "DELETE FROM eventos WHERE nome = :nome OR evento_base_id IN (SELECT evento_id FROM eventos WHERE nome = :nome)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":nome", $nome, PDO::PARAM_STR);
        return $stmt->execute();
    }
}
