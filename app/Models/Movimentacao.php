<?php

namespace App\Models;

use App\Core\Database;
use PDO;

class Movimentacao
{
    private $db;

    public function __construct()
    {
        $this->db = Database::conectar();
    }

    public function listarTodas()
    {
        $sql = "SELECT m.*, f.nome AS forma_pagamento, p.id AS pedido_id_ref
                FROM movimentacoes m
                LEFT JOIN formas_pagamento f ON f.id = m.forma_pagamento_id
                LEFT JOIN pedidos p ON p.id = m.pedido_id
                ORDER BY m.data DESC";

        return $this->db->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    public function buscarPorId($id)
    {
        $stmt = $this->db->prepare("SELECT * FROM movimentacoes WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function salvar($dados)
    {
        if (!empty($dados['id'])) {
            $stmt = $this->db->prepare("UPDATE movimentacoes SET 
                tipo = ?, valor = ?, descricao = ?, data = ?, forma_pagamento_id = ?, pedido_id = ?
                WHERE id = ?");
            return $stmt->execute([
                $dados['tipo'], $dados['valor'], $dados['descricao'], $dados['data'],
                $dados['forma_pagamento_id'], $dados['pedido_id'], $dados['id']
            ]);
        } else {
            $stmt = $this->db->prepare("INSERT INTO movimentacoes 
                (tipo, valor, descricao, data, forma_pagamento_id, pedido_id)
                VALUES (?, ?, ?, ?, ?, ?)");
            return $stmt->execute([
                $dados['tipo'], $dados['valor'], $dados['descricao'], $dados['data'],
                $dados['forma_pagamento_id'], $dados['pedido_id']
            ]);
        }
    }

    public function excluir($id)
    {
        $stmt = $this->db->prepare("DELETE FROM movimentacoes WHERE id = ?");
        return $stmt->execute([$id]);
    }

    public function resumoMensal()
    {
        $sql = "SELECT tipo, SUM(valor) as total
                FROM movimentacoes
                WHERE MONTH(data) = MONTH(CURDATE()) AND YEAR(data) = YEAR(CURDATE())
                GROUP BY tipo";

        $result = $this->db->query($sql)->fetchAll(PDO::FETCH_ASSOC);

        return [
            'entrada' => (float) ($result[0]['tipo'] === 'entrada' ? $result[0]['total'] : ($result[1]['total'] ?? 0)),
            'saida'   => (float) ($result[0]['tipo'] === 'saida'   ? $result[0]['total'] : ($result[1]['total'] ?? 0))
        ];
    }
}
