<?php

namespace App\Controllers;

use App\Models\Pedido;
use App\Models\Cliente;
use App\Models\FormaPagamento;

class PedidosController
{
    public function index()
    {
        $pedidoModel = new Pedido();
        $pedidos = $pedidoModel->listarTodos();
        require 'app/Views/pedidos/index.php';
    }

    public function modal($id = null)
    {
        $pedido = null;
        $itens = [];
        $clientes = (new Cliente())->listarTodos();

        if ($id) {
            $model = new Pedido();
            $pedido = $model->buscarPorId($id);
            $itens = $model->listarItens($id);
        }

        require 'app/Views/pedidos/form_pedido.php';
    }

    public function salvar()
    {
        $dados = [
            'id' => $_POST['id'] ?? null,
            'cliente_id' => $_POST['cliente_id'],
            'data_pedido' => $_POST['data_pedido'],
            'previsao_entrega' => $_POST['previsao_entrega'],
            'status' => $_POST['status'],
            'observacao' => $_POST['observacao'] ?? null,
        ];

        $itens = [];
        foreach ($_POST['descricao'] as $i => $desc) {
            $itens[] = [
                'descricao' => $desc,
                'valor_unitario' => $_POST['valor_unitario'][$i],
                'quantidade' => $_POST['quantidade'][$i],
                'subtotal' => $_POST['subtotal'][$i]
            ];
        }

        $model = new Pedido();
        $model->salvar($dados, $itens);

        $_SESSION['sucesso'] = 'Pedido salvo com sucesso.';
        header('Location: /pedidos');
        exit;
    }

    public function excluir($id)
    {
        $model = new Pedido();
        $model->excluir($id);
        $_SESSION['sucesso'] = 'Pedido excluído com sucesso.';
        header('Location: /pedidos');
        exit;
    }

    public function pagamentos($id)
    {
        $model = new Pedido();
        $pagamentos = $model->listarPagamentos($id);
        require 'app/Views/pedidos/pagamentos.php';
    }
}
