<?php

namespace App\Models;

use App\Core\Database;
use PDO;

class Produto
{
    private $db;

    public function __construct()
    {
        $this->db = Database::conectar();
    }

    public function listarTodos()
    {
        $sql = "SELECT * FROM produtos ORDER BY descricao";
        return $this->db->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    public function buscarPorId($id)
    {
        $stmt = $this->db->prepare("SELECT * FROM produtos WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function salvar($dados)
    {
        if (!empty($dados['id'])) {
            $stmt = $this->db->prepare("UPDATE produtos SET descricao = ?, valor = ? WHERE id = ?");
            return $stmt->execute([$dados['descricao'], $dados['valor'], $dados['id']]);
        } else {
            $stmt = $this->db->prepare("INSERT INTO produtos (descricao, valor) VALUES (?, ?)");
            return $stmt->execute([$dados['descricao'], $dados['valor']]);
        }
    }

    public function excluir($id)
    {
        $stmt = $this->db->prepare("DELETE FROM produtos WHERE id = ?");
        return $stmt->execute([$id]);
    }
}
