<?php
namespace Backend\Api\Models;

class Event {
    private $eventoId;
    private $titulo;
    private $descricao;
    private $datainicial;
    private $datafinal;
    private $recorrencia;
    private $nome;

    public function getEventoId() {
        return $this->eventoId;
    }
    public function setEventoId($eventoId) {
         $this->eventoId = $eventoId;
        }

    public function getTitulo() {
         return $this->titulo;
        }
    public function setTitulo($titulo) {
         $this->titulo = $titulo;
        }

    public function getDescricao() {
         return $this->descricao;
        }
    public function setDescricao($descricao) {
         $this->descricao = $descricao;
        }

    public function getDataInicial() {
         return $this->datainicial;
        }
    public function setDataInicial($datainicial) {
         $this->datainicial = $datainicial;
        }

    public function getDataFial() {
         return $this->datafinal;
    }
    public function setDataFInal($datafinal) {
         $this->datafinal = $datafinal;
        }

    public function getRecorrencia() {
         return $this->recorrencia;
        }
    public function setRecorrencia($recorrencia) {
         $this->recorrencia = $recorrencia;
        }

    public function getNome() {
         return $this->nome;
    }
    public function setNome($nome) {
         $this->nome = $nome;
    }
}
