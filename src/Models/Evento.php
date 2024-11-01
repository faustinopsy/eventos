<?php
namespace Backend\Api\Models;

class Evento {
    private $eventoId;
    private $eventobaseid;
    private $titulo;
    private $descricao;
    private $datainicial;
    private $datafinal;
    private $horarioinicial;
    private $horariofinal;
    private $recorrencia;
    private $nome;
    private $cor;

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

    public function getDataFinal() {
        return $this->datafinal;
    }
    public function setDataFinal($datafinal) {
        $this->datafinal = $datafinal;
    }

    public function getHorarioInicial() {
        return $this->horarioinicial;
    }
    public function setHorarioInicial($horarioinicial) {
        $this->horarioinicial = $horarioinicial;
    }

    public function getHorarioFinal() {
        return $this->horariofinal;
    }
    public function setHorarioFinal($horariofinal) {
        $this->horariofinal = $horariofinal;
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

    public function getEventoBaseId() {
        return $this->eventobaseid;
    }
    public function setEventoBaseId($eventoBaseId) {
        $this->eventobaseid = $eventoBaseId;
    }

    public function getCor() {
        return $this->cor;
    }
    public function setCor($cor) {
        $this->cor = $cor;
    }
}
