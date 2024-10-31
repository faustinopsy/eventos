<?php
namespace Backend\Api\Controllers;

use Backend\Api\Models\Event;
use Backend\Api\Repositories\EventoRepository;
use Backend\Api\Rotas\Router;

class EventoController {
    private $eventoRepository;

    public function __construct() {
        $this->eventoRepository = new EventoRepository();
    }

    #[Router('/eventos', methods: ['GET'])]
    public function obterTodosEventos() {
        $eventos = $this->eventoRepository->obterTodosEventos();
        http_response_code(200);
        echo json_encode($eventos);
    }

    #[Router('/eventos', methods: ['POST'])]
    public function criarEvento($dados) {
        $evento = new Event();
        $evento->setTitulo($dados->titulo)
               ->setDescricao($dados->descricao)
               ->setDataInicial($dados->datainicial)
               ->setDataFinal($dados->datafinal)
               ->setRecorrencia($dados->recorrencia)
               ->setNome($dados->nome);
        $eventoCriado = $this->eventoRepository->criarEvento($evento);
        http_response_code(201);
        echo json_encode(['status' => $eventoCriado]);
    }

    #[Router('/eventos/{id}', methods: ['PUT'])]
    public function atualizarEvento($id, $dados) {
        $eventoExistente = $this->eventoRepository->obterEventoPorId($id);
        if ($eventoExistente) {
            $evento = new Event();
            $evento->setEventoId($id)
                   ->setTitulo($dados->titulo ?? $eventoExistente['titulo'])
                   ->setDescricao($dados->descricao ?? $eventoExistente['descricao'])
                   ->setDataInicial($dados->datainicial ?? $eventoExistente['datainicial'])
                   ->setDataFinal($dados->datafinal ?? $eventoExistente['datafinal'])
                   ->setRecorrencia($dados->recorrencia ?? $eventoExistente['recorrencia'])
                   ->setNome($dados->nome ?? $eventoExistente['nome']);
            
            $eventoAtualizado = $this->eventoRepository->atualizarEvento($evento);
            if ($eventoAtualizado) {
                http_response_code(200);
                echo json_encode(['status' => true, 'mensagem' => 'Evento atualizado com sucesso']);
            }
        } else {
            http_response_code(404);
            echo json_encode(['status' => false, 'mensagem' => 'Evento não encontrado']);
        }
    }

    #[Router('/eventos/{id}', methods: ['DELETE'])]
    public function excluirEvento($id) {
        if ($this->eventoRepository->excluirEvento($id)) {
            http_response_code(200);
            echo json_encode(['status' => true, 'mensagem' => 'Evento excluído com sucesso']);
        } else {
            http_response_code(404);
            echo json_encode(['status' => false, 'mensagem' => 'Evento não encontrado']);
        }
    }
}
