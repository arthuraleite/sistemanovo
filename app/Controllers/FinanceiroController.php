<?php

namespace App\Controllers;

use App\Models\Movimentacao;
use App\Models\MovimentacaoRecorrente;
use App\Models\FormaPagamento;
use App\Models\Pedido;

class FinanceiroController
{
    public function index()
    {
        $model = new Movimentacao();
        $recorrentes = (new MovimentacaoRecorrente())->listarTodas();
        $movimentacoes = $model->listarTodas();

        require 'app/Views/financeiro/index.php';
    }

    public function modal($id = null)
    {
        $movimentacao = null;
        $formas = (new FormaPagamento())->listarTodos();
        $pedidos = (new Pedido())->listarTodos();

        if ($id) {
            $movimentacao = (new Movimentacao())->buscarPorId($id);
        }

        require 'app/Views/financeiro/form_movimentacao.php';
    }

    public function salvar()
    {
        $dados = [
            'id' => $_POST['id'] ?? null,
            'tipo' => $_POST['tipo'],
            'valor' => $_POST['valor'],
            'descricao' => $_POST['descricao'],
            'data' => $_POST['data'],
            'forma_pagamento_id' => $_POST['forma_pagamento_id'],
            'pedido_id' => $_POST['pedido_id'] ?? null
        ];

        $model = new Movimentacao();
        $model->salvar($dados);

        $_SESSION['sucesso'] = 'Movimentação salva com sucesso.';
        header('Location: /financeiro');
        exit;
    }

    public function excluir($id)
    {
        (new Movimentacao())->excluir($id);
        $_SESSION['sucesso'] = 'Movimentação excluída com sucesso.';
        header('Location: /financeiro');
        exit;
    }

    public function modalRecorrente($id = null)
    {
        $recorrente = null;
        if ($id) {
            $recorrente = (new MovimentacaoRecorrente())->buscarPorId($id);
        }

        require 'app/Views/financeiro/form_recorrente.php';
    }

    public function salvarRecorrente()
    {
        $dados = [
            'id' => $_POST['id'] ?? null,
            'descricao' => $_POST['descricao'],
            'valor' => $_POST['valor'],
            'tipo' => $_POST['tipo'],
            'data_vencimento' => $_POST['data_vencimento'],
            'frequencia' => $_POST['frequencia'],
            'ativo' => isset($_POST['ativo']) ? 1 : 0
        ];

        (new MovimentacaoRecorrente())->salvar($dados);

        $_SESSION['sucesso'] = 'Recorrente salvo com sucesso.';
        header('Location: /financeiro');
        exit;
    }

    public function excluirRecorrente($id)
    {
        (new MovimentacaoRecorrente())->excluir($id);
        $_SESSION['sucesso'] = 'Recorrente excluído com sucesso.';
        header('Location: /financeiro');
        exit;
    }
}
