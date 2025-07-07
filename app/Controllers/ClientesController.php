<?php

namespace App\Controllers;

use App\Models\Cliente;

class ClientesController
{
    public function index()
    {
        $model = new Cliente();
        $clientes = $model->listarTodos();
        require 'app/Views/clientes/index.php';
    }

    public function modal($id = null)
    {
        $cliente = null;
        if ($id) {
            $model = new Cliente();
            $cliente = $model->buscarPorId($id);
        }
        require 'app/Views/clientes/form_cliente.php';
    }

    public function salvar()
    {
        $dados = [
            'id' => $_POST['id'] ?? null,
            'nome' => $_POST['nome'],
            'telefone' => $_POST['telefone'],
            'email' => $_POST['email'],
            'cpf_cnpj' => $_POST['cpf_cnpj'],
            'inscricao_estadual' => $_POST['inscricao_estadual'] ?? null,
            'inscricao_municipal' => $_POST['inscricao_municipal'] ?? null,
            'responsavel_nome' => $_POST['responsavel_nome'] ?? null,
            'responsavel_contato' => $_POST['responsavel_contato'] ?? null,
            'cep' => $_POST['cep'] ?? null,
            'logradouro' => $_POST['logradouro'] ?? null,
            'numero' => $_POST['numero'] ?? null,
            'bairro' => $_POST['bairro'] ?? null,
            'cidade' => $_POST['cidade'] ?? null,
            'estado' => $_POST['estado'] ?? null,
            'observacao' => $_POST['observacao'] ?? null,
        ];

        $model = new Cliente();

        if ($model->existeCpfCnpj($dados['cpf_cnpj'], $dados['id'])) {
            $_SESSION['erro'] = 'CPF ou CNPJ já cadastrado.';
            header('Location: /clientes');
            exit;
        }

        if ($model->salvar($dados)) {
            $_SESSION['sucesso'] = 'Cliente salvo com sucesso.';
        } else {
            $_SESSION['erro'] = 'Erro ao salvar cliente.';
        }

        header('Location: /clientes');
        exit;
    }

    public function excluir($id)
    {
        $model = new Cliente();
        if ($model->excluir($id)) {
            $_SESSION['sucesso'] = 'Cliente excluído com sucesso.';
        } else {
            $_SESSION['erro'] = 'Erro ao excluir cliente.';
        }
        header('Location: /clientes');
        exit;
    }
}
