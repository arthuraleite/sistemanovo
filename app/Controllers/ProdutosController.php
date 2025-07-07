<?php

namespace App\Controllers;

use App\Models\Produto;

class ProdutosController
{
    public function index()
    {
        $model = new Produto();
        $produtos = $model->listarTodos();
        require 'app/Views/produtos/index.php';
    }

    public function modal($id = null)
    {
        $produto = null;
        if ($id) {
            $model = new Produto();
            $produto = $model->buscarPorId($id);
        }
        require 'app/Views/produtos/form_produto.php';
    }

    public function salvar()
    {
        $dados = [
            'id' => $_POST['id'] ?? null,
            'descricao' => $_POST['descricao'],
            'valor' => $_POST['valor']
        ];

        $model = new Produto();
        if ($model->salvar($dados)) {
            $_SESSION['sucesso'] = 'Produto salvo com sucesso.';
        } else {
            $_SESSION['erro'] = 'Erro ao salvar produto.';
        }

        header('Location: /produtos');
        exit;
    }

    public function excluir($id)
    {
        $model = new Produto();
        if ($model->excluir($id)) {
            $_SESSION['sucesso'] = 'Produto excluído com sucesso.';
        } else {
            $_SESSION['erro'] = 'Erro ao excluir produto.';
        }
        header('Location: /produtos');
        exit;
    }
}
