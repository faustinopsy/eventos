<?php
namespace Backend\Api\Controllers;

use Backend\Api\Models\Evento;
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

    #[Router('/eventos/{id}', methods: ['GET'])]
    public function obterEventoPorId($id) {
        $evento = $this->eventoRepository->obterEventoPorId($id);
        if ($evento) {
            http_response_code(200);
            echo json_encode($evento);
        } else {
            http_response_code(404);
            echo json_encode(['status' => false, 'message' => 'Evento não encontrado']);
        }
    }

    #[Router('/eventos/nome/{nome:.+}', methods: ['GET'])]
    public function obterEventoPorNome($nome) {
        $evento = $this->eventoRepository->obterEventoPorNome($nome);
        if ($evento) {
            http_response_code(200);
            echo json_encode($evento);
        } else {
            http_response_code(404);
            echo json_encode(['status' => false, 'message' => 'Evento não encontrado']);
        }
    }

    #[Router('/eventos/data/{dataini}/{datafim}', methods: ['GET'])]
    public function obterEventosPorIntervaloDeData($dataini, $datafim) {
        if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $dataini) || !preg_match('/^\d{4}-\d{2}-\d{2}$/', $datafim)) {
            http_response_code(400);
            echo json_encode(['status' => false, 'message' => 'Formato de data inválido']);
            return;
        }
        
        $eventos = $this->eventoRepository->obterEventosPorIntervaloDeData($dataini, $datafim);
        if ($eventos) {
            http_response_code(200);
            echo json_encode($eventos);
        } else {
            http_response_code(404);
            echo json_encode(['status' => false, 'message' => 'Nenhum evento encontrado para o intervalo de datas especificado']);
        }
    }
    
    #[Router('/eventos', methods: ['POST'])]
    public function criarEvento($data) {
        $evento = new Evento();
        $evento->setTitulo($data->titulo);
        $evento->setDescricao($data->descricao);
        $evento->setDataInicial($data->datainicial);
        $evento->setDataFinal($data->datafinal);
        $evento->setRecorrencia($data->recorrencia);
        $evento->setNome($data->nome);
        $eventoCriado = $this->eventoRepository->criarEvento($evento);
        http_response_code(201);
        echo json_encode(['status' => $eventoCriado]);
    }

    #[Router('/eventos/{id}', methods: ['PUT'])]
    public function atualizarEvento($id, $data) {
        $eventoExistente = $this->eventoRepository->obterEventoPorId($id);
        if ($eventoExistente) {
            $evento = new Evento();
            $evento->setEventoId($id);
            $evento->setTitulo($data->titulo ?? $eventoExistente['titulo']);
            $evento->setDescricao($data->descricao ?? $eventoExistente['descricao']);
            $evento->setDataInicial($data->datainicial ?? $eventoExistente['datainicial']);
            $evento->setDataFinal($data->datafinal ?? $eventoExistente['datafinal']);
            $evento->setRecorrencia($data->recorrencia ?? $eventoExistente['recorrencia']);
            $evento->setNome($data->nome ?? $eventoExistente['nome']);
            
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
