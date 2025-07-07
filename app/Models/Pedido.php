<?php

namespace App\Models;

use App\Core\Database;
use PDO;

class Pedido
{
    private $db;

    public function __construct()
    {
        $this->db = Database::conectar();
    }

    public function listarTodos()
    {
        $sql = "SELECT p.*, c.nome AS cliente 
                FROM pedidos p
                JOIN clientes c ON c.id = p.cliente_id
                ORDER BY p.data_pedido DESC";

        return $this->db->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    public function buscarPorId($id)
    {
        $stmt = $this->db->prepare("SELECT * FROM pedidos WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function listarItens($pedido_id)
    {
        $stmt = $this->db->prepare("SELECT * FROM pedido_itens WHERE pedido_id = ?");
        $stmt->execute([$pedido_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function listarPagamentos($pedido_id)
    {
        $sql = "SELECT m.*, f.nome AS forma_pagamento 
                FROM movimentacoes m
                JOIN formas_pagamento f ON f.id = m.forma_pagamento_id
                WHERE m.pedido_id = ?";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([$pedido_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function salvar($dados, $itens = [])
    {
        if (!empty($dados['id'])) {
            // Atualiza pedido
            $stmt = $this->db->prepare("UPDATE pedidos SET 
                cliente_id = ?, data_pedido = ?, previsao_entrega = ?, status = ?, observacao = ?
                WHERE id = ?");
            $stmt->execute([
                $dados['cliente_id'],
                $dados['data_pedido'],
                $dados['previsao_entrega'],
                $dados['status'],
                $dados['observacao'],
                $dados['id']
            ]);

            $pedido_id = $dados['id'];

            // Apaga e reinsere itens
            $this->db->prepare("DELETE FROM pedido_itens WHERE pedido_id = ?")->execute([$pedido_id]);
        } else {
            // Insere novo pedido
            $stmt = $this->db->prepare("INSERT INTO pedidos 
                (cliente_id, data_pedido, previsao_entrega, status, observacao) 
                VALUES (?, ?, ?, ?, ?)");
            $stmt->execute([
                $dados['cliente_id'],
                $dados['data_pedido'],
                $dados['previsao_entrega'],
                $dados['status'],
                $dados['observacao']
            ]);

            $pedido_id = $this->db->lastInsertId();
        }

        // Inserir itens
        foreach ($itens as $item) {
            $stmt = $this->db->prepare("INSERT INTO pedido_itens 
                (pedido_id, descricao, valor_unitario, quantidade, subtotal)
                VALUES (?, ?, ?, ?, ?)");
            $stmt->execute([
                $pedido_id,
                $item['descricao'],
                $item['valor_unitario'],
                $item['quantidade'],
                $item['subtotal']
            ]);
        }

        return $pedido_id;
    }

    public function excluir($id)
    {
        $this->db->prepare("DELETE FROM pedido_itens WHERE pedido_id = ?")->execute([$id]);
        $this->db->prepare("DELETE FROM movimentacoes WHERE pedido_id = ?")->execute([$id]);
        $stmt = $this->db->prepare("DELETE FROM pedidos WHERE id = ?");
        return $stmt->execute([$id]);
    }

    public function listarPorStatusEPrevisao($statusArray, $dias)
    {
        $in = implode(',', array_fill(0, count($statusArray), '?'));
        $sql = "SELECT p.*, c.nome as cliente
                FROM pedidos p
                JOIN clientes c ON c.id = p.cliente_id
                WHERE p.status IN ($in)
                AND previsao_entrega BETWEEN CURDATE() AND DATE_ADD(CURDATE(), INTERVAL ? DAY)
                ORDER BY previsao_entrega ASC";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([...$statusArray, $dias]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function listarPorStatus($statusArray)
    {
        $in = implode(',', array_fill(0, count($statusArray), '?'));
        $sql = "SELECT p.*, c.nome as cliente
                FROM pedidos p
                JOIN clientes c ON c.id = p.cliente_id
                WHERE p.status IN ($in)
                ORDER BY p.data_pedido DESC";

        $stmt = $this->db->prepare($sql);
        $stmt->execute($statusArray);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

}
s