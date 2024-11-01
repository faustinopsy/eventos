<?php
namespace Backend\Api\Controllers;

use Backend\Api\Models\Evento;
use Backend\Api\Repositories\EventoRepository;
use Backend\Api\Rotas\Router;
use DateTime;
use DateInterval;
use DatePeriod;

class EventoController {
    private $eventoRepository;

    public function __construct() {
        $this->eventoRepository = new EventoRepository();
    }

    private function criarTabelaSeNaoExistir() {
        $query = "
            CREATE TABLE IF NOT EXISTS eventos (
                evento_id INTEGER PRIMARY KEY AUTOINCREMENT,
                evento_base_id INTEGER NULL,
                titulo TEXT NOT NULL,
                descricao TEXT,
                datainicial TEXT NOT NULL,
                datafinal TEXT,
                recorrencia TEXT,
                nome TEXT NOT NULL,
                FOREIGN KEY (evento_base_id) REFERENCES eventos(evento_id)
            )
        ";
        $this->conexao->exec($query);
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
        $numerox = $this->gerarStringAlfanumerica(8);
        
        if ($data->recorrencia === 'nenhuma') {
            $evento = new Evento();
            $evento->setTitulo($data->titulo);
            $evento->setDescricao($data->descricao);
            $evento->setDataInicial($data->datainicial);
            $evento->setDescricao($data->descricao);
            $evento->setHorarioInicial($data->horarioinicial);
            $evento->setHorarioFinal($data->horariofinal);
            $evento->setRecorrencia($data->recorrencia);
            $evento->setNome($data->nome);
            $evento->setCor($data->cor);
            $evento->setEventoBaseId($numerox);
            $eventoCriado = $this->eventoRepository->criarEvento($evento);
        } else {
            $evento = new Evento();
            $evento->setTitulo($data->titulo);
            $evento->setDescricao($data->descricao);
            $evento->setDataInicial($data->datainicial);
            $evento->setDataFinal($data->datafinal);
            $evento->setHorarioInicial($data->horarioinicial);
            $evento->setHorarioFinal($data->horariofinal);
            $evento->setRecorrencia($data->recorrencia);
            $evento->setNome($data->nome);
            $evento->setCor($data->cor);
            $evento->setEventoBaseId($numerox);
            
            $eventoCriado = $this->criarEventosRecorrentes($evento, $numerox);
        }
    
        http_response_code(201);
        echo json_encode(['status' => $eventoCriado]);
    }
    
    private function criarEventosRecorrentes(Evento $evento, $eventoBaseId) {
        $recorrencia = $evento->getRecorrencia();
        $dataInicial = new DateTime($evento->getDataInicial());
        $dataFinalOriginal = new DateTime($evento->getDataFinal());
    
        $intervalo = match($recorrencia) {
            'diaria' => new DateInterval('P1D'),
            'semanal' => new DateInterval('P1W'),
            'mensal' => new DateInterval('P1M'),
            default => null,
        };
    
        if ($intervalo) {
            $periodo = new DatePeriod($dataInicial, $intervalo, $dataFinalOriginal);
            
            foreach ($periodo as $dataRecorrente) {
                if ($recorrencia === 'semanal' && $dataRecorrente->format('N') !== $dataInicial->format('N')) {
                    continue;
                }
    
                $eventoRecorrente = clone $evento;
                $dataRecorrenteFormatada = $dataRecorrente->format('Y-m-d');
                $eventoRecorrente->setDataInicial($dataRecorrenteFormatada);
                $eventoRecorrente->setDataFinal($dataRecorrenteFormatada);
                $eventoRecorrente->setEventoBaseId($eventoBaseId);
    
                if (!$this->eventoRepository->criarEvento($eventoRecorrente)) {
                    return false;
                }
            }
            return true;
        }
    
        return false;
    }
    

    public function gerarStringAlfanumerica($tamanho) {
        $caracteres = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $stringAleatoria = '';
        for ($i = 0; $i < $tamanho; $i++) {
            $index = rand(0, strlen($caracteres) - 1);
            $stringAleatoria .= $caracteres[$index];
        }
        return $stringAleatoria;
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
            $evento->setHorarioInicial($data->horarioinicial  ?? $eventoExistente['horarioinicial']);
            $evento->setHorarioFinal($data->horariofinal  ?? $eventoExistente['horariofinal']);
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

    #[Router('/eventos/forenkey/{nome}', methods: ['DELETE'])]
    public function excluirEventosPorID($nome) {
        $eventosExcluidos = $this->eventoRepository->excluirEventosPorID($nome);
        if ($eventosExcluidos) {
            http_response_code(200);
            echo json_encode(['status' => true, 'message' => 'Evento(s) excluído(s) com sucesso.']);
        } else {
            http_response_code(404);
            echo json_encode(['status' => false, 'message' => 'Evento não encontrado.']);
        }
    }
    #[Router('/eventos/nome/{nome}', methods: ['DELETE'])]
    public function excluirEventosPorNome($nome) {
        $eventosExcluidos = $this->eventoRepository->excluirEventosPorNome($nome);
        if ($eventosExcluidos) {
            http_response_code(200);
            echo json_encode(['status' => true, 'message' => 'Evento(s) excluído(s) com sucesso.']);
        } else {
            http_response_code(404);
            echo json_encode(['status' => false, 'message' => 'Evento não encontrado.']);
        }
    }

}
