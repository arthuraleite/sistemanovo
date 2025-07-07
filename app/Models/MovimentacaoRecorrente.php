<?php

namespace App\Models;

use App\Core\Database;
use PDO;

class MovimentacaoRecorrente
{
    private $db;

    public function __construct()
    {
        $this->db = Database::conectar();
    }

    public function listarTodas()
    {
        $sql = "SELECT * FROM movimentacoes_recorrentes WHERE ativo = 1 ORDER BY data_vencimento ASC";
        return $this->db->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    public function buscarPorId($id)
    {
        $stmt = $this->db->prepare("SELECT * FROM movimentacoes_recorrentes WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function salvar($dados)
    {
        if (!empty($dados['id'])) {
            $stmt = $this->db->prepare("UPDATE movimentacoes_recorrentes SET 
                descricao = ?, valor = ?, tipo = ?, data_vencimento = ?, frequencia = ?, ativo = ?
                WHERE id = ?");
            return $stmt->execute([
                $dados['descricao'], $dados['valor'], $dados['tipo'], $dados['data_vencimento'],
                $dados['frequencia'], $dados['ativo'], $dados['id']
            ]);
        } else {
            $stmt = $this->db->prepare("INSERT INTO movimentacoes_recorrentes 
                (descricao, valor, tipo, data_vencimento, frequencia, ativo)
                VALUES (?, ?, ?, ?, ?, ?)");
            return $stmt->execute([
                $dados['descricao'], $dados['valor'], $dados['tipo'], $dados['data_vencimento'],
                $dados['frequencia'], $dados['ativo']
            ]);
        }
    }

    public function excluir($id)
    {
        $stmt = $this->db->prepare("DELETE FROM movimentacoes_recorrentes WHERE id = ?");
        return $stmt->execute([$id]);
    }

    public function listarVencendoEmDias($dias = 7)
    {
        $sql = "SELECT * FROM movimentacoes_recorrentes 
                WHERE ativo = 1 
                AND data_vencimento BETWEEN CURDATE() AND DATE_ADD(CURDATE(), INTERVAL ? DAY)
                ORDER BY data_vencimento ASC";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([$dias]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
