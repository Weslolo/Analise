<?php
namespace Models {

    class Usuario
    {
        public $id;
        public $nome;
        public $endereco;

        public $telefone;

        function __construct($id, $nome, $endereco, $telefone = null)
        {
            $this->id = $id;
            $this->nome = $nome;
            $this->endereco = $endereco;
            $this->telefone = $telefone;
        }
    }
}